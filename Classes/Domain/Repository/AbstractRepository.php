<?php

declare(strict_types=1);

namespace EtfUnsa\SparkAcademics\Domain\Repository;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Repository;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;

abstract class AbstractRepository extends Repository
{
    public function initializeObject(): void
    {
        /** @var Typo3QuerySettings $querySettings */
        $querySettings = GeneralUtility::makeInstance(Typo3QuerySettings::class);
        
        // Default settings for academic repositories:
        // We set respectStoragePage to false so that items can be fetched globally 
        // without requiring a specific storagePid to be set in TypoScript.
        $querySettings->setRespectStoragePage(false);
        
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

        if (!empty($constraints)) {
            $query->matching($query->logicalAnd(...$constraints));
        }

        return $query->execute();
    }
}
