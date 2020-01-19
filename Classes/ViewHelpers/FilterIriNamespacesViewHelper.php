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
            'The list existing IRI namespaces',
            true
        );
        $this->registerArgument(
            'filterValues',
            'array',
            'The list of namespaces to filter out',
            true
        );
    }

    /**
     * Determine the class for the given entity
     *
     * @return array
     */
    public function render()
    {
        $filteredIriNamespaces = [];
        foreach ($this->arguments['iriNamespaces'] as $namespace) {
            if (!in_array($namespace->getIri(), $this->arguments['filterValues'])) {
                $filteredIriNamespaces[] = $namespace;
            }
        }

        return $filteredIriNamespaces;
    }

}
