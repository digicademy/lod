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

class UuidIdentifierGenerator extends AbstractIdentifierGenerator implements IdentifierGeneratorInterface
{

    /**
     * Generates an (optionally xml conforming) uuid with a possible prefix for entities, properties or bnodes
     *
     * @return string
     */
    public function generate()
    {
        if ($this->configuration['xmlConformance'] == '1') {
            do {
                $uuid = $this->createUUID();
            } while (preg_match('/^[a-z]/', $uuid) !== 1);
        } else {
            $uuid = $this->createUUID();
        }

        return $this->setIdentifierPrefix($uuid);
    }

    /**
     * Generates a universally unique identifier (UUID) according to RFC 4122 v4.
     * The algorithm used here, might not be completely random.
     *
     * @return string The universally unique id
     */
    private function createUUID()
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff));
    }

}
