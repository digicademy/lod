<?php

namespace Digicademy\Lod\Utility\Backend;

/***************************************************************
 *  Copyright notice
 *
 *  (c) Torsten Schrade <Torsten.Schrade@adwmainz.de>
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

class LabelUtility
{

    /**
     * @param array $parameters
     *
     * @return array
     */
    public function iriLabel(array &$parameters)
    {
        // get PageTSConfig for current record (the page where the record is stored, not necessary the current page)
        $TSConfig = BackendUtility::getPagesTSconfig($parameters['row']['pid']);

        // check for display pattern and initialize $iriLabel
        if (is_array($TSConfig['tx_lod.']['settings.']['iriLabel.']) && array_key_exists('displayPattern', $TSConfig['tx_lod.']['settings.']['iriLabel.'])) {
            $iriLabel = $TSConfig['tx_lod.']['settings.']['iriLabel.']['displayPattern'];

            // strangely, we do not get the full row anymore in the label_userFunc of TYPO3 11 - which is why we need to fetch the full IRI here
            $iri = BackendUtility::getRecord('tx_lod_domain_model_iri', (int)$parameters['row']['uid']);
            $parameters['row'] = $iri;
        } else {
            $iriLabel = '###NAMESPACE_PREFIX###:###IRI_VALUE###';
        }

        // replace namespace markers
        if (preg_match('/###NAMESPACE_PREFIX###/', $iriLabel) > 0 || preg_match('/###NAMESPACE_IRI###/', $iriLabel) > 0) {

            // initialize namespace var
            $namespace = [];

            // if called in the context of an edit form title the namespace field (strangely) is an array and not an integer - reset
            if (is_array($parameters['row']['namespace'])) $parameters['row']['namespace'] = $parameters['row']['namespace'][0];

            // if namespace fetch namespace record
            if ($parameters['row']['namespace'] > 0) {
                $namespace = BackendUtility::getRecord('tx_lod_domain_model_namespace', (int)$parameters['row']['namespace']);
            }

            // replace ###NAMESPACE_PREFIX###
            if (is_array($namespace) && $namespace['prefix']) {
                $iriLabel = preg_replace('/###NAMESPACE_PREFIX###/', $namespace['prefix'], $iriLabel);
            }

            // replace ###NAMESPACE_IRI###
            if (is_array($namespace) && $namespace['iri']) {
                $iriLabel = preg_replace('/###NAMESPACE_IRI###/', $namespace['iri'], $iriLabel);
            }
        }

        // replace iri markers
        if (preg_match('/###IRI_VALUE###/', $iriLabel) > 0 && $parameters['row']['value']) {
            $iriLabel = preg_replace('/###IRI_VALUE###/', $parameters['row']['value'], $iriLabel);
        }

        if (preg_match('/###IRI_LABEL###/', $iriLabel) > 0 && $parameters['row']['label']) {
            $iriLabel = preg_replace('/###IRI_LABEL###/', $parameters['row']['label'], $iriLabel);
        }

        // set title
        $parameters['title'] = $iriLabel;

        return $parameters;
    }

}
