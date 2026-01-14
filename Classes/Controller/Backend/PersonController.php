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

namespace RootBa\Academics\Controller\Backend;

use RootBa\Academics\Domain\Repository\PersonRepository;
use RootBa\Academics\Domain\Repository\AcademicRankRepository;
use RootBa\Academics\Domain\Repository\AcademicTitleRepository;
use RootBa\Academics\Domain\Repository\OrganizationRepository;
use RootBa\Academics\Service\DemandFactory;
use RootBa\Academics\Service\BackendPermissionService;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Backend\Template\Components\ButtonBar;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Pagination\SlidingWindowPagination;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;

/**
 * Backend controller for Person entity management
 * 
 * @author Ernedin Zajko <ezajko@root.ba>
 */
class PersonController extends AbstractBackendController
{
    protected AcademicRankRepository $academicRankRepository;
    protected AcademicTitleRepository $academicTitleRepository;
    protected OrganizationRepository $organizationRepository;
    protected BackendPermissionService $backendPermissionService;
    protected IconFactory $iconFactory;
    protected SiteFinder $siteFinder;
    protected DemandFactory $demandFactory;

    public function __construct(
        PersonRepository $personRepository,
        AcademicRankRepository $academicRankRepository,
        AcademicTitleRepository $academicTitleRepository,
        OrganizationRepository $organizationRepository,
        BackendPermissionService $backendPermissionService,
        ModuleTemplateFactory $moduleTemplateFactory,
        UriBuilder $backendUriBuilder,
        IconFactory $iconFactory,
        SiteFinder $siteFinder,
        DemandFactory $demandFactory
    ) {
        parent::__construct($moduleTemplateFactory, $backendUriBuilder);
        $this->repository = $personRepository;
        $this->academicRankRepository = $academicRankRepository;
        $this->academicTitleRepository = $academicTitleRepository;
        $this->organizationRepository = $organizationRepository;
        $this->backendPermissionService = $backendPermissionService;
        $this->iconFactory = $iconFactory;
        $this->siteFinder = $siteFinder;
        $this->demandFactory = $demandFactory;
        $this->tableName = 'tx_academics_domain_model_person';
    }

    protected function getTemplatePath(): string
    {
        return 'Backend/Person/List';
    }

    /**
     * Get storage PID from Site Settings
     */
    protected function getStoragePid(): int
    {
        try {
            $sites = $this->siteFinder->getAllSites();
            foreach ($sites as $site) {
                $config = $site->getConfiguration();
                $storagePid = (int)($config['sparkAcademic_person_storage_pid'] ?? 0);
                if ($storagePid > 0) {
                    return $storagePid;
                }
            }
        } catch (\Exception $e) {
            // Ignore
        }
        return 0;
    }

    public function listAction(): ResponseInterface
    {
        $currentBeUser = $this->getCurrentBeUser();
        
        // Get filter, sort, and pagination from request (POST for form, GET for links)
        $queryParams = $this->request->getQueryParams();
        $postParams = $this->request->getParsedBody() ?? [];
        
        // Build filter array for DemandFactory
        $filter = $postParams['filter'] ?? $queryParams['filter'] ?? [];
        $sort = $queryParams['sort'] ?? 'lastName';
        $direction = $queryParams['direction'] ?? 'asc';
        $currentPage = (int)($queryParams['page'] ?? 1);
        if ($currentPage < 1) {
            $currentPage = 1;
        }

        // Check Permissions first (may add backendUser to filter)
        $canManage = $this->backendPermissionService->canViewAllRecords('sparkAcademic_person_permission_groups');
        if (!$canManage) {
            $filter['backendUser'] = (int)$currentBeUser['uid'];
        }

        // Create demand using factory
        $demand = $this->demandFactory->createPersonDemand([], $filter);
        
        // Apply orderings
        $orderings = [$sort => $direction === 'asc' ? QueryInterface::ORDER_ASCENDING : QueryInterface::ORDER_DESCENDING];
        $demand->setOrderings($orderings);

        $items = $this->repository->findByDemand($demand);

        $paginator = new \TYPO3\CMS\Extbase\Pagination\QueryResultPaginator($items, $currentPage, 20);
        $pagination = new SlidingWindowPagination($paginator, 5);

        $userItems = [];
        foreach ($paginator->getPaginatedItems() as $item) {
             $userItems[] = [
                'item' => $item,
                'editUrl' => $this->getEditUrl($item)
            ];
        }

        $this->additionalViewVariables = [
            'paginator' => $paginator,
            'pagination' => $pagination,
            'items' => $userItems,
            'filter' => $filter,
            'sort' => $sort,
            'direction' => $direction,
        ];
        
        // Organization options (unified, replaces department)
        $this->additionalViewVariables['availableOrganizations'] = $this->getOrganizations();
        $this->additionalViewVariables['availableRanks'] = $this->getRanks();
        $this->additionalViewVariables['availableTitles'] = $this->getTitles();

        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        
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
        
        $storagePid = $this->getStoragePid();
        if ($storagePid === 0) {
             $storagePid = (int)($this->request->getQueryParams()['id'] ?? 0);
        }
        
        $newIcon = $this->iconFactory->getIcon('actions-add', Icon::SIZE_SMALL);
        
        $newLink = $this->backendUriBuilder->buildUriFromRoute('record_edit', [
            'edit' => [
                'tx_academics_domain_model_person' => [
                    $storagePid => 'new'
                ]
            ],
            'returnUrl' => (string)$this->backendUriBuilder->buildUriFromRoute($this->request->getAttribute('module')->getIdentifier())
        ]);
        
        $newButton = $buttonBar->makeLinkButton()
            ->setHref($newLink)
            ->setTitle('Create New Person')
            ->setIcon($newIcon);
        
        $buttonBar->addButton($newButton, ButtonBar::BUTTON_POSITION_LEFT);
    }

    protected function getOrganizations(): array
    {
        $q = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_academics_domain_model_organization');
        return $q->select('uid', 'title')->from('tx_academics_domain_model_organization')->orderBy('title')->executeQuery()->fetchAllAssociative();
    }

    protected function getRanks(): array
    {
        $q = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_academics_domain_model_academic_rank');
        return $q->select('uid', 'title')->from('tx_academics_domain_model_academic_rank')->orderBy('sorting')->executeQuery()->fetchAllAssociative();
    }

    protected function getTitles(): array
    {
        $q = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_academics_domain_model_academic_title');
        return $q->select('uid', 'title')->from('tx_academics_domain_model_academic_title')->orderBy('sorting')->executeQuery()->fetchAllAssociative();
    }
}
