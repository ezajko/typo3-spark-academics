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

use RootBa\Academics\Domain\Repository\ScientificFieldRepository;
use RootBa\Academics\Domain\Repository\CourseCategoryRepository;
use RootBa\Academics\Domain\Repository\StudyCycleRepository;
use RootBa\Academics\Domain\Repository\OrganizationRepository;
use RootBa\Academics\Service\DemandFactory;
use RootBa\Academics\Domain\Repository\CourseRepository;
use RootBa\Academics\Service\BackendPermissionService;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Backend\Template\Components\ButtonBar;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Site\SiteFinder;

/**
 * Backend controller for Course entity management
 * Uses sparkAcademic_course_permission_groups for permission checks
 * 
 * @author Ernedin Zajko <ezajko@root.ba>
 */
class CourseController extends AbstractBackendController
{
    protected BackendPermissionService $backendPermissionService;
    protected IconFactory $iconFactory;
    protected SiteFinder $siteFinder;
    protected DemandFactory $demandFactory;
    protected CourseCategoryRepository $courseCategoryRepository;
    protected StudyCycleRepository $studyCycleRepository;
    protected ScientificFieldRepository $scientificFieldRepository;
    protected OrganizationRepository $organizationRepository;

    public function __construct(
        CourseRepository $courseRepository,
        BackendPermissionService $backendPermissionService,
        ModuleTemplateFactory $moduleTemplateFactory,
        UriBuilder $backendUriBuilder,
        IconFactory $iconFactory,
        SiteFinder $siteFinder,
        DemandFactory $demandFactory,
        CourseCategoryRepository $courseCategoryRepository,
        StudyCycleRepository $studyCycleRepository,
        ScientificFieldRepository $scientificFieldRepository,
        OrganizationRepository $organizationRepository
    ) {
        parent::__construct(
            $moduleTemplateFactory, 
            $backendUriBuilder,
            $backendPermissionService,
            $iconFactory,
            $siteFinder
        );
        $this->repository = $courseRepository;
        $this->demandFactory = $demandFactory;
        $this->courseCategoryRepository = $courseCategoryRepository;
        $this->studyCycleRepository = $studyCycleRepository;
        $this->scientificFieldRepository = $scientificFieldRepository;
        $this->organizationRepository = $organizationRepository;
        
        $this->tableName = 'tx_academics_domain_model_course';
        $this->permissionGroup = 'sparkAcademic_course_permission_groups';
        $this->storagePidConfigKey = 'sparkAcademic_course_storage_pid';
        $this->newRecordLabel = 'Create New Course';
    }

    protected function getTemplatePath(): string
    {
        return 'Backend/Course/List';
    }

    protected function findItems(array $currentBeUser, bool $canManage = true)
    {
        $queryParams = $this->request->getQueryParams();
        $postParams = $this->request->getParsedBody() ?? [];
        
        $filter = $postParams['filter'] ?? $queryParams['filter'] ?? [];
        $sort = $queryParams['sort'] ?? 'title';
        $direction = $queryParams['direction'] ?? 'asc';

        // Build Demand object using DemandFactory
        $demand = $this->demandFactory->createCourseDemand([], $filter);
        
        $orderings = [$sort => $direction === 'asc' ? \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING : \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING];
        $demand->setOrderings($orderings);

        // Pass variables to view
        $this->additionalViewVariables['filter'] = $filter;
        $this->additionalViewVariables['sort'] = $sort;
        $this->additionalViewVariables['direction'] = $direction;

        return $this->repository->findByDemand($demand);
    }

    protected function prepareAction(): void
    {
        // Assign options for filters
        $cycleQuery = $this->studyCycleRepository->createQuery();
        $cycleQuery->getQuerySettings()->setRespectStoragePage(false);
        $this->additionalViewVariables['availableCycle'] = $cycleQuery->execute();

        $categoryQuery = $this->courseCategoryRepository->createQuery();
        $categoryQuery->getQuerySettings()->setRespectStoragePage(false);
        $this->additionalViewVariables['availableCategories'] = $categoryQuery->execute();

        $fieldQuery = $this->scientificFieldRepository->createQuery();
        $fieldQuery->getQuerySettings()->setRespectStoragePage(false);
        $fieldQuery->matching($fieldQuery->equals('level', 2)); // Minor fields
        $this->additionalViewVariables['availableFields'] = $fieldQuery->execute();

        // Assign Organization options (unified, replaces Department/Chair)
        $orgQuery = $this->organizationRepository->createQuery();
        $orgQuery->getQuerySettings()->setRespectStoragePage(false);
        $this->additionalViewVariables['availableOrganizations'] = $orgQuery->execute();
    }
}
