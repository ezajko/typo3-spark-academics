<?php

declare(strict_types=1);

namespace EtfUnsa\SparkAcademics\Service;

use EtfUnsa\SparkAcademics\Domain\Model\Dto\Demand;
use EtfUnsa\SparkAcademics\Domain\Repository\ChairRepository;
use EtfUnsa\SparkAcademics\Domain\Repository\DepartmentRepository;
use EtfUnsa\SparkAcademics\Domain\Repository\ResearchGroupRepository;
use EtfUnsa\SparkAcademics\Domain\Repository\ResearchLabRepository;

class DemandService
{
    protected DepartmentRepository $departmentRepository;
    protected ResearchLabRepository $researchLabRepository;
    protected ResearchGroupRepository $researchGroupRepository;
    protected ChairRepository $chairRepository;

    public function __construct(
        DepartmentRepository $departmentRepository,
        ResearchLabRepository $researchLabRepository,
        ResearchGroupRepository $researchGroupRepository,
        ChairRepository $chairRepository
    ) {
        $this->departmentRepository = $departmentRepository;
        $this->researchLabRepository = $researchLabRepository;
        $this->researchGroupRepository = $researchGroupRepository;
        $this->chairRepository = $chairRepository;
    }

    public function createFromSettings(array $settings): Demand
    {
        $demand = new Demand();
        // Logical operator from settings if available (optional)
        if (isset($settings['filter']['operator'])) {
            $demand->setLogicalOperator($settings['filter']['operator']);
        }
        $entityType = $settings['entityType'] ?? '';

        // Check for specific UIDs (Selection Mode)
        if (!empty($settings['uids'])) {
             $uidList = \TYPO3\CMS\Core\Utility\GeneralUtility::intExplode(',', $settings['uids'], true);
             if (!empty($uidList)) {
                 $demand->addFilter('uid', $uidList, 'in');
             }
        }

        // Person-specific filters
        if ($entityType === 'Person') {
            $this->applyPersonFilters($demand, $settings);
        }
        
        // Course-specific filters
        if ($entityType === 'Course') {
            $this->applyCourseFilters($demand, $settings);
        }

        return $demand;
    }

    protected function applyCourseFilters(Demand $demand, array $settings): void
    {
        $departmentUid = (int)($settings['filter']['department'] ?? $settings['filter.department'] ?? $settings['filter_department'] ?? 0);
        $chairUid = (int)($settings['filter']['chair'] ?? $settings['filter.chair'] ?? $settings['filter_chair'] ?? 0);
        
        // Filter by Department
        if ($departmentUid > 0) {
            $department = $this->departmentRepository->findBy(['uid' => $departmentUid])->getFirst();
            if ($department) {
                $demand->addFilter('department', $department);
            }
        }
        
        // Filter by Chair
        if ($chairUid > 0) {
            $chair = $this->chairRepository->findBy(['uid' => $chairUid])->getFirst();
            if ($chair) {
                $demand->addFilter('chair', $chair);
            }
        }
    }

    protected function applyPersonFilters(Demand $demand, array $settings): void
    {
        $primaryDepartmentUid = (int)($settings['filter']['primary_department'] ?? $settings['filter.primary_department'] ?? $settings['filter_primary_department'] ?? 0);
        $departmentUid = (int)($settings['filter']['department'] ?? $settings['filter.department'] ?? $settings['filter_department'] ?? 0);
        $academicRankUid = (int)($settings['filter']['academic_rank'] ?? $settings['filter.academic_rank'] ?? $settings['filter_academic_rank'] ?? 0);
        $academicTitleUid = (int)($settings['filter']['academic_title'] ?? $settings['filter.academic_title'] ?? $settings['filter_academic_title'] ?? 0);
        $groupUid = (int)($settings['filter']['research_group'] ?? $settings['filter.research_group'] ?? $settings['filter_research_group'] ?? 0);
        $labUid = (int)($settings['filter']['research_lab'] ?? $settings['filter.research_lab'] ?? $settings['filter_research_lab'] ?? 0);
        $chairUid = (int)($settings['filter']['chair'] ?? $settings['filter.chair'] ?? $settings['filter_chair'] ?? 0);

        if ($primaryDepartmentUid > 0) {
            $primaryDepartment = $this->departmentRepository->findBy(['uid' => $primaryDepartmentUid])->getFirst();
            if ($primaryDepartment) {
                $demand->addFilter('primaryDepartment', $primaryDepartment);
            } else {
                 $demand->addFilter('primaryDepartment', $primaryDepartmentUid);
            }
        }
        if ($departmentUid > 0) {
            $department = $this->departmentRepository->findBy(['uid' => $departmentUid])->getFirst();
            if ($department) {
                $demand->addFilter('departments', $department, 'contains');
            }
        }
        if ($academicRankUid > 0) {
            $demand->addFilter('academicRank', $academicRankUid);
        }
        if ($academicTitleUid > 0) {
            $demand->addFilter('academicTitle', $academicTitleUid);
        }
        if ($groupUid > 0) {
            $group = $this->researchGroupRepository->findBy(['uid' => $groupUid])->getFirst();
            if ($group) {
                $demand->addFilter('groups', $group, 'contains');
            }
        }
        if ($labUid > 0) {
            $lab = $this->researchLabRepository->findBy(['uid' => $labUid])->getFirst();
            if ($lab) {
                $demand->addFilter('laboratories', $lab, 'contains');
            }
        }
        if ($chairUid > 0) {
            $chair = $this->chairRepository->findBy(['uid' => $chairUid])->getFirst();
            if ($chair) {
                $demand->addFilter('chairs', $chair, 'contains');
            }
        }
    }
}
