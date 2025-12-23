<?php

declare(strict_types=1);

namespace EtfUnsa\SparkAcademics\Controller\Backend;

use EtfUnsa\SparkAcademics\Domain\Repository\ProjectRepository;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;

class ProjectController extends AbstractBackendController
{
    protected string $tableName = 'tx_spark_project';

    public function __construct(
        ProjectRepository $projectRepository,
        ModuleTemplateFactory $moduleTemplateFactory,
        UriBuilder $backendUriBuilder
    ) {
        parent::__construct($moduleTemplateFactory, $backendUriBuilder);
        $this->repository = $projectRepository;
    }

    protected function getTemplatePath(): string
    {
        return 'Backend/Project/List';
    }
}
