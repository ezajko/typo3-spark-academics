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
        parent::__construct(
            $moduleTemplateFactory, 
            $backendUriBuilder,
            $backendPermissionService,
            $iconFactory,
            $siteFinder
        );
        $this->repository = $personRepository;
        $this->academicRankRepository = $academicRankRepository;
        $this->academicTitleRepository = $academicTitleRepository;
        $this->organizationRepository = $organizationRepository;
        $this->demandFactory = $demandFactory;
        
        $this->tableName = 'tx_academics_domain_model_person';
        $this->permissionGroup = 'sparkAcademic_person_permission_groups';
        $this->storagePidConfigKey = 'sparkAcademic_person_storage_pid';
        $this->newRecordLabel = 'Create New Person';
    }

    protected function getTemplatePath(): string
    {
        return 'Backend/Person/List';
    }



    protected function findItems(array $currentBeUser, bool $canManage = true)
    {
        $queryParams = $this->request->getQueryParams();
        $postParams = $this->request->getParsedBody() ?? [];
        
        $filter = $postParams['filter'] ?? $queryParams['filter'] ?? [];
        $sort = $queryParams['sort'] ?? 'lastName';
        $direction = $queryParams['direction'] ?? 'asc';

        if (!$canManage) {
            $filter['backendUser'] = (int)$currentBeUser['uid'];
        }

        $demand = $this->demandFactory->createPersonDemand([], $filter);
        
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
        $this->additionalViewVariables['availableOrganizations'] = $this->getOrganizations();
        $this->additionalViewVariables['availableRanks'] = $this->getRanks();
        $this->additionalViewVariables['availableTitles'] = $this->getTitles();
    }

    protected function getOrganizations(): array
    {
        $q = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_academics_domain_model_organization');
        return $q->select('uid', 'title')->from('tx_academics_domain_model_organization')->orderBy('title')->executeQuery()->fetchAllAssociative();
    }

    protected function getRanks(): array
    {
        $q = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_academics_domain_model_academicrank');
        return $q->select('uid', 'title')->from('tx_academics_domain_model_academicrank')->orderBy('sorting')->executeQuery()->fetchAllAssociative();
    }

    protected function getTitles(): array
    {
        $q = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_academics_domain_model_academictitle');
        return $q->select('uid', 'title')->from('tx_academics_domain_model_academictitle')->orderBy('sorting')->executeQuery()->fetchAllAssociative();
    }
}
