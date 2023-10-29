<?php
namespace Digicademy\Lod\Hooks\Backend;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2019 Torsten Schrade <Torsten.Schrade@adwmainz.de>
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

use Digicademy\Lod\Service\IdentifierGeneratorService;
use Digicademy\Lod\Service\TableTrackingService;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\VersionNumberUtility;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;

class DataHandler
{

    /**
     * Ensures that all fields in statement table are in sync depending on the editing context (IRRE or other)
     *
     * @param $status
     * @param $table
     * @param $id
     * @param $fieldArray
     * @param $pObj
     */
    public function processDatamap_postProcessFieldArray($status, $table, $id, &$fieldArray, &$pObj)
    {

        if ($table == 'tx_lod_domain_model_statement' && $fieldArray['sys_language_uid'] <= 0) {
            $fieldArray = $this->synchronizeStatement($status, $id, $fieldArray, $pObj);
            // force language of statements to ALL
            $fieldArray['sys_language_uid'] = -1;
        }

        if ($table == 'tx_lod_domain_model_iri' && $fieldArray['sys_language_uid'] <= 0) {
            $fieldArray = $this->synchronizeIri($status, $id, $fieldArray, $pObj);
            // force language of iris to ALL
            $fieldArray['sys_language_uid'] = -1;
        }

        // do not create iri, statement or representation records for any other language than default or all
        if (($table == 'tx_lod_domain_model_iri' || $table == 'tx_lod_domain_model_statement' || $table == 'tx_lod_domain_model_representation') && $fieldArray['sys_language_uid'] > 0) {
            $fieldArray = [];
        }

    }

    /**
     * Implements identifier generation IRIs and bnodes and table tracking for new/update/copy actions
     *
     * @param $status
     * @param $table
     * @param $id
     * @param $fieldArray
     * @param $pObj
     * @throws
     */
    public function processDatamap_afterDatabaseOperations($status, $table, $id, $fieldArray, $pObj)
    {
        // identifier generation for IRIs and bnodes
        if ($table == 'tx_lod_domain_model_iri' || $table == 'tx_lod_domain_model_bnode') {
            $this->generateIdentifier($status, $table, $id, $fieldArray, $pObj);
        }

        // prefix_value generation for finding IRIs with suggest wizard
        if ($table == 'tx_lod_domain_model_iri') {
            $this->generatePrefixValue($status, $table, $id, $fieldArray, $pObj);
        }

        // retrieve current sys_language_uid either from new/copy or update status
        if (array_key_exists('sys_language_uid', $fieldArray)) {
            $sysLanguageUid = $fieldArray['sys_language_uid'];
        }
        if ($status == 'update') {
            $record = BackendUtility::getRecord($table, (int)$id);
            $sysLanguageUid = $record['sys_language_uid'];
        }

        // track tables during IRI generation but only for records in the default or ALL language
        if ($sysLanguageUid <= 0) {
            $this->trackTables($status, $table, $id, $pObj);
        }
    }

    /**
     * Implements table tracking for delete/undelete actions
     *
     * @param $command
     * @param $table
     * @param $id
     * @param $value
     * @param $pObj
     * @param $pasteUpdate
     */
    public function processCmdmap_preProcess($command, $table, $id, $value, $pObj, $pasteUpdate)
    {
        if ($command == 'delete' || $command == 'undelete' ) {
            $this->trackTables($command, $table, $id);
        }
    }

