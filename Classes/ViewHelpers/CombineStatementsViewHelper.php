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
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class CombineStatementsViewHelper extends AbstractViewHelper
{

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
            'Iri for which to combine statements and inverse statements',
            true
        );
        $this->registerArgument(
            'excludeRdfStarStatements',
            0,
            'Exclude RDF* statements',
            false
        );
    }

    /**
     * Combines statements and inverse statements in one object storage
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function render()
    {
        $iri = $this->arguments['iri'];
        $inverseStatements = $iri->getInverseStatements();

        if ($inverseStatements) {

            $combinedStatements = $iri->getStatements();

            foreach ($inverseStatements as $inverseStatement) {
                // look for owl:inverseOf to check if predicate needs to be swapped
                if ($inverseStatement->getPredicate()->getStatements()) {
                    foreach ($inverseStatement->getPredicate()->getStatements() as $statement) {
                        $predicate = $statement->getPredicate();
                        if ($predicate->getValue() == 'inverseOf' && $predicate->getNamespace()->getIri() == 'http://www.w3.org/2002/07/owl#') {
                            $inverseStatement->setPredicate($statement->getObject());
                        }
                    }
                }
                $combinedStatements->attach($inverseStatement);
            }
        } else {
            $combinedStatements = $iri->getStatements();
        }

        if ($this->arguments['excludeRdfStarStatements']) {
            $statementsToDetach = [];
            foreach ($combinedStatements as $key => $statement) {
                if (get_class($statement->getObject()) == 'Digicademy\Lod\Domain\Model\Statement') $statementsToDetach[] = $statement;
            }
            if ($statementsToDetach) {
                foreach ($statementsToDetach as $statement) {
                    $combinedStatements->detach($statement);
                }
            }
        }

        return $combinedStatements;
    }
}
