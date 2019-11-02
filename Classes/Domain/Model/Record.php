<?php
namespace Digicademy\Lod\Domain\Model;

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

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class Record extends AbstractEntity
{

    /**
     * label
     *
     * @var string
     */
    protected $label;

    /**
     * comment
     *
     * @var string
     */
    protected $comment;

    /**
     * tablename
     *
     * @var string
     */
    protected $tablename;

    /**
     * row
     *
     * @var array
     */
    protected $row;

    /**
     * domainObject
     *
     * @var object
     */
    protected $domainObject = null;

    /**
     * Returns the label
     *
     * @return string $label
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Sets the label
     *
     * @param string $label
     *
     * @return void
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * Returns the comment
     *
     * @return string $comment
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Sets the comment
     *
     * @param string $comment
     *
     * @return void
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    /**
     * Returns the tablename
     *
     * @return string $tablename
     */
    public function getTablename()
    {
        return $this->tablename;
    }

    /**
     * Sets the tablename
     *
     * @param string $tablename
     *
     * @return void
     */
    public function setTablename($tablename)
    {
        $this->tablename = $tablename;
    }

    /**
     * Returns the row
     *
     * @return array $row
     */
    public function getRow()
    {
        return $this->row;
    }

    /**
     * Sets the row
     *
     * @param array $row
     *
     * @return void
     */
    public function setRow($row)
    {
        $this->row = $row;
    }

    /**
     * Returns the domainObject
     *
     * @return object $domainObject
     */
    public function getDomainObject()
    {
        return $this->domainObject;
    }

    /**
     * Sets the domainObject
     *
     * @param object $domainObject
     *
     * @return void
     */
    public function setDomainObject($domainObject)
    {
        $this->domainObject = $domainObject;
    }

}
