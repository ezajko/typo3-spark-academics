<?php

declare(strict_types=1);

namespace EtfUnsa\SparkAcademics\Controller;

use EtfUnsa\SparkAcademics\Domain\Model\Dto\Demand;
use EtfUnsa\SparkAcademics\Domain\Model\Chair;
use EtfUnsa\SparkAcademics\Domain\Model\Course;
use EtfUnsa\SparkAcademics\Domain\Model\Department;
use EtfUnsa\SparkAcademics\Domain\Model\Person;
use EtfUnsa\SparkAcademics\Domain\Model\Project;
use EtfUnsa\SparkAcademics\Domain\Model\ResearchGroup;
use EtfUnsa\SparkAcademics\Domain\Model\ResearchLab;
use EtfUnsa\SparkAcademics\Domain\Model\StudyProgram;
use EtfUnsa\SparkAcademics\Domain\Repository\ChairRepository;
use EtfUnsa\SparkAcademics\Domain\Repository\CourseRepository;
use EtfUnsa\SparkAcademics\Domain\Repository\DepartmentRepository;
use EtfUnsa\SparkAcademics\Domain\Repository\PersonRepository;
use EtfUnsa\SparkAcademics\Domain\Repository\ProjectRepository;
use EtfUnsa\SparkAcademics\Domain\Repository\ResearchGroupRepository;
use EtfUnsa\SparkAcademics\Domain\Repository\ResearchLabRepository;
use EtfUnsa\SparkAcademics\Domain\Repository\StudyProgramRepository;
use Psr\Http\Message\ResponseInterface;

class AcademicController extends AbstractFrontendController
{
    protected DepartmentRepository $departmentRepository;
    protected ResearchLabRepository $labRepository;
    protected ResearchGroupRepository $groupRepository;
    protected ChairRepository $chairRepository;
    protected CourseRepository $courseRepository;
    protected StudyProgramRepository $programRepository;
    protected ProjectRepository $projectRepository;
    protected PersonRepository $personRepository;

    public function __construct(
        DepartmentRepository $departmentRepository,
        ResearchLabRepository $labRepository,
        ResearchGroupRepository $groupRepository,
        ChairRepository $chairRepository,
        CourseRepository $courseRepository,
        StudyProgramRepository $programRepository,
        ProjectRepository $projectRepository,
        PersonRepository $personRepository
    ) {
        $this->departmentRepository = $departmentRepository;
        $this->labRepository = $labRepository;
        $this->groupRepository = $groupRepository;
        $this->chairRepository = $chairRepository;
        $this->courseRepository = $courseRepository;
        $this->programRepository = $programRepository;
        $this->projectRepository = $projectRepository;
        $this->personRepository = $personRepository;
    }

    /**
     * Generic list action (usually findAll)
     */
    public function listAction(): ResponseInterface
    {
        return $this->listAllAction();
    }

    /**
     * Generic list all action
     */
    public function listAllAction(): ResponseInterface
    {
        $entityType = $this->settings['entityType'] ?? 'department';

        // DEBUG: Temporary logging to Frontend
        // \TYPO3\CMS\Core\Utility\DebugUtility::debug($this->settings, 'Settings');
        // \TYPO3\CMS\Core\Utility\DebugUtility::debug($entityType, 'Entity Type (Raw)');

        $repoField = $this->getRepositoryFieldName($entityType);
        
        // \TYPO3\CMS\Core\Utility\DebugUtility::debug($repoField, 'Resolved Repository Field');

        if (!property_exists($this, $repoField)) {
             // Debug why it failed
             // \TYPO3\CMS\Core\Utility\DebugUtility::debug(get_object_vars($this), 'Controller Properties');
             $repoField = 'departmentRepository'; // Force fallback
        }

        $items = $this->$repoField->findAll();

        $this->view->assign('items', $items);
        $this->view->assign('entityType', $entityType);
        $this->view->assign('detailPid', $this->resolveDetailPid($entityType));

        return $this->htmlResponse();
    }

