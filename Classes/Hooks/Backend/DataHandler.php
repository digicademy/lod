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

use TYPO3\CMS\Backend\Utility\BackendUtility;

class DataHandler
{

    /**
     * Generates XML conformant UUIDs for bnodes
     *
     * @param $status
     * @param $table
     * @param $id
     * @param $fieldArray
     * @param $pObj
     */
    public function processDatamap_postProcessFieldArray($status, $table, $id, &$fieldArray, &$pObj)
    {
        if (
            $table == 'tx_lod_domain_model_bnode'
        ) {
            $generate = false;

            switch ($status) {

                case 'new':
                    // copy record - can be guessed because in this case t3_origuid is filled and a origin record exists
                    if ($fieldArray['t3_origuid'] > 0) {
                        $originalRecord = BackendUtility::getRecord($table, (int)$fieldArray['t3_origuid'], 'value');
                        if ($originalRecord['value']) {
                            $generate = true;
                        }
                    // create record
                    } elseif (empty($fieldArray['value'])) {
                        $generate = true;
                    }
                    break;

                case 'update':
                    $record = BackendUtility::getRecord($table, (int)$id, 'value');
                    if (empty($fieldArray['value']) && !$record['value']) {
                        $generate = true;
                    }
                    break;
            }

            if ($generate == true) {
                do {
                    $uuid = $this->generateUUID();
                } while (preg_match('/^[a-z]/', $uuid) !== 1);
                $fieldArray['value'] = $uuid;
            }
        }
    }

    /**
     * Generates a universally unique identifier (UUID) according to RFC 4122 v4.
     * The algorithm used here, might not be completely random. Copied from the identity extension.
     *
     * @return string The universally unique id
     * @author Unknown
     */
    private function generateUUID()
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff));
    }

}
