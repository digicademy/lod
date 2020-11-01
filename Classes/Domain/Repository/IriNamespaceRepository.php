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

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException;

class IriNamespaceRepository extends Repository
{
    protected $defaultOrderings = array(
        'prefix' => QueryInterface::ORDER_ASCENDING
    );

    /**
     * @param string $action
     * @param array  $settings
     *
     * @return QueryResultInterface
     * @throws InvalidQueryException
     */
    public function findSelected($action, $settings)
    {
        $query = $this->createQuery();

        $constraints = [];

        // optionally switch to defined uid list of namespaces
        $namespaceList = $this->includeNamespaceList($action, $settings);
        if ($namespaceList) {
            $query->getQuerySettings()->setRespectStoragePage(false);
            $constraints[] = $query->in('uid', $namespaceList);
            $query->matching(
                $query->logicalAnd($constraints)
            );
        }

        // optionally extend storagePids for the query
        if ($settings[$action]['additionalPidList']) {
            $additionalPidList = GeneralUtility::intExplode(',', $settings[$action]['additionalPidList'], true);
            $query->getQuerySettings()->setStoragePageIds(
                array_merge($query->getQuerySettings()->getStoragePageIds(), $additionalPidList)
            );
        }

        return $query->execute();
    }

    /**
     * @param string $prefix
     * @param string $action
     * @param array  $settings
     *
     * @return QueryResultInterface
     * @throws InvalidQueryException
     */
    public function findByPrefix($prefix, $action, $settings)
    {
        $query = $this->createQuery();

        $constraints = [];

        $namespaceList = $this->includeNamespaceList($action, $settings);

        // optionally switch to defined uid list of namespaces
        if ($namespaceList) {
            $query->getQuerySettings()->setRespectStoragePage(false);
            $constraints[] = $query->in('uid', $namespaceList);
        }

        // optionally extend storagePids for the query
        if ($settings[$action]['additionalPidList']) {
            $additionalPidList = GeneralUtility::intExplode(',', $settings[$action]['additionalPidList'], true);
            $query->getQuerySettings()->setStoragePageIds(
                array_merge($query->getQuerySettings()->getStoragePageIds(), $additionalPidList)
            );
        }

        $constraints[] = $query->like('prefix', $prefix);

        $query->matching(
            $query->logicalAnd($constraints)
        );

        return $query->execute();
    }

    /**
     * @param string $action
     * @param array $settings
     *
     * @return array
     */
    private function includeNamespaceList($action, $settings)
    {
        $namespaceList = [];

        switch ($action) {
            case 'show':
                    if ($settings['show']['namespaceList']) {
                        $namespaceList = GeneralUtility::intExplode(',', $settings['show']['namespaceList']);
                    }
                break;
            case 'list':
                    if ($settings['list']['namespaceList']) {
                        $namespaceList = GeneralUtility::intExplode(',', $settings['list']['namespaceList']);
                    }
                break;
        }

        return $namespaceList;
    }
}
