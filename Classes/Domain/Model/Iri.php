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

use Digicademy\Lod\Service\ItemMappingService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class Iri extends AbstractEntity
{

    /**
     * type
     *
     * @var integer
     */
    protected $type;

    /**
     * title
     *
     * @var string
     */
    protected $title = '';

    /**
     * description
     *
     * @var string
     */
    protected $description = '';

    /**
     * namespace
     *
     * @var \Digicademy\Lod\Domain\Model\IriNamespace
     */
    protected $namespace;

    /**
     * value
     *
     * @var string
     * @validate NotEmpty
     */
    protected $value;

    /**
     * tablename
     *
     * @var string
     */
    protected $tablename;

    /**
     * record
     *
     * @var string
     */
    protected $record;

    /**
     * item retrieved from record
     *
     * @var object
     */
    protected $item = null;

    /**
     * Document representations for the subject
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Digicademy\Lod\Domain\Model\Representation> $representations
     * @lazy
     */
    protected $representations;

    /**
     * Returns the type
     *
     * @return integer type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets the type
     *
     * @param string $type
     *
     * @return void
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Returns the title
     *
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the title
     *
     * @param string $title
     *
     * @return void
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Returns the description
     *
     * @return string $description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets the description
     *
     * @param string $description
     *
     * @return void
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Returns the namespace
     *
     * @return \Digicademy\Lod\Domain\Model\IriNamespace $namespace
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * Sets the namespace
     *
     * @param \Digicademy\Lod\Domain\Model\IriNamespace $namespace
     *
     * @return void
     */
    public function setNamespace(\Digicademy\Lod\Domain\Model\IriNamespace $namespace)
    {
        $this->namespace = $namespace;
    }

    /**
     * Returns the value
     *
     * @return string $value
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Sets the value
     *
     * @param string $value
     *
     * @return void
     */
    public function setValue($value)
    {
        $this->value = $value;
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
     * Returns the record
     *
     * @return string $record
     */
    public function getRecord()
    {
        return $this->record;
    }

    /**
     * Sets the record
     *
     * @param string $record
     *
     * @return void
     */
    public function setRecord($record)
    {
        $this->record = $record;
    }

    /**
     * Loads the item
     *
     * @return object
     */
    public function getItem()
    {
        $itemMappingService = GeneralUtility::makeInstance(ItemMappingService::class);
        $item = $itemMappingService->loadItem($this->getRecord());
        $this->setItem($item);
        return $item;
    }

    /**
     * Sets the item
     *
     * @param $item
     *
     * @return void
     */
    public function setItem($item)
    {
        $this->item = $item;
    }

    /**
     * Returns the representations
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Digicademy\Lod\Domain\Model\Representation> $representations
     */
    public function getRepresentations()
    {
        return $this->representations;
    }

    /**
     * Sets the representations
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Digicademy\Lod\Domain\Model\Representation> $representations
     *
     * @return void
     */
    public function setRepresentations($representations)
    {
        $this->representations = $representations;
    }

}
