<?php
namespace Digicademy\Lod\Controller;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) Torsten Schrade <Torsten.Schrade@adwmainz.de>, Academy of Sciences and Literature | Mainz
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
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

use Psr\Http\Message\ResponseInterface;
use Digicademy\Lod\Domain\Repository\IriNamespaceRepository;
use Digicademy\Lod\Domain\Repository\IriRepository;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class SerializerController extends ActionController
{
    /**
     * @var \Digicademy\Lod\Domain\Repository\IriNamespaceRepository
     */
    protected $iriNamespaceRepository;

    /**
     * @var \Digicademy\Lod\Domain\Repository\IriRepository
     */
    protected $iriRepository = null;

    /**
     * Initializes the controller and dependencies
     *
     * @param \Digicademy\Lod\Domain\Repository\IriNamespaceRepository      $iriNamespaceRepository
     * @param \Digicademy\Lod\Domain\Repository\IriRepository               $iriRepository
     */
    public function __construct(
        IriNamespaceRepository $iriNamespaceRepository,
        IriRepository $iriRepository
    ) {
        $this->iriNamespaceRepository = $iriNamespaceRepository;
        $this->iriRepository = $iriRepository;
    }

    /**
     * The initialize action executes an IRI lookup for the current request either by a uid directly set from a
     * serializer plugin or by tracking specified arguments (by TS) in the current request arguments. If an IRI
     * could be found it is retrieved and set as request argument for the serializeAction.
     *
     * @return void
     * @throws
     */
    public function initializeAction(): void
    {
        // check if Iri is set from flexform or TS (flexform overriding TS)
        if ($this->settings['general']['selectedIri'] || $this->settings['selectedIri']) {

            ($this->settings['general']['selectedIri']) ? $iri = $this->settings['general']['selectedIri'] :
                $iri = $this->settings['selectedIri'];
            $iri = $this->iriRepository->findByUid($iri);

        // otherwise iterate through record mappings configured in TS
        } elseif ($this->settings['recordToArgumentMapping']) {

            // first iterate through all possible tables and try to catch a record from current request arguments
            foreach ($this->settings['recordToArgumentMapping'] as $tablename => $recordConfiguration) {
                if ($tablename == 'pages') continue;
                if ($recordConfiguration['pluginNamespace']) {
                    $foreignPluginVars = GeneralUtility::_GPmerged($recordConfiguration['pluginNamespace']);
                    if ($foreignPluginVars[$recordConfiguration['argumentName']] > 0) {
                        $tablenameRecord = $tablename . '_' . (int)$foreignPluginVars[$recordConfiguration['argumentName']];
                    }
                } else {
                    $getParameters = GeneralUtility::_GET();
                    $postParameters = GeneralUtility::_POST();
                    if ($getParameters[$recordConfiguration['argumentName']] > 0) {
                        $tablenameRecord = $tablename . '_' . (int)$getParameters[$recordConfiguration['argumentName']];
                    } elseif ($postParameters[$recordConfiguration['argumentName']] > 0) {
                        $tablenameRecord = $tablename . '_' . (int)$postParameters[$recordConfiguration['argumentName']];
                    }
                }
            }

            // if no iri was found check if pages table was mapped (serves as a default)
            if (!isset($tablenameRecord) && $this->settings['recordToArgumentMapping']['pages']) {
                $argumentValue = GeneralUtility::_GET($this->settings['recordToArgumentMapping']['pages']['argumentName']);
                if ($argumentValue > 0) $tablenameRecord = 'pages_' . (int)$argumentValue;
            }

            // IRI record lookup
            if (isset($tablenameRecord)) $iri = $this->iriRepository->findByRecord($tablenameRecord)->getFirst();
        }

        // if IRI could be found
        if (isset($iri)) {

            // set as argument
            $this->request->setArgument('iri', $iri);

            // determine serialization format
           if (is_object($iri->getRecord())) {
                $tablename = $iri->getRecord()->getTablename();
            }
        }

        // set serialization format (will always be set even if no iri could be found
        if ($this->settings['general']['format']) {
            $format = $this->settings['general']['format'];
        } elseif ($this->settings['format'][$tablename]) {
            $format = $this->settings['format'][$tablename];
        } elseif ($this->settings['format']['default']) {
            $format = $this->settings['format']['default'];
        } else {
            $format = 'jsonld';
        }

        $this->request->setFormat($format);

    }

    /**
     * Serialize a given IRI. If no IRI is given, nothing is serialized/returned
     *
     * @return ResponseInterface
     * @throws
     */
    public function iriAction(): ResponseInterface
    {

        // assign current settings
        if (isset($this->settings['general']['mode'])) $this->settings['mode'] = $this->settings['general']['mode'];
        $this->view->assign('settings', $this->settings);

        // assign IRI if available
        if ($this->request->hasArgument('iri')) {

            // assign namespaces
            $apiSettings = $this->configurationManager->getConfiguration('Settings', 'lod', 'api');
            $this->view->assign('iriNamespaces', $this->iriNamespaceRepository->findSelected('show', $apiSettings));

            // assign iri
            $this->view->assign('resource', $this->request->getArgument('iri'));

            // provide environment vars
            $environment = [
                'TYPO3_REQUEST_HOST' => GeneralUtility::getIndpEnv('TYPO3_REQUEST_HOST'),
                'TYPO3_REQUEST_URL' => GeneralUtility::getIndpEnv('TYPO3_REQUEST_URL'),
                'TSFE' => ['pageArguments' => $GLOBALS['TSFE']->pageArguments]
            ];

            $this->view->assign('environment', $environment);
        }

        // return PSR-7/PSR-17 compliant response
        return $this->htmlResponse();
    }

    // could potentially also include graphAction() in the future

}
