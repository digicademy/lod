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

}