    /**
     * Generic list selected action (using group selector in FlexForm)
     */
    public function listSelectedAction(): ResponseInterface
    {
        $entityType = $this->settings['entityType'] ?? 'department';
        $selectionField = $this->settings['selectionField'] ?? strtolower($entityType) . 's'; // e.g. 'persons', 'departments'
        
        $items = $this->getItemsFromSelection($selectionField);

        $this->view->assign('items', $items);
        $this->view->assign('entityType', $entityType);
        $this->view->assign('detailPid', $this->resolveDetailPid($entityType));

        return $this->htmlResponse();
    }

    /**
     * Generic filtered list action
     */
    public function listFilteredAction(): ResponseInterface
    {
        $entityType = $this->settings['entityType'] ?? 'department';
        $repoField = $this->getRepositoryFieldName($entityType);
        
        $demand = $this->createDemandFromSettings();
        $items = $this->$repoField->findByDemand($demand);

        $this->view->assign('items', $items);
        $this->view->assign('entityType', $entityType);
        $this->view->assign('detailPid', $this->resolveDetailPid($entityType));

        return $this->htmlResponse();
    }

    public function showAction(
        ?Department $department = null,
        ?ResearchLab $lab = null,
        ?ResearchGroup $group = null,
        ?Chair $chair = null,
        ?Course $course = null,
        ?StudyProgram $program = null,
        ?Project $project = null,
        ?Person $person = null
    ): ResponseInterface {
        if ($department) {
            $item = $department;
            $entityType = 'department';
        } elseif ($lab) {
            $item = $lab;
            $entityType = 'lab';
        } elseif ($group) {
            $item = $group;
            $entityType = 'group';
        } elseif ($chair) {
            $item = $chair;
            $entityType = 'chair';
        } elseif ($course) {
            $item = $course;
            $entityType = 'course';
        } elseif ($program) {
            $item = $program;
            $entityType = 'program';
        } elseif ($project) {
            $item = $project;
            $entityType = 'project';
        } elseif ($person) {
            $item = $person;
            $entityType = 'person';
        } else {
            // Fallback or error handling if needed, though routing usually ensures one is set
            $item = null;
            $entityType = 'unknown'; // This will likely still cause the template error, but safer than empty
        }

        $this->view->assign('item', $item);
        $this->view->assign('entityType', $entityType);
        $this->view->assign('detailPid', $this->resolveDetailPid($entityType));

        return $this->htmlResponse();
    }

    /**
     * Specialized Demand creation for filters (shared logic)
     */
    protected function createDemandFromSettings(): Demand
    {
        $demand = parent::createDemandFromSettings();
        $entityType = $this->settings['entityType'] ?? '';

        // Person-specific filters
        if ($entityType === 'person') {
            $primaryDepartmentUid = (int)($this->settings['filter']['primary_department'] ?? $this->settings['filter_primary_department'] ?? 0);
            $academicRankUid = (int)($this->settings['filter']['academic_rank'] ?? $this->settings['filter_academic_rank'] ?? 0);
            $academicTitleUid = (int)($this->settings['filter']['academic_title'] ?? $this->settings['filter_academic_title'] ?? 0);

            if ($primaryDepartmentUid > 0) {
                $demand->addFilter('primaryDepartment', $primaryDepartmentUid);
            }
            if ($academicRankUid > 0) {
                $demand->addFilter('academicRank', $academicRankUid);
            }
            if ($academicTitleUid > 0) {
                $demand->addFilter('academicTitle', $academicTitleUid);
            }
        }

        return $demand;
    }

    protected function getRepositoryFieldName(string $entityType): string
    {
        // Normalize input basics (remove spaces, etc if needed)
        $normalizedType = \TYPO3\CMS\Core\Utility\GeneralUtility::underscoredToUpperCamelCase(trim($entityType));
        
        // Explicit mapping to Controller properties
        switch ($normalizedType) {
            case 'ResearchLab':
            case 'Lab':
                return 'labRepository';
            case 'ResearchGroup':
            case 'Group':
                return 'groupRepository';
            case 'Chair':
                return 'chairRepository';
            case 'Course':
                return 'courseRepository';
            case 'StudyProgram':
            case 'Program':
                return 'programRepository';
            case 'Project':
                return 'projectRepository';
            case 'Person':
                return 'personRepository';
            case 'Department':
            case 'Dept':
            default:
                return 'departmentRepository';
        }
    }
}
