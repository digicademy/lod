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

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Database\QueryGenerator;
use TYPO3\CMS\Core\Utility\VersionNumberUtility;
use TYPO3\CMS\Core\DataHandling\DataHandler;

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
                $uid = 'NEW_' . uniqid('');
                ($this->configuration['createOnPid']) ? $pid = (int)$this->configuration['createOnPid'] : $pid = (int)$this->record['pid'];
                ($this->configuration['createType']) ? $type = (int)$this->configuration['createType'] : $type = 1;
                $dataMap = array(
                    'tx_lod_domain_model_iri' => array(
                        $uid => [
                            'pid' => $pid,
                            'type' => $type,
                            'hidden' => $this->record['hidden'],
                            'record' => $tableAndUid,
                        ],
                    )
                );
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

        $typo3Version = VersionNumberUtility::convertVersionNumberToInteger(VersionNumberUtility::getNumericTypo3Version());
        if ($typo3Version >= 8007000) {
// @TODO: implement 8.7 and 9.5 doctrine query
        } else {
            $result = BackendUtility::getRecordsByField(
                'tx_lod_domain_model_iri',
                'record',
                $this->table . '_' . $this->record['uid'],
                'AND pid IN (' . $iriPidList . ')'
            );
        }

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
