<?php

defined('TYPO3') or die();

return [
    'ctrl' => [
        'title' => 'Course Group (Slot)',
        'label' => 'title',
        'label_alt' => 'type',
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
        'hideTable' => true, // Hide from root list, accessed via IRRE
    ],
    'types' => [
        '1' => [
            'showitem' => '
                --div--;General,
                    sys_language_uid, l10n_parent, l10n_diffsource, hidden,
                    title, type, required_counts, color,
                --div--;Courses,
                    courses
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
                'foreign_table' => 'tx_spark_course_group',
                'foreign_table_where' => 'AND {#tx_spark_course_group}.{#pid}=###CURRENT_PID### AND {#tx_spark_course_group}.{#sys_language_uid} IN (-1,0)',
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
            'label' => 'Group Title (e.g. Mandatory, Electives A)',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],
        ],
        'type' => [
            'exclude' => false,
            'label' => 'Type',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['Mandatory (All required)', 'mandatory'],
                    ['Elective (Choose N)', 'elective'],
                ],
                'default' => 'mandatory'
            ],
        ],
        'required_counts' => [
            'exclude' => true,
            'label' => 'Required Selection Count (0 = All)',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int',
                'default' => 0
            ]
        ],
        'color' => [
            'exclude' => true,
            'label' => 'Color Label (Optional)',
            'config' => [
                'type' => 'input',
                'renderType' => 'colorpicker',
                'size' => 10,
            ]
        ],
        'courses' => [
            'exclude' => true,
            'label' => 'Courses',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_spark_course',
                'MM' => 'tx_spark_coursegroup_course_mm',
                'size' => 10,
                'maxitems' => 99,
            ],
        ],
        'curriculum_semester' => [
             'config' => [
                'type' => 'passthrough',
             ],
        ],
    ],
];
