<?php

namespace Digicademy\Lod\ViewHelpers;

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
 *  the Free Software Foundation; either version 2 of the License, or
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

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

class JsonldSerializeViewHelper extends AbstractViewHelper
{

    /**
     * @var array
     */
    protected $jsonLD = [];

    /**
     * Decodes incoming JSON-LD containing suffixed keys and recursively compile the data structure to
     * a JSON-LD array containing unique keys and values on each level
     *
     * @return string
     */
    public function render()
    {
        $this->jsonLD = json_decode($this->renderChildren(), true);
        $this->mergeKeysRecursive($this->jsonLD['@graph']);
        return json_encode($this->jsonLD, JSON_UNESCAPED_SLASHES);
    }

    /**
     * Recursive function for compiling keys and values (removing the '_tx_lod_UID' suffixes) and combining
     * type properties into an array of strings on each level.
     *
     * @param array
     * @return void
     */
    private function mergeKeysRecursive(&$array)
    {
        foreach ($array as $elementKey => &$element) {
            if (is_array($element) && is_numeric($elementKey)) {
                $type = [];
                foreach ($element as $key => &$value) {
                    if (preg_match('/@type/', $key)) {
                        $type[] = $value;
                        unset($element[$key]);
                    } elseif (preg_match('/_ext_lod_/', $key)) {
                        $property = substr($key, 0, strpos($key, '_ext_lod_'));
                        $element[$property][] = $value;
                        unset($element[$key]);
                    }
                    if (is_array($value)) $this->mergeKeysRecursive($value);
                }
                if ($type) {
                    $element['@type'] = $type;
                }
            }
        }
    }

}
