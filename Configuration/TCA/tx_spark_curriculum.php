<?php

defined('TYPO3') or die();

return [
    'ctrl' => [
        'title' => 'Curriculum (Version)',
        'label' => 'title',
        'label_alt' => 'year',
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
        'searchFields' => 'title,uuid,note',
        'iconfile' => 'EXT:spark_academics/Resources/Public/Icons/Extension.svg',
        'hideTable' => true, 
    ],
    'types' => [
        '1' => [
            'showitem' => '
                --div--;General,
                    sys_language_uid, l10n_parent, l10n_diffsource, hidden, is_active,
                    title, year, uuid, note,
                --div--;Semesters,
                    semesters
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
                'foreign_table' => 'tx_spark_curriculum',
                'foreign_table_where' => 'AND {#tx_spark_curriculum}.{#pid}=###CURRENT_PID### AND {#tx_spark_curriculum}.{#sys_language_uid} IN (-1,0)',
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
        'is_active' => [
            'exclude' => true,
            'label' => 'Active',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
            ],
        ],
        'title' => [
            'exclude' => false,
            'label' => 'Title (e.g. 2024/2025 Revised)',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],
        ],
        'year' => [
            'exclude' => false,
            'label' => 'Year adopted',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int,required',
                'default' => 2024
            ],
        ],
        'uuid' => [
            'exclude' => true,
            'label' => 'UUID',
            'config' => [
                'type' => 'uuid',
            ],
        ],
        'note' => [
            'exclude' => true,
            'label' => 'Note',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 5,
                'eval' => 'trim',
            ],
        ],
        'semesters' => [
            'exclude' => true,
            'label' => 'Semesters',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_spark_curriculum_semester',
                'foreign_field' => 'curriculum',
                'foreign_sortby' => 'sorting',
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
                    'useSortable' => true,
                ]
            ],
        ],
        'study_program' => [
             'config' => [
                'type' => 'passthrough',
             ],
        ],
    ],
];
