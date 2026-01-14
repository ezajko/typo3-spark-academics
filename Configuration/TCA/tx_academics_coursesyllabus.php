<?php

/*
 * This file is part of the "Spark Academics" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) Ernedin Zajko <ezajko@root.ba>
 */

/**
 * TCA Configuration for Course Syllabus
 * Versioned syllabus information for a Course (like DocumentVersion for Document)
 * 
 * @author Ernedin Zajko <ezajko@root.ba>
 */

defined('TYPO3') or die();

return [
    'ctrl' => [
        'title' => 'Course Syllabus',
        'label' => 'version_label',
        'label_alt' => 'academic_year',
        'label_alt_force' => true,
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'sortby' => 'sorting',
        'versioningWS' => true,
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'hideTable' => false,
        'searchFields' => 'version_label,academic_year',
        'iconfile' => 'EXT:spark_academics/Resources/Public/Icons/Extension.svg',
        'security' => [
            'ignorePageTypeRestriction' => true,
        ],
    ],
    'types' => [
        '1' => [
            'showitem' => '
                --div--;Version,
                    hidden, uuid, version_label, academic_year, valid_from,
                --div--;Classification,
                    scientific_field, course_category, study_cycle, course_status, language, ects,
                --div--;Contact Hours,
                    hours_lecture, hours_exercise, hours_seminar, hours_lab, hours_practice,
                    --linebreak--,
                    hours_total, hours_self_study,
                --div--;Learning Objectives,
                    course_objectives, thematic_units,
                --div--;Learning Outcomes,
                    outcomes_knowledge, outcomes_skills, outcomes_competencies,
                --div--;Methods,
                    sdg_goals, teaching_methods, assessment_methods,
                --div--;Prerequisites,
                    prerequisites_description, prerequisite_courses,
                --div--;Literature,
                    literature_required, literature_supplementary,
                --div--;Language,
                    sys_language_uid, l10n_parent, l10n_diffsource,
            ',
        ],
    ],
    'columns' => [
        // System fields
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => ['type' => 'language'],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [['label' => '', 'value' => 0]],
                'foreign_table' => 'tx_academics_course_syllabus',
                'foreign_table_where' => 'AND {#tx_spark_course_syllabus}.{#sys_language_uid} IN (-1,0)',
                'default' => 0,
            ],
        ],
        'l10n_diffsource' => [
            'config' => ['type' => 'passthrough'],
        ],
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.visible',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [['label' => '', 'invertStateDisplay' => true]],
            ],
        ],

        // Version Info
        'uuid' => [
            'exclude' => true,
            'label' => 'UUID',
            'config' => ['type' => 'uuid'],
        ],
        'version_label' => [
            'exclude' => false,
            'label' => 'Version Label (e.g., v1.0, Draft)',
            'config' => [
                'type' => 'input',
                'size' => 20,
                'max' => 50,
                'eval' => 'trim',
                'placeholder' => 'v1.0',
            ],
        ],
        'academic_year' => [
            'exclude' => false,
            'label' => 'Academic Year (e.g., 2024/2025)',
            'config' => [
                'type' => 'input',
                'size' => 15,
                'max' => 20,
                'eval' => 'trim',
                'required' => true,
            ],
        ],
        'valid_from' => [
            'exclude' => true,
            'label' => 'Valid From',
            'config' => [
                'type' => 'datetime',
                'format' => 'date',
                'default' => 0,
            ],
        ],
        'course' => [
            'config' => ['type' => 'passthrough'],
        ],

        // Classification
        'scientific_field' => [
            'exclude' => true,
            'label' => 'Scientific Field',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_academics_scientific_field',
                'foreign_table_where' => 'ORDER BY title',
                'items' => [['label' => '-- Select --', 'value' => 0]],
                'minitems' => 0,
                'maxitems' => 1,
            ],
        ],
        'course_category' => [
            'exclude' => true,
            'label' => 'Course Category',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_academics_course_category',
                'foreign_table_where' => 'ORDER BY sorting',
                'items' => [['label' => '-- Select --', 'value' => 0]],
                'minitems' => 0,
                'maxitems' => 1,
            ],
        ],
        'study_cycle' => [
            'exclude' => true,
            'label' => 'Study Cycle',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_academics_study_cycle',
                'foreign_table_where' => 'ORDER BY level',
                'items' => [['label' => '-- Select --', 'value' => 0]],
                'minitems' => 0,
                'maxitems' => 1,
            ],
        ],
        'course_status' => [
            'exclude' => true,
            'label' => 'Course Status',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_academics_course_status',
                'foreign_table_where' => 'ORDER BY sorting',
                'items' => [['label' => '-- Select --', 'value' => 0]],
                'minitems' => 0,
                'maxitems' => 1,
            ],
        ],
        'language' => [
            'exclude' => true,
            'label' => 'Language of Instruction',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_academics_language',
                'foreign_table_where' => 'ORDER BY sorting',
                'items' => [['label' => '-- Select --', 'value' => 0]],
                'minitems' => 0,
                'maxitems' => 1,
            ],
        ],
        'ects' => [
            'exclude' => true,
            'label' => 'ECTS Credits',
            'config' => [
                'type' => 'number',
                'range' => ['lower' => 0, 'upper' => 60],
                'default' => 0,
            ],
        ],

        // Contact Hours
        'hours_lecture' => [
            'exclude' => true,
            'label' => 'Lecture Hours (Predavanja)',
            'config' => [
                'type' => 'number',
                'range' => ['lower' => 0, 'upper' => 500],
                'default' => 0,
            ],
        ],
        'hours_exercise' => [
            'exclude' => true,
            'label' => 'Exercise Hours (Vježbe)',
            'config' => [
                'type' => 'number',
                'range' => ['lower' => 0, 'upper' => 500],
                'default' => 0,
            ],
        ],
        'hours_seminar' => [
            'exclude' => true,
            'label' => 'Seminar Hours',
            'config' => [
                'type' => 'number',
                'range' => ['lower' => 0, 'upper' => 500],
                'default' => 0,
            ],
        ],
        'hours_lab' => [
            'exclude' => true,
            'label' => 'Lab Hours (Laboratorijske vježbe)',
            'config' => [
                'type' => 'number',
                'range' => ['lower' => 0, 'upper' => 500],
                'default' => 0,
            ],
        ],
        'hours_practice' => [
            'exclude' => true,
            'label' => 'Practice Hours (Praksa)',
            'config' => [
                'type' => 'number',
                'range' => ['lower' => 0, 'upper' => 500],
                'default' => 0,
            ],
        ],
        'hours_total' => [
            'exclude' => true,
            'label' => 'Total Contact Hours',
            'config' => [
                'type' => 'number',
                'range' => ['lower' => 0, 'upper' => 1000],
                'default' => 0,
            ],
        ],
        'hours_self_study' => [
            'exclude' => true,
            'label' => 'Self-Study Hours (Samostalno opterećenje)',
            'config' => [
                'type' => 'number',
                'range' => ['lower' => 0, 'upper' => 1000],
                'default' => 0,
            ],
        ],

        // Learning Objectives
        'course_objectives' => [
            'exclude' => true,
            'label' => 'Course Objectives (Ciljevi predmeta)',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 10,
                'enableRichtext' => true,
            ],
        ],
        'thematic_units' => [
            'exclude' => true,
            'label' => 'Thematic Units (Tematske jedinice)',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'enableRichtext' => true,
            ],
        ],

        // Learning Outcomes
        'outcomes_knowledge' => [
            'exclude' => true,
            'label' => 'Learning Outcomes: Knowledge (Znanje)',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 8,
                'enableRichtext' => true,
            ],
        ],
        'outcomes_skills' => [
            'exclude' => true,
            'label' => 'Learning Outcomes: Skills (Vještine)',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 8,
                'enableRichtext' => true,
            ],
        ],
        'outcomes_competencies' => [
            'exclude' => true,
            'label' => 'Learning Outcomes: Competencies (Kompetencije)',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 8,
                'enableRichtext' => true,
            ],
        ],

        // Methods
        'sdg_goals' => [
            'exclude' => true,
            'label' => 'SDG Goals (Ciljevi održivog razvoja)',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_academics_sdg',
                'foreign_table_where' => 'ORDER BY number',
                'MM' => 'tx_academics_coursesyllabus_sdg_mm',
                'size' => 5,
                'maxitems' => 17,
            ],
        ],
        'teaching_methods' => [
            'exclude' => true,
            'label' => 'Teaching Methods (Metode izvođenja nastave)',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_academics_teaching_method',
                'foreign_table_where' => 'ORDER BY sorting',
                'MM' => 'tx_academics_coursesyllabus_teachingmethod_mm',
                'size' => 5,
                'maxitems' => 99,
            ],
        ],
        'assessment_methods' => [
            'exclude' => true,
            'label' => 'Assessment Methods (Metode provjere znanja sa strukturom ocjene)',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 10,
                'enableRichtext' => true,
            ],
        ],

        // Prerequisites
        'prerequisites_description' => [
            'exclude' => true,
            'label' => 'Prerequisites Description (Preduvjet za upis)',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 5,
                'enableRichtext' => true,
            ],
        ],
        'prerequisite_courses' => [
            'exclude' => true,
            'label' => 'Prerequisite Courses',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_academics_course',
                'foreign_table_where' => 'ORDER BY title',
                'MM' => 'tx_academics_coursesyllabus_prerequisite_mm',
                'size' => 5,
                'maxitems' => 99,
            ],
        ],

        // Literature
        'literature_required' => [
            'exclude' => true,
            'label' => 'Required Literature (Obavezna literatura)',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 10,
                'enableRichtext' => true,
            ],
        ],
        'literature_supplementary' => [
            'exclude' => true,
            'label' => 'Supplementary Literature (Dopunska literatura)',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 10,
                'enableRichtext' => true,
            ],
        ],
    ],
];
