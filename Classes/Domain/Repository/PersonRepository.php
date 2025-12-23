<?php

declare(strict_types=1);

namespace EtfUnsa\SparkAcademics\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * Repository for Person
 */
class PersonRepository extends Repository
{
    public function initializeObject(): void
    {
        $querySettings = $this->createQuery()->getQuerySettings();
        $querySettings->setRespectStoragePage(false);
        $this->setDefaultQuerySettings($querySettings);
    }

    /**
     * @param int $primaryDepartmentUid
     * @param int $academicRankUid
     * @param int $academicTitleUid
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findByFilters(int $primaryDepartmentUid, int $academicRankUid, int $academicTitleUid)
    {
        $query = $this->createQuery();
        $constraints = [];

        if ($primaryDepartmentUid > 0) {
            $constraints[] = $query->equals('primaryDepartment', $primaryDepartmentUid);
        }
        if ($academicRankUid > 0) {
            $constraints[] = $query->equals('academicRank', $academicRankUid);
        }
        if ($academicTitleUid > 0) {
            $constraints[] = $query->equals('academicTitle', $academicTitleUid);
        }

        if (!empty($constraints)) {
            $query->matching($query->and(...$constraints));
        }

        return $query->execute();
    }
}
