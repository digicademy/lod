<?php
namespace Digicademy\Lod\Service;

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

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\QueryGenerator;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

class TableTrackingService
{

    /**
     * @var array
     */
    protected $configuration;

    /**
     * @var array
     */
    protected $record;

    /**
     * @var string
     */
    protected $table;

    /**
     * @var string
     */
    protected $action;

    /**
     * Service constructor
     */
     public function __construct($action, $table, $record, $configuration)
     {
        $this->action = $action;
        $this->table = $table;
        $this->record = $record;
        $this->configuration = $configuration;
     }

    /**
     * Creates IRI records for records in tracked tables
     */
     public function track()
     {
        $existingIRIs = $this->iriExists();
        $tableAndUid = $this->table . '_' . $this->record['uid'];
        $dataMap = [];
        $cmdMap = [];

        // create contentObjectRenderer for TypoScript functionality during record creation
        $contentObjectRenderer = GeneralUtility::makeInstance(ContentObjectRenderer::class);
        $contentObjectRenderer->start($this->record, $this->table);

        // first of all check if IRI exists for the current record
        if ($existingIRIs) {
            // action update and hideUnhide = 1 is set
            if ($this->action == 'update' && array_key_exists('hidden', $this->record) && $this->configuration['hideUnhide'] == '1') {
                foreach ($existingIRIs as $iri) {
                    if ($this->record['hidden'] != $iri['hidden']) {
                        $dataMap = array(
                            'tx_lod_domain_model_iri' => array(
                                $iri['uid'] => ['hidden' => $this->record['hidden']],
                            )
                        );
                    }
                }
            }
            // action delete and deleteUndelete = 1 is set
            if ($this->action == 'delete' && $this->configuration['deleteUndelete'] == '1') {
                foreach ($existingIRIs as $iri) {
                    $cmdMap = array(
                        'tx_lod_domain_model_iri' => array(
                            $iri['uid'] => ['delete' => 1],
                        )
                    );
                }
            }
            // action undelete and deleteUndelete = 1 is set
            if ($this->action == 'undelete' && $this->configuration['deleteUndelete'] == '1') {
                foreach ($existingIRIs as $iri) {
                    $cmdMap = array(
                        'tx_lod_domain_model_iri' => array(
                            $iri['uid'] => ['undelete' => 1],
                        )
                    );
                }
            }
        } else {
            // this case covers all three conditions - new, copy, update - if no iri exists for the current record
            // in case of 'new' tracked record an iri is created
            // in case of an 'updated' tracked record that has no iri (this is why we are in else) also leads to iri creation
            // a copied tracked record is the same as a new record - no iri will yet exists with a 'tablename_uid' in the iri record field
            if ($this->action == 'new' || $this->action == 'update') {

                $iriUid = 'NEW' . uniqid('');

                if ($this->configuration['iri.']['pid']) {
                    $pid = (int)$contentObjectRenderer->stdWrap($this->configuration['iri.']['pid'], $this->configuration['iri.']['pid.']);
                } else {
                    $pid = (int)$this->record['pid'];
                }

                ($this->configuration['iri.']['type'] || $this->configuration['iri.']['type.']) ?
                    $type = (int)$contentObjectRenderer->stdWrap($this->configuration['iri.']['type'], $this->configuration['iri.']['type.']) : $type = 1;

                ($this->configuration['iri.']['namespace'] || $this->configuration['iri.']['namespace.']) ?
                    $namespace = (int)$contentObjectRenderer->stdWrap($this->configuration['iri.']['namespace'], $this->configuration['iri.']['namespace.']) : $namespace = 0;

                ($this->configuration['iri.']['label'] || $this->configuration['iri.']['label.']) ?
                    $label = $contentObjectRenderer->stdWrap($this->configuration['iri.']['label'], $this->configuration['iri.']['label.']) : $label = '';

                ($this->configuration['iri.']['label_language'] || $this->configuration['iri.']['label_language.']) ?
                    $label_language = (int)$contentObjectRenderer->stdWrap($this->configuration['iri.']['label_language'], $this->configuration['iri.']['label_language.']) : $label_language = 0;

                ($this->configuration['iri.']['comment'] || $this->configuration['iri.']['comment.']) ?
                    $comment = $contentObjectRenderer->stdWrap($this->configuration['iri.']['comment'], $this->configuration['iri.']['comment.']) : $comment = '';

                ($this->configuration['iri.']['comment_language'] || $this->configuration['iri.']['comment_language.']) ?
                    $comment_language = (int)$contentObjectRenderer->stdWrap($this->configuration['iri.']['comment_language'], $this->configuration['iri.']['comment_language.']) : $comment_language = 0;

                $dataMap = [
                    'tx_lod_domain_model_iri' => [
                        $iriUid => [
                            'pid' => $pid,
                            'type' => $type,
                            'hidden' => $this->record['hidden'],
                            'namespace' => $namespace,
                            'label' => $label,
                            'label_language' => $label_language,
                            'comment' => $comment,
                            'comment_language' => $comment_language,
                            'record' => $tableAndUid,
                            'record_uid' => $this->record['uid'],
                            'record_tablename' => $this->table,
                        ],
                    ]
                ];

                if (is_array($this->configuration['representations.'])) {

                    foreach ($this->configuration['representations.'] as $representationToCreate) {

                        $representationUid = 'NEW' . uniqid('');

                        ($representationToCreate['pid'] || $representationToCreate['pid.']) ?
                            $representationPid = (int)$contentObjectRenderer->stdWrap(
                                $representationToCreate['pid'], $representationToCreate['pid.']
                            ) : $representationPid = 1;

                        ($representationToCreate['scheme'] || $representationToCreate['scheme.']) ?
                            $scheme = $contentObjectRenderer->stdWrap(
                                $representationToCreate['scheme'], $representationToCreate['scheme.']
                            ) : $scheme = '';

                        ($representationToCreate['authority'] || $representationToCreate['authority.']) ?
                            $authority = $contentObjectRenderer->stdWrap(
                                $representationToCreate['authority'], $representationToCreate['authority.']
                            ) : $authority = '';

                        ($representationToCreate['path'] || $representationToCreate['path.']) ?
                            $path = $contentObjectRenderer->stdWrap(
                                $representationToCreate['path'], $representationToCreate['path.']
                            ) : $path = '';

                        ($representationToCreate['query'] || $representationToCreate['query.']) ?
                            $query = $contentObjectRenderer->stdWrap(
                                $representationToCreate['query'], $representationToCreate['query.']
                            ) : $query = '';

                        ($representationToCreate['fragment'] || $representationToCreate['fragment.']) ?
                            $fragment = $contentObjectRenderer->stdWrap(
                                $representationToCreate['fragment'], $representationToCreate['fragment.']
                            ) : $fragment = '';

                        ($representationToCreate['content_type'] || $representationToCreate['content_type.']) ?
                            $content_type = $contentObjectRenderer->stdWrap(
                                $representationToCreate['content_type'], $representationToCreate['content_type.']
                            ) : $content_type = '';

                        ($representationToCreate['content_language'] || $representationToCreate['content_language.']) ?
                            $content_language = $contentObjectRenderer->stdWrap(
                                $representationToCreate['content_language'], $representationToCreate['content_language.']
                            ) : $content_language = '';

                        $dataMap['tx_lod_domain_model_representation'][$representationUid] = [
                            'pid' => $representationPid,
                            'parent' => $iriUid,
                            'scheme' => $scheme,
                            'authority'  => $authority,
                            'path' => $path,
                            'query' => $query,
                            'fragment' => $fragment,
                            'content_type' => $content_type,
                            'content_language' => $content_language
                        ];
                    }
                }
            }

            // in case of a deleted tracked record that has no IRI nothing is done
        }

        $tce = GeneralUtility::makeInstance(DataHandler::class);

        if ($dataMap) {
            $tce->start($dataMap, null);
            $tce->process_datamap();
        } elseif ($cmdMap) {
            $tce->start(null, $cmdMap);
            $tce->process_cmdmap();
        }

// @TODO: if iri was created and "createRepresentations" and/or "createStatements" is set: implement right here

     }

