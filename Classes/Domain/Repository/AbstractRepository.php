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

    public function findByBackendUser(int $beUserUid)
    {
        $querySettings = $this->createQuery()->getQuerySettings();
        $querySettings->setRespectStoragePage(false);
        $querySettings->setRespectSysLanguage(false);
        
        $demand = new \EtfUnsa\SparkAcademics\Domain\Model\Dto\Demand();
        $demand->addFilter('beUsers', $beUserUid, 'contains');
        
        return $this->findByDemand($demand);
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

            // Skip empty values if they are not specifically requested
            if (empty($val) && $val !== 0 && $val !== '0') {
                continue;
            }

            switch ($op) {
                case 'contains':
                    $constraints[] = $query->contains($prop, $val);
                    break;
                case 'like':
                    $constraints[] = $query->like($prop, '%' . $val . '%');
                    break;
                case 'greaterThan':
                    $constraints[] = $query->greaterThan($prop, $val);
                    break;
                case 'lessThan':
                    $constraints[] = $query->lessThan($prop, $val);
                    break;
                case 'in':
                    $constraints[] = $query->in($prop, (array)$val);
                    break;
                case 'equals':
                default:
                    $constraints[] = $query->equals($prop, $val);
                    break;
            }
        }

        if (!empty($constraints)) {
            if ($demand->getLogicalOperator() === 'OR') {
                $query->matching($query->logicalOr(...$constraints));
            } else {
                $query->matching($query->logicalAnd(...$constraints));
            }
        }

        return $query->execute();
    }
}
