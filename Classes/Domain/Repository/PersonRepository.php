<?php

declare(strict_types=1);

namespace EtfUnsa\SparkAcademics\Domain\Repository;

/**
 * Repository for Person
 */
class PersonRepository extends AbstractRepository
{
    public function findByPersonDemand(\EtfUnsa\SparkAcademics\Domain\Model\Dto\PersonDemand $demand, array $orderings = []): \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
    {
        $query = $this->createQuery();
        $constraints = [];

        if ($demand->getSearch() !== '') {
            $constraints[] = $query->logicalOr(
                $query->like('firstName', '%' . $demand->getSearch() . '%'),
                $query->like('lastName', '%' . $demand->getSearch() . '%'),
                $query->like('contactEmail', '%' . $demand->getSearch() . '%')
            );
        }

        if ($demand->getDepartment() > 0) {
            $constraints[] = $query->contains('departments', $demand->getDepartment());
        }

        if ($demand->getAcademicRank() > 0) {
            $constraints[] = $query->equals('academicRank', $demand->getAcademicRank());
        }

        if ($demand->getAcademicTitle() > 0) {
            $constraints[] = $query->equals('academicTitle', $demand->getAcademicTitle());
        }

        if ($demand->getBackendUser() > 0) {
            $constraints[] = $query->contains('beUsers', $demand->getBackendUser());
        }

        if (!empty($constraints)) {
            $query->matching($query->logicalAnd(...$constraints));
        }

        if (!empty($orderings)) {
            $query->setOrderings($orderings);
        }

        return $query->execute();
    }
}
