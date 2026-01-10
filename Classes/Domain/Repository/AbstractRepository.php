<?php

/*
 * This file is part of the "Spark Academics" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) Ernedin Zajko <ezajko@root.ba>
 */

declare(strict_types=1);

namespace EtfUnsa\SparkAcademics\Domain\Repository;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Repository;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;

abstract class AbstractRepository extends Repository
{
    protected array $searchFields = ['title'];

    public function initializeObject(): void
    {
        /** @var Typo3QuerySettings $querySettings */
        $querySettings = GeneralUtility::makeInstance(Typo3QuerySettings::class);
        
        // Default settings for academic repositories:
        // We set respectStoragePage to false so that items can be fetched globally 
        // without requiring a specific storagePid to be set in TypoScript.
        $querySettings->setRespectStoragePage(false);
        
        // Language settings: respect the current language to avoid duplicate records
        // Fallback behavior is handled by site configuration
        $querySettings->setRespectSysLanguage(true);
        
        $this->setDefaultQuerySettings($querySettings);
    }

    /**
     * @param \EtfUnsa\SparkAcademics\Domain\Model\Dto\Demand $demand
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findByDemand(\EtfUnsa\SparkAcademics\Domain\Model\Dto\Demand $demand)
    {
        $query = $this->createQuery();
        if (!$demand->hasFilters()) {
            return $query->execute();
        }

        $constraints = [];
        foreach ($demand->getFilters() as $filter) {
            $prop = $filter['property'];
            $val = $filter['value'];
            $op = $filter['operator'];

            // Skip empty values (including 0 which usually means 'All' in frontend filters)
            if (empty($val)) {
                continue;
            }

            // Dynamic constraint creation (e.g. $query->equals(), $query->contains())
            if (method_exists($query, $op)) {
                $constraints[] = $query->{$op}($prop, $val);
            } else {
                // Fallback to equals if invalid operator is provided
                $constraints[] = $query->equals($prop, $val);
            }
        }

        if (!empty($demand->getSearch())) {
            $search = $demand->getSearch();
            $searchConstraints = [];
            foreach ($this->searchFields as $field) {
                // Check if property exists in model is hard without ReflectionService injection,
                // but let's assume repositories set valid fields.
                $searchConstraints[] = $query->like($field, '%' . $search . '%');
            }
            if (!empty($searchConstraints)) {
                $constraints[] = $query->logicalOr(...$searchConstraints);
            }
        }

        if (!empty($constraints)) {
            $query->matching($query->logicalAnd(...$constraints));
        }

        return $query->execute();
    }
}
