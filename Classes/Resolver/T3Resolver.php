<?php
namespace Digicademy\Lod\Resolver;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) Torsten Schrade <Torsten.Schrade@adwmainz.de>, Academy of Sciences and Literature | Mainz
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use Digicademy\Lod\Domain\Model\Representation;

class T3Resolver extends AbstractResolver implements ResolverInterface
{

    /**
     * @param \Digicademy\Lod\Domain\Model\Representation $representation
     *
     * @return string
     */
    public function resolveToUrl(Representation $representation)
    {

// @TODO: call different typolink handlers according to $representation->getAuthority();

        $url = '';

        $tsfe = $this->getTypoScriptFrontendController();
        $pageTsConfig = $tsfe->getPagesTSconfig();
        $linkDetails = $this->getLinkDetails($representation->getQuery());

        if ($linkDetails['identifier'] && $linkDetails['uid']) {

            $configurationKey = $linkDetails['identifier'] . '.';
            $configuration = $tsfe->tmpl->setup['config.']['recordLinks.'];
            $linkHandlerConfiguration = $pageTsConfig['TCEMAIN.']['linkHandler.'][$configurationKey]['configuration.'];
            $typoScriptConfiguration = $configuration[$configurationKey]['typolink.'];
            $typoScriptConfiguration['forceAbsoluteUrl'] = '1';

            if ($configurationKey && $configuration && $linkHandlerConfiguration && $typoScriptConfiguration) {

                $record = $tsfe->sys_page->checkRecord($linkHandlerConfiguration['table'], $linkDetails['uid']);

                if ($record) {
                    $representation->getFragment() ? $record['fragment'] = $representation->getFragment() : false;
                    $this->contentObjectRenderer->start($record, $linkHandlerConfiguration['table']);
                    $url = $this->contentObjectRenderer->typoLink_URL($typoScriptConfiguration);
                }
            }
        }

        return $url;
    }

    /**
     * @param string $query
     * @return array
     */
    protected function getLinkDetails($query)
    {
        $queryParameters = GeneralUtility::trimExplode('&', $query, true);
        $linkDetails = [];

        foreach ($queryParameters as $parameter) {
            if (preg_match('/identifier=/', $parameter)) $linkDetails['identifier'] = substr($parameter, 11);
            if (preg_match('/uid=/', $parameter)) $linkDetails['uid'] = (int)substr($parameter, 4);
        }
        return $linkDetails;
    }

    /**
     * @return TypoScriptFrontendController
     */
    protected function getTypoScriptFrontendController()
    {
        return $GLOBALS['TSFE'];
    }

}
