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
use TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Backend\Form\FormDataProvider\TcaRecordTitle;

/**
 * Service to load and map records from generic TCA group fields
 */
class ItemMappingService
{

    /**
     * Loads the item
     *
     * @param string $record
     *
     * @return mixed
     */
    public function loadItem($record)
    {
        $result = null;

        // split incoming record into tablename and uid
        $tableNameAndUid = BackendUtility::splitTable_Uid($record);
        $tablename = $tableNameAndUid[0];
        $uid = $tableNameAndUid[1];

        // try to find a TypoScript based class mapping for the given tablename
        $configurationManager = GeneralUtility::makeInstance(ConfigurationManager::class);
        $className = '';
        $frameworkConfiguration = $configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);

        foreach ($frameworkConfiguration['persistence']['classes'] as $key => $value) {
            if ($value['mapping']['tableName'] == $tablename) {
                $className = $key;
            }
        }

// @TODO: implement generic record class

        // if class and tablename exist perform MM query for items, map them and add them to the object storage
        if ($tablename && $uid) {
// @TODO: Doctrine switch for 8.7 and 9.5
            $resource = $this->getDatabaseConnection()->exec_SELECTgetRows(
                '*',
                $tablename,
                'uid=' . (int) $uid,
                '',
                '',
                '1'
            );

            if ($resource) {

                // if a class name exists, map row to domain object
                if ($className) {

                    $dataMapper = GeneralUtility::makeInstance(DataMapper::class);
                    $mappedRecord = $dataMapper->map($className, $resource);
                    $result = $mappedRecord[0];

                // if no class name exists, map row to generic object
                } else {

                    $formDataProvider = GeneralUtility::makeInstance(TcaRecordTitle::class);
                    $tcaProcessing = $formDataProvider->addData([
                        'databaseRow' => $resource[0],
                        'processedTca' => $GLOBALS['TCA'][$tablename],
                        'tablename' => $tablename,
                        'title' => $GLOBALS['TSFE']->sL($GLOBALS['TCA'][$tablename]['ctrl']['title'])
                    ]);

                    $result = GeneralUtility::makeInstance(Record::class);
                    $result->setLabel($tcaProcessing['recordTitle']);
                    $result->setTablename($tablename);
                    $result->setType($tcaProcessing['title']);
                    $result->setRow($tcaProcessing['databaseRow']);
                    $result->_setProperty('uid', (int)$tcaProcessing['databaseRow']['uid']);
                    $result->setPid($tcaProcessing['databaseRow']['pid']);
                }
            }
        }

        return $result;

    }

    /**
     * Gets the database object.
     *
     * @return \TYPO3\CMS\Core\Database\DatabaseConnection
     */
    protected function getDatabaseConnection()
    {
        return $GLOBALS['TYPO3_DB'];
    }

    /**
     * Signal/Slot method that maps tablename_uid strings from TCA group fields to objects
     *
     * @return void
     */
    public function mapGenericProperty(&$domainObject)
    {
        // map record property of IRI object (if not empty)
        if (get_class($domainObject) == 'Digicademy\Lod\Domain\Model\Iri') {
            if ($domainObject->getRecord() !== '') $domainObject->setRecord($this->loadItem($domainObject->getRecord()));
        }

        // map subject, predicate and object in statements
        if (get_class($domainObject) == 'Digicademy\Lod\Domain\Model\Statement') {
            if ($domainObject->getSubject() !== '') $domainObject->setSubject($this->loadItem($domainObject->getSubject()));
            if ($domainObject->getPredicate() !== '') $domainObject->setPredicate($this->loadItem($domainObject->getPredicate()));
            if ($domainObject->getObject() !== '') $domainObject->setObject($this->loadItem($domainObject->getObject()));
        }
    }

}
