<?php

declare(strict_types=1);

namespace EtfUnsa\SparkAcademics\Controller\Backend;

use EtfUnsa\SparkAcademics\Domain\Repository\DepartmentRepository;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;

class DepartmentController extends AbstractBackendController
{
    protected string $tableName = 'tx_spark_department';

    public function __construct(
        DepartmentRepository $departmentRepository,
        ModuleTemplateFactory $moduleTemplateFactory,
        UriBuilder $backendUriBuilder
    ) {
        parent::__construct($moduleTemplateFactory, $backendUriBuilder);
        $this->repository = $departmentRepository;
    }

    protected function getTemplatePath(): string
    {
        return 'Backend/Department/List';
    }
}
