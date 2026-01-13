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
 * TCA Configuration for ScientificField
 * OECD FOS (Fields of Science) compatible structure
 * CERIF: cfClass (semantic layer for research domain classification)
 * 
 * @author Ernedin Zajko <ezajko@root.ba>
 */

defined('TYPO3') or die();

return [
    'ctrl' => [
        'title' => 'Scientific Field',
        'label' => 'title',
        'label_alt' => 'code',
        'label_alt_force' => true,
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'sortby' => 'sorting',
        'sortby' => 'sorting',
        'versioningWS' => true,
        'rootLevel' => -1,
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'searchFields' => 'title,code,description',
        'iconfile' => 'EXT:spark_academics/Resources/Public/Icons/Extension.svg',
        'default_sortby' => 'code ASC, title ASC',
    ],
    'types' => [
        '1' => [
            'showitem' => '
                --div--;General,
                    sys_language_uid, l10n_parent, l10n_diffsource, hidden,
                    title, code, level, parent,
                --div--;Details,
                    description, uuid,
                --div--;Sub-fields,
                    children,
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
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tx_spark_scientific_field',
                'foreign_table_where' => 'AND {#tx_spark_scientific_field}.{#pid}=###CURRENT_PID### AND {#tx_spark_scientific_field}.{#sys_language_uid} IN (-1,0)',
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
            'label' => 'Title',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'max' => 255,
                'eval' => 'trim',
                'required' => true,
            ],
        ],
        'code' => [
            'exclude' => true,
            'label' => 'OECD FOS Code',
            'description' => 'Classification code (e.g., "1" for Natural Sciences, "1.2" for Computer Sciences)',
            'config' => [
                'type' => 'input',
                'size' => 10,
                'max' => 20,
                'eval' => 'trim',
            ],
        ],
        'level' => [
            'exclude' => true,
            'label' => 'Hierarchy Level',
            'description' => '1 = Major field, 2 = Minor/Sub-field',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['Major Field (Level 1)', 1],
                    ['Minor Field (Level 2)', 2],
                    ['Sub-field (Level 3)', 3],
                ],
                'default' => 1,
            ],
        ],
        'parent' => [
            'exclude' => true,
            'label' => 'Parent Field',
            'description' => 'Select parent field for hierarchical structure',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_spark_scientific_field',
                'foreign_table_where' => 'AND {#tx_spark_scientific_field}.{#level} < ###REC_FIELD_level### ORDER BY code, title',
                'items' => [
                    ['-- No parent (Top level) --', 0],
                ],
                'default' => 0,
            ],
        ],
        'children' => [
            'exclude' => true,
            'label' => 'Sub-fields',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_spark_scientific_field',
                'foreign_field' => 'parent',
                'maxitems' => 9999,
                'appearance' => [
                    'collapseAll' => true,
                    'expandSingle' => true,
                    'levelLinksPosition' => 'top',
                    'showSynchronizationLink' => false,
                    'showPossibleLocalizationRecords' => true,
                    'showAllLocalizationLink' => true,
                    'useSortable' => true,
                ],
            ],
        ],
        'description' => [
            'exclude' => true,
            'label' => 'Description',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 10,
                'eval' => 'trim',
                'enableRichtext' => true,
            ],
        ],
        'uuid' => [
            'exclude' => true,
            'label' => 'UUID',
            'config' => [
                'type' => 'uuid',
            ],
        ],
    ],
];
