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
use Digicademy\Lod\Domain\Model\Iri;
use Digicademy\Lod\Domain\Repository\IriRepository;
use Digicademy\Lod\Service\ResolverService;
use Digicademy\Lod\Domain\Repository\StatementRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper;

class ApiController extends ActionController
{
    /**
     * @var \TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper
     */
    protected $dataMapper;

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
     * @param \TYPO3\CMS\Extbase\Object\ObjectManagerInterface              $objectManager
     * @param \TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper      $dataMapper
     * @param \Digicademy\Lod\Domain\Repository\IriRepository               $iriRepository
     * @param \Digicademy\Lod\Domain\Repository\StatementRepository         $statementRepository
     * @param \Digicademy\Lod\Service\ContentNegotiationService             $contentNegotiationService
     * @param \Digicademy\Lod\Service\ResolverService                       $resolverService
     */
    public function __construct(
        ObjectManagerInterface $objectManager,
        DataMapper $dataMapper,
        IriRepository $iriRepository,
        StatementRepository $statementRepository,
        ContentNegotiationService $contentNegotiationService,
        ResolverService $resolverService
    ) {
        parent::__construct($objectManager);
        $this->dataMapper = $dataMapper;
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

            // generate url to redirect depending if realurl is installed or not
            if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl'])) {
                $uri =  $requestUrl . '/about.' . $this->contentNegotiationService->getFormat();
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

            // possibility to include namespace prefix in query (prefix:value)
            if (substr_count($iri, ':') == 1 && substr_count($iri, '://') == 0) {
                $iriParts = GeneralUtility::trimExplode(':', $iri);

// @TODO: check if this is still there in 9.5
                $namespace = $GLOBALS['TSFE']->sys_page->getRecordsByField(
                    'tx_lod_domain_model_namespace',
                    'prefix',
                    $iriParts[0],
                    '',
                    '',
                    '',
                    '1'
                );

                if (is_array($namespace)) {
                    $whereClause = 'AND tx_lod_domain_model_iri.namespace = ' . (int)$namespace[0]['uid'];
                } else {
                    $whereClause = 'AND tx_lod_domain_model_iri.namespace = -1';
                }

                $value = $iriParts[1];

            // otherwise just query the value (could potentially be ambiguous)
            } else {
                $value = $iri;
                $whereClause = '';
            }

// @TODO: check if this is still there in 9.5
            $result = $GLOBALS['TSFE']->sys_page->getRecordsByField(
                'tx_lod_domain_model_iri',
                'value',
                $value,
                $whereClause,
                $groupBy = '',
                $orderBy = '',
                '1'
            );

            // if value is found, redirect to resourceAction with uid and format as param, else send 404
            if (is_array($result)) {
                $mappingResult = $this->dataMapper->map(Iri::class, $result);
                $this->resourceAction($mappingResult[0]);
            } else {
                $GLOBALS['TSFE']->pageNotFoundAndExit();
            }

        // no value, forward to list
        } else {
            $this->listAction();
        }
    }

    /**
     * Returns metadata about a list of resources in different content types / document representations
     *
     * @return void
     * @throws
     */
    private function listAction()
    {
        $arguments = $this->request->getArguments();

        ($arguments['limit']) ? $limit = (int)$arguments['limit'] : $limit = 100;
        if ($limit > 500) $limit = 500;

        $totalItems = $this->iriRepository->countAll();
        $totalPages = (int)floor($totalItems / $limit);

        if ($arguments['page']) {
            ($arguments['page'] <= $totalPages) ? $page = (int)$arguments['page'] : $page = $totalPages;
        } else {
            $page = 1;
            $this->request->setArgument('page', 0);
        }

        $offset = ($page - 1) * $limit;

// @TODO: check / implement recursive storage pids
        $resources = $this->iriRepository->findAll()
            ->getQuery()
            ->setOffset($offset)
            ->setLimit($limit)
            ->execute();

// @TODO: continue with list templates
\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($resources, NULL, 5, FALSE, TRUE, FALSE, array(), array());
die();

        // pagination
        $pagination = ['first' => 1];
        $pagination['last'] = $totalPages;
        ($page <= 1) ? $pagination['previous'] = 1 : $pagination['previous'] = $page - 1;
        ($page < $totalPages) ? $pagination['next'] = $page + 1 : $pagination['next'] = $totalPages;

        $this->view->assign('action', 'list');

        $this->view->assign('totalItems', $totalItems);

        $this->view->assign('pagination', $pagination);

        $this->view->assign('resources', $resources);

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
     * Returns metadata about a single resource in different content types / document representations
     *
     * @param \Digicademy\Lod\Domain\Model\Iri $resource
     *
     * @return void
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
     */
    private function resourceAction(
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
        $this->view->assign('action', 'resource');

        // assign the resource
        $this->view->assign('resource', $resource);

// @TODO: check / implement recursive storage pids
        // assign statements containing the resource
        switch ($resource->getType()) {
            // in case resource is an entity: find statements with IRI in subject / object position
            case 1:
                $statements = $this->statementRepository->findBySubjectAndObject($resource);
                break;
            // in case resource is a property: find statements with IRI in predicate position
            case 2:
                $statements = $this->statementRepository->findByPredicate($resource);
                break;
        }
        $this->view->assign('statements', $statements);

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