    /**
     * Checks if an IRI exists for the tracked record (by looking at the record field and the uid)
     * @return array A result array with iri records if existing
     */
    private function iriExists()
    {
        if ($this->configuration['iriPidList']) {
            (is_array($this->configuration['iriPidList.']) && array_key_exists('recursive', $this->configuration['iriPidList.'])) ?
                $recursive = $this->configuration['iriPidList.']['recursive'] : $recursive = 0;
            $iriPidList = $this->getIriPidList($this->configuration['iriPidList'], $recursive);
        } else {
            $iriPidList = $this->record['pid'];
        }

        $pidList = GeneralUtility::intExplode(',', $iriPidList);

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_lod_domain_model_iri');

        $result = $queryBuilder
            ->select('*')
            ->from('tx_lod_domain_model_iri')
            ->where(
                $queryBuilder->expr()->andX(
                    $queryBuilder->expr()->eq('record', ':record'),
                    $queryBuilder->expr()->in('pid', ':pidList')
                )
            )
            ->setParameter('record', $this->table . '_' . $this->record['uid'], \PDO::PARAM_STR)
            ->setParameter('pidList', $pidList, \Doctrine\DBAL\Connection::PARAM_INT_ARRAY)
            ->execute()
            ->fetchAll();

        return $result;
    }

    /**
     * Compiles a potentially recursive pid list on which to look for IRI records
     *
     * @param string $pidList
     * @param int $recursive
     *
     * @return string
     */
    protected function getIriPidList($pidList, $recursive)
    {
        $recursiveIriPids = '';
        $storagePids = \TYPO3\CMS\Core\Utility\GeneralUtility::intExplode(',', $pidList);
        $permsClause = $GLOBALS['BE_USER']->getPagePermsClause(1);
        $queryGenerator = GeneralUtility::makeInstance(QueryGenerator::class);
        foreach ($storagePids as $startPid) {
            $pids = $queryGenerator->getTreeList($startPid, $recursive, 0, $permsClause);
            if ((string)$pids !== '') {
                $recursiveIriPids .= $pids . ',';
            }
        }

        return rtrim($recursiveIriPids, ',');
    }

}
