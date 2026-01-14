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

use RootBa\Academics\Domain\Repository\AbstractRepository;
use RootBa\Academics\Service\BackendPermissionService;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Backend\Template\Components\ButtonBar;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Pagination\SlidingWindowPagination;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Pagination\QueryResultPaginator;

abstract class AbstractBackendController extends ActionController
{
    protected ModuleTemplateFactory $moduleTemplateFactory;
    protected UriBuilder $backendUriBuilder;
    protected AbstractRepository $repository;
    protected BackendPermissionService $backendPermissionService;
    protected IconFactory $iconFactory;
    protected SiteFinder $siteFinder;

    protected string $tableName = '';
    protected string $permissionGroup = '';
    protected string $storagePidConfigKey = '';
    protected string $newRecordLabel = 'Create New Record';

    protected array $additionalViewVariables = [];

    public function __construct(
        ModuleTemplateFactory $moduleTemplateFactory,
        UriBuilder $backendUriBuilder,
        BackendPermissionService $backendPermissionService,
        IconFactory $iconFactory,
        SiteFinder $siteFinder
    ) {
        $this->moduleTemplateFactory = $moduleTemplateFactory;
        $this->backendUriBuilder = $backendUriBuilder;
        $this->backendPermissionService = $backendPermissionService;
        $this->iconFactory = $iconFactory;
        $this->siteFinder = $siteFinder;
    }

    public function listAction(): ResponseInterface
    {
        $currentBeUser = $this->getCurrentBeUser();

        // Hook for child controllers to prepare view variables (filters etc.)
        $this->prepareAction();

        // 1. Permission Check
        $canManage = true;
        if (!empty($this->permissionGroup)) {
            $canManage = $this->backendPermissionService->canViewAllRecords($this->permissionGroup);
        }

        // 2. Find Items
        // Pass permission check result so child can filter by user if needed
        $items = $this->findItems($currentBeUser, $canManage);

        // 3. Pagination
        $currentPage = $this->request->hasArgument('page') ? (int)$this->request->getArgument('page') : 1;
        $paginator = new QueryResultPaginator($items, $currentPage, 20);
        $pagination = new SlidingWindowPagination($paginator, 5);

        // 4. Wrap items with Edit URL
        $userItems = [];
        foreach ($paginator->getPaginatedItems() as $item) {
            $userItems[] = [
                'item' => $item,
                'editUrl' => $this->getEditUrl($item)
            ];
        }

        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);

        // 5. DocHeader Buttons
        if ($canManage) {
            $this->addDocHeaderButtons($moduleTemplate);
        }

        // 6. Assign Variables
        $moduleTemplate->assignMultiple([
            'items' => $userItems,
            'paginator' => $paginator,
            'pagination' => $pagination,
            'currentPage' => $currentPage,
            'totalItems' => count($items),
            'canManage' => $canManage,
        ]);
        
        $moduleTemplate->assignMultiple($this->additionalViewVariables);

        return $moduleTemplate->renderResponse($this->getTemplatePath());
    }

    protected function prepareAction(): void
    {
        // Hook for child controllers
    }

    protected function addDocHeaderButtons(\TYPO3\CMS\Backend\Template\ModuleTemplate $moduleTemplate): void
    {
        $buttonBar = $moduleTemplate->getDocHeaderComponent()->getButtonBar();

        // A. Create New Record Record
        $storagePid = $this->getStoragePid();
        if ($storagePid === 0) {
            $storagePid = (int)($this->request->getQueryParams()['id'] ?? 0);
        }

        if (!empty($this->tableName)) {
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
                ->setTitle($this->newRecordLabel)
                ->setIcon($newIcon);
            
            $buttonBar->addButton($newButton, ButtonBar::BUTTON_POSITION_LEFT);
        }
        
        // B. Reload Button
        $reloadIcon = $this->iconFactory->getIcon('actions-refresh', Icon::SIZE_SMALL);
        $reloadLink = (string)$this->backendUriBuilder->buildUriFromRoute($this->request->getAttribute('module')->getIdentifier());
        $reloadButton = $buttonBar->makeLinkButton()
            ->setHref($reloadLink)
            ->setTitle('Reload')
            ->setIcon($reloadIcon);
        $buttonBar->addButton($reloadButton, ButtonBar::BUTTON_POSITION_RIGHT);
    }

    protected function getStoragePid(): int
    {
        if (empty($this->storagePidConfigKey)) {
            return 0;
        }

        try {
            $sites = $this->siteFinder->getAllSites();
            foreach ($sites as $site) {
                $config = $site->getConfiguration();
                $storagePid = (int)($config[$this->storagePidConfigKey] ?? 0);
                if ($storagePid > 0) {
                    return $storagePid;
                }
            }
        } catch (\Exception $e) {
            // Ignore
        }
        return 0;
    }

    /**
     * Build an edit URL that targets both the record and its translations.
     */
    protected function getEditUrl($item): string
    {
        $returnUrl = (string)$this->backendUriBuilder->buildUriFromRoute($this->request->getAttribute('module')->getIdentifier());
        
        return (string)$this->backendUriBuilder->buildUriFromRoute('record_edit', [
            'edit' => [
                $this->tableName => [
                    $item->getUid() => 'edit'
                ]
            ],
            'returnUrl' => $returnUrl
        ]);
    }

    protected function getCurrentBeUser(): array
    {
        return $GLOBALS['BE_USER']->user;
    }

    abstract protected function getTemplatePath(): string;
    protected function findItems(array $currentBeUser, bool $canManage = true)
    {
        return $this->repository->findAll();
    }
}
