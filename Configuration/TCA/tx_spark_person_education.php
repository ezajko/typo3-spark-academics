<?php

return [
    'ctrl' => [
        'title' => 'Education',
        'label' => 'qualification',
        'label_alt' => 'institution, year',
        'label_alt_force' => true,
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
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
        'searchFields' => 'qualification,institution,year',
        'iconfile' => 'EXT:core/Resources/Public/Icons/T3Icons/content/content-special-menu.svg',
        'hideTable' => true,
    ],
    'types' => [
        '1' => [
            'showitem' => 'year, qualification, institution, --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access, hidden, starttime, endtime',
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
                    ['label' => '', 'value' => 0],
                ],
                'foreign_table' => 'tx_spark_person_education',
                'foreign_table_where' => 'AND tx_spark_person_education.pid=###CURRENT_PID### AND tx_spark_person_education.sys_language_uid IN (-1,0)',
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
                'items' => [
                    '1' => [
                        'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_core.xlf:labels.enabled',
                    ],
                ],
            ],
        ],
        'starttime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'datetime',
                'default' => 0,
            ],
        ],
        'endtime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'datetime',
                'default' => 0,
                'range' => [
                    'upper' => 2147483647,
                ],
            ],
        ],
        'year' => [
            'exclude' => true,
            'label' => 'LLL:EXT:spark_academics/Resources/Private/Language/locallang.xlf:education.year',
            'config' => [
                'type' => 'input',
                'size' => 10,
                'eval' => 'trim,required',
            ],
        ],
        'qualification' => [
            'exclude' => true,
            'label' => 'LLL:EXT:spark_academics/Resources/Private/Language/locallang.xlf:education.qualification',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required',
            ],
        ],
        'institution' => [
            'exclude' => true,
            'label' => 'LLL:EXT:spark_academics/Resources/Private/Language/locallang.xlf:education.institution',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required',
            ],
        ],
    ],
];
