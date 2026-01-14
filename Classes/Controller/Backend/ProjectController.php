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

use RootBa\Academics\Domain\Repository\ProjectRepository;
use RootBa\Academics\Domain\Repository\ProjectStatusRepository;
use RootBa\Academics\Domain\Repository\ProjectTypeRepository;
use RootBa\Academics\Domain\Repository\FundingProgramRepository;
use RootBa\Academics\Domain\Repository\ScientificFieldRepository;
use RootBa\Academics\Service\DemandFactory;
use RootBa\Academics\Service\BackendPermissionService;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Backend\Template\Components\ButtonBar;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
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
        parent::__construct(
            $moduleTemplateFactory, 
            $backendUriBuilder,
            $backendPermissionService,
            $iconFactory,
            $siteFinder
        );
        $this->repository = $projectRepository;
        $this->projectStatusRepository = $projectStatusRepository;
        $this->projectTypeRepository = $projectTypeRepository;
        $this->fundingProgramRepository = $fundingProgramRepository;
        $this->scientificFieldRepository = $scientificFieldRepository;
        $this->demandFactory = $demandFactory;
        
        $this->tableName = 'tx_academics_domain_model_project';
        $this->permissionGroup = 'sparkAcademic_project_permission_groups';
        $this->storagePidConfigKey = 'sparkAcademic_project_storage_pid';
        $this->newRecordLabel = 'Create New Project';
    }

    protected function getTemplatePath(): string
    {
        return 'Backend/Project/List';
    }



    protected function findItems(array $currentBeUser, bool $canManage = true)
    {
        $queryParams = $this->request->getQueryParams();
        $postParams = $this->request->getParsedBody() ?? [];
        
        $filter = $postParams['filter'] ?? $queryParams['filter'] ?? [];
        $sort = $queryParams['sort'] ?? 'title'; // Default sort for project
        $direction = $queryParams['direction'] ?? 'asc';

        if (!$canManage) {
            $filter['backendUser'] = (int)$currentBeUser['uid'];
        }

        $demand = $this->demandFactory->createProjectDemand([], $filter);
        
        $orderings = [$sort => $direction === 'asc' ? QueryInterface::ORDER_ASCENDING : QueryInterface::ORDER_DESCENDING];
        $demand->setOrderings($orderings);

        // Add variables for view that depend on request params (like sort/filter state)
        $this->additionalViewVariables['filter'] = $filter;
        $this->additionalViewVariables['sort'] = $sort;
        $this->additionalViewVariables['direction'] = $direction;

        return $this->repository->findByDemand($demand);
    }

    protected function prepareAction(): void
    {
        $this->additionalViewVariables['availableStatuses'] = $this->getStatuses();
        $this->additionalViewVariables['availableTypes'] = $this->getTypes(); 
        $this->additionalViewVariables['availablePrograms'] = $this->getFundingPrograms();
        $this->additionalViewVariables['availableFields'] = $this->getScientificFields();
    }

    protected function getStatuses(): array
    {
        $q = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_academics_domain_model_projectstatus');
        return $q->select('*')->from('tx_academics_domain_model_projectstatus')->executeQuery()->fetchAllAssociative();
    }

    protected function getTypes(): array
    {
        // Simple fetch or use repository
        $q = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_academics_domain_model_projecttype');
        return $q->select('*')->from('tx_academics_domain_model_projecttype')->executeQuery()->fetchAllAssociative();
    }

    protected function getFundingPrograms(): array
    {
        $q = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_academics_domain_model_fundingprogram');
        return $q->select('*')->from('tx_academics_domain_model_fundingprogram')->executeQuery()->fetchAllAssociative();
    }

    protected function getScientificFields(): array
    {
        $q = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_academics_domain_model_scientificfield');
        return $q->select('*')->from('tx_academics_domain_model_scientificfield')->where($q->expr()->eq('level', $q->createNamedParameter(2)))->executeQuery()->fetchAllAssociative();
    }
}
