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

use Digicademy\Lod\Domain\Model\Iri;
use Digicademy\Lod\Domain\Repository\IriRepository;
use Digicademy\Lod\Domain\Repository\StatementRepository;
use Digicademy\Lod\Service\ResolverService;
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
     * @var \Digicademy\Lod\Service\ResolverService
     */
    protected $resolverService;

    /**
     * @var array
     */
    protected $acceptedMimeTypes = [];

    /**
     * @var array
     */
    protected $availableMimeTypes = [];

    /**
     * @var string
     */
    protected $contentType = 'text/html';

    /**
     * @var string
     */
    protected $format = 'html';

    /**
     * Initializes the controller and dependencies
     *
     * @param \TYPO3\CMS\Extbase\Object\ObjectManagerInterface              $objectManager
     * @param \TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper      $dataMapper
     * @param \Digicademy\Lod\Domain\Repository\IriRepository               $iriRepository
     * @param \Digicademy\Lod\Domain\Repository\StatementRepository         $statementRepository
     * @param \Digicademy\Lod\Service\ResolverService                       $resolverService
     */
    public function __construct(
        ObjectManagerInterface $objectManager,
        DataMapper $dataMapper,
        IriRepository $iriRepository,
        StatementRepository $statementRepository,
        ResolverService $resolverService
    ) {
        parent::__construct($objectManager);
        $this->dataMapper = $dataMapper;
        $this->iriRepository = $iriRepository;
        $this->statementRepository = $statementRepository;
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

        $this->determineFormatAndContentType($pageType);

        // if page type is given set format and content type directly
        if ($pageType > 0) {

            $this->request->setFormat($this->format);

            // otherwise redirect to URL including correct page type
        } else {

            // make sure request url does not end in a slash
            $requestUrl = rtrim(GeneralUtility::getIndpEnv('TYPO3_REQUEST_URL'), '/');

            // generate url to redirect depending if realurl is installed or not
            if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl'])) {
                $uri =  $requestUrl . '/about.' . $this->format;
            } else {
                $targetPageType = array_search($this->contentType, $this->availableMimeTypes);
                $uri = $requestUrl . '?type=' . $targetPageType;
            }

// @TODO: think about configurable redirection including cHash (=> cacheable content?)

            $this->redirectToUri($uri);
        }

        // look up subject by identifier, otherwise forward to list
        if ($this->request->hasArgument('value')) {

            $result = $GLOBALS['TSFE']->sys_page->getRecordsByField(
                'tx_lod_domain_model_iri',
                'value',
                $this->request->getArgument('value'),
                $whereClause = '',
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

// @TODO: subjectsRepository: recursive storage pids

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

        $this->view->assign('arguments', $arguments);

        $this->view->assign('settings', $this->settings);

        // provide environment vars
        $environment = ['TYPO3_REQUEST_HOST' => GeneralUtility::getIndpEnv('TYPO3_REQUEST_HOST')];
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

        foreach ($this->acceptedMimeTypes as $mimeType) {
            // if resource representations are available go through each representation and
            // check if current media type is among representation content types; if yes call resolver
            if ($resource->getRepresentations()) {
                foreach ($resource->getRepresentations() as $key => $representation) {
                    $contentType = $this->resolverService->processContentType($representation->getContentType());
                    if ($contentType['mime'] == $mimeType && $contentType['mime'] == $this->contentType) {
                        // call representation resolver service
                        $url = $this->resolverService->resolve($representation, $this->settings['resolver']);
                        if (GeneralUtility::isValidUrl($url)) {
                            $this->redirectToUri($url);
                        }
                    }
                }
            }

            // if $mimeType equals $contentType deliver a "generated representation"
            if ($mimeType == $this->contentType) {
                break;
            }
        }

        // assign current action for disambiguation in about template
        $this->view->assign('action', 'resource');

        // assign the resource
        $this->view->assign('resource', $resource);

        // assign statements about the resource
        $statements = $this->statementRepository->findBySubject($resource);
        $this->view->assign('statements', $statements);

        // assign current arguments
        $this->view->assign('arguments', $this->request->getArguments());

        // assign current settings
        $this->view->assign('settings', $this->settings);

        // provide environment vars
        $environment = ['TYPO3_REQUEST_HOST' => GeneralUtility::getIndpEnv('TYPO3_REQUEST_HOST')];
        $this->view->assign('environment', $environment);
    }

// @TODO: think about content negotiation service and move following logic

    /**
     * Compiles an array of accepted mime types from client
     *
     * @return void
     */
    private function getAcceptedMimeTypes()
    {
        // if accept header is set get a weighted list of accepted formats
        $httpAcceptHeader = getenv('HTTP_ACCEPT');
        if ($httpAcceptHeader) {
            $this->acceptedMimeTypes = $this->resolverService->processAcceptHeader($httpAcceptHeader);
        } else {
            $this->acceptedMimeTypes[] = 'text/html';
        }
    }

    /**
     * Compiles available content types by page type from TypoScript configuration
     * (header: Content-type:XY must be set in TypoScript)
     *
     * @return void
     */
    private function getAvailableMimeTypes()
    {
        foreach ($GLOBALS['TSFE']->tmpl->setup['types.'] as $key => $type) {
            if ($type == 'page') {
                continue;
            }
            $type = $type . '.';
            if (
                $GLOBALS['TSFE']->tmpl->setup[$type]['typeNum'] == $key
                && $GLOBALS['TSFE']->tmpl->setup[$type]['config.']['additionalHeaders.']
            ) {
                $additionalHeaders = $GLOBALS['TSFE']->tmpl->setup[$type]['config.']['additionalHeaders.'];
                foreach ($additionalHeaders as $additionalHeader) {
                    if (preg_match('/Content-type:/', $additionalHeader['header'])) {
                        $this->availableMimeTypes[$key] = str_replace('Content-type:', '', $additionalHeader['header']);
                    }
                }
            }
        }
    }

    /**
     * Content negotiation: Determines the best mime type for the response by negotiating
     * between mime types accepted by the client and mime types available from TypoScript.
     * Available mime types are defined by page types. Additionally, each resource (subject)
     * can forward directly to different document representations (configured in each record).
     *
     * @param integer $pageType
     *
     * @return void
     */
    private function determineFormatAndContentType($pageType)
    {
        $this->getAcceptedMimeTypes();
        $this->getAvailableMimeTypes();

        // if a page type is already set, format and content type can be set directly
        if ($pageType > 0) {

            $this->contentType = $this->availableMimeTypes[$pageType];
            $this->format = $GLOBALS['TSFE']->tmpl->setup['types.'][$pageType];

        // if no page type is set compare accepted mime types with available mime types and set best format
        // reminder: $this->acceptedMimeTypes is in order from best to least format
        } else {

            foreach ($this->acceptedMimeTypes as $mimeType) {
                if (in_array($mimeType, $this->availableMimeTypes)) {
                    $type = array_search($mimeType, $this->availableMimeTypes);
                    if ($type == 0) {
                        continue;
                    } else {
                        $this->format = $GLOBALS['TSFE']->tmpl->setup['types.'][$type];
                    }
                    $this->contentType = $this->availableMimeTypes[$type];
                    break;
                }
            }
        }

    }

}
