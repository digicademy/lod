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

class Literal extends AbstractEntity
{

    /**
     * value
     *
     * @var string
     * @validate NotEmpty
     */
    protected $value;

    /**
     * language
     *
     * @var string
     */
    protected $language;

    /**
     * datatype
     *
     * @var string
     */
    protected $datatype;

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
     * Returns the language
     *
     * @return string $lang
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Sets the language
     *
     * @param string $language
     *
     * @return void
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * Returns the datatype
     *
     * @return string $datatype
     */
    public function getDatatype()
    {
        return $this->datatype;
    }

    /**
     * Sets the datatype
     *
     * @param string $datatype
     *
     * @return void
     */
    public function setDatatype($datatype)
    {
        $this->datatype = $datatype;
    }

}
