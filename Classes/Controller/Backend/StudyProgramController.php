<?php

declare(strict_types=1);

namespace EtfUnsa\SparkAcademics\Controller\Backend;

use EtfUnsa\SparkAcademics\Domain\Repository\StudyProgramRepository;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;

class StudyProgramController extends AbstractBackendController
{
    public function __construct(
        StudyProgramRepository $studyProgramRepository,
        ModuleTemplateFactory $moduleTemplateFactory,
        UriBuilder $backendUriBuilder
    ) {
        parent::__construct($moduleTemplateFactory, $backendUriBuilder);
        $this->repository = $studyProgramRepository;
        $this->tableName = 'tx_spark_study_program';
    }

    protected function getTemplatePath(): string
    {
        return 'Backend/StudyProgram/List';
    }
}
