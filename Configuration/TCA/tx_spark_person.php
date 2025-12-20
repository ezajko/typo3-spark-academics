<?php

return [
    'ctrl' => [
        'title' => 'LLL:EXT:spark_academics/Resources/Private/Language/locallang.xlf:person',
        'label' => 'last_name',
        'label_alt' => 'first_name',
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
        'searchFields' => 'first_name,last_name,biography',
        'iconfile' => 'EXT:core/Resources/Public/Icons/T3Icons/content/content-user.svg'
    ],
    'types' => [
        '1' => [
            'showitem' => '
                --div--;General,
                    --palette--;;name,
                    biography,
                    be_users,
                --div--;Access,
                    --palette--;;hidden,
                    --palette--;;access,
            ',
        ],
    ],
    'palettes' => [
        'name' => [
            'showitem' => 'first_name, last_name',
        ],
        'hidden' => [
            'showitem' => 'hidden',
        ],
        'access' => [
            'showitem' => 'starttime, endtime',
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
                'foreign_table' => 'tx_spark_person',
                'foreign_table_where' => 'AND tx_spark_person.pid=###CURRENT_PID### AND tx_spark_person.sys_language_uid IN (-1,0)',
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
        'first_name' => [
            'exclude' => true,
            'label' => 'LLL:EXT:spark_academics/Resources/Private/Language/locallang.xlf:person.first_name',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'required' => true,
            ],
        ],
        'last_name' => [
            'exclude' => true,
            'label' => 'LLL:EXT:spark_academics/Resources/Private/Language/locallang.xlf:person.last_name',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'required' => true,
            ],
        ],
        'biography' => [
            'exclude' => true,
            'label' => 'LLL:EXT:spark_academics/Resources/Private/Language/locallang.xlf:person.biography',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
                'richtextConfiguration' => 'default',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
            ],
        ],
        'be_users' => [
            'exclude' => true,
            'label' => 'LLL:EXT:spark_academics/Resources/Private/Language/locallang.xlf:person.be_users',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'be_users',
                'MM' => 'tx_spark_person_beuser_mm',
                'size' => 5,
                'autoSizeMax' => 30,
                'maxitems' => 9999,
                'multiple' => 0,
            ],
        ],
    ],
];
