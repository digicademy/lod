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
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\VersionNumberUtility;

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
        if ($table == 'tx_lod_domain_model_statement') {
            $fieldArray = $this->synchronizeStatement($status, $id, $fieldArray, $pObj);
        }

        if ($table == 'tx_lod_domain_model_iri') {
            $fieldArray = $this->synchronizeIri($status, $id, $fieldArray, $pObj);
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

        // track tables during IRI generation
        $this->trackTables($status, $table, $id, $pObj);
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

                // inline edition context (iri/bnode table) with predicate/object fields
                // IRRE context is subsumed because then parent and child tables will exist in the datamap
                $parentTable = '';
                if (array_key_exists('tx_lod_domain_model_iri', $pObj->datamap)) {
                    $parentTable = 'tx_lod_domain_model_iri';
                } elseif (array_key_exists('tx_lod_domain_model_bnode', $pObj->datamap)) {
                    $parentTable = 'tx_lod_domain_model_bnode';
                }
                if ($parentTable && substr($id, 0, 3) == 'NEW') {
                    $parentUid = key($pObj->datamap[$parentTable]);
                    if (substr($parentUid, 0, 3) == 'NEW') $parentUid = $pObj->substNEWwithIDs[$parentUid];
                    $fieldArray['subject'] = $parentTable . '_' . $parentUid;
                    $fieldArray['subject_uid'] = $parentUid;
                    $fieldArray['subject_type'] = $parentTable;
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
        // get TSConfig for record
        $TSConfig = BackendUtility::getPagesTSconfig($record['pid']);

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
// @TODO: 9.5 compatibility
                $GLOBALS['TYPO3_DB']->exec_UPDATEquery($table, 'uid = ' . (int)$id, ['value' => $generatedIdentifier]);
            } else {
                throw new \TYPO3\CMS\Backend\Exception('Given identifier generator is not loaded and/or does not exist',
                    1577284728);
            }
        }
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
        $typo3Version = VersionNumberUtility::convertVersionNumberToInteger(VersionNumberUtility::getNumericTypo3Version());
        if ($typo3Version >= 9005000) {
// @TODO: check 9.5 compat
            $backendConfiguration = GeneralUtility::makeInstance(
                \TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class
            )->get('lod');
        } else {
            $extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['lod']);
        }

        // get list of tables registered in extConf for tracking
        $tablesToTrack = GeneralUtility::trimExplode(',', $extConf['trackTables']);

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
