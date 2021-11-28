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

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Annotation as Extbase;
use TYPO3\CMS\Extbase\Annotation\ORM\Lazy;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class Statement extends AbstractEntity
{

    /**
     * graph
     *
     * @var \Digicademy\Lod\Domain\Model\Graph
     */
    protected $graph;

    /**
     * subject
     *
     * @var string
     */
    protected $subject;

    /**
     * predicate
     *
     * @var string
     */
    protected $predicate;

    /**
     * object
     *
     * @var string
     */
    protected $object;

    /**
     * reference statements
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Digicademy\Lod\Domain\Model\Statement> $referenceStatements
     * @Lazy
     */
    protected $referenceStatements;

    /**
     * objectRecursion
     *
     * @var int
     */
    protected $objectRecursion;

    /**
     * objectInversion
     *
     * @var int
     */
    protected $objectInversion;

    /**
     * Returns the named graph
     *
     * @return \Digicademy\Lod\Domain\Model\Graph $graph
     */
    public function getGraph()
    {
        return $this->graph;
    }

    /**
     * Sets the graph
     *
     * @param \Digicademy\Lod\Domain\Model\Graph $graph
     *
     * @return void
     */
    public function setGraph(\Digicademy\Lod\Domain\Model\Graph $graph)
    {
        $this->graph = $graph;
    }

    /**
     * Returns the subject
     *
     * @return mixed
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Sets the subject
     *
     * @param $subject
     *
     * @return void
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * Returns the predicate
     *
     * @return mixed
     */
    public function getPredicate()
    {
        return $this->predicate;
    }

    /**
     * Sets the predicate
     *
     * @param $predicate
     *
     * @return void
     */
    public function setPredicate($predicate)
    {
        $this->predicate = $predicate;
    }

    /**
     * Returns the object
     *
     * @return mixed
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * Sets the object
     *
     * @param $object
     *
     * @return void
     */
    public function setObject($object)
    {
        $this->object = $object;
    }

    /**
     * Returns the objectRecursion
     *
     * @return int
     */
    public function getObjectRecursion()
    {
        return $this->objectRecursion;
    }

    /**
     * Sets the objectRecursion
     *
     * @param int $objectRecursion
     *
     * @return void
     */
    public function setObjectRecursion($objectRecursion)
    {
        $this->objectRecursion = $objectRecursion;
    }

    /**
     * Returns the objectInversion
     *
     * @return int
     */
    public function getObjectInversion()
    {
        return $this->objectInversion;
    }

    /**
     * Sets the objectInversion
     *
     * @param int $objectInversion
     *
     * @return void
     */
    public function setObjectInversion($objectInversion)
    {
        $this->objectInversion = $objectInversion;
    }

    /**
     * Returns valid reference statements (RDF*)
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Digicademy\Lod\Domain\Model\Statement> $referenceStatements
     */
    public function getReferenceStatements()
    {
        $statementObjectStorage = GeneralUtility::makeInstance(ObjectStorage::class);

        foreach ($this->referenceStatements as $statement) {
            if ($statement->getPredicate() !== null &&
                $statement->getObject() !== null) {
                    $statementObjectStorage->attach($statement);
            }
        }

        $this->referenceStatements = $statementObjectStorage;

        return $this->referenceStatements;
    }

    /**
     * Sets the referenceStatements
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Digicademy\Lod\Domain\Model\Statement> $referenceStatements
     *
     * @return void
     */
    public function setReferenceStatements($referenceStatements)
    {
        $this->referenceStatements = $referenceStatements;
    }

}
