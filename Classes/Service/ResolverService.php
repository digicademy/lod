<?php
namespace Digicademy\Lod\Service;

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
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;
use Digicademy\Lod\Domain\Model\Representation;

/**
 * The resolver service resolves IRIs to URLs (by representations).
 * For each type of representation (by scheme: t3, http, https, ftp etc.)
 * a custom resolver can be registered.
 */
class ResolverService
{

    /**
     * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @param \TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager
     */
    public function injectObjectManager(ObjectManagerInterface $objectManager)
        {
        $this->objectManager = $objectManager;
    }

    /**
     * @var array
     */
    protected $availableResolvers = [];

    /**
     * ResolverService constructor
     */
    public function __construct()
    {
        $this->availableResolvers = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['lod']['resolver'];
//@TODO: implement hook for resolvers from extensions
    }

    /**
     * @param \Digicademy\Lod\Domain\Model\Representation $representation
     * @param array $settings
     *
     * @return string
     */
     public function resolve(Representation $representation, $settings)
     {
        $url = '';
        $scheme = $representation->getScheme();

        if ($this->availableResolvers[$scheme]) {
            $resolver = $this->objectManager->get(
                $this->availableResolvers[$scheme],
                $settings[$scheme]
            );
            $url = $resolver->resolveToUrl($representation);
        }

        return $url;
     }

    /**
     * @param string $httpAcceptHeader
     * @return array
     */
     public function processAcceptHeader($httpAcceptHeader)
     {
        $acceptedMediaTypes = GeneralUtility::trimExplode(',', $httpAcceptHeader);
        $weightedMediaTypes = [];
        foreach ($acceptedMediaTypes as $key => $mediaType) {
            if (strpos($mediaType, ';q')) {
                $mediaTypeWithQFactor = GeneralUtility::trimExplode(';', $mediaType);
                $qFactor = substr($mediaTypeWithQFactor[1], 2);
                $weightedMediaTypes[$qFactor][] = $mediaTypeWithQFactor[0];
            } else {
                $weightedMediaTypes['1.0'][] = $mediaType;
            }
        }
        krsort($weightedMediaTypes);
        $sortedHttpAcceptHeaders = call_user_func_array('array_merge', $weightedMediaTypes);

        return $sortedHttpAcceptHeaders;
     }

    /**
     * @param string $httpContentType
     * @return array
     */
     public function processContentType($httpContentType)
     {
        $splitHttpContentType = GeneralUtility::trimExplode(';', $httpContentType);
        if (count($splitHttpContentType) == 2 ) {
            $contentType['mime'] = $splitHttpContentType[0];
            $contentType['charset'] = trim(str_replace('charset=', '', $splitHttpContentType[1]));
        } else {
            $contentType['mime'] = $splitHttpContentType[0];
        }

        return $contentType;
     }

}
