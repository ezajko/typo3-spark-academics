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

use EtfUnsa\SparkAcademics\Domain\Model\Dto\ProjectDemand;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

class ProjectRepository extends AbstractRepository
{
    public function findByProjectDemand(ProjectDemand $demand, array $orderings = []): QueryResultInterface
    {
        $query = $this->createQuery();
        $constraints = [];

        // 1. Search (Title, Acronym, Description, Keywords, Grant Number)
        if ($demand->getSearch()) {
            $search = $demand->getSearch();
            $constraints[] = $query->logicalOr(
                $query->like('title', '%' . $search . '%'),
                $query->like('acronym', '%' . $search . '%'),
                $query->like('description', '%' . $search . '%'),
                $query->like('keywords', '%' . $search . '%'),
                $query->like('grantAgreementNumber', '%' . $search . '%')
            );
        }

        // 2. Project Status
        if ($demand->getProjectStatus()) {
            $constraints[] = $query->equals('status', $demand->getProjectStatus());
        }

        // 3. Project Type
        if ($demand->getProjectType()) {
            $constraints[] = $query->equals('projectType', $demand->getProjectType());
        }

        // 4. Funding Program
        if ($demand->getFundingProgram()) {
            $constraints[] = $query->equals('fundingProgram', $demand->getFundingProgram());
        }

        // 5. Scientific Field (M:N relation)
        // Note: Extbase 'contains' works for M:N checks if the property holds the object
        // But here we have UID. We assume 'scientificFields' is the property name in Project model.
        if ($demand->getScientificField()) {
            $constraints[] = $query->contains('scientificFields', $demand->getScientificField());
        }

        // 6. Dates
        // Valid if: (StartDate <= FilterTo) AND (EndDate >= FilterFrom)
        // Overlapping Logic
        if ($demand->getDateFrom()) {
            // Project must end AFTER the filter start date
            $constraints[] = $query->greaterThanOrEqual('endDate', $demand->getDateFrom()->getTimestamp());
        }
        if ($demand->getDateTo()) {
            // Project must start BEFORE the filter end date
            $constraints[] = $query->lessThanOrEqual('startDate', $demand->getDateTo()->getTimestamp());
        }

        // 7. Organizational Units (Implicit Joins)
        if ($demand->getDepartment()) {
            $constraints[] = $query->equals('departments.uid', $demand->getDepartment());
        }
        if ($demand->getResearchLab()) {
            $constraints[] = $query->equals('researchLabs.uid', $demand->getResearchLab());
        }
        if ($demand->getResearchGroup()) {
            $constraints[] = $query->equals('researchGroups.uid', $demand->getResearchGroup());
        }
        if ($demand->getChair()) {
            $constraints[] = $query->equals('chairs.uid', $demand->getChair());
        }

        // 8. Backend User Permission (Permissions)
        if ($demand->getBackendUser()) {
             $constraints[] = $query->equals('beUsers.uid', $demand->getBackendUser());
        }

        if (!empty($constraints)) {
            $query->matching($query->logicalAnd(...$constraints));
        }

        // Order by sorting by default, or define specific ordering
        if (!empty($orderings)) {
            $query->setOrderings($orderings);
        } else {
            $query->setOrderings(['sorting' => QueryInterface::ORDER_ASCENDING]);
        }
        
        return $query->execute();
    }
}
