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

class LangDatatypeViewHelper extends AbstractViewHelper
{

    /**
     * Initialize ViewHelper arguments
     *
     * @return void
     * @throws
     */
    public function initializeArguments() {
        $this->registerArgument(
            'literal',
            'Digicademy\Lod\Domain\Model\Literal',
            'Literal value for which to set language or datatype',
            true
        );
        $this->registerArgument(
            'format',
            'string',
            'Serialisation format for which to generate lang and datatype',
            true
        );
    }

    /**
     * Render RDFa prefix string
     *
     * @return string
     */
    public function render()
    {
        $language = $this->arguments['literal']->getLanguage();
        $datatype = $this->arguments['literal']->getDatatype();
        $format = $this->arguments['format'];

        switch ($format) {
            case 'rdfxml':
                    $languageValue = ' xml:lang="' . $language . '"';
                    $datatypeValue = ' rdf:datatype="' . $datatype . '"';
                break;
            case 'rdfa':
                    $languageValue = ' lang="' . $language . '"';
                    $datatypeValue = ' datatype="' . $datatype . '"';
                break;
            case 'jsonld':
                    $languageValue = '"@language": "'. $language .'",';
                    $datatypeValue = '"@type": "'. $datatype .'",';
                break;
            case 'turtle':
            case 'ntriples':
                    $languageValue = '@' . $language;
                    $datatypeValue = '^^<' . $datatype . '>';
                break;
            default:
                    $languageValue = '';
                    $datatypeValue = '';
                break;
        }

        if ($language && $datatype == '') {
            $result = $languageValue;
        } elseif ($datatype && $language == '') {
            $result = $datatypeValue;
        } else {
            $result = '';
        }

        return $result;
    }

}
