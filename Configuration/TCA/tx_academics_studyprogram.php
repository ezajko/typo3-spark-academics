<?php

/*
 * This file is part of the "Spark Academics" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) Ernedin Zajko <ezajko@root.ba>
 */

defined('TYPO3') or die();

return [
    'ctrl' => [
        'title' => 'Study Program',
        'label' => 'title',
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
        'searchFields' => 'title,acronym,uuid',
        'iconfile' => 'EXT:spark_academics/Resources/Public/Icons/Extension.svg',
    ],
    'types' => [
        '1' => [
            'showitem' => '
                --div--;General,
                    hidden, landing_page, title, acronym, uuid, description,
                --div--;Organization,
                    departments, chairs, study_cycle, scientific_field, study_types, modes_of_study, languages,
                --div--;Details,
                    qualification_title, duration_years, duration_semesters, ects_credits,
                --div--;Curriculum,
                    curricula,
                --div--;Editors,
                    be_users,
                --div--;People,
                    persons,
                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,
                    starttime, endtime
            '
        ],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'language',
            ],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tx_spark_study_program',
                'foreign_table_where' => 'AND {#tx_spark_study_program}.{#pid}=###CURRENT_PID### AND {#tx_spark_study_program}.{#sys_language_uid} IN (-1,0)',
                'default' => 0,
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.visible',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        0 => '',
                        1 => '',
                        'invertStateDisplay' => true
                    ]
                ],
            ],
        ],
        'starttime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int',
                'default' => 0,
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ],
                'l10n_mode' => 'exclude',
            ],
        ],
        'endtime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int',
                'default' => 0,
                'range' => [
                    'upper' => 2147483647,
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ],
                'l10n_mode' => 'exclude',
            ],
        ],
        'landing_page' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'label' => 'Landing Page',
            'config' => [
                'type' => 'group',
                'allowed' => 'pages',
                'maxitems' => 1,
                'minitems' => 0,
                'size' => 1,
                'default' => 0,
            ],
        ],
        'title' => [
            'exclude' => true,
            'label' => 'Title',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],
        ],
        'acronym' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'label' => 'Acronym',
            'config' => [
                'type' => 'input',
                'size' => 10,
                'eval' => 'trim'
            ],
        ],
        'uuid' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'label' => 'UUID',
            'config' => [
                'type' => 'uuid',
            ],
        ],
        'departments' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'label' => 'Departments',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_spark_department',
                'MM' => 'tx_spark_studyprogram_department_mm',
                'size' => 5,
                'maxitems' => 99,
                'default' => 0,
            ],
        ],
        'chairs' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'label' => 'Chairs',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_spark_chair',
                'MM' => 'tx_spark_studyprogram_chair_mm',
                'size' => 5,
                'maxitems' => 99,
                'default' => 0,
            ],
        ],
        'study_cycle' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'label' => 'Study Cycle',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_spark_study_cycle',
                'items' => [['label' => '-- Select --', 'value' => 0]],
                'default' => 0,
            ],
        ],
        'scientific_field' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'label' => 'Scientific Field (Primary)',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectTree',
                'foreign_table' => 'tx_spark_scientific_field',
                'foreign_table_where' => 'ORDER BY tx_spark_scientific_field.sorting',
                'treeConfig' => [
                    'parentField' => 'parent',
                    'appearance' => [
                        'expandAll' => true,
                        'showHeader' => true,
                    ],
                ],
                'default' => 0,
                'minitems' => 0,
                'maxitems' => 1,
            ],
        ],
        'study_types' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'label' => 'Study Types',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_spark_study_type',
                'MM' => 'tx_spark_studyprogram_studytype_mm',
                'size' => 5,
                'maxitems' => 99,
                'default' => 0,
            ],
        ],
        'modes_of_study' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'label' => 'Modes of Study',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_spark_mode_of_study',
                'MM' => 'tx_spark_studyprogram_modeofstudy_mm',
                'size' => 5,
                'maxitems' => 99,
                'default' => 0,
            ],
        ],
        'languages' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'label' => 'Languages',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_spark_language',
                'MM' => 'tx_spark_studyprogram_language_mm',
                'size' => 5,
                'maxitems' => 99,
                'default' => 0,
                'fieldControl' => [
                    'editPopup' => [
                        'disabled' => false,
                    ],
                    'addRecord' => [
                        'disabled' => false,
                    ],
                    'listModule' => [
                        'disabled' => true,
                    ],
                ],
            ],
        ],
        'curricula' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'label' => 'Curricula',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_spark_curriculum',
                'foreign_field' => 'study_program',
                'appearance' => [
                    'collapseAll' => true,
                    'expandSingle' => true,
                    'levelLinksPosition' => 'top',
                    'showSynchronizationLink' => true,
                    'showPossibleLocalizationRecords' => true,
                    'showAllLocalizationLink' => true,
                    'enabledControls' => [
                        'info' => false,
                    ],
                ]
            ],
        ],
        'duration_years' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'label' => 'Duration (Years)',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int',
                'default' => 0
            ]
        ],
        'duration_semesters' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'label' => 'Duration (Semesters)',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int',
                'default' => 0
            ]
        ],
        'ects_credits' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'label' => 'ECTS Credits',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int',
                'default' => 0
            ]
        ],
        'qualification_title' => [
            'exclude' => true,
            'label' => 'Qualification Title',
            'config' => [
                'type' => 'input',
                'size' => 40,
                'eval' => 'trim'
            ]
        ],
        'description' => [
            'exclude' => true,
            'label' => 'Description',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
                'enableRichtext' => true,
            ],
        ],
        'be_users' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'label' => 'Editors',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'be_users',
                'MM' => 'tx_spark_studyprogram_beuser_mm',
                'size' => 5,
                'maxitems' => 99,
            ],
        ],
        'persons' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'label' => 'Members',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_spark_person',
                'MM' => 'tx_spark_person_studyprogram_mm',
                'size' => 10,
                'maxitems' => 9999,
            ],
        ],
    ],
];
