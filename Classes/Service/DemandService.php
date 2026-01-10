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

        // StudyProgram-specific filters
        if ($entityType === 'StudyProgram') {
            $this->applyStudyProgramFilters($demand, $settings);
        }

        return $demand;
    }

    protected function applyStudyProgramFilters(Demand $demand, array $settings): void
    {
        $departmentUid = (int)($settings['filter']['department'] ?? 0);
        $typeUid = (int)($settings['filter']['studyType'] ?? 0);
        $modeUid = (int)($settings['filter']['modeOfStudy'] ?? 0);
        $languageUid = (int)($settings['filter']['language'] ?? 0);

        // Filter by Department
        if ($departmentUid > 0) {
            $department = $this->departmentRepository->findBy(['uid' => $departmentUid])->getFirst();
            if ($department) {
                $demand->addFilter('departments', $department, 'contains');
            }
        }

        // Filter by Study Type
        if ($typeUid > 0) {
            $demand->addFilter('studyTypes', $typeUid, 'contains');
        }

        // Filter by Mode of Study
        if ($modeUid > 0) {
            $demand->addFilter('modesOfStudy', $modeUid, 'contains');
        }

        // Filter by Language
        if ($languageUid > 0) {
            $demand->addFilter('languages', $languageUid, 'contains');
        }
    }

    protected function applyCourseFilters(Demand $demand, array $settings): void
    {
        $departmentUid = (int)($settings['filter']['department'] ?? 0);
        $chairUid = (int)($settings['filter']['chair'] ?? 0);
        $cycleUid = (int)($settings['filter']['studyCycle'] ?? 0);
        $categoryUid = (int)($settings['filter']['courseCategory'] ?? 0);
        $fieldUid = (int)($settings['filter']['scientificField'] ?? 0);
        
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
        
        // Filter by Study Cycle (via Sullabus)
        if ($cycleUid > 0) {
            $demand->addFilter('syllabi.studyCycle', $cycleUid);
        }

        // Filter by Course Category (via Syllabus)
        if ($categoryUid > 0) {
            $demand->addFilter('syllabi.courseCategory', $categoryUid);
        }

        // Filter by Scientific Field (via Syllabus)
        if ($fieldUid > 0) {
            $demand->addFilter('syllabi.scientificField', $fieldUid);
        }
    }

    protected function applyPersonFilters(Demand $demand, array $settings): void
    {
        $primaryDepartmentUid = (int)($settings['filter']['primaryDepartment'] ?? 0);
        $departmentUid = (int)($settings['filter']['department'] ?? 0);
        $academicRankUid = (int)($settings['filter']['academicRank'] ?? 0);
        $academicTitleUid = (int)($settings['filter']['academicTitle'] ?? 0);
        $groupUid = (int)($settings['filter']['researchGroup'] ?? 0);
        $labUid = (int)($settings['filter']['researchLab'] ?? 0);
        $chairUid = (int)($settings['filter']['chair'] ?? 0);

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
