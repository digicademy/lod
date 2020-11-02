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

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class FilterIriNamespacesViewHelper extends AbstractViewHelper
{

    /**
     * Initialize ViewHelper arguments
     *
     * @return void
     * @throws
     */
    public function initializeArguments() {

        $this->registerArgument(
            'iriNamespaces',
            'object',
            'IRI namespaces from DB',
            true
        );

        $this->registerArgument(
            'returnDifference',
            'bool',
            'Return the difference between predefined namespaces and iriNamespaces',
            false,
            true
        );

        $this->registerArgument(
            'predefinedNamespaces',
            'array',
            'The list of namespaces to filter out',
            false,
            [
                "rdf" => "http://www.w3.org/1999/02/22-rdf-syntax-ns#",
                "rdfs" => "http://www.w3.org/2000/01/rdf-schema#",
                "owl" => "http://www.w3.org/2002/07/owl#",
                "dc" => "http://purl.org/dc/elements/1.1/",
                "void" => "http://rdfs.org/ns/void#",
                "hydra" => "http://www.w3.org/ns/hydra/core#",
            ]
        );
    }

    /**
     * Determine the class for the given entity
     *
     * @return array
     */
    public function render()
    {
        $predefinedNamespaces = $this->arguments['predefinedNamespaces'];
        $difference = [];
        foreach ($this->arguments['iriNamespaces'] as $namespace) {
            if (!in_array($namespace->getIri(), $predefinedNamespaces)) {
                $difference[$namespace->getPrefix()] = $namespace->getIri();
            }
        }

        if ($this->arguments['returnDifference']) {
            $filteredIriNamespaces = $difference;
        } else {
            $filteredIriNamespaces = array_merge($predefinedNamespaces, $difference);
        }

        return $filteredIriNamespaces;
    }

}
