<?php

declare(strict_types=1);

namespace EtfUnsa\SparkAcademics\Controller\Backend;

use EtfUnsa\SparkAcademics\Domain\Repository\CourseRepository;
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

    public function __construct(
        CourseRepository $courseRepository,
        BackendPermissionService $backendPermissionService,
        ModuleTemplateFactory $moduleTemplateFactory,
        UriBuilder $backendUriBuilder,
        IconFactory $iconFactory,
        SiteFinder $siteFinder
    ) {
        parent::__construct($moduleTemplateFactory, $backendUriBuilder);
        $this->repository = $courseRepository;
        $this->backendPermissionService = $backendPermissionService;
        $this->iconFactory = $iconFactory;
        $this->siteFinder = $siteFinder;
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

        // Get all items and apply search filter
        $allItems = $this->repository->findAll();
        $items = [];
        $searchTerm = strtolower(trim($filter['search'] ?? ''));
        foreach ($allItems as $item) {
            if (empty($searchTerm) || 
                str_contains(strtolower($item->getTitle() ?? ''), $searchTerm) ||
                str_contains(strtolower($item->getDescription() ?? ''), $searchTerm)
            ) {
                $items[] = $item;
            }
        }

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
