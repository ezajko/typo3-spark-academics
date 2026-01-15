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

use RootBa\Academics\Domain\Repository\OrganizationRepository;
use RootBa\Academics\Domain\Repository\OrganizationTypeRepository;
use RootBa\Academics\Service\DemandFactory;
use RootBa\Academics\Service\BackendPermissionService;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Site\SiteFinder;

/**
 * Backend controller for Organization entities
 * 
 * @author Ernedin Zajko <ezajko@root.ba>
 */
class OrganizationController extends AbstractBackendController
{
    protected ?OrganizationRepository $organizationRepository = null;
    protected ?OrganizationTypeRepository $organizationTypeRepository = null;
    protected DemandFactory $demandFactory;
    
    public function __construct(
        OrganizationRepository $organizationRepository,
        OrganizationTypeRepository $organizationTypeRepository,
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
        $this->organizationRepository = $organizationRepository;
        $this->organizationTypeRepository = $organizationTypeRepository;
        $this->demandFactory = $demandFactory;
        $this->repository = $organizationRepository;

        $this->tableName = 'tx_academics_domain_model_organization';
        $this->permissionGroup = 'academics_organization_permission_groups';
        $this->storagePidConfigKey = 'academics_organization_storage_pid';
        $this->newRecordLabel = 'Create New Organization';
    }



    protected function getTemplatePath(): string
    {
        return 'Backend/Organization/List';
    }

    protected function findItems(array $currentBeUser, bool $canManage = true)
    {
        $queryParams = $this->request->getQueryParams();
        $postParams = $this->request->getParsedBody() ?? [];
        
        $filter = $postParams['filter'] ?? $queryParams['filter'] ?? [];
        $sort = $queryParams['sort'] ?? 'title';
        $direction = $queryParams['direction'] ?? 'asc';

        $this->additionalViewVariables['filter'] = $filter;
        $this->additionalViewVariables['sort'] = $sort;
        $this->additionalViewVariables['direction'] = $direction;

        $demand = $this->demandFactory->createOrganizationDemand([], $filter);
        
        $orderings = [$sort => $direction === 'asc' ? \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING : \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING];
        $demand->setOrderings($orderings);

        return $this->repository->findByDemand($demand);
    }

    protected function prepareAction(): void
    {
        $this->additionalViewVariables['availableTypes'] = $this->organizationTypeRepository->findAll();
        $this->additionalViewVariables['availableParents'] = $this->organizationRepository->findAll();
    }
}
