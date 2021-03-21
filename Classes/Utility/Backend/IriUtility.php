<?php
namespace Digicademy\Lod\Utility\Backend;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2019 Torsten Schrade <Torsten.Schrade@adwmainz.de>, Academy of Sciences and Literature | Mainz
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

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\DataHandling\DataHandler;

class IriUtility
{

    /**
     * Filters IRIs by type (class = 1 or property = 2) configured in TCA
     *
     * @param array $parameters
     * @param object $parentObject
     *
     * @return array
     */
    public function filterByType(array $parameters, $parentObject)
    {
        // basic setting: record is allowed
        $filterResult = $parameters['values'];

        // apply filter to record, but only for IRI table
        if (preg_match('/tx_lod_domain_model_iri/', $parameters['values'][0])) {

            // trim record uid
            $recordUid = (int)substr(strrchr($parameters['values'][0], '_'), 1);

            // fetch parent page depending on context
            if ($parentObject instanceof DataHandler) {
                $iriRecord = BackendUtility::getRecord(
                    'tx_lod_domain_model_iri',
                    $recordUid,
                    'pid',
                );
                $pid = $iriRecord['pid'];
            } else {
                $pid = $parentObject->id;
            }

            // get PageTSConfig for parent page and set type configuration
            $pagesTSConfig = BackendUtility::getPagesTSconfig($pid);
            if ($pagesTSConfig['tx_lod.']['settings.']['iriTypeFilter.'][$parameters['field']]) {
                $types = implode(',', GeneralUtility::intExplode(',', $pagesTSConfig['tx_lod.']['settings.']['iriTypeFilter.'][$parameters['field']]));
            } else {
                $types = (int)$parameters['default'];
            }

            // test type of record
            $checkRecordType = BackendUtility::getRecord(
                'tx_lod_domain_model_iri',
                $recordUid,
                '*',
                ' AND tx_lod_domain_model_iri.type IN (' . $types . ')'
            );

            // if the type doesn't match, reset return value (skip record)
            if ($checkRecordType === null) {
                $filterResult = [];
            }
        }

        return $filterResult;
    }

}
