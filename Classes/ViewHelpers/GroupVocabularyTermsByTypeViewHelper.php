<?php

namespace Digicademy\Lod\ViewHelpers;

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
use TYPO3Fluid\Fluid\Core\ViewHelper\Exception;

class GroupVocabularyTermsByTypeViewHelper extends AbstractViewHelper
{

    /**
     * Initialize arguments
     *
     * @return void
     *
     * @throws Exception
     */
    public function initializeArguments()
    {
        $this->registerArgument(
            'terms',
            'mixed',
            '',
            true
        );

        $this->registerArgument(
            'as',
            'string',
            '',
            false,
            'groupedTerms'
        );
    }

    /**
     * @return void
     */
    public function render()
    {
        $terms = $this->arguments['terms'];
        $groupedTerms = [
            'classes' => [],
            'objectProperties' => [],
            'dataProperties' => [],
            'namedIndividuals' => [],
            'annotationProperties' => [],
        ];
        $as = $this->arguments['as'];

        if ($terms) {
            foreach ($terms as $term) {
                foreach ($term->getStatements() as $statement) {
                    if (is_object($statement->getPredicate()) && is_object($statement->getObject())
                        && $statement->getPredicate()->getNamespace()->getPrefix() == 'rdf'
                        && $statement->getPredicate()->getValue() == 'type') {
                        switch ($statement->getObject()->getValue()) {
                            case 'Class':
                                $groupedTerms['classes'][] = $term;
                                break;
                            case 'ObjectProperty':
                                $groupedTerms['objectProperties'][] = $term;
                                break;
                            case 'DataProperty':
                                $groupedTerms['dataProperties'][] = $term;
                                break;
                            case 'NamedIndividual':
                                $groupedTerms['namedIndividuals'][] = $term;
                                break;
                            case 'AnnotationProperty':
                                $groupedTerms['annotationProperties'][] = $term;
                                break;
                            default:
                                break;
                        }
                    }
                }
            }
            if ($groupedTerms) {
                $this->templateVariableContainer->add($as, $groupedTerms);
            }
        }
    }
}
