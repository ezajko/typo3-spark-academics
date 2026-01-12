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

namespace EtfUnsa\SparkAcademics\Controller;

use EtfUnsa\SparkAcademics\Domain\Model\Course;
use EtfUnsa\SparkAcademics\Domain\Model\Organization;
use EtfUnsa\SparkAcademics\Domain\Model\Person;
use EtfUnsa\SparkAcademics\Domain\Model\Project;
use EtfUnsa\SparkAcademics\Domain\Model\StudyProgram;
use EtfUnsa\SparkAcademics\Domain\Repository\AcademicRankRepository;
use EtfUnsa\SparkAcademics\Domain\Repository\AcademicTitleRepository;
use EtfUnsa\SparkAcademics\Domain\Repository\CourseCategoryRepository;
use EtfUnsa\SparkAcademics\Domain\Repository\CourseRepository;
use EtfUnsa\SparkAcademics\Domain\Repository\FundingProgramRepository;
use EtfUnsa\SparkAcademics\Domain\Repository\LanguageRepository;
use EtfUnsa\SparkAcademics\Domain\Repository\ModeOfStudyRepository;
use EtfUnsa\SparkAcademics\Domain\Repository\OrganizationRepository;
use EtfUnsa\SparkAcademics\Domain\Repository\OrganizationTypeRepository;
use EtfUnsa\SparkAcademics\Domain\Repository\PersonRepository;
use EtfUnsa\SparkAcademics\Domain\Repository\ProjectRepository;
use EtfUnsa\SparkAcademics\Domain\Repository\ProjectStatusRepository;
use EtfUnsa\SparkAcademics\Domain\Repository\ProjectTypeRepository;
use EtfUnsa\SparkAcademics\Domain\Repository\ScientificFieldRepository;
use EtfUnsa\SparkAcademics\Domain\Repository\StudyCycleRepository;
use EtfUnsa\SparkAcademics\Domain\Repository\StudyProgramRepository;
use EtfUnsa\SparkAcademics\Domain\Repository\StudyTypeRepository;
use EtfUnsa\SparkAcademics\Service\DemandFactory;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Pagination\SlidingWindowPagination;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Pagination\QueryResultPaginator;

/**
 * Frontend controller for displaying academic entities
 * 
 * Handles listing and detail views for Person, Course, Project, StudyProgram, and Organization.
 * Uses DemandFactory for creating entity-specific demand objects.
 *
 * @author Ernedin Zajko <ezajko@root.ba>
 */
class AcademicController extends ActionController
{
    // Core entity repositories
    protected OrganizationRepository $organizationRepository;
    protected CourseRepository $courseRepository;
    protected StudyProgramRepository $studyProgramRepository;
    protected ProjectRepository $projectRepository;
    protected PersonRepository $personRepository;
    
    // Lookup repositories for filter dropdowns
    protected OrganizationTypeRepository $organizationTypeRepository;
    protected ProjectStatusRepository $projectStatusRepository;
    protected ProjectTypeRepository $projectTypeRepository;
    protected FundingProgramRepository $fundingProgramRepository;
    protected ScientificFieldRepository $scientificFieldRepository;
    protected CourseCategoryRepository $courseCategoryRepository;
    protected StudyCycleRepository $studyCycleRepository;
    protected LanguageRepository $languageRepository;
    protected StudyTypeRepository $studyTypeRepository;
    protected ModeOfStudyRepository $modeOfStudyRepository;
    protected AcademicRankRepository $academicRankRepository;
    protected AcademicTitleRepository $academicTitleRepository;

    // Demand factory for creating entity-specific demands
    protected DemandFactory $demandFactory;

