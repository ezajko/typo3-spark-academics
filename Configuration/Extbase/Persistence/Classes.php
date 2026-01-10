<?php

/*
 * This file is part of the "Spark Academics" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) Ernedin Zajko <ezajko@root.ba>
 */

declare(strict_types=1);

return [
    // --- Core Entities ---
    \EtfUnsa\SparkAcademics\Domain\Model\Person::class => [
        'tableName' => 'tx_spark_person',
    ],
    \EtfUnsa\SparkAcademics\Domain\Model\PersonEducation::class => [
        'tableName' => 'tx_spark_person_education',
    ],
    \EtfUnsa\SparkAcademics\Domain\Model\PersonMentoring::class => [
        'tableName' => 'tx_spark_person_mentoring',
    ],
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
    \EtfUnsa\SparkAcademics\Domain\Model\Project::class => [
        'tableName' => 'tx_spark_project',
    ],
    \EtfUnsa\SparkAcademics\Domain\Model\Partner::class => [
        'tableName' => 'tx_spark_partner',
    ],

    // --- Course Entities ---
    \EtfUnsa\SparkAcademics\Domain\Model\Course::class => [
        'tableName' => 'tx_spark_course',
    ],
    \EtfUnsa\SparkAcademics\Domain\Model\CourseSyllabus::class => [
        'tableName' => 'tx_spark_course_syllabus',
    ],
    \EtfUnsa\SparkAcademics\Domain\Model\ExternalCourse::class => [
        'tableName' => 'tx_spark_external_course',
    ],
    \EtfUnsa\SparkAcademics\Domain\Model\StudyProgram::class => [
        'tableName' => 'tx_spark_study_program',
    ],

    // --- Lookup Tables ---
    \EtfUnsa\SparkAcademics\Domain\Model\SDG::class => [
        'tableName' => 'tx_spark_sdg',
    ],
    \EtfUnsa\SparkAcademics\Domain\Model\ScientificField::class => [
        'tableName' => 'tx_spark_scientific_field',
    ],
    \EtfUnsa\SparkAcademics\Domain\Model\AcademicRank::class => [
        'tableName' => 'tx_spark_academic_rank',
    ],
    \EtfUnsa\SparkAcademics\Domain\Model\AcademicTitle::class => [
        'tableName' => 'tx_spark_academic_title',
    ],
    \EtfUnsa\SparkAcademics\Domain\Model\FundingProgram::class => [
        'tableName' => 'tx_spark_funding_program',
    ],
    \EtfUnsa\SparkAcademics\Domain\Model\ProjectStatus::class => [
        'tableName' => 'tx_spark_project_status',
    ],
    \EtfUnsa\SparkAcademics\Domain\Model\ProjectType::class => [
        'tableName' => 'tx_spark_project_type',
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
    \EtfUnsa\SparkAcademics\Domain\Model\StudyType::class => [
        'tableName' => 'tx_spark_study_type',
    ],
    \EtfUnsa\SparkAcademics\Domain\Model\ModeOfStudy::class => [
        'tableName' => 'tx_spark_mode_of_study',
    ],

    // --- Curriculum Entities ---
    \EtfUnsa\SparkAcademics\Domain\Model\Curriculum::class => [
        'tableName' => 'tx_spark_curriculum',
    ],
    \EtfUnsa\SparkAcademics\Domain\Model\CurriculumSemester::class => [
        'tableName' => 'tx_spark_curriculum_semester',
    ],
    \EtfUnsa\SparkAcademics\Domain\Model\CourseGroup::class => [
        'tableName' => 'tx_spark_course_group',
    ],

    // --- Publication Entities ---
    \EtfUnsa\SparkAcademics\Domain\Model\Publication::class => [
        'tableName' => 'tx_spark_publication',
    ],
];
