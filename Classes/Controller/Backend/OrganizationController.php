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
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Backend\Routing\UriBuilder;

/**
 * Backend controller for Organization entities
 * 
 * @author Ernedin Zajko <ezajko@root.ba>
 */
class OrganizationController extends AbstractBackendController
{
    protected ?OrganizationRepository $organizationRepository = null;
    protected ?OrganizationTypeRepository $organizationTypeRepository = null;
    protected \RootBa\SparkCore\Service\BackendPermissionService $backendPermissionService;
    protected string $tableName = 'tx_spark_organization';

    public function __construct(
        ModuleTemplateFactory $moduleTemplateFactory,
        UriBuilder $backendUriBuilder,
        OrganizationTypeRepository $organizationTypeRepository
    ) {
        parent::__construct($moduleTemplateFactory, $backendUriBuilder);
        $this->organizationTypeRepository = $organizationTypeRepository;
    }

    public function injectOrganizationRepository(OrganizationRepository $organizationRepository): void
    {
        $this->organizationRepository = $organizationRepository;
        $this->repository = $organizationRepository;
    }

    public function injectBackendPermissionService(\RootBa\SparkCore\Service\BackendPermissionService $backendPermissionService): void
    {
        $this->backendPermissionService = $backendPermissionService;
    }

    protected function getTemplatePath(): string
    {
        return 'Backend/Organization/List';
    }

    /**
     * Find organization items based on current filter
     * 
     * @param array $currentBeUser Current backend user data
     * @return iterable Organization entities
     */
    protected function findItems(array $currentBeUser)
    {
        $filter = $this->request->hasArgument('filter') 
            ? $this->request->getArgument('filter') 
            : [];

        // Create OrganizationDemand and apply filters
        $demand = new \RootBa\Academics\Domain\Model\Dto\OrganizationDemand();
        
        if (!empty($filter['search'])) {
            $demand->setSearch($filter['search']);
        }
        if (!empty($filter['type'])) {
            $demand->setType((int)$filter['type']);
        }
        if (!empty($filter['parent'])) {
            $demand->setParent((int)$filter['parent']);
        }
        
        // Use findByDemand if any filters are active, otherwise findAll
        if ($demand->hasSearch() || $demand->hasFilters()) {
            return $this->organizationRepository->findByDemand($demand);
        }

        return $this->organizationRepository->findAll();
    }

    public function listAction(): ResponseInterface
    {
        // Get filter value
        $filter = $this->request->hasArgument('filter') 
            ? $this->request->getArgument('filter') 
            : [];

        // Permission check for "new record" button
        $canCreate = $this->backendPermissionService->hasPermission('sparkAcademic_organization_permission_groups');
        
        // Assign filter options
        $this->additionalViewVariables = [
            'canCreate' => $canCreate,
            'filter' => $filter,
            'availableTypes' => $this->organizationTypeRepository->findAll(),
            'availableParents' => $this->organizationRepository->findAll(),
        ];
        
        return parent::listAction();
    }
}
