<?php

declare(strict_types=1);

namespace EtfUnsa\SparkAcademics\Controller\Backend;

use EtfUnsa\SparkAcademics\Domain\Repository\ResearchLabRepository;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;

class ResearchLabController extends AbstractBackendController
{
    protected string $tableName = 'tx_spark_research_lab';

    public function __construct(
        ResearchLabRepository $researchLabRepository,
        ModuleTemplateFactory $moduleTemplateFactory,
        UriBuilder $backendUriBuilder
    ) {
        parent::__construct($moduleTemplateFactory, $backendUriBuilder);
        $this->repository = $researchLabRepository;
    }

    protected function getTemplatePath(): string
    {
        return 'Backend/ResearchLab/List';
    }
}
