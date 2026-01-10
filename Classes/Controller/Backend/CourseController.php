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

namespace EtfUnsa\SparkAcademics\Controller\Backend;

use EtfUnsa\SparkAcademics\Domain\Repository\ScientificFieldRepository;
use EtfUnsa\SparkAcademics\Domain\Repository\CourseCategoryRepository;
use EtfUnsa\SparkAcademics\Domain\Repository\StudyCycleRepository;
use EtfUnsa\SparkAcademics\Service\DemandService;
use EtfUnsa\SparkAcademics\Domain\Model\Dto\Demand;
use EtfUnsa\SparkAcademics\Domain\Repository\CourseRepository;
use EtfUnsa\SparkAcademics\Domain\Repository\DepartmentRepository;
use EtfUnsa\SparkAcademics\Domain\Repository\ChairRepository;
use EtfUnsa\SparkAcademics\Service\BackendPermissionService;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Backend\Template\Components\ButtonBar;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Site\SiteFinder;

/**
 * Backend controller for Course entity management
 * Uses spark_perm_study_groups for permission checks
 * 
 * @author Ernedin Zajko <ezajko@root.ba>
 */
class CourseController extends AbstractBackendController
{
    protected BackendPermissionService $backendPermissionService;
    protected IconFactory $iconFactory;
    protected SiteFinder $siteFinder;
    protected DemandService $demandService;
    protected CourseCategoryRepository $courseCategoryRepository;
    protected StudyCycleRepository $studyCycleRepository;

    protected ScientificFieldRepository $scientificFieldRepository;
    protected DepartmentRepository $departmentRepository;
    protected ChairRepository $chairRepository;

    public function __construct(
        CourseRepository $courseRepository,
        BackendPermissionService $backendPermissionService,
        ModuleTemplateFactory $moduleTemplateFactory,
        UriBuilder $backendUriBuilder,
        IconFactory $iconFactory,
        SiteFinder $siteFinder,
        DemandService $demandService,
        CourseCategoryRepository $courseCategoryRepository,
        StudyCycleRepository $studyCycleRepository,
        ScientificFieldRepository $scientificFieldRepository,
        DepartmentRepository $departmentRepository,
        ChairRepository $chairRepository
    ) {
        parent::__construct($moduleTemplateFactory, $backendUriBuilder);
        $this->repository = $courseRepository;
        $this->backendPermissionService = $backendPermissionService;
        $this->iconFactory = $iconFactory;
        $this->siteFinder = $siteFinder;
        $this->demandService = $demandService;
        $this->courseCategoryRepository = $courseCategoryRepository;
        $this->studyCycleRepository = $studyCycleRepository;

        $this->scientificFieldRepository = $scientificFieldRepository;
        $this->departmentRepository = $departmentRepository;
        $this->chairRepository = $chairRepository;
        $this->tableName = 'tx_spark_course';
    }

    protected function getTemplatePath(): string
    {
        return 'Backend/Course/List';
    }

    protected function getStoragePid(): int
    {
        try {
            $sites = $this->siteFinder->getAllSites();
            foreach ($sites as $site) {
                $config = $site->getConfiguration();
                $storagePid = (int)($config['academic_pid_course_storage'] ?? 0);
                if ($storagePid > 0) {
                    return $storagePid;
                }
            }
        } catch (\Exception $e) {
        }
        return 0;
    }

