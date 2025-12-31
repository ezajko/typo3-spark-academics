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
            ]);
        } else {
            // Default generic behavior for other entities
            $demand = $this->demandService->createFromSettings($settings);
            $items = $this->$repoField->findByDemand($demand);
        }

        // Pagination
        $itemsPerPage = (int)($settings['view']['itemsPerPage'] ?? 10);
        if ($itemsPerPage < 1) $itemsPerPage = 10;
        
        $paginator = new QueryResultPaginator($items, $currentPage, $itemsPerPage);
        $pagination = new SlidingWindowPagination($paginator, 5);

        $this->view->assign('pagination', $pagination);
        $this->view->assign('paginator', $paginator);
        $this->view->assign('items', $paginator->getPaginatedItems());

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
