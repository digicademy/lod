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

use Digicademy\Lod\Utility\Frontend\SearchUtility;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

class IriRepository extends Repository
{

    protected $defaultOrderings = array(
        'label' => QueryInterface::ORDER_ASCENDING,
        'value' => QueryInterface::ORDER_ASCENDING
    );

    /**
     * @param string $value
     * @param object $entity
     * @param \Digicademy\Lod\Domain\Model\IriNamespace $namespace
     *
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findByValue($value, $namespace = null)
    {
        // initialize query object
        $query = $this->createQuery();

        // initialize constraints
        $constraints = [];

        // namespace constraint if given
        if ($namespace) $constraints[] = $query->equals('namespace', $namespace);

        // value constraint
        $constraints[] = $query->equals('value', $value);

        // match
        $query->matching(
            $query->logicalAnd($constraints)
        );

        // execute the query
        $result = $query->execute();

        // return result
        return $result;
    }

    /**
     * @param string $keywords
     *
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
     */
    public function findByQuery($keywords)
    {
        // initialize query object
        $query = $this->createQuery();

        // initialize constraints
        $constraints = [];

        if ($keywords) {

            $keywordList = SearchUtility::wordSplit($keywords);

            foreach ($keywordList as $keyword) {
                $keyword = '%' . $keyword . '%';
                $labelConstraint[] = $query->like('label', $keyword);
                $commentConstraint[] = $query->like('comment', $keyword);
                $valueConstraint[] = $query->like('value', $keyword);
            }

            $constraints[] = $query->logicalOr(
                $query->logicalAnd($labelConstraint),
                $query->logicalAnd($commentConstraint),
                $query->logicalAnd($valueConstraint)
            );
        }

        $query->matching(
            $query->logicalAnd($constraints)
        );

        // return result
        return $query->execute();
    }

}
