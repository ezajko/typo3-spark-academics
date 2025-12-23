<?php

declare(strict_types=1);

namespace EtfUnsa\SparkAcademics\Controller\Backend;

use EtfUnsa\SparkAcademics\Domain\Repository\ResearchGroupRepository;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;

class ResearchGroupController extends AbstractBackendController
{
    protected string $tableName = 'tx_spark_research_group';

    public function __construct(
        ResearchGroupRepository $researchGroupRepository,
        ModuleTemplateFactory $moduleTemplateFactory,
        UriBuilder $backendUriBuilder
    ) {
        parent::__construct($moduleTemplateFactory, $backendUriBuilder);
        $this->repository = $researchGroupRepository;
    }

    protected function getTemplatePath(): string
    {
        return 'Backend/ResearchGroup/List';
    }
}
