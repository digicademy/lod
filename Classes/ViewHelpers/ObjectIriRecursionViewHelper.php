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

use Digicademy\Lod\Domain\Model\Iri;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

class ObjectIriRecursionViewHelper extends AbstractViewHelper
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
            'iri',
            '\Digicademy\Lod\Domain\Model\Iri',
            'Iri to collect',
            true
        );
        $this->registerArgument(
            'maxDepth',
            'int',
            'Maximum depth of recursion',
            true,
            5
        );
    }

    /**
     * Recursively collects object IRIs in a flat list.
     * Especially needed for Turtle serialisation.
     *
     * @return
     * @throws
     */
    public function render()
    {
        $this->recurse($this->arguments['iri'], $this->arguments['maxDepth']);
        return $this->iriCollection;
    }

    /**
     * @param \Digicademy\Lod\Domain\Model\Iri $iri
     * @param int $maxDepth
     * @param int $currentDepth
     * @return
     */
    private function recurse(Iri $iri, $maxDepth, $currentDepth = 1)
    {
        if ($iri->getStatements() && $currentDepth <= $maxDepth) {
            foreach ($iri->getStatements() as $statement) {
                if ($statement->getObject() instanceof Iri && $statement->getObjectRecursion() == 1) {
                    $this->iriCollection[] = $statement->getObject();
                    if ($statement->getObject()->getStatements()) {
                        self::recurse($statement->getObject(), $maxDepth, $currentDepth + 1);
                    }
                }
            }
        }
        return;
    }

}
