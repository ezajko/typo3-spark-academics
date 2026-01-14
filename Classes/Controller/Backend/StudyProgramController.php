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

use RootBa\Academics\Domain\Repository\StudyProgramRepository;
use RootBa\Academics\Domain\Repository\OrganizationRepository;
use RootBa\Academics\Domain\Repository\StudyCycleRepository;
use RootBa\Academics\Service\BackendPermissionService;
use RootBa\Academics\Service\DemandFactory;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Backend\Template\Components\ButtonBar;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Site\SiteFinder;

/**
 * Backend controller for Study Program entity management
 * Uses sparkAcademic_course_permission_groups for permission checks
 * 
 * @author Ernedin Zajko <ezajko@root.ba>
 */
class StudyProgramController extends AbstractBackendController
{
    protected BackendPermissionService $backendPermissionService;
    protected IconFactory $iconFactory;
    protected SiteFinder $siteFinder;
    protected DemandFactory $demandFactory;
    protected OrganizationRepository $organizationRepository;
    protected StudyCycleRepository $studyCycleRepository;

    public function __construct(
        StudyProgramRepository $studyProgramRepository,
        BackendPermissionService $backendPermissionService,
        ModuleTemplateFactory $moduleTemplateFactory,
        UriBuilder $backendUriBuilder,
        IconFactory $iconFactory,
        SiteFinder $siteFinder,
        DemandFactory $demandFactory,
        OrganizationRepository $organizationRepository,
        StudyCycleRepository $studyCycleRepository
    ) {
        parent::__construct($moduleTemplateFactory, $backendUriBuilder);
        $this->repository = $studyProgramRepository;
        $this->backendPermissionService = $backendPermissionService;
        $this->iconFactory = $iconFactory;
        $this->siteFinder = $siteFinder;
        $this->demandFactory = $demandFactory;
        $this->organizationRepository = $organizationRepository;
        $this->studyCycleRepository = $studyCycleRepository;
        $this->tableName = 'tx_academics_domain_model_study_program';
    }

    protected function getTemplatePath(): string
    {
        return 'Backend/StudyProgram/List';
    }

    protected function getStoragePid(): int
    {
        try {
            $sites = $this->siteFinder->getAllSites();
            foreach ($sites as $site) {
                $config = $site->getConfiguration();
                $storagePid = (int)($config['sparkAcademic_studyprogram_storage_pid'] ?? 0);
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
        // StudyProgram entities use sparkAcademic_course_permission_groups
        $canManage = $this->backendPermissionService->canViewAllRecords('sparkAcademic_course_permission_groups');

        // Get filter from request
        $queryParams = $this->request->getQueryParams();
        $postParams = $this->request->getParsedBody() ?? [];
        $filter = $postParams['filter'] ?? $queryParams['filter'] ?? [];

        // Build Demand object using DemandFactory
        $demand = $this->demandFactory->createStudyProgramDemand([], $filter);

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
        
        // Assign options for filters - Organization (unified, replaces department/chair)
        $orgQuery = $this->organizationRepository->createQuery();
        $orgQuery->getQuerySettings()->setRespectStoragePage(false);
        $moduleTemplate->assign('availableOrganizations', $orgQuery->execute());

        $cycleQuery = $this->studyCycleRepository->createQuery();
        $cycleQuery->getQuerySettings()->setRespectStoragePage(false);
        $moduleTemplate->assign('availableCycle', $cycleQuery->execute());

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
            ->setTitle('Create New Study Program')
            ->setIcon($newIcon);
        
        $buttonBar->addButton($newButton, ButtonBar::BUTTON_POSITION_LEFT);
    }
}
