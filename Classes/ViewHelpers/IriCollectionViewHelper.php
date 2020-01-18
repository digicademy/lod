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

class IriCollectionViewHelper extends AbstractViewHelper
{

    /**
     * @var array
     */
    protected $iriCollection = [];

    /**
     * Initialize ViewHelper arguments
     *
     * @return void
     * @throws
     */
    public function initializeArguments() {
        $this->registerArgument(
            'add',
            'array',
            'List of IRIs to add to the collection',
            true
        );
    }

    /**
     * Collects lists of IRIs (as template variable).
     * Useful for RDF serialisation (esp. JSON-LD).
     *
     * @return void
     * @throws
     */
    public function render()
    {
        if (count($this->arguments['add']) > 0) {
            if ($this->templateVariableContainer->exists('iriCollection')) {
                $existingCollection = $this->templateVariableContainer->get('iriCollection');
                $this->templateVariableContainer->remove('iriCollection');
                $iriCollection = $existingCollection + $this->arguments['add'];
            } else {
                $iriCollection = $this->arguments['add'];
            }
            $this->templateVariableContainer->add('iriCollection', $iriCollection);
        }

       return null;
    }

}
