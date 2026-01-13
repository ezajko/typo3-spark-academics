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

use EtfUnsa\SparkAcademics\Domain\Repository\ProjectRepository;
use EtfUnsa\SparkAcademics\Domain\Repository\ProjectStatusRepository;
use EtfUnsa\SparkAcademics\Domain\Repository\ProjectTypeRepository;
use EtfUnsa\SparkAcademics\Domain\Repository\FundingProgramRepository;
use EtfUnsa\SparkAcademics\Domain\Repository\ScientificFieldRepository;
use EtfUnsa\SparkAcademics\Service\DemandFactory;
use EtfUnsa\SparkAcademics\Service\BackendPermissionService;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Backend\Template\Components\ButtonBar;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Pagination\SlidingWindowPagination;
use TYPO3\CMS\Core\Pagination\SimplePagination; /// unused but kept if needed
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;

class ProjectController extends AbstractBackendController
{
    protected ProjectStatusRepository $projectStatusRepository;
    protected ProjectTypeRepository $projectTypeRepository;
    protected FundingProgramRepository $fundingProgramRepository;
    protected ScientificFieldRepository $scientificFieldRepository;
    protected BackendPermissionService $backendPermissionService;
    protected IconFactory $iconFactory;
    protected SiteFinder $siteFinder;
    protected DemandFactory $demandFactory;

    public function __construct(
        ProjectRepository $projectRepository,
        ProjectStatusRepository $projectStatusRepository,
        ProjectTypeRepository $projectTypeRepository,
        FundingProgramRepository $fundingProgramRepository,
        ScientificFieldRepository $scientificFieldRepository,
        BackendPermissionService $backendPermissionService,
        ModuleTemplateFactory $moduleTemplateFactory,
        UriBuilder $backendUriBuilder,
        IconFactory $iconFactory,
        SiteFinder $siteFinder,
        DemandFactory $demandFactory
    ) {
        parent::__construct($moduleTemplateFactory, $backendUriBuilder);
        $this->repository = $projectRepository;
        $this->projectStatusRepository = $projectStatusRepository;
        $this->projectTypeRepository = $projectTypeRepository;
        $this->fundingProgramRepository = $fundingProgramRepository;
        $this->scientificFieldRepository = $scientificFieldRepository;
        $this->backendPermissionService = $backendPermissionService;
        $this->iconFactory = $iconFactory;
        $this->siteFinder = $siteFinder;
        $this->demandFactory = $demandFactory;
        $this->tableName = 'tx_spark_project';
    }

    protected function getTemplatePath(): string
    {
        return 'Backend/Project/List';
    }

    /**
     * Get storage PID from Site Settings
     */
    protected function getStoragePid(): int
    {
        try {
            $sites = $this->siteFinder->getAllSites();
            foreach ($sites as $site) {
                // Check if current request matches this site? 
                // Or just find ANY configured storage PID?
                // Ideally we should resolve site from request, but in Backend context it's tricky without site resolver.
                // Simpler: Check all sites, take first valid config.
                // Or better: Check if we are inside a site tree?
                // For "New" button, usually we default to a specific storage folder regardless of where we are.
                $config = $site->getConfiguration();
                $storagePid = (int)($config['sparkAcademic_project_storage_pid'] ?? 0);
                if ($storagePid > 0) {
                    return $storagePid;
                }
            }
        } catch (\Exception $e) {
            // Ignore if no site found
        }
        return 0;
    }

