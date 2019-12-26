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

abstract class AbstractIdentifierGenerator
{

    /**
    * @var array $configuration
    */
    protected $configuration;

    /**
     * @var array $record
     */
    protected $record;

    /**
     * AbstractResolver constructor
     *
     * @param array $configuration
     * @param array $record
     */
    public function __construct($configuration, $record)
    {
        $this->configuration = $configuration;
        $this->record = $record;
    }

    /**
     * Prefixes an identifier with a string set in TSConfig (depending on type entitiy, property or bnode)
     *
     * @param string $identifier
     *
     * @return string
     */
    protected function setIdentifierPrefix($identifier) {

        switch ($this->record['type']) {
            case '1':
                ($this->configuration['entityPrefix']) ?
                    $prefixedIdentifier = $this->configuration['entityPrefix'] . $identifier :
                    $prefixedIdentifier = $identifier;
                break;
            case '2':
                ($this->configuration['propertyPrefix']) ?
                    $prefixedIdentifier = $this->configuration['propertyPrefix'] . $identifier :
                    $prefixedIdentifier = $identifier;
                break;
            default:
                ($this->configuration['bnodePrefix']) ?
                    $prefixedIdentifier = $this->configuration['bnodePrefix'] . $identifier :
                    $prefixedIdentifier = $identifier;
                break;
        }

        return $prefixedIdentifier;
    }

}
