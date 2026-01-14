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
    \RootBa\Academics\Domain\Model\Person::class => [
        'tableName' => 'tx_academics_person',
    ],
    \RootBa\Academics\Domain\Model\PersonEducation::class => [
        'tableName' => 'tx_academics_person_education',
    ],
    \RootBa\Academics\Domain\Model\PersonMentoring::class => [
        'tableName' => 'tx_academics_person_mentoring',
    ],
    \RootBa\Academics\Domain\Model\Organization::class => [
        'tableName' => 'tx_academics_organization',
    ],
    \RootBa\Academics\Domain\Model\OrganizationType::class => [
        'tableName' => 'tx_academics_organization_type',
    ],
    \RootBa\Academics\Domain\Model\Project::class => [
        'tableName' => 'tx_academics_project',
    ],
    \RootBa\Academics\Domain\Model\Partner::class => [
        'tableName' => 'tx_academics_partner',
    ],
    \RootBa\Academics\Domain\Model\CoursePerson::class => [
        'tableName' => 'tx_academics_course_person',
    ],
    \RootBa\Academics\Domain\Model\ProjectPerson::class => [
        'tableName' => 'tx_academics_project_person',
    ],

    // --- Course Entities ---
    \RootBa\Academics\Domain\Model\Course::class => [
        'tableName' => 'tx_academics_course',
    ],
    \RootBa\Academics\Domain\Model\CourseSyllabus::class => [
        'tableName' => 'tx_academics_course_syllabus',
    ],
    \RootBa\Academics\Domain\Model\ExternalCourse::class => [
        'tableName' => 'tx_academics_external_course',
    ],
    \RootBa\Academics\Domain\Model\StudyProgram::class => [
        'tableName' => 'tx_academics_study_program',
    ],

    // --- Lookup Tables ---
    \RootBa\Academics\Domain\Model\SDG::class => [
        'tableName' => 'tx_academics_sdg',
    ],
    \RootBa\Academics\Domain\Model\ScientificField::class => [
        'tableName' => 'tx_academics_scientific_field',
    ],
    \RootBa\Academics\Domain\Model\AcademicRank::class => [
        'tableName' => 'tx_academics_academic_rank',
    ],
    \RootBa\Academics\Domain\Model\AcademicTitle::class => [
        'tableName' => 'tx_academics_academic_title',
    ],
    \RootBa\Academics\Domain\Model\FundingProgram::class => [
        'tableName' => 'tx_academics_funding_program',
    ],
    \RootBa\Academics\Domain\Model\ProjectStatus::class => [
        'tableName' => 'tx_academics_project_status',
    ],
    \RootBa\Academics\Domain\Model\ProjectType::class => [
        'tableName' => 'tx_academics_project_type',
    ],
    \RootBa\Academics\Domain\Model\CourseCategory::class => [
        'tableName' => 'tx_academics_course_category',
    ],
    \RootBa\Academics\Domain\Model\StudyCycle::class => [
        'tableName' => 'tx_academics_study_cycle',
    ],
    \RootBa\Academics\Domain\Model\CourseStatus::class => [
        'tableName' => 'tx_academics_course_status',
    ],
    \RootBa\Academics\Domain\Model\TeachingMethod::class => [
        'tableName' => 'tx_academics_teaching_method',
    ],
    \RootBa\Academics\Domain\Model\Language::class => [
        'tableName' => 'tx_academics_language',
    ],
    \RootBa\Academics\Domain\Model\StudyType::class => [
        'tableName' => 'tx_academics_study_type',
    ],
    \RootBa\Academics\Domain\Model\ModeOfStudy::class => [
        'tableName' => 'tx_academics_mode_of_study',
    ],

    // --- Curriculum Entities ---
    \RootBa\Academics\Domain\Model\Curriculum::class => [
        'tableName' => 'tx_academics_curriculum',
    ],
    \RootBa\Academics\Domain\Model\CurriculumSemester::class => [
        'tableName' => 'tx_academics_curriculum_semester',
    ],
    \RootBa\Academics\Domain\Model\CourseGroup::class => [
        'tableName' => 'tx_academics_course_group',
    ],

    // --- Publication Entities ---
    \RootBa\Academics\Domain\Model\Publication::class => [
        'tableName' => 'tx_academics_publication',
    ],
];
