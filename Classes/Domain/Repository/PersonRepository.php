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

/**
 * Repository for Person
 * 
 * @author Ernedin Zajko <ezajko@root.ba>
 */
class PersonRepository extends AbstractRepository
{
    /**
     * Default ordering for Person lists: by last name, then first name
     */
    protected $defaultOrderings = [
        'lastName' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING,
        'firstName' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING,
    ];
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
            $constraints[] = $query->equals('primaryDepartment', $demand->getDepartment());
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
