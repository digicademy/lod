<?php
namespace Digicademy\Lod\Resolver;

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

use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

abstract class AbstractResolver
{
    /**
     * @var ContentObjectRenderer
     */
    protected $contentObjectRenderer;

    /**
    * @var array
    */
    protected $settings;

    /**
     * AbstractResolver constructor
     *
     * @param array $settings
     * @param $contentObjectRenderer ContentObjectRenderer
     */
    public function __construct($settings, ContentObjectRenderer $contentObjectRenderer)
    {
        $this->settings = $settings;
        $this->contentObjectRenderer = $contentObjectRenderer;
    }

}