    /**
     * Keeps subject, predicate, object values synchronised (with and without prepended table names)
     *
     * @param string $status
     * @param string $id
     * @param array $fieldArray
     * @param object $pObj
     *
     * @return array
     */
    private function synchronizeStatement($status, $id, $fieldArray, $pObj)
    {

        switch ($status) {
            case 'update':
            case 'new':
                // standard editing context with three group fields
                foreach (['subject', 'predicate', 'object'] as $key => $value) {
                    if (
                        array_key_exists($value . '_uid', $fieldArray) == false &&
                        array_key_exists($value . '_type', $fieldArray) == false &&
                        array_key_exists($value, $fieldArray) == true
                    ) {
                        $tableNameAndUid = BackendUtility::splitTable_Uid($fieldArray[$value]);
                        $fieldArray[$value . '_type'] = $tableNameAndUid[0];
                        $fieldArray[$value .'_uid'] = $tableNameAndUid[1];
                    }
                }

                // IRRE context: subsumed because parent and child tables will exist in the datamap
                $newStatements = 0;
                $parentStatement = 0;
                $reference = 0;
                if (array_key_exists('tx_lod_domain_model_statement', $pObj->datamap)) {
                    $newStatements = array_filter(
                        $pObj->datamap['tx_lod_domain_model_statement'],
                        function ($key) {
                            return(strpos($key,'NEW') !== false);
                        },
                        ARRAY_FILTER_USE_KEY
                    );
                    // check for reference statements
                    if ($newStatements) {
                        foreach ($pObj->datamap['tx_lod_domain_model_statement'] as $key => $value) {
                            if (
                                array_key_exists('reference_statements', $value) &&
                                strpos($value['reference_statements'], strval($id)) !== false
                            ) {
                                $parentStatement = $key;
                                if ($id !== $parentStatement) $reference = 1;
                            }
                        }
                    }
                }

                $parentTable = '';
                if (array_key_exists('tx_lod_domain_model_iri', $pObj->datamap)) {
                    $parentTable = 'tx_lod_domain_model_iri';
                } elseif (array_key_exists('tx_lod_domain_model_bnode', $pObj->datamap)) {
                    $parentTable = 'tx_lod_domain_model_bnode';
                } elseif ($newStatements && $parentStatement && $reference == 1) {
                    $parentTable = 'tx_lod_domain_model_statement';
                }

                if ($parentTable && substr($id, 0, 3) == 'NEW') {
                    if ($newStatements && $parentStatement && $reference == 1) {
                        if (substr($parentStatement, 0, 3) == 'NEW') $parentStatement = $pObj->substNEWwithIDs[$parentStatement];
                        $fieldArray['subject'] = 'tx_lod_domain_model_statement_' . $parentStatement;
                        $fieldArray['subject_uid'] = $parentStatement;
                        $fieldArray['subject_type'] = 'tx_lod_domain_model_statement';
                    } else {
                        $parentUid = key($pObj->datamap[$parentTable]);
                        if (substr($parentUid, 0, 3) == 'NEW') $parentUid = $pObj->substNEWwithIDs[$parentUid];
                        $fieldArray['subject'] = $parentTable . '_' . $parentUid;
                        $fieldArray['subject_uid'] = $parentUid;
                        $fieldArray['subject_type'] = $parentTable;
                    }
                }

                break;
        }

        return $fieldArray;
    }

    /**
     * Keeps record, record_uid and record_tablename field in IRI records in sync depending on editing context
     *
     * @param string $status
     * @param string $id
     * @param array $fieldArray
     * @param object $pObj
     *
     * @return array
     */
    private function synchronizeIri($status, $id, $fieldArray, $pObj)
    {

        switch ($status) {
            case 'update':
            case 'new':

                // standard editing context (single iri record)
                if (
                    array_key_exists('record_uid', $fieldArray) == false &&
                    array_key_exists('record_tablename', $fieldArray) == false &&
                    array_key_exists('record', $fieldArray) == true
                ) {
                    $tableNameAndUid = BackendUtility::splitTable_Uid($fieldArray['record']);
                    $fieldArray['record_tablename'] = $tableNameAndUid[0];
                    $fieldArray['record_uid'] = $tableNameAndUid[1];
                }

                // inline editing context (iri and parent table)
                if (count($pObj->datamap) > 1  && substr($id, 0, 3) == 'NEW') {
                    $parentTable = '';
                    $parentUid = 0;
                    foreach ($pObj->datamap as $tableName => $records) {
                        if ($tableName == 'tx_lod_domain_model_iri') continue;
                        foreach ($records as $uid => $fields) {
                            if (array_key_exists('iri', $fields) && preg_match('/' . $id . '/', $fields['iri'])) {
                                $parentTable = $tableName;
                                $parentUid = $uid;
                                if (substr($parentUid, 0, 3) == 'NEW') $parentUid = $pObj->substNEWwithIDs[$parentUid];
                            }
                        }
                    }
                    if ($parentTable && $parentUid) {
                        $fieldArray['record'] = $parentTable . '_' . $parentUid;
                        $fieldArray['record_uid'] = $parentUid;
                        $fieldArray['record_tablename'] = $parentTable;
                    }
                }

                break;
        }

        return $fieldArray;
    }