    public function listAction(): ResponseInterface
    {
        // Study entities use spark_perm_study_groups
        $canManage = $this->backendPermissionService->canViewAllRecords('spark_perm_study_groups');

        // Get filter from request
        $queryParams = $this->request->getQueryParams();
        $postParams = $this->request->getParsedBody() ?? [];
        $filter = $postParams['filter'] ?? $queryParams['filter'] ?? [];

        // Build Demand object using DemandService helper logic (manually since backend form is simpler)
        // or re-use createFromSettings by mocking settings structure, but manual is cleaner here.
        $demand = new Demand();
        
        if (!empty($filter['search'])) {
            $demand->setSearch($filter['search']);
        }

        // Organizational Filters
        if (!empty($filter['department'])) {
            $demand->addFilter('department', (int)$filter['department']);
        }
        if (!empty($filter['chair'])) {
            $demand->addFilter('chair', (int)$filter['chair']);
        }

        // Advanced Filters (Syllabus based) - handled by DemandService logic if we used it, 
        // but since we want to expose them directly, let's map them.
        // Or better: Use DemandService->applyCourseFilters logic locally or extract it.
        // For simplicity and consistency, let's just add them to Demand as 'syllabi.property'
        if (!empty($filter['study_cycle'])) {
            $demand->addFilter('syllabi.studyCycle', (int)$filter['study_cycle']);
        }
        if (!empty($filter['course_category'])) {
            $demand->addFilter('syllabi.courseCategory', (int)$filter['course_category']);
        }
        if (!empty($filter['scientific_field'])) {
            $demand->addFilter('syllabi.scientificField', (int)$filter['scientific_field']);
        }

        // Execute Query
        $items = $this->repository->findByDemand($demand);

        $userItems = [];
        foreach ($items as $item) {
            $userItems[] = [
                'item' => $item,
                'editUrl' => $this->getEditUrl($item)
            ];
        }

        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        
        if ($canManage) {
            $this->addDocHeaderButtons($moduleTemplate);
        }

        $moduleTemplate->assign('items', $userItems);
        $moduleTemplate->assign('filter', $filter);
        $moduleTemplate->assign('canManage', $canManage);
        
        // Assign options for filters
        // Disable storage page respect for lookups to find them globally/in storage folders
        $cycleQuery = $this->studyCycleRepository->createQuery();
        $cycleQuery->getQuerySettings()->setRespectStoragePage(false);
        $moduleTemplate->assign('availableCycle', $cycleQuery->execute());

        $categoryQuery = $this->courseCategoryRepository->createQuery();
        $categoryQuery->getQuerySettings()->setRespectStoragePage(false);
        $moduleTemplate->assign('availableCategories', $categoryQuery->execute());

        $fieldQuery = $this->scientificFieldRepository->createQuery();
        $fieldQuery->getQuerySettings()->setRespectStoragePage(false);
        $fieldQuery->matching($fieldQuery->equals('level', 2)); // Minor fields
        $moduleTemplate->assign('availableFields', $fieldQuery->execute());

        // Assign Department and Chair options (globally)
        $deptQuery = $this->departmentRepository->createQuery();
        $deptQuery->getQuerySettings()->setRespectStoragePage(false);
        $moduleTemplate->assign('availableDepartments', $deptQuery->execute());

        $chairQuery = $this->chairRepository->createQuery();
        $chairQuery->getQuerySettings()->setRespectStoragePage(false);
        $moduleTemplate->assign('availableChairs', $chairQuery->execute());
        $moduleTemplate->assignMultiple($this->additionalViewVariables);

        return $moduleTemplate->renderResponse($this->getTemplatePath());
    }

    protected function addDocHeaderButtons(\TYPO3\CMS\Backend\Template\ModuleTemplate $moduleTemplate): void
    {
        $buttonBar = $moduleTemplate->getDocHeaderComponent()->getButtonBar();
        
        $storagePid = $this->getStoragePid();
        if ($storagePid === 0) {
            $storagePid = (int)($this->request->getQueryParams()['id'] ?? 0);
        }
        
        $newIcon = $this->iconFactory->getIcon('actions-add', Icon::SIZE_SMALL);
        
        $newLink = $this->backendUriBuilder->buildUriFromRoute('record_edit', [
            'edit' => [
                $this->tableName => [
                    $storagePid => 'new'
                ]
            ],
            'returnUrl' => (string)$this->backendUriBuilder->buildUriFromRoute($this->request->getAttribute('module')->getIdentifier())
        ]);
        
        $newButton = $buttonBar->makeLinkButton()
            ->setHref($newLink)
            ->setTitle('Create New Course')
            ->setIcon($newIcon);
        
        $buttonBar->addButton($newButton, ButtonBar::BUTTON_POSITION_LEFT);
    }
}
