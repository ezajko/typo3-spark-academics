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
use EtfUnsa\SparkAcademics\Domain\Repository\ProjectStatusRepository;
use EtfUnsa\SparkAcademics\Domain\Repository\ProjectTypeRepository;
use EtfUnsa\SparkAcademics\Domain\Repository\FundingProgramRepository;
use EtfUnsa\SparkAcademics\Domain\Repository\ScientificFieldRepository;
use EtfUnsa\SparkAcademics\Domain\Repository\ResearchGroupRepository;
use EtfUnsa\SparkAcademics\Domain\Repository\ResearchLabRepository;
use EtfUnsa\SparkAcademics\Domain\Repository\StudyProgramRepository;
use EtfUnsa\SparkAcademics\Domain\Repository\CourseCategoryRepository;
use EtfUnsa\SparkAcademics\Domain\Repository\StudyCycleRepository;
use EtfUnsa\SparkAcademics\Domain\Repository\LanguageRepository;
use EtfUnsa\SparkAcademics\Domain\Repository\StudyTypeRepository;
use EtfUnsa\SparkAcademics\Domain\Repository\ModeOfStudyRepository;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use Psr\Http\Message\ResponseInterface;
use EtfUnsa\SparkAcademics\Service\DemandService;
use EtfUnsa\SparkAcademics\Domain\Model\Dto\ProjectDemand;
use TYPO3\CMS\Core\Pagination\SlidingWindowPagination;
use TYPO3\CMS\Extbase\Pagination\QueryResultPaginator;

class AcademicController extends ActionController
{
    protected DepartmentRepository $departmentRepository;
    protected ResearchLabRepository $researchLabRepository;
    protected ResearchGroupRepository $researchGroupRepository;
    protected ChairRepository $chairRepository;
    protected CourseRepository $courseRepository;
    protected StudyProgramRepository $studyProgramRepository;
    protected ProjectRepository $projectRepository;
    protected ProjectStatusRepository $projectStatusRepository;
    protected ProjectTypeRepository $projectTypeRepository;
    protected FundingProgramRepository $fundingProgramRepository;
    protected ScientificFieldRepository $scientificFieldRepository;
    protected PersonRepository $personRepository;
    
    // Course Lookups
    protected CourseCategoryRepository $courseCategoryRepository;
    protected StudyCycleRepository $studyCycleRepository;
    protected LanguageRepository $languageRepository;
    protected StudyTypeRepository $studyTypeRepository;
    protected ModeOfStudyRepository $modeOfStudyRepository;

    protected DemandService $demandService;

    public function __construct(
        DepartmentRepository $departmentRepository,
        ResearchLabRepository $researchLabRepository,
        ResearchGroupRepository $researchGroupRepository,
        ChairRepository $chairRepository,
        CourseRepository $courseRepository,
        StudyProgramRepository $studyProgramRepository,
        ProjectRepository $projectRepository,
        ProjectStatusRepository $projectStatusRepository,
        ProjectTypeRepository $projectTypeRepository,
        FundingProgramRepository $fundingProgramRepository,
        ScientificFieldRepository $scientificFieldRepository,
        PersonRepository $personRepository,
        CourseCategoryRepository $courseCategoryRepository,
        StudyCycleRepository $studyCycleRepository,
        LanguageRepository $languageRepository,
        StudyTypeRepository $studyTypeRepository,
        ModeOfStudyRepository $modeOfStudyRepository,
        DemandService $demandService
    ) {
        $this->departmentRepository = $departmentRepository;
        $this->researchLabRepository = $researchLabRepository;
        $this->researchGroupRepository = $researchGroupRepository;
        $this->chairRepository = $chairRepository;
        $this->courseRepository = $courseRepository;
        $this->studyProgramRepository = $studyProgramRepository;
        $this->projectRepository = $projectRepository;
        $this->projectStatusRepository = $projectStatusRepository;
        $this->projectTypeRepository = $projectTypeRepository;
        $this->fundingProgramRepository = $fundingProgramRepository;
        $this->scientificFieldRepository = $scientificFieldRepository;
        $this->personRepository = $personRepository;
        $this->courseCategoryRepository = $courseCategoryRepository;
        $this->studyCycleRepository = $studyCycleRepository;
        $this->languageRepository = $languageRepository;
        $this->studyTypeRepository = $studyTypeRepository;
        $this->modeOfStudyRepository = $modeOfStudyRepository;
        $this->demandService = $demandService;
    }

