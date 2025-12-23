<?php

declare(strict_types=1);

namespace EtfUnsa\SparkAcademics\Domain\Repository;

/**
 * Repository for Person
 */
class PersonRepository extends AbstractRepository
{
    public function findByFilters(int $primaryDepartmentUid, int $academicRankUid, int $academicTitleUid)
    {
        $demand = new \EtfUnsa\SparkAcademics\Domain\Model\Dto\Demand();
        
        if ($primaryDepartmentUid > 0) {
            $demand->addFilter('primaryDepartment', $primaryDepartmentUid);
        }
        if ($academicRankUid > 0) {
            $demand->addFilter('academicRank', $academicRankUid);
        }
        if ($academicTitleUid > 0) {
            $demand->addFilter('academicTitle', $academicTitleUid);
        }

        return $this->findByDemand($demand);
    }
}