    public function __construct(
        OrganizationRepository $organizationRepository,
        CourseRepository $courseRepository,
        StudyProgramRepository $studyProgramRepository,
        ProjectRepository $projectRepository,
        PersonRepository $personRepository,
        OrganizationTypeRepository $organizationTypeRepository,
        ProjectStatusRepository $projectStatusRepository,
        ProjectTypeRepository $projectTypeRepository,
        FundingProgramRepository $fundingProgramRepository,
        ScientificFieldRepository $scientificFieldRepository,
        CourseCategoryRepository $courseCategoryRepository,
        StudyCycleRepository $studyCycleRepository,
        LanguageRepository $languageRepository,
        StudyTypeRepository $studyTypeRepository,
        ModeOfStudyRepository $modeOfStudyRepository,
        AcademicRankRepository $academicRankRepository,
        AcademicTitleRepository $academicTitleRepository,
        DemandFactory $demandFactory
    ) {
        $this->organizationRepository = $organizationRepository;
        $this->courseRepository = $courseRepository;
        $this->studyProgramRepository = $studyProgramRepository;
        $this->projectRepository = $projectRepository;
        $this->personRepository = $personRepository;
        $this->organizationTypeRepository = $organizationTypeRepository;
        $this->projectStatusRepository = $projectStatusRepository;
        $this->projectTypeRepository = $projectTypeRepository;
        $this->fundingProgramRepository = $fundingProgramRepository;
        $this->scientificFieldRepository = $scientificFieldRepository;
        $this->courseCategoryRepository = $courseCategoryRepository;
        $this->studyCycleRepository = $studyCycleRepository;
        $this->languageRepository = $languageRepository;
        $this->studyTypeRepository = $studyTypeRepository;
        $this->modeOfStudyRepository = $modeOfStudyRepository;
        $this->academicRankRepository = $academicRankRepository;
        $this->academicTitleRepository = $academicTitleRepository;
        $this->demandFactory = $demandFactory;
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

    /**
     * Central dispatch method for list actions
     * 
     * Uses DemandFactory to create entity-specific demand objects and
     * delegates to the appropriate repository.
     */
    protected function dispatchListAction(array $settings, int $currentPage = 1): ResponseInterface
    {
        $entityType = $settings['entityType'] ?? 'Organization';
        
        // Get request filter from frontend form
        $requestFilter = $this->request->hasArgument('filter') 
            ? $this->request->getArgument('filter') 
            : [];

        // Create entity-specific demand using factory
        $demand = $this->demandFactory->createDemand($entityType, $settings, $requestFilter);
        
        // Get repository and execute query
        $repository = $this->getRepositoryForEntity($entityType);
        $items = $repository->findByDemand($demand);
        
        // Assign filter lookup data for frontend forms
        $this->assignFilterLookupData($entityType, $requestFilter);
        
        // Handle pagination
        $this->applyPagination($items, $settings, $currentPage);

        // Assign common view variables
        $this->view->assign('entityType', $entityType);
        $this->view->assign('detailPid', $this->resolveDetailPid($entityType));
        $this->view->assign('settings', $settings);

        return $this->htmlResponse();
    }

    /**
     * Get the appropriate repository for an entity type
     */
    protected function getRepositoryForEntity(string $entityType): object
    {
        return match ($entityType) {
            'Person' => $this->personRepository,
            'Project' => $this->projectRepository,
            'Course' => $this->courseRepository,
            'StudyProgram' => $this->studyProgramRepository,
            'Organization' => $this->organizationRepository,
            default => throw new \InvalidArgumentException("Unknown entity type: $entityType"),
        };
    }

    /**
     * Assign filter lookup data for frontend filter forms
     */
    protected function assignFilterLookupData(string $entityType, array $filter): void
    {
        // Common: always assign filter values and organizations
        $this->view->assign('filter', $filter);
        $this->view->assign('availableOrganizations', $this->organizationRepository->findAll());

        // Entity-specific lookup data
        match ($entityType) {
            'Person' => $this->view->assignMultiple([
                'availableRanks' => $this->academicRankRepository->findAll(),
                'availableTitles' => $this->academicTitleRepository->findAll(),
            ]),
            'Project' => $this->view->assignMultiple([
                'availableStatuses' => $this->projectStatusRepository->findAll(),
                'availableTypes' => $this->projectTypeRepository->findAll(),
                'availablePrograms' => $this->fundingProgramRepository->findAll(),
                'availableFields' => $this->scientificFieldRepository->findBy(['level' => 2]),
            ]),
            'Course' => $this->view->assignMultiple([
                'availableCategories' => $this->courseCategoryRepository->findAll(),
                'availableCycles' => $this->studyCycleRepository->findAll(),
                'availableFields' => $this->scientificFieldRepository->findBy(['level' => 2]),
            ]),
            'StudyProgram' => $this->view->assignMultiple([
                'availableCycles' => $this->studyCycleRepository->findAll(),
                'availableStudyTypes' => $this->studyTypeRepository->findAll(),
                'availableModes' => $this->modeOfStudyRepository->findAll(),
                'availableLanguages' => $this->languageRepository->findAll(),
            ]),
            'Organization' => $this->view->assignMultiple([
                'availableTypes' => $this->organizationTypeRepository->findAll(),
            ]),
            default => null,
        };
    }

    /**
     * Apply pagination to query results
     */
    protected function applyPagination($items, array $settings, int $currentPage): void
    {
        $viewSettings = $settings['view'] ?? [];
        $paginationSettings = $viewSettings['pagination'] ?? [];
        
        $itemsPerPage = (int)($paginationSettings['itemsPerPage'] ?? 10);
        if ($itemsPerPage < 0) {
            $itemsPerPage = 10;
        }
        
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
    }

    /**
     * Show action for displaying a single entity
     * Accepts Organization (unified) plus core entities: Course, StudyProgram, Project, Person
     */
    public function showAction(
        ?Organization $organization = null,
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
                    $repository = $this->getRepositoryForEntity($entityType);
                    $item = $repository->findByUid((int)$uid);
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
        } elseif (!isset($entityType) || $entityType === 'unknown') {
            // Fallback if entityType wasn't set above (though it should be)
            $entityType = (new \ReflectionClass($item))->getShortName();
        }

        $this->view->assign('item', $item);
        $this->view->assign('entityType', $entityType);
        $this->view->assign('detailPid', $this->resolveDetailPid($entityType));
        $this->view->assign('settings', $this->settings);

        return $this->htmlResponse();
    }

    /**
     * Resolve the detail page PID from settings or site configuration
     */
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
