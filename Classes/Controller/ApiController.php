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

use Digicademy\Lod\Service\ContentNegotiationService;
use Digicademy\Lod\Domain\Repository\IriNamespaceRepository;
use Digicademy\Lod\Domain\Model\Iri;
use Digicademy\Lod\Domain\Repository\IriRepository;
use Digicademy\Lod\Service\ResolverService;
use Digicademy\Lod\Domain\Repository\StatementRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class ApiController extends ActionController
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
     * @var \Digicademy\Lod\Domain\Repository\StatementRepository
     */
    protected $statementRepository = null;

    /**
     * @var \Digicademy\Lod\Service\ContentNegotiationService
     */
    protected $contentNegotiationService;

    /**
     * @var \Digicademy\Lod\Service\ResolverService
     */
    protected $resolverService;

    /**
     * Initializes the controller and dependencies
     *
     * @param \Digicademy\Lod\Domain\Repository\IriNamespaceRepository      $iriNamespaceRepository
     * @param \Digicademy\Lod\Domain\Repository\IriRepository               $iriRepository
     * @param \Digicademy\Lod\Domain\Repository\StatementRepository         $statementRepository
     * @param \Digicademy\Lod\Service\ContentNegotiationService             $contentNegotiationService
     * @param \Digicademy\Lod\Service\ResolverService                       $resolverService
     */
    public function __construct(
        IriNamespaceRepository $iriNamespaceRepository,
        IriRepository $iriRepository,
        StatementRepository $statementRepository,
        ContentNegotiationService $contentNegotiationService,
        ResolverService $resolverService
    ) {
        $this->iriNamespaceRepository = $iriNamespaceRepository;
        $this->iriRepository = $iriRepository;
        $this->statementRepository = $statementRepository;
        $this->contentNegotiationService = $contentNegotiationService;
        $this->resolverService = $resolverService;
    }

    /**
     * Main action and entry point of this controller. Returns metadata either about a single resource
     * OR a list of resources. In contrast to a standard TYPO3 action controller this action uses two
     * 'sub actions'. The reason for this logic is to keep request arguments as concise as possible:
     *
     * ROOT/ENTRYPOINT/ABOUT => list of resources
     * ROOT/ENTRYPOINT/VALUE/ABOUT => single resource
     *
     * @throws
     */
    public function aboutAction()
    {
        $pageType = GeneralUtility::_GP('type');

        // if page type is given set extbase response format directly
        if ($pageType > 0) {

            $this->request->setFormat($this->contentNegotiationService->getFormat());

        // otherwise redirect to URL including negotiated page type
        } else {

            // make sure request url does not end in a slash
            $requestUrl = rtrim(GeneralUtility::getIndpEnv('TYPO3_REQUEST_URL'), '/');

// @TODO: check type param rewriting in TYPO3 9.5

            // generate url for redirection depending if realurl is installed or not
            if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl'])) {
                $uri =
                    $requestUrl .
                    '/.' .
                    $this->settings['api']['realurlTypeParam'] .
                    $this->contentNegotiationService->getFormat();
            } else {
                $targetPageType = array_search(
                    $this->contentNegotiationService->getContentType(),
                    $this->contentNegotiationService->getAvailableMimeTypes()
                );
                if (preg_match('/\?/', $requestUrl)) {
                    $typeParameterKeyword = '&type=';
                } else {
                    $typeParameterKeyword = '?type=';
                }
                $uri = $requestUrl . $typeParameterKeyword . $targetPageType;
            }

            $this->redirectToUri($uri);
        }

        // look up subject by identifier, otherwise forward to list
        if ($this->request->hasArgument('iri')) {

            $iri = $this->request->getArgument('iri');

            // possibility to include resource namespace prefix in query (prefix:value)
            if (substr_count($iri, ':') == 1 && substr_count($iri, '://') == 0) {
                $iriParts = GeneralUtility::trimExplode(':', $iri);
                $namespace = $this->iriNamespaceRepository->findByPrefix($iriParts[0])->getFirst();
                $value = $iriParts[1];
            // otherwise just query the resource value (could potentially be ambiguous)
            } else {
                $value = $iri;
            }

            // try to fetch the resource
            $resource = $this->iriRepository->findByValue($value, $namespace)->getFirst();

            // if the resource is found, redirect to show action, else send 404
            if ($resource) {
                $this->showAction($resource);
            } else {
                $GLOBALS['TSFE']->pageNotFoundAndExit();
            }

        // no resource argument, forward to list
        } else {
            $this->listAction();
        }
    }

    /**
     * Returns list of resources in different content types / document representations
     *
     * @return void
     * @throws
     */
    private function listAction()
    {
        $arguments = $this->request->getArguments();

        // calculate pagination
        ($arguments['limit']) ? $limit = (int)$arguments['limit'] : $limit = 50;
        if ($limit > 500) $limit = 500;

        $totalItems = $this->iriRepository->countAll();
        $totalPages = (int)ceil($totalItems / $limit);
        if ($totalPages < 1) $totalPages = 1;

        if ($arguments['page']) {
            ($arguments['page'] <= $totalPages) ? $page = (int)$arguments['page'] : $page = $totalPages;
        } else {
            $page = 1;
            $this->request->setArgument('page', 0);
        }

        $offset = ($page - 1) * $limit;

        // fetch resources (possibly from a specific graph)
        $resources = $this->iriRepository->findAll()
            ->getQuery()
            ->setOffset($offset)
            ->setLimit($limit)
            ->execute();

        // pagination
        $pagination = ['first' => 1];
        $pagination['last'] = $totalPages;
        ($page <= 1) ? $pagination['previous'] = 1 : $pagination['previous'] = $page - 1;
        ($page < $totalPages) ? $pagination['next'] = $page + 1 : $pagination['next'] = $totalPages;

        $this->view->assign('action', 'list');

        $this->view->assign('totalItems', $totalItems);

        $this->view->assign('pagination', $pagination);

        $this->view->assign('resources', $resources);

        $this->view->assign('namespaces', $this->iriNamespaceRepository->findAll());

        $this->view->assign('arguments', $arguments);

        $this->view->assign('settings', $this->settings);

        // provide environment vars
        $environment = [
            'TYPO3_REQUEST_HOST' => GeneralUtility::getIndpEnv('TYPO3_REQUEST_HOST'),
            'TYPO3_REQUEST_URL' => GeneralUtility::getIndpEnv('TYPO3_REQUEST_URL')
        ];
        $this->view->assign('environment', $environment);
    }

    /**
     * Returns a single resource in different content types / document representations
     *
     * @param \Digicademy\Lod\Domain\Model\Iri $resource
     *
     * @return void
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
     * @throws \TYPO3\CMS\Extbase\Exception
     */
    private function showAction(
        Iri $resource
    ) {

        foreach ($this->contentNegotiationService->getAcceptedMimeTypes() as $mimeType) {
            // if resource representations are available go through each representation and
            // check if current media type is among representation content types; if yes call resolver
            if ($resource->getRepresentations()) {
                foreach ($resource->getRepresentations() as $key => $representation) {
                    $representationContentType = $this->contentNegotiationService->processContentType($representation->getContentType());
                    if ($representationContentType['mime'] == $mimeType && $representationContentType['mime'] == $this->contentNegotiationService->getContentType()) {
                        // call representation resolver service
                        $url = $this->resolverService->resolve($representation, $this->settings['resolver']);
                        if (GeneralUtility::isValidUrl($url)) {
                            $this->redirectToUri($url);
                        }
                    }
                }
            }

            // if $mimeType equals $contentType deliver a "generated representation"
            if ($mimeType == $this->contentNegotiationService->getContentType()) {
                break;
            }
        }

        // assign current action for disambiguation in about template
        $this->view->assign('action', 'show');

        // assign the resource
        $this->view->assign('resource', $resource);

        // assign existing namespaces
        $this->view->assign('namespaces', $this->iriNamespaceRepository->findAll());

        // assign current arguments
        $this->view->assign('arguments', $this->request->getArguments());

        // assign current settings
        $this->view->assign('settings', $this->settings);

        // provide environment vars
        $environment = [
            'TYPO3_REQUEST_HOST' => GeneralUtility::getIndpEnv('TYPO3_REQUEST_HOST'),
            'TYPO3_REQUEST_URL' => GeneralUtility::getIndpEnv('TYPO3_REQUEST_URL')
        ];
        $this->view->assign('environment', $environment);
    }

}
