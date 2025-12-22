<?php

return [
    'ctrl' => [
        'title' => 'LLL:EXT:spark_academics/Resources/Private/Language/locallang.xlf:person',
        'label' => 'last_name',
        'label_alt' => 'first_name',
        'label_alt_force' => true,
        'label_userFunc' => \EtfUnsa\SparkAcademics\UserFunc\UserLabelService::class . '->getPersonLabel',
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
        'searchFields' => 'first_name,last_name',
        'iconfile' => 'EXT:core/Resources/Public/Icons/T3Icons/content/content-user.svg'
    ],
    'types' => [
        '1' => [
            'showitem' => '
                --div--;LLL:EXT:spark_academics/Resources/Private/Language/locallang.xlf:person.tab.general,
                    --palette--;;name,
                    be_users,
                --div--;LLL:EXT:spark_academics/Resources/Private/Language/locallang.xlf:person.tab.biography,
                    biography, biography_file_pdf,
                --div--;LLL:EXT:spark_academics/Resources/Private/Language/locallang.xlf:person.tab.contact,
                    contact_office, phone_office, phone_mobile, contact_email, contact_website,
                --div--;LLL:EXT:spark_academics/Resources/Private/Language/locallang.xlf:person.tab.profiles,
                    profile_google_scholar, profile_research_gate, profile_github, profile_orcid, profile_linkedin,
                --div--;LLL:EXT:spark_academics/Resources/Private/Language/locallang.xlf:person.tab.media,
                    media_image,
                --div--;LLL:EXT:spark_academics/Resources/Private/Language/locallang.xlf:person.tab.access,
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
        'contact_office' => [
            'exclude' => true,
            'label' => 'LLL:EXT:spark_academics/Resources/Private/Language/locallang.xlf:person.contact_office',
            'config' => [
                'type' => 'input',
                'size' => 30,
            ],
        ],
        'phone_office' => [
            'exclude' => true,
            'label' => 'LLL:EXT:spark_academics/Resources/Private/Language/locallang.xlf:person.phone_office',
            'config' => [
                'type' => 'input',
                'size' => 30,
            ],
        ],
        'phone_mobile' => [
            'exclude' => true,
            'label' => 'LLL:EXT:spark_academics/Resources/Private/Language/locallang.xlf:person.phone_mobile',
            'config' => [
                'type' => 'input',
                'size' => 30,
            ],
        ],
        'contact_email' => [
            'exclude' => true,
            'label' => 'LLL:EXT:spark_academics/Resources/Private/Language/locallang.xlf:person.contact_email',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,email',
            ],
        ],
        'contact_website' => [
            'exclude' => true,
            'label' => 'LLL:EXT:spark_academics/Resources/Private/Language/locallang.xlf:person.contact_website',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputLink',
            ],
        ],
        'profile_google_scholar' => [
            'exclude' => true,
            'label' => 'LLL:EXT:spark_academics/Resources/Private/Language/locallang.xlf:person.profile_google_scholar',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputLink',
            ],
        ],
        'profile_research_gate' => [
            'exclude' => true,
            'label' => 'LLL:EXT:spark_academics/Resources/Private/Language/locallang.xlf:person.profile_research_gate',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputLink',
            ],
        ],
        'profile_github' => [
            'exclude' => true,
            'label' => 'LLL:EXT:spark_academics/Resources/Private/Language/locallang.xlf:person.profile_github',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputLink',
            ],
        ],
        'profile_orcid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:spark_academics/Resources/Private/Language/locallang.xlf:person.profile_orcid',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputLink',
            ],
        ],
        'profile_linkedin' => [
            'exclude' => true,
            'label' => 'LLL:EXT:spark_academics/Resources/Private/Language/locallang.xlf:person.profile_linkedin',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputLink',
            ],
        ],
        'media_image' => [
            'exclude' => true,
            'label' => 'LLL:EXT:spark_academics/Resources/Private/Language/locallang.xlf:person.media_image',
            'config' => [
                'type' => 'file',
                'allowed' => 'common-image-types',
                'maxitems' => 1,
            ],
        ],
        'biography_file_pdf' => [
            'exclude' => true,
            'label' => 'LLL:EXT:spark_academics/Resources/Private/Language/locallang.xlf:person.biography_file_pdf',
            'config' => [
                'type' => 'file',
                'allowed' => 'pdf',
                'maxitems' => 1,
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
