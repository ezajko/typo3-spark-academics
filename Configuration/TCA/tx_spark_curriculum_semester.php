<?php

defined('TYPO3') or die();

return [
    'ctrl' => [
        'title' => 'Semester',
        'label' => 'title',
        'label_alt' => 'semester_number',
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
        'searchFields' => 'title',
        'iconfile' => 'EXT:spark_academics/Resources/Public/Icons/Extension.svg',
        'hideTable' => true, 
    ],
    'types' => [
        '1' => [
            'showitem' => '
                --div--;General,
                    sys_language_uid, l10n_parent, l10n_diffsource, hidden,
                    title, semester_number,
                --div--;Course Groups (Slots),
                    course_groups
            ',
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
                'foreign_table' => 'tx_spark_curriculum_semester',
                'foreign_table_where' => 'AND {#tx_spark_curriculum_semester}.{#pid}=###CURRENT_PID### AND {#tx_spark_curriculum_semester}.{#sys_language_uid} IN (-1,0)',
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
        'title' => [
            'exclude' => false,
            'label' => 'Title (e.g. Semester 1)',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],
        ],
        'semester_number' => [
            'exclude' => false,
            'label' => 'Semester Number',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int,required',
                'default' => 1
            ],
        ],
        'course_groups' => [
            'exclude' => true,
            'label' => 'Course Groups (Slots)',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_spark_course_group',
                'foreign_field' => 'curriculum_semester',
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
        'curriculum' => [
             'config' => [
                'type' => 'passthrough',
             ],
        ],
    ],
];
