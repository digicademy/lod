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
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class Vocabulary extends AbstractEntity
{

    /**
     * IRI
     *
     * @var \Digicademy\Lod\Domain\Model\Iri
     */
    protected $iri;

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
    protected $comment = '';

    /**
     * terms
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Digicademy\Lod\Domain\Model\Iri>
     */
    protected $terms = null;

    /**
     * Returns the graph iri
     *
     * @return \Digicademy\Lod\Domain\Model\Iri $iri
     */
    public function getIri()
    {
        return $this->iri;
    }

    /**
     * Sets the graph iri
     *
     * @param \Digicademy\Lod\Domain\Model\Iri $iri
     *
     * @return void
     */
    public function setIri(\Digicademy\Lod\Domain\Model\Iri $iri)
    {
        $this->iri = $iri;
    }


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
     * Returns the terms
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Digicademy\Lod\Domain\Model\Iri> $terms
     */
    public function getTerms()
    {
        return $this->terms;
    }

    /**
     * Sets the terms
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Digicademy\Lod\Domain\Model\Iri> $terms
     *
     * @return void
     */
    public function setTerms(ObjectStorage $terms)
    {
        $this->terms = $terms;
    }

}
