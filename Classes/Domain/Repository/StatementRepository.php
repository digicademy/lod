<?php
namespace Digicademy\Lod\Domain\Repository;

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

use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

class StatementRepository extends Repository
{

    protected $defaultOrderings = array(
        'subject' => QueryInterface::ORDER_ASCENDING
    );

    /**
     * @param string $position
     * @param object $entity
     * @param \Digicademy\Lod\Domain\Model\IriNamespace $graphName
     *
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     * @throws
     */
    public function findEntityByPosition($position, $entity, $graphName = null)
    {
        // initialize query object
        $query = $this->createQuery();

        // initialize constraints
        $constraints = [];

        // find IRIs or Bnodes in subject, predicate or object position
        if ($position == 'subject' || $position == 'predicate' || $position == 'object') {
            switch (get_class($entity)) {
                case 'Digicademy\Lod\Domain\Model\Iri':
                    // set position
                    $constraints[] = $query->equals($position, 'tx_lod_domain_model_iri_' . $entity->getUid());
                    // possibly set graph name
                    if ($graphName) $constraints[] = $query->equals('name', $graphName);
                    break;
                case 'Digicademy\Lod\Domain\Model\Bnode':
                    // set position
                    $constraints[] = $query->equals($position, 'tx_lod_domain_model_bnode_' . $entity->getUid());
                    break;
                default:
                    throw new \TYPO3\CMS\Extbase\Exception('Unknown entity class', 1572638672);
                    break;
            }
        } else {
            throw new \TYPO3\CMS\Extbase\Exception('Position string can only be subject, predicate or object', 1572638693);
        }

        // match
        $query->matching(
            $query->logicalAnd($constraints)
        );

        // execute the query
        $result = $query->execute();

        // return result
        return $result;
    }

}
