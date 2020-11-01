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

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Exception;

class EasyRdfGraphViewHelper extends AbstractViewHelper
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
            'url',
            'string',
            'The URL of a resource representation for fetching RDF data',
            true
        );
        $this->registerArgument(
            'resource',
            'string',
            'The URI of the resource (e.g. http://example.com/joe#me)',
            false
        );
        $this->registerArgument(
            'propertyPath',
            'string',
            'A valid property path',
            false
        );
        $this->registerArgument(
            'property',
            'string',
            'A valid property',
            false
        );
        $this->registerArgument(
            'value',
            'string',
            'A value of a property',
            false
        );
        $this->registerArgument(
            'format',
            'string',
            'The format of the resource',
            false,
            'guess'
        );
        $this->registerArgument(
            'type',
            'string',
            'The type of value to filter by (e.g. literal)',
            false
        );
        $this->registerArgument(
            'lang',
            'string',
            'The language to filter by (e.g. en)',
            false
        );
        $this->registerArgument(
            'namespaces',
            'array',
            'An array of prefix:uri namespaces',
            false
        );
        $this->registerArgument(
            'method',
            'string',
            'A keyword for the method to execute (currently: properties, all, get)',
            false
        );
    }

    /**
     * @see http://www.easyrdf.org/docs/api/EasyRdf_Graph.html
     * @return string
     * @throws
     */
    public function render()
    {
        $result = [];

        if (class_exists('EasyRdf_Graph') || class_exists('\EasyRdf\Graph')) {

            // fetch resource RDF from URI
            $data = GeneralUtility::getUrl($this->arguments['url']);

            if ($data) {

                // take care of EasyRdf namespaces after version 0.9
                if (class_exists('EasyRdf_Graph')) {
                    $graphClassname = 'EasyRdf_Graph';
                    $namespaceClass = 'EasyRdf_Namespace';
                } else {
                    $graphClassname = '\EasyRdf\Graph';
                    $namespaceClass = '\EasyRdf\Namespace';
                }

                // optionally set namespaces
                if (is_array($this->arguments['namespaces'])) {
                    $namespaceRegistry = $this->objectManager->get($namespaceClass);
                    foreach ($this->arguments['namespaces'] as $prefix => $fqdn) {
                        $namespaceRegistry::set($prefix, $fqdn);
                    }
                }

                // parse data and apply property path
                $graph = $this->objectManager->get($graphClassname);
                $graph->parse($data, $this->arguments['format']);

                // execute method by key
                switch ($this->arguments['method']) {

                    case 'resources':
                        $result = $graph->resources();
                        break;

                    case 'resourcesMatching':
                        $result = $graph->resourcesMatching(
                            $this->arguments['property'],
                            $this->arguments['value']
                        );
                        break;

                    case 'get':
                        $result = $graph->get(
                            $this->arguments['resource'],
                            $this->arguments['propertyPath'],
                            $this->arguments['type'],
                            $this->arguments['lang']
                        );
                        break;

                    case 'getLiteral':
                        $result = $graph->getLiteral(
                            $this->arguments['resource'],
                            $this->arguments['property'],
                            $this->arguments['lang']
                        );
                        break;

                    case 'getResource':
                        $result = $graph->getLiteral(
                            $this->arguments['resource'],
                            $this->arguments['property']
                        );
                        break;

                    case 'all':
                        $result = $graph->all(
                            $this->arguments['resource'],
                            $this->arguments['propertyPath'],
                            $this->arguments['type'],
                            $this->arguments['lang']
                        );
                        break;

                    case 'allLiterals':
                        $result = $graph->allLiterals(
                            $this->arguments['resource'],
                            $this->arguments['property'],
                            $this->arguments['lang']
                        );
                        break;

                    case 'allResources':
                        $result = $graph->allResources(
                            $this->arguments['resource'],
                            $this->arguments['property']
                        );
                        break;

                    case 'allOfType':
                        $result = $graph->allOfType(
                            $this->arguments['resource'],
                            $this->arguments['property']
                        );
                        break;

                    case 'properties':
                        $result = $graph->properties($this->arguments['resource']);
                        break;

                    case 'countTriples':
                        $result = $graph->countTriples();
                        break;

                    default:
                        $result = $graph->toRdfPhp();
                        break;
                }
            }
        } else {
            throw new Exception('The EasyRdf library is needed but seems not to be available', 1555768472);
        }

        return $result;
    }

}
