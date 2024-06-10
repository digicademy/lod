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

use Digicademy\Lod\Domain\Model\Record;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Backend\Form\FormDataProvider\TcaRecordTitle;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * Service to load and map records from generic TCA group fields
 */
class ItemMappingService
{

    /**
     * @param string $record
     *
     * @return object
     */
    public function mapItem($record)
    {
        $item = null;

        $result = $this->load($record);

        if ($result['row']) {
            $item = $this->map($result['row'], $result['tablename']);
        }

        return $item;
    }

    /**
     * @param string $record
     *
     * @return \Digicademy\Lod\Domain\Model\Record
     */
    public function mapGenericItem($record)
    {
        $item = null;

        $result = $this->load($record);

        if ($result['row']) {

            $formDataProvider = GeneralUtility::makeInstance(TcaRecordTitle::class);
            $tcaProcessing = $formDataProvider->addData([
                'databaseRow' => $result['row'],
                'processedTca' => $GLOBALS['TCA'][$result['tablename']],
                'tablename' => $result['tablename'],
                'title' => $GLOBALS['TSFE']->sL($GLOBALS['TCA'][$result['tablename']]['ctrl']['title'])
            ]);

            $item = GeneralUtility::makeInstance(Record::class);
            $item->setLabel($tcaProcessing['recordTitle']);
            $item->setComment($tcaProcessing['title']);
            $item->setTablename($result['tablename']);
            $item->setRow($tcaProcessing['databaseRow']);
            $item->_setProperty('uid', (int)$tcaProcessing['databaseRow']['uid']);
            $item->setPid($tcaProcessing['databaseRow']['pid']);
            $item->setDomainObject($this->map($result['row'], $result['tablename']));
        }

        return $item;
    }

    /**
     * Loads a record (syntax: tablename_uid)
     *
     * @param string $record
     *
     * @return array
     */
    protected function load($record)
    {
        $result = [];

        // split incoming record into tablename and uid
        $tableNameAndUid = BackendUtility::splitTable_Uid($record);
        $tablename = $tableNameAndUid[0];
        $uid = $tableNameAndUid[1];

        // if class and tablename exist perform MM query for items, map them and add them to the object storage
        if ($tablename && $uid) {

            $row = GeneralUtility::makeInstance(ConnectionPool::class)
                ->getConnectionForTable($tablename)
                ->select(
                    ['*'], // fields
                    $tablename, // from
                    [ 'uid' => (int)$uid ] // where
                )->fetch();

            if ($row) {
                $result = [
                    'tablename'  => $tablename,
                    'uid' => $uid,
                    'row' => $row
                ];
            }
        }

        return $result;
    }

    /**
     * Maps record row to a configured domain object
     *
     * @param array $row
     * @param string $tablename
     *
     * @return object
     */
    protected function map($row, $tablename)
    {
        $result = null;

        // try to find a TypoScript based class mapping for the given tablename
        $configurationManager = GeneralUtility::makeInstance(ConfigurationManager::class);
        $className = '';
        $frameworkConfiguration = $configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);

        foreach ($frameworkConfiguration['persistence']['classes'] as $key => $value) {
            // if current table name matches a configured table name
            if (
                array_key_exists('mapping', $value) &&
                array_key_exists('tableName', $value['mapping']) &&
                $value['mapping']['tableName'] == $tablename
            ) {
                // check if recordType is configured and matches the row
                if (array_key_exists('recordType', $value['mapping'])) {
                    $typeColumnName = $GLOBALS['TCA'][$tablename]['ctrl']['type'];
                    if ($row[$typeColumnName] == $value['mapping']['recordType']) {
                        $className = $key;
                    } else {
                        continue;
                    }
                // if no recordType is configured directly match table name to configured class
                } else {
                    $className = $key;
                }
            }
        }

        // if a class name exists, map row to domain object
        if ($className) {
            // from 9.5 onwards we (irritatingly) need the object manager otherwise DI does not seem to work
            $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
            $dataMapper = $objectManager->get(DataMapper::class);
            $mappedRecord = $dataMapper->map($className, [$row]);
            $result = $mappedRecord[0];
        }

        return $result;
    }

    /**
     * Signal/Slot method that maps tablename_uid strings from TCA group fields to objects
     *
     * @return void
     */
    public function mapGenericProperty($domainObject)
    {
        // map record property of IRI object (if not empty)
        if (get_class($domainObject) == 'Digicademy\Lod\Domain\Model\Iri') {
            if ($domainObject->getRecord() !== '') $domainObject->setRecord($this->mapGenericItem($domainObject->getRecord()));
        }

        // map subject, predicate and object in statements
        if (get_class($domainObject) == 'Digicademy\Lod\Domain\Model\Statement') {
            if ($domainObject->getSubject() !== '') $domainObject->setSubject($this->mapItem($domainObject->getSubject()));
            if ($domainObject->getPredicate() !== '') $domainObject->setPredicate($this->mapItem($domainObject->getPredicate()));
            if ($domainObject->getObject() !== '') $domainObject->setObject($this->mapItem($domainObject->getObject()));
        }
    }

}
