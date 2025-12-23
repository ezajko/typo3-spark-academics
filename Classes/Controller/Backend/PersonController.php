<?php

declare(strict_types=1);

namespace EtfUnsa\SparkAcademics\Controller\Backend;

use EtfUnsa\SparkAcademics\Domain\Repository\PersonRepository;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;

class PersonController extends AbstractBackendController
{
    public function __construct(
        PersonRepository $personRepository,
        ModuleTemplateFactory $moduleTemplateFactory,
        UriBuilder $backendUriBuilder
    ) {
        parent::__construct($moduleTemplateFactory, $backendUriBuilder);
        $this->repository = $personRepository;
        $this->tableName = 'tx_spark_person';
    }

    protected function getTemplatePath(): string
    {
        return 'Backend/Person/List';
    }
}
