<?php

declare(strict_types=1);

namespace EtfUnsa\SparkAcademics\Controller\Backend;

use EtfUnsa\SparkAcademics\Domain\Repository\ChairRepository;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;

class ChairController extends AbstractBackendController
{
    public function __construct(
        ChairRepository $chairRepository,
        ModuleTemplateFactory $moduleTemplateFactory,
        UriBuilder $backendUriBuilder
    ) {
        parent::__construct($moduleTemplateFactory, $backendUriBuilder);
        $this->repository = $chairRepository;
        $this->tableName = 'tx_spark_chair';
    }

    protected function getTemplatePath(): string
    {
        return 'Backend/Chair/List';
    }
}
