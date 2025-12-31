<?php

defined('TYPO3') or die();

return [
    'ctrl' => [
        'title' => 'LLL:EXT:spark_academics/Resources/Private/Language/locallang_db.xlf:tx_spark_research_lab',
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
        'searchFields' => 'title,description',
        'iconfile' => 'EXT:spark_academics/Resources/Public/Icons/Extension.svg',
    ],
    'palettes' => [
        'social' => ['showitem' => 'facebook, twitter, linkedin, instagram'],
    ],
    'types' => [
        '1' => [
            'showitem' => '
                --div--;General, sys_language_uid, l10n_parent, l10n_diffsource, hidden, title, acronym, uuid, description, landing_page, head_person,
                --div--;Contact & Social, email, phone, website, address, room, --palette--;Social;social,
                --div--;Details, research_focus, mission, vision,
                --div--;Media, logo, main_image,
                --div--;Relations, be_users, persons, departments, chairs, research_groups,
                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access, starttime, endtime
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
                'foreign_table' => 'tx_spark_research_lab',
                'foreign_table_where' => 'AND {#tx_spark_research_lab}.{#pid}=###CURRENT_PID### AND {#tx_spark_research_lab}.{#sys_language_uid} IN (-1,0)',
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
                ]
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
                ]
            ],
        ],
        'landing_page' => [
            'exclude' => true,
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
        'email' => [
            'exclude' => true,
            'label' => 'Email',
            'config' => ['type' => 'input', 'eval' => 'trim,email'],
        ],
        'phone' => [
            'exclude' => true,
            'label' => 'Phone',
            'config' => ['type' => 'input', 'eval' => 'trim'],
        ],
        'website' => [
            'exclude' => true,
            'label' => 'Website',
            'config' => ['type' => 'input', 'eval' => 'trim', 'renderType' => 'inputLink'],
        ],
        'address' => [
            'exclude' => true,
            'label' => 'Address',
            'config' => ['type' => 'text', 'cols' => 40, 'rows' => 3, 'eval' => 'trim'],
        ],
        'room' => [
            'exclude' => true,
            'label' => 'Room',
            'config' => ['type' => 'input', 'eval' => 'trim'],
        ],
        'facebook' => [
            'exclude' => true,
            'label' => 'Facebook',
            'config' => ['type' => 'input', 'eval' => 'trim'],
        ],
        'twitter' => [
            'exclude' => true,
            'label' => 'Twitter/X',
            'config' => ['type' => 'input', 'eval' => 'trim'],
        ],
        'linkedin' => [
            'exclude' => true,
            'label' => 'LinkedIn',
            'config' => ['type' => 'input', 'eval' => 'trim'],
        ],
        'instagram' => [
            'exclude' => true,
            'label' => 'Instagram',
            'config' => ['type' => 'input', 'eval' => 'trim'],
        ],
        'head_person' => [
            'exclude' => true,
            'label' => 'Head of Lab',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_spark_person',
                'items' => [['', 0]],
                'default' => 0,
            ],
        ],
        'departments' => [
            'exclude' => true,
            'label' => 'Departments',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_spark_department',
                'MM' => 'tx_spark_department_research_lab_mm',
                'MM_opposite_field' => 'research_labs',
                'size' => 10,
                'maxitems' => 9999,
            ],
        ],
        'chairs' => [
            'exclude' => true,
            'label' => 'Chairs',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_spark_chair',
                'MM' => 'tx_spark_chair_research_lab_mm',
                'MM_opposite_field' => 'research_labs',
                'size' => 10,
                'maxitems' => 9999,
            ],
        ],
        'research_groups' => [
            'exclude' => true,
            'label' => 'Research Groups',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_spark_research_group',
                'MM' => 'tx_spark_research_lab_research_group_mm',
                'size' => 10,
                'maxitems' => 9999,
            ],
        ],
        'logo' => [
            'exclude' => true,
            'label' => 'Logo',
            'config' => [
                'type' => 'file',
                'allowed' => 'common-image-types',
                'maxitems' => 1,
            ],
        ],
        'main_image' => [
            'exclude' => true,
            'label' => 'Main Image',
            'config' => [
                'type' => 'file',
                'allowed' => 'common-image-types',
                'maxitems' => 1,
            ],
        ],
        'research_focus' => [
            'exclude' => true,
            'label' => 'Research Focus',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
            ],
        ],
        'mission' => [
            'exclude' => true,
            'label' => 'Mission',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
            ],
        ],
        'vision' => [
            'exclude' => true,
            'label' => 'Vision',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
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
        'acronym' => [
            'exclude' => true,
            'label' => 'Acronym',
            'config' => [
                'type' => 'input',
                'size' => 10,
                'eval' => 'trim'
            ],
        ],
        'uuid' => [
            'exclude' => true,
            'label' => 'UUID',
            'config' => [
                'type' => 'uuid',
            ],
        ],
        'be_users' => [
            'exclude' => true,
            'label' => 'Editors',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'be_users',
                'MM' => 'tx_spark_research_lab_beuser_mm',
                'size' => 5,
                'maxitems' => 99,
            ],
        ],
        'persons' => [
            'exclude' => true,
            'label' => 'Members',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_spark_person',
                'MM' => 'tx_spark_person_research_lab_mm',
                'MM_opposite_field' => 'laboratories',
                'size' => 10,
                'maxitems' => 9999,
            ],
        ],
    ],
];