    /**
     * Generates identifiers using a generator specified in TSConfig
     *
     * @param $status
     * @param $table
     * @param $id
     * @param $fieldArray
     * @param $pObj
     * @throws
     */
    private function generateIdentifier($status, $table, $id, $fieldArray, $pObj)
    {
        // get full record - in case it is a new record swap id from substNEWwithIDs
        if ($status == 'new') $id = $pObj->substNEWwithIDs[$id];
        $record = BackendUtility::getRecord($table, (int)$id);

        // try to get TSConfig for current backend page (and NOT the page the IRI is possibly saved)
        // this is very likely the pid of the current parent record of the IRI
        // as fallback we take the pid the IRI is saved on (also for editing contexts without parent record)
        if ($record['record']) {
            $parentRecordPid = BackendUtility::getRecord($record['record_tablename'], (int)$record['record_uid'], 'pid');
            ($parentRecordPid['pid']) ? $pid = $parentRecordPid['pid'] : $pid = $record['pid'];
        } else {
            $pid = $record['pid'];
        }
        $TSConfig = BackendUtility::getPagesTSconfig($pid);

        // on copy action empty the value field - copy action can be guessed because t3_origuid is set
        if ($fieldArray['t3_origuid'] > 0) $record['value'] = '';

        // flag to only execute generation if it is configured in TSConfig
        $tableConfiguredForIdentifierGeneration = false;

        // optional identifier generation for iri table
        if ($table == 'tx_lod_domain_model_iri' && $TSConfig['tx_lod.']['settings.']['identifierGenerator.']['tx_lod_domain_model_iri.']) {
            $tableConfiguredForIdentifierGeneration = true;
            // mandatory identifier generation for bnode table
        } elseif ($table == 'tx_lod_domain_model_bnode') {
            // always set uid generator unless configured otherwise
            if (empty($TSConfig['tx_lod.']['settings.']['identifierGenerator.']['tx_lod_domain_model_bnode.'])) {
                $TSConfig['tx_lod.']['settings.']['identifierGenerator.']['tx_lod_domain_model_bnode.'] = [
                    'type' => 'Digicademy\Lod\Generator\UidIdentifierGenerator',
                    'Digicademy\Lod\Generator\UidIdentifierGenerator.' => ['bnodePrefix' => 'b']
                ];
            }
            $tableConfiguredForIdentifierGeneration = true;
        }

        // start generation if configured and no identifier exists
        if ($tableConfiguredForIdentifierGeneration == true && $record['value'] == '') {

            // get generator service
            $generatorService = GeneralUtility::makeInstance(IdentifierGeneratorService::class);
            $generatorName = $TSConfig['tx_lod.']['settings.']['identifierGenerator.'][$table . '.']['type'];

            if (class_exists($generatorName)) {

                // get configuration
                if ($TSConfig['tx_lod.']['settings.']['identifierGenerator.'][$table . '.'][$generatorName . '.']) {
                    $generatorConfiguration = $TSConfig['tx_lod.']['settings.']['identifierGenerator.'][$table . '.'][$generatorName . '.'];
                } else {
                    $generatorConfiguration = [];
                }

                // generate identifier
                $generatedIdentifier = $generatorService->generateIdentifier($generatorName, $generatorConfiguration, $record);

                // update record with generated identifier
                GeneralUtility::makeInstance(ConnectionPool::class)
                    ->getConnectionForTable($table)
                    ->update(
                        $table,
                        ['value' => $generatedIdentifier],
                        ['uid' => (int)$id]
                    );

            } else {
                throw new \TYPO3\CMS\Backend\Exception('Given identifier generator is not loaded and/or does not exist',
                    1577284728);
            }
        }
    }

    /**
     * Generates prefix:value combination for enhanced search with suggest wizard
     *
     * @param $status
     * @param $table
     * @param $id
     * @param $fieldArray
     * @param $pObj
     * @throws
     */
    private function generatePrefixValue($status, $table, $id, $fieldArray, $pObj)
    {
        if ($status == 'new') $id = $pObj->substNEWwithIDs[$id];
        $iri = BackendUtility::getRecord('tx_lod_domain_model_iri', (int)$id);
        $namespace = [];
        if ($iri['namespace'] > 0) {
            $namespace = BackendUtility::getRecord('tx_lod_domain_model_namespace', (int)$iri['namespace']);
        }
        array_key_exists('prefix', $namespace) ? $prefixValue = $namespace['prefix'] .':'. $iri['value'] : $prefixValue = $iri['value'];
        // update record
        GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_lod_domain_model_iri')
            ->update(
                'tx_lod_domain_model_iri',
                ['prefix_value' => $prefixValue],
                ['uid' => (int)$id]
            );
    }

    /**
     * Keeps track on registered tables and creates iris for records in those tables
     *
     * @param $action
     * @param $table
     * @param $id
     * @param $fieldArray
     * @param $pObj
     */
    private function trackTables($action, $table, $id, $pObj = null)
    {
        // get extension configuration
        $backendConfiguration = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('lod');

        // get list of tables registered in extConf for tracking
        $tablesToTrack = GeneralUtility::trimExplode(',', $backendConfiguration['trackTables']);

        // further steps only executed if current table is in tracked table list
        if (in_array($table, $tablesToTrack)) {

            // get the tracked record and according TSConfig
            if ($action == 'new' && is_object($pObj)) $id = $pObj->substNEWwithIDs[$id];
            // get record
            $trackedRecord = BackendUtility::getRecord($table, (int)$id);

            // get TSConfig for record
            $TSConfig = BackendUtility::getPagesTSconfig($trackedRecord['pid']);

            // only if the registered table is configured in TSConfig next steps are executed
            if ($TSConfig['tx_lod.']['settings.']['tableTracking.'][$table . '.']['track'] == '1') {
                $trackingService = GeneralUtility::makeInstance(
                    TableTrackingService::class,
                    $action,
                    $table,
                    $trackedRecord,
                    $TSConfig['tx_lod.']['settings.']['tableTracking.'][$table . '.']
                );
                $trackingService->track();
            }
        }
    }

}
