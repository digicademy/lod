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
use Digicademy\Lod\Domain\Repository\IriNamespaceRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\QueryResult;
use TYPO3\CMS\Extbase\Persistence\Repository;

class IriRepository extends Repository
{

    protected $defaultOrderings = array(
        'value' => QueryInterface::ORDER_ASCENDING,
        'label' => QueryInterface::ORDER_ASCENDING
    );

    /**
     * IRI lookup by string representation (like 'prefix:value').
     * Takes given namespaces into account. If IRI contains more than
     * one colon it is interpreted as value without namespace prefix
     *
     * @param string $value
     *
     * @return object|null;
     */
    public function findByValue($value)
    {
        // initialize query object
        $query = $this->createQuery();

        // initialize constraints
        $constraints = [];

        // namespace constraint (but only if it appears once and without http(s)://)
        if (substr_count($value, ':') == 1 && substr_count($value, '://') == 0) {

            $iriParts = GeneralUtility::trimExplode(':', $value);

            $iriNamespaceRepository = $this->objectManager->get(IriNamespaceRepository::class);
            $namespace = $iriNamespaceRepository->findByPrefix($iriParts[0])->getFirst();

            if ($namespace) {
                $constraints[] = $query->equals('namespace', $namespace);
                $value = $iriParts[1];
            // if namespace was given but could not be resolved whole IRI has to be considered non existent
            } else {
                $value = '';
            }
        }

        // only continue if no namespace was given or given namespace could be resolved successfully
        if ($value) {

            // value constraint
            $constraints[] = $query->equals('value', $value);
            // match
            $query->matching(
                $query->logicalAnd($constraints)
            );
            // execute
            $result = $query->execute()->getFirst();

        } else {
            // empty result
            $result = null;
        }

        return $result;
    }

    /**
     * @param array $arguments
     *
     * @return object|null
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
     */
    public function findByArguments($arguments)
    {
        // initialize query object
        $query = $this->createQuery();

        // initialize constraints
        $constraints = [];

        // search constraint
        if ($arguments['query']) {

            $keywordList = SearchUtility::wordSplit($arguments['query']);

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

        // $subject constraint if valid
        if ($arguments['subject']) {
            $subject = $this->findByValue($arguments['subject']);
            if ($subject) {
                $constraints[] = $query->equals('statements.subject_uid', $subject);
            } else {
                $constraints[] = $query->equals('statements.subject_uid', null);
            }
        }

        // predicate constraint if valid
        if ($arguments['predicate']) {
            $predicate = $this->findByValue($arguments['predicate']);
            if ($predicate) {
                $constraints[] = $query->equals('statements.predicate_uid', $predicate);
            } else {
                $constraints[] = $query->equals('statements.predicate_uid', null);
            }
        }

        // object constraint if valid
        if ($arguments['object']) {
            $object = $this->findByValue($arguments['object']);
            if ($object) {
                $constraints[] = $query->equals('statements.object_uid', $object);
            } else {
                $constraints[] = $query->equals('statements.object_uid', null);
            }
        }

        $query->matching(
            $query->logicalAnd($constraints)
        );

        return $query->execute();
    }

}
