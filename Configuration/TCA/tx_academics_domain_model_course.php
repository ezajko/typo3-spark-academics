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
 * TCA Configuration for Course
 * Container entity with versioned syllabi (IRRE inline)
 * 
 * @author Ernedin Zajko <ezajko@root.ba>
 */

defined('TYPO3') or die();

return [
    'ctrl' => [
        'title' => 'Course',
        'label' => 'title',
        'label_alt' => 'acronym',
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
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'title,acronym,uuid,description',
        'iconfile' => 'EXT:academics/Resources/Public/Icons/Extension.svg',
        'security' => [
            'ignorePageTypeRestriction' => true,
        ],
    ],
    'types' => [
        '1' => [
            'showitem' => '
                --div--;General,
                    hidden, code, title, acronym, uuid, description, courseware_url,
                --div--;Organization,
                    organization,
                --div--;Syllabi,
                    syllabi,
                --div--;External Courses,
                    external_courses,
                --div--;Editors,
                    be_users,
                --div--;People,
                    persons,
                --div--;Notes,
                    notes,
                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,
                    starttime, endtime
            '
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
                'foreign_table' => 'tx_academics_domain_model_course',
                'foreign_table_where' => 'AND {#tx_academics_domain_model_course}.{#pid}=###CURRENT_PID### AND {#tx_academics_domain_model_course}.{#sys_language_uid} IN (-1,0)',
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
        'starttime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'datetime',
                'default' => 0,
                'l10n_mode' => 'exclude',
            ],
        ],
        'endtime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'datetime',
                'default' => 0,
                'range' => ['upper' => 2145916800],
                'l10n_mode' => 'exclude',
            ],
        ],

        // Basic Info
        'code' => [
            'exclude' => false,
            'label' => 'Course Code (Šifra predmeta)',
            'config' => [
                'type' => 'input',
                'size' => 20,
                'max' => 50,
                'eval' => 'trim',
                'l10n_mode' => 'exclude',
            ],
        ],
        'title' => [
            'exclude' => false,
            'label' => 'Title',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'max' => 255,
                'eval' => 'trim',
                'required' => true,
            ],
        ],
        'acronym' => [
            'exclude' => true,
            'label' => 'Acronym',
            'config' => [
                'type' => 'input',
                'size' => 15,
                'max' => 30,
                'eval' => 'trim',
                'l10n_mode' => 'exclude',
            ],
        ],
        'uuid' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'label' => 'UUID',
            'config' => ['type' => 'uuid'],
        ],
        'description' => [
            'exclude' => true,
            'label' => 'Short Description',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 5,
                'eval' => 'trim',
                'enableRichtext' => true,
            ],
        ],
        'courseware_url' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'label' => 'Courseware URL (Moodle/LMS)',
            'config' => [
                'type' => 'link',
                'allowedTypes' => ['url'],
            ],
        ],

        // Organization
        'organization' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'label' => 'Organization (Department/Chair)',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_academics_domain_model_organization',
                'foreign_table_where' => 'AND {#tx_academics_domain_model_organization}.{#sys_language_uid} IN (-1,0) ORDER BY title',
                'items' => [['label' => '-- Select --', 'value' => 0]],
                'minitems' => 0,
                'maxitems' => 1,
            ],
        ],

        // Syllabi (IRRE inline like Document->Versions)
        'syllabi' => [
            'exclude' => true,
            'label' => 'Syllabi (Verzije silabusa)',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_academics_domain_model_coursesyllabus',
                'foreign_field' => 'course',
                'foreign_sortby' => 'sorting',
                'maxitems' => 99,
                'appearance' => [
                    'collapseAll' => true,
                    'expandSingle' => true,
                    'useSortable' => true,
                    'showNewRecordLink' => true,
                    'newRecordLinkTitle' => 'Add Syllabus Version',
                    'levelLinksPosition' => 'top',
                ],
            ],
        ],

        // External Courses (IRRE inline - each Course has its own references)
        'external_courses' => [
            'exclude' => true,
            'label' => 'Similar External Courses',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_academics_domain_model_externalcourse',
                'foreign_field' => 'course',
                'foreign_sortby' => 'sorting',
                'maxitems' => 99,
                'appearance' => [
                    'collapseAll' => true,
                    'expandSingle' => true,
                    'useSortable' => true,
                    'showNewRecordLink' => true,
                    'newRecordLinkTitle' => 'Add External Course',
                    'levelLinksPosition' => 'top',
                ],
            ],
            'l10n_mode' => 'exclude',
        ],

        // Editors
        'be_users' => [
            'exclude' => true,
            'label' => 'Editors',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'be_users',
                'MM' => 'tx_academics_domain_model_course_beuser_mm',
                'size' => 5,
                'maxitems' => 99,
            ],
            'l10n_mode' => 'exclude',
        ],

        // People
        'persons' => [
            'exclude' => true,
            'label' => 'Associated People',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_academics_domain_model_courseperson',
                'foreign_field' => 'course',
                'foreign_sortby' => 'sorting',
                'maxitems' => 99,
                'appearance' => [
                    'collapseAll' => true,
                    'expandSingle' => true,
                    'useSortable' => true,
                    'showNewRecordLink' => true,
                    'newRecordLinkTitle' => 'Add Person to Course',
                    'levelLinksPosition' => 'top',
                ],
            ],
            'l10n_mode' => 'exclude',
        ],

        // Notes
        'notes' => [
            'exclude' => true,
            'label' => 'Notes (Napomene)',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 10,
                'eval' => 'trim',
                'enableRichtext' => true,
            ],
        ],
    ],
];
