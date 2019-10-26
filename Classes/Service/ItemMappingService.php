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

use TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Service to load and map records from generic TCA group entries
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

        // if class and tablename exist perform MM query for items, map them and add them to the object storage
        if ($tablename && $className) {
// @TODO: Doctrine switch
            $resource = $this->getDatabaseConnection()->exec_SELECTgetRows(
                '*',
                $tablename,
                'uid=' . (int) $uid,
                '',
                '',
                '1'
            );
            if ($resource) {
                $dataMapper = GeneralUtility::makeInstance(DataMapper::class);
                $result = $dataMapper->map($className, $resource);
            }
        }

        return $result[0];

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

}
