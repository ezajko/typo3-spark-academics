<?php

declare(strict_types=1);

return [
    \EtfUnsa\SparkAcademics\Domain\Model\Person::class => [
        'tableName' => 'tx_spark_person',
    ],
    \EtfUnsa\SparkAcademics\Domain\Model\Department::class => [
        'tableName' => 'tx_spark_department',
    ],
    \EtfUnsa\SparkAcademics\Domain\Model\ResearchLab::class => [
        'tableName' => 'tx_spark_research_lab',
    ],
    \EtfUnsa\SparkAcademics\Domain\Model\ResearchGroup::class => [
        'tableName' => 'tx_spark_research_group',
    ],
    \EtfUnsa\SparkAcademics\Domain\Model\ScientificField::class => [
        'tableName' => 'tx_spark_scientific_field',
    ],
    \EtfUnsa\SparkAcademics\Domain\Model\AcademicTitle::class => [
        'tableName' => 'tx_spark_academic_title',
    ],
    \EtfUnsa\SparkAcademics\Domain\Model\AcademicRank::class => [
        'tableName' => 'tx_spark_academic_rank',
    ],
];
