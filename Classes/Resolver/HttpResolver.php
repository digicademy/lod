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
use Digicademy\Lod\Domain\Model\Representation;

class HttpResolver extends AbstractResolver implements ResolverInterface
{

    /**
     * @param \Digicademy\Lod\Domain\Model\Representation $representation
     *
     * @return string
     * @throws \TYPO3\CMS\Core\Error\Exception
     */
    public function resolveToUrl(Representation $representation)
    {
        $recordUrl =
            $representation->getScheme() . '://' .
            $representation->getAuthority() .
            $representation->getPath() .
            $representation->getQuery() .
            $representation->getFragment();

        if (GeneralUtility::isValidUrl($recordUrl)) {
            $url = $recordUrl;
        } else {
            throw new \TYPO3\CMS\Core\Error\Exception(
                'URL of representation with uid ' . $representation->getUid() . ' is invalid', 1555043405
            );
        }

        return $url;
    }

}
