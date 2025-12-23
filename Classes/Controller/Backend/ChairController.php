<?php

declare(strict_types=1);

namespace EtfUnsa\SparkAcademics\Controller\Backend;

use EtfUnsa\SparkAcademics\Domain\Repository\ChairRepository;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;

class ChairController extends AbstractBackendController
{
    protected string $tableName = 'tx_spark_chair';

    public function __construct(
        ChairRepository $chairRepository,
        ModuleTemplateFactory $moduleTemplateFactory,
        UriBuilder $backendUriBuilder
    ) {
        parent::__construct($moduleTemplateFactory, $backendUriBuilder);
        $this->repository = $chairRepository;
    }

    protected function getTemplatePath(): string
    {
        return 'Backend/Chair/List';
    }
}