    /**
     * Generic list action (usually findAll)
     */
    public function listAction(int $currentPage = 1): ResponseInterface
    {
        return $this->dispatchListAction($this->settings, $currentPage);
    }

    public function listAllAction(int $currentPage = 1): ResponseInterface
    {
        return $this->dispatchListAction($this->settings, $currentPage);
    }

    public function listSelectedAction(int $currentPage = 1): ResponseInterface
    {
        return $this->dispatchListAction($this->settings, $currentPage);
    }

    public function listFilteredAction(int $currentPage = 1): ResponseInterface
    {
        return $this->dispatchListAction($this->settings, $currentPage);
    }

    protected function dispatchListAction(array $settings, int $currentPage = 1): ResponseInterface
    {
        $entityType = $settings['entityType'] ?? 'department';
        
        // Resolve uids from generic "settings.select.{type}" if not already set
        if (empty($settings['uids'])) {
            $suffix = strtolower($entityType);
            $settings['uids'] = $settings['select'][$suffix] 
                                ?? $settings['select.' . $suffix] 
                                ?? '';
        }

        $repoField = $this->getRepositoryFieldName($entityType);
        
        // Special handling for Project entity filtering
        if ($entityType === 'Project') {
            $filter = $this->request->hasArgument('filter') ? $this->request->getArgument('filter') : [];
            $settingsFilter = $settings['filter'] ?? [];

            $demand = new ProjectDemand();
            
            // 1. Map Frontend Filters (Priority)
            if (!empty($filter['search'])) {
                $demand->setSearch($filter['search']);
            }
            // 2. Map Settings (Backend Filters) - overridden by Frontend if set, or just set if empty
            // Logic: Use Frontend if present, else use Backend setting
            
            // Status
            $status = !empty($filter['status']) ? (int)$filter['status'] : (int)($settingsFilter['project_status'] ?? 0);
            if ($status > 0) $demand->setProjectStatus($status);

            // Type
            $type = !empty($filter['type']) ? (int)$filter['type'] : (int)($settingsFilter['project_type'] ?? 0);
            if ($type > 0) $demand->setProjectType($type);

            // Program
            $program = !empty($filter['program']) ? (int)$filter['program'] : (int)($settingsFilter['funding_program'] ?? 0);
            if ($program > 0) $demand->setFundingProgram($program);

            // Field
            $field = !empty($filter['field']) ? (int)$filter['field'] : 0; // Field not in generic settings yet?
            if ($field > 0) $demand->setScientificField($field);

            // Organizational Filters (From Backend Settings Only for now, unless extended in frontend)
            $dept = (int)($settingsFilter['department'] ?? 0);
            if ($dept > 0) $demand->setDepartment($dept);

            $lab = (int)($settingsFilter['research_lab'] ?? 0);
            if ($lab > 0) $demand->setResearchLab($lab);

            $group = (int)($settingsFilter['research_group'] ?? 0);
            if ($group > 0) $demand->setResearchGroup($group);

            $chair = (int)($settingsFilter['chair'] ?? 0);
            if ($chair > 0) $demand->setChair($chair);


            $items = $this->projectRepository->findByProjectDemand($demand);
            
            // Assign lookup data for the filter form
            $this->view->assignMultiple([
                'filter' => $filter,
                'availableStatuses' => $this->projectStatusRepository->findAll(),
                'availableTypes' => $this->projectTypeRepository->findAll(),
                'availablePrograms' => $this->fundingProgramRepository->findAll(),
                'availableFields' => $this->scientificFieldRepository->findBy(['level' => 2]), // Only show minor fields
                'availableFields' => $this->scientificFieldRepository->findBy(['level' => 2]), // Only show minor fields
            ]);
        } elseif ($entityType === 'Course') {
            $filter = $this->request->hasArgument('filter') ? $this->request->getArgument('filter') : [];
            $settingsFilter = $settings['filter'] ?? [];

            // Use generic Demand for filtering (handled by DemandService)
            $demand = $this->demandService->createFromSettings($settings);
            
            // Apply frontend filter overrides
            // Note: Currently DemandService maps backend settings.
            // We should map frontend filters here to Demand if we want dynamic filtering.
            // For now, let's just assume basic settings filtering works, 
            // and if we add frontend filter form, we might need to extend DemandService::createFromRequest logic here 
            // or implicitly handle it via DemandService::createFromSettings if merging request args into settings.
            // Simplified: If 'filter' arg exists, use it?
            if (!empty($filter['department'])) $demand->addFilter('department', $filter['department']);
            if (!empty($filter['chair'])) $demand->addFilter('chair', $filter['chair']);
            if (!empty($filter['study_cycle'])) $demand->addFilter('syllabi.studyCycle', $filter['study_cycle']);
            if (!empty($filter['course_category'])) $demand->addFilter('syllabi.courseCategory', $filter['course_category']);
            if (!empty($filter['scientific_field'])) $demand->addFilter('syllabi.scientificField', $filter['scientific_field']);
            
            // Note: CourseCategory and StudyCycle filtering on Course entity requires complex lookup 
            // because they are on Syllabus relation.
            // For now, simple Department/Chair filtering.

            $items = $this->courseRepository->findByDemand($demand);
            
            $this->view->assignMultiple([
                'filter' => $filter,
                'availableDepartments' => $this->departmentRepository->findAll(),
                'availableChairs' => $this->chairRepository->findAll(),
                'availableCategories' => $this->courseCategoryRepository->findAll()->toArray(), // Fallback if query usage is complex, but let's use query
            ]);
            
            // Re-assign using storage-ignoring queries
            $categoryQuery = $this->courseCategoryRepository->createQuery();
            $categoryQuery->getQuerySettings()->setRespectStoragePage(false);
            
            $cycleQuery = $this->studyCycleRepository->createQuery();
            $cycleQuery->getQuerySettings()->setRespectStoragePage(false);

            $fieldQuery = $this->scientificFieldRepository->createQuery();
            $fieldQuery->getQuerySettings()->setRespectStoragePage(false);
            $fieldQuery->matching($fieldQuery->equals('level', 2));

            $this->view->assignMultiple([
                'availableCategories' => $categoryQuery->execute(),
                'availableCycles' => $cycleQuery->execute(),
                'availableFields' => $fieldQuery->execute(),
            ]);
        } elseif ($entityType === 'StudyProgram') {
            $filter = $this->request->hasArgument('filter') ? $this->request->getArgument('filter') : [];
            
            // Merge request filter into settings for DemandService
            if (!empty($filter)) {
                $settings['filter'] = array_replace_recursive($settings['filter'] ?? [], $filter);
            }
            
            $demand = $this->demandService->createFromSettings($settings);
            $items = $this->studyProgramRepository->findByDemand($demand);
            
            // Assign lookup data
            $this->view->assignMultiple([
                'filter' => $filter,
                'availableDepartments' => $this->departmentRepository->findAll(),
                'availableStudyTypes' => $this->studyTypeRepository->findAll(),
                'availableModes' => $this->modeOfStudyRepository->findAll(),
                'availableLanguages' => $this->languageRepository->findAll(),
            ]);
        } else {
            // Default generic behavior for other entities
            $demand = $this->demandService->createFromSettings($settings);
            $items = $this->$repoField->findByDemand($demand);
        }

        // Pagination Settings
        // Read from settings.view.pagination.* (aligned with DMS structure)
        $viewSettings = $settings['view'] ?? [];
        $paginationSettings = $viewSettings['pagination'] ?? [];
        
        // Items per page (0 = show all without pagination)
        $itemsPerPage = (int)($paginationSettings['itemsPerPage'] ?? 10);
        if ($itemsPerPage < 0) {
            $itemsPerPage = 10; // Reset negative values to default
        }
        
        // Optional: Apply total limit from settings
        // $limit = (int)($paginationSettings['limit'] ?? 0);
        // if ($limit > 0) { /* apply limit to $items if needed */ }
        
        if ($itemsPerPage === 0) {
            // Show all items without pagination
            $this->view->assign('pagination', null);
            $this->view->assign('paginator', null);
            $this->view->assign('items', $items);
        } else {
            // Normal pagination
            $paginator = new QueryResultPaginator($items, $currentPage, $itemsPerPage);
            $pagination = new SlidingWindowPagination($paginator, 5);

            $this->view->assign('pagination', $pagination);
            $this->view->assign('paginator', $paginator);
            $this->view->assign('items', $paginator->getPaginatedItems());
        }

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
        ?StudyProgram $studyProgram = null,
        ?Project $project = null,
        ?Person $person = null
    ): ResponseInterface {
        $args = array_filter(get_defined_vars());
        $item = reset($args); // Get first non-null argument
        $entityType = '';
        
        if ($item) {
            $key = key($args);
            if ($key) {
                $entityType = ucfirst($key);
            }
        }

        if (!$item) {
             // Try to resolve from settings (Static Selection)
             $entityType = $this->settings['entityType'] ?? '';
             if ($entityType) {
                 $suffix = strtolower($entityType);
                 $uid = $this->settings['select'][$suffix] ?? $this->settings['select.' . $suffix] ?? 0;
                 
                 if ($uid) {
                     $repoField = $this->getRepositoryFieldName($entityType);
                     if (property_exists($this, $repoField)) {
                         $item = $this->$repoField->findByUid((int)$uid);
                     }
                 }
                 // If item found, update entityType to Clean Class Name for consistency
                 if ($item) {
                     $entityType = (new \ReflectionClass($item))->getShortName();
                 }
             }
        }

        
        if (!$item) { 
             $item = null;
             $entityType = 'unknown';
        } else if (!isset($entityType) || $entityType === 'unknown') {
             // Fallback if entityType wasn't set above (though it should be)
             $entityType = (new \ReflectionClass($item))->getShortName();
        }

        $this->view->assign('item', $item);
        $this->view->assign('entityType', $entityType);
        $this->view->assign('detailPid', $this->resolveDetailPid($entityType));

        return $this->htmlResponse();
    }

