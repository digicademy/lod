<?php

namespace Digicademy\Lod\Utility\Backend;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2022 Torsten Schrade <Torsten.Schrade@adwmainz.de>
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

        // initialise
        $namespace = [];

        // if called in the context of an edit form title the namespace field (strangely) is an array and not an integer - reset
        if (is_array($parameters['row']['namespace'])) $parameters['row']['namespace'] = $parameters['row']['namespace'][0];

        // if namespace exist fetch prefix
        if ($parameters['row']['namespace'] > 0) {
            $namespace = BackendUtility::getRecord('tx_lod_domain_model_namespace', (int)$parameters['row']['namespace']);
        }

        // if namespace prefix exists set to prefix:value, otherwise just value
        array_key_exists('prefix', $namespace) ? $parameters['title'] = $namespace['prefix'] .':'. $parameters['row']['value'] : $parameters['title'] = $parameters['row']['value'];

        return $parameters;
    }

}