    public function listAction(): ResponseInterface
    {
        $currentBeUser = $this->getCurrentBeUser();
        
        // Handle sorting and pagination
        $currentPage = $this->request->hasArgument('page') ? (int)$this->request->getArgument('page') : 1;
        $sort = $this->request->hasArgument('sort') ? $this->request->getArgument('sort') : 'title';
        $direction = $this->request->hasArgument('direction') ? $this->request->getArgument('direction') : 'asc';
        $filter = $this->request->hasArgument('filter') ? $this->request->getArgument('filter') : [];

        // Check Permissions first (may add backendUser to filter)
        $canManage = $this->backendPermissionService->canViewAllRecords('sparkAcademic_project_permission_groups');
        if (!$canManage) {
            $filter['backendUser'] = (int)$currentBeUser['uid'];
        }

        // Create demand using factory
        $demand = $this->demandFactory->createProjectDemand([], $filter);
        
        // Apply orderings
        $orderings = [$sort => $direction === 'asc' ? QueryInterface::ORDER_ASCENDING : QueryInterface::ORDER_DESCENDING];
        $demand->setOrderings($orderings);

        $items = $this->repository->findByDemand($demand);

        // Wrap items for view
        $userItems = [];
        foreach ($items as $item) {
             $userItems[] = [
                'item' => $item,
                'editUrl' => $this->getEditUrl($item)
            ];
        }

        $paginator = new \TYPO3\CMS\Extbase\Pagination\QueryResultPaginator($items, $currentPage, 20);
        $pagination = new SlidingWindowPagination($paginator, 5);

        $this->additionalViewVariables = [
            'paginator' => $paginator,
            'pagination' => $pagination,
            'items' => $userItems,
            'filter' => $filter,
            'sort' => $sort,
            'direction' => $direction,
        ];
        
        $this->additionalViewVariables['availableStatuses'] = $this->getStatuses();
        $this->additionalViewVariables['availableTypes'] = $this->getTypes(); 
        $this->additionalViewVariables['availablePrograms'] = $this->getFundingPrograms();
        $this->additionalViewVariables['availableFields'] = $this->getScientificFields();

        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        
        // Add "New Project" button if authorized
        if ($canManage) {
            $this->addDocHeaderButtons($moduleTemplate);
        }

        $moduleTemplate->assign('items', $userItems);
        $moduleTemplate->assignMultiple($this->additionalViewVariables);

        return $moduleTemplate->renderResponse($this->getTemplatePath());
    }

    protected function addDocHeaderButtons(\TYPO3\CMS\Backend\Template\ModuleTemplate $moduleTemplate): void
    {
        $buttonBar = $moduleTemplate->getDocHeaderComponent()->getButtonBar();
        
        // Determine storage PID from Site Settings
        $storagePid = $this->getStoragePid();
        
        // If not configured, fall back to current page ID if > 0, else 0 (root)
        if ($storagePid === 0) {
             $storagePid = (int)($this->request->getQueryParams()['id'] ?? 0);
        }
        
        $newIcon = $this->iconFactory->getIcon('actions-add', Icon::SIZE_SMALL);
        
        $newLink = $this->backendUriBuilder->buildUriFromRoute('record_edit', [
            'edit' => [
                'tx_spark_project' => [
                    $storagePid => 'new'
                ]
            ],
            'returnUrl' => (string)$this->backendUriBuilder->buildUriFromRoute($this->request->getAttribute('module')->getIdentifier())
        ]);
        
        $newButton = $buttonBar->makeLinkButton()
            ->setHref($newLink)
            ->setTitle('Create New Project')
            ->setIcon($newIcon);
        
        $buttonBar->addButton($newButton, ButtonBar::BUTTON_POSITION_LEFT);
    }

    protected function getStatuses(): array
    {
        $q = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_spark_project_status');
        return $q->select('*')->from('tx_spark_project_status')->executeQuery()->fetchAllAssociative();
    }

    protected function getTypes(): array
    {
        // Simple fetch or use repository
        $q = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_spark_project_type');
        return $q->select('*')->from('tx_spark_project_type')->executeQuery()->fetchAllAssociative();
    }

    protected function getFundingPrograms(): array
    {
        $q = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_spark_funding_program');
        return $q->select('*')->from('tx_spark_funding_program')->executeQuery()->fetchAllAssociative();
    }

    protected function getScientificFields(): array
    {
        $q = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_spark_scientific_field');
        return $q->select('*')->from('tx_spark_scientific_field')->where($q->expr()->eq('level', $q->createNamedParameter(2)))->executeQuery()->fetchAllAssociative();
    }
}
