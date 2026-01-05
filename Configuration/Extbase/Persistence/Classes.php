<?php

declare(strict_types=1);

return [
    \EtfUnsa\SparkAcademics\Domain\Model\Course::class => [
        'tableName' => 'tx_spark_course',
    ],
    \EtfUnsa\SparkAcademics\Domain\Model\CourseSyllabus::class => [
        'tableName' => 'tx_spark_course_syllabus',
    ],
    \EtfUnsa\SparkAcademics\Domain\Model\Sdg::class => [
        'tableName' => 'tx_spark_sdg',
    ],
    \EtfUnsa\SparkAcademics\Domain\Model\CourseCategory::class => [
        'tableName' => 'tx_spark_course_category',
    ],
    \EtfUnsa\SparkAcademics\Domain\Model\StudyCycle::class => [
        'tableName' => 'tx_spark_study_cycle',
    ],
    \EtfUnsa\SparkAcademics\Domain\Model\CourseStatus::class => [
        'tableName' => 'tx_spark_course_status',
    ],
    \EtfUnsa\SparkAcademics\Domain\Model\TeachingMethod::class => [
        'tableName' => 'tx_spark_teaching_method',
    ],
    \EtfUnsa\SparkAcademics\Domain\Model\Language::class => [
        'tableName' => 'tx_spark_language',
    ],
    \EtfUnsa\SparkAcademics\Domain\Model\ExternalCourse::class => [
        'tableName' => 'tx_spark_external_course',
    ],
    
    // Existing models mapping (just in case they are missing too)
    \EtfUnsa\SparkAcademics\Domain\Model\Department::class => [
        'tableName' => 'tx_spark_department',
    ],
    \EtfUnsa\SparkAcademics\Domain\Model\Chair::class => [
        'tableName' => 'tx_spark_chair',
    ],
    \EtfUnsa\SparkAcademics\Domain\Model\ResearchLab::class => [
        'tableName' => 'tx_spark_research_lab',
    ],
    \EtfUnsa\SparkAcademics\Domain\Model\ResearchGroup::class => [
        'tableName' => 'tx_spark_research_group',
    ],
    \EtfUnsa\SparkAcademics\Domain\Model\Person::class => [
        'tableName' => 'tx_spark_person',
    ],
    \EtfUnsa\SparkAcademics\Domain\Model\Project::class => [
        'tableName' => 'tx_spark_project',
    ],
    \EtfUnsa\SparkAcademics\Domain\Model\ScientificField::class => [
        'tableName' => 'tx_spark_scientific_field',
    ],
];
