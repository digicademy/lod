<?php
namespace Digicademy\Lod\Generator;

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

use TYPO3\CMS\Backend\Utility\BackendUtility;

class ForeignRecordIdentifierGenerator extends AbstractIdentifierGenerator implements IdentifierGeneratorInterface
{

    /**
     * Takes the uid of the record associated with the IRI as basis for the identifier generation.
     * Will only set an identifier if the record field in the IRI is set
     *
     * @return string
     */
    public function generate()
    {
        $identifier = '';

        if ($this->configuration['includeTablename'] == '1' && $this->record['record']) {
            $identifier = $this->record['record'];
        } elseif ($this->record['record']) {
            $tableNameAndUid = BackendUtility::splitTable_Uid($this->record['record']);
            $identifier = $tableNameAndUid[1];
        }

        return $this->setIdentifierPrefix($identifier);
    }

}
