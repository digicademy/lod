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

use TYPO3\CMS\Extbase\DomainObject\AbstractValueObject;

class Representation extends AbstractValueObject
{

    /**
     * parent
     *
     * @var string
     */
    protected $parent;

    /**
     * tablename
     *
     * @var string
     */
    protected $tablename;

    /**
     * scheme
     *
     * @var string
     */
    protected $scheme;

    /**
     * authority
     *
     * @var string
     */
    protected $authority;

    /**
     * path
     *
     * @var string
     */
    protected $path;

    /**
     * query
     *
     * @var string
     */
    protected $query;

    /**
     * fragment
     *
     * @var string
     */
    protected $fragment;

    /**
     * contentType
     *
     * @var string
     */
    protected $contentType;

    /**
     * contentLanguage
     *
     * @var string
     */
    protected $contentLanguage;

    /**
     * Returns the parent
     *
     * @return string $parent
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Sets the parent
     *
     * @param string $parent
     *
     * @return void
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
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
     * Returns the scheme
     *
     * @return string $scheme
     */
    public function getScheme()
    {
        return $this->scheme;
    }

    /**
     * Sets the scheme
     *
     * @param string $scheme
     *
     * @return void
     */
    public function setScheme($scheme)
    {
        $this->scheme = $scheme;
    }

    /**
     * Returns the authority
     *
     * @return string $authority
     */
    public function getAuthority()
    {
        return $this->authority;
    }

    /**
     * Sets the authority
     *
     * @param string $authority
     *
     * @return void
     */
    public function setAuthority($authority)
    {
        $this->authority = $authority;
    }

    /**
     * Returns the path
     *
     * @return string $path
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Sets the path
     *
     * @param string $path
     *
     * @return void
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * Returns the query
     *
     * @return string $query
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Sets the query
     *
     * @param string $query
     *
     * @return void
     */
    public function setQuery($query)
    {
        $this->query = $query;
    }

    /**
     * Returns the fragment
     *
     * @return string $fragment
     */
    public function getFragment()
    {
        return $this->fragment;
    }

    /**
     * Sets the fragment
     *
     * @param string $fragment
     *
     * @return void
     */
    public function setFragment($fragment)
    {
        $this->fragment = $fragment;
    }

    /**
     * Returns the contentType
     *
     * @return string $contentType
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * Sets the contentType
     *
     * @param string $contentType
     *
     * @return void
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
    }

    /**
     * Returns the contentLanguage
     *
     * @return string $contentLanguage
     */
    public function getContentLanguage()
    {
        return $this->contentLanguage;
    }

    /**
     * Sets the contentLanguage
     *
     * @param string $contentLanguage
     *
     * @return void
     */
    public function setContentLanguage($contentLanguage)
    {
        $this->contentLanguage = $contentLanguage;
    }

}
