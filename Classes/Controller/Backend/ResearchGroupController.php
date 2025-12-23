<?php

declare(strict_types=1);

namespace EtfUnsa\SparkAcademics\Controller\Backend;

use EtfUnsa\SparkAcademics\Domain\Repository\ResearchGroupRepository;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;

class ResearchGroupController extends AbstractBackendController
{
    public function __construct(
        ResearchGroupRepository $researchGroupRepository,
        ModuleTemplateFactory $moduleTemplateFactory,
        UriBuilder $backendUriBuilder
    ) {
        parent::__construct($moduleTemplateFactory, $backendUriBuilder);
        $this->repository = $researchGroupRepository;
        $this->tableName = 'tx_spark_research_group';
    }

    protected function getTemplatePath(): string
    {
        return 'Backend/ResearchGroup/List';
    }
}
