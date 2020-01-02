<?php

namespace Digicademy\Lod\ViewHelpers;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2019 Torsten Schrade <Torsten.Schrade@adwmainz.de>
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

use TYPO3\CMS\Extbase\Exception;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;

class EasyRdfConverterViewHelper extends AbstractViewHelper
{

    /**
     * @var ObjectManagerInterface
     */
    protected $objectManager = null;

    /**
     * @param ObjectManagerInterface $objectManager
     */
    public function injectObjectManager(ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * Initialize ViewHelper arguments
     *
     * @return void
     * @throws
     */
    public function initializeArguments() {
        $this->registerArgument(
            'inputFormat',
            'string',
            'Input format (php, json, jsonld, ntriples, turtle, rdfxml, rdfa)',
            true
        );
        $this->registerArgument(
            'outputFormat',
            'string',
            'Output format (php, json, jsonld, ntriples, turtle, rdfxml)',
            true
        );
        $this->registerArgument(
            'options',
            'array',
            'Serialiser-specific options, for fine-tuning the output',
            false
        );
    }

    /**
     * @return string
     * @throws
     */
    public function render()
    {
        if (class_exists('EasyRdf_Graph') || class_exists('\EasyRdf\Graph')) {

            // render the data
            $data = $this->renderChildren();

            // take care of EasyRdf namespaces after version 0.9
            if (class_exists('EasyRdf_Graph')) {
                $graph = $this->objectManager->get(\EasyRdf_Graph::class);
            } else {
                $graph = $this->objectManager->get(\EasyRdf\Graph::class);
            }

            // parse rendered data into EasyRdf graph
            $graph->parse($data, $this->arguments['inputFormat'], '#');

            // set options for conversion and convert data
            if (is_array($this->arguments['options'])) {
                $convertedData = $graph->serialise($this->arguments['outputFormat'], $this->arguments['options']);
            } else {
                $convertedData = $graph->serialise($this->arguments['outputFormat']);
            }

        } else {
            throw new Exception('RdfConverterViewHelper needs the EasyRdf library but it seems the library is not available', 1577942008);
        }

        return $convertedData;
    }

}