    protected function getRepositoryFieldName(string $entityType): string
    {
        return lcfirst($entityType) . 'Repository';
    }

    protected function resolveDetailPid(string $entityType): int
    {
        // 1. FlexForm override
        if (!empty($this->settings['detailPid'])) {
            return (int)$this->settings['detailPid'];
        }

        // 2. Site Configuration / Settings fallback
        /** @var Site $site */
        $site = $this->request->getAttribute('site');
        
        $configSuffix = strtolower($entityType);

        $fieldName = 'academic_pid_' . $configSuffix . '_detail';
        $pidValue = null;

        // Check Site Configuration (config.yaml)
        $siteConfig = $site->getConfiguration();
        if (isset($siteConfig[$fieldName])) {
            $pidValue = $siteConfig[$fieldName];
        }

        // Check Site Settings (settings.yaml - TYPO3 v12+)
        if (!$pidValue && method_exists($site, 'getSettings')) {
            $siteSettings = $site->getSettings();
            if ($siteSettings->has($fieldName)) {
                $pidValue = $siteSettings->get($fieldName);
            }
        }

        if ($pidValue) {
            // Handle t3:// link syntax
            if (is_string($pidValue) && strpos($pidValue, 't3://page?uid=') === 0) {
                return (int)str_replace('t3://page?uid=', '', $pidValue);
            }
            return (int)$pidValue;
        }

        return 0;
    }


}
