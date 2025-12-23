<?php

declare(strict_types=1);

namespace EtfUnsa\SparkAcademics\Controller\Backend;

use EtfUnsa\SparkAcademics\Domain\Repository\CourseRepository;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;

class CourseController extends AbstractBackendController
{
    protected string $tableName = 'tx_spark_course';

    public function __construct(
        CourseRepository $courseRepository,
        ModuleTemplateFactory $moduleTemplateFactory,
        UriBuilder $backendUriBuilder
    ) {
        parent::__construct($moduleTemplateFactory, $backendUriBuilder);
        $this->repository = $courseRepository;
    }

    protected function getTemplatePath(): string
    {
        return 'Backend/Course/List';
    }
}
