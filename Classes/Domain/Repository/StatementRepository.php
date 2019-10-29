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
     * @param \Digicademy\Lod\Domain\Model\Iri $iri
     *
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findBySubjectAndObject($iri)
    {
        // initialize query object
        $query = $this->createQuery();

        // table_uid constraint in subject OR object position
        $query->matching(
            $query->logicalOr(
                $query->equals('subject', 'tx_lod_domain_model_iri_' . $iri->getUid()),
                $query->equals('object', 'tx_lod_domain_model_iri_' . $iri->getUid())
            )
        );

        // execute the query
        $result = $query->execute();

        // return result
        return $result;
    }

    /**
     * @param \Digicademy\Lod\Domain\Model\Iri $iri
     *
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findByPredicate($iri)
    {
        // initialize query object
        $query = $this->createQuery();

        // table_uid constraint in predicate position
        $query->matching(
            $query->equals('predicate', 'tx_lod_domain_model_iri_' . $iri->getUid())
        );

        // execute the query
        $result = $query->execute();

        // return result
        return $result;
    }

}
