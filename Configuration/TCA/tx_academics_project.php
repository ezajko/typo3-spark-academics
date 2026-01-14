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
        'title' => 'Project',
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
        'iconfile' => 'EXT:academics/Resources/Public/Icons/Extension.svg',
    ],
    'types' => [
        '1' => [
            'showitem' => '
                --div--;General, sys_language_uid, l10n_parent, l10n_diffsource, hidden, title, acronym, uuid, description, start_date, end_date, status, project_type, keywords, landing_page,
                --div--;Budget, funding_program, grant_agreement_number, total_budget, local_budget,
                --div--;People, coordinator, persons, partners,
                --div--;Classification, scientific_fields,
                --div--;Details, objectives, outcomes, website,
                --div--;Media, logo, main_image,
                --div--;Relations, be_users, organizations,
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
                'foreign_table' => 'tx_academics_domain_model_project',
                'foreign_table_where' => 'AND {#tx_academics_domain_model_project}.{#pid}=###CURRENT_PID### AND {#tx_academics_domain_model_project}.{#sys_language_uid} IN (-1,0)',
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
                'MM' => 'tx_academics_domain_model_project_beuser_mm',
                'size' => 5,
                'maxitems' => 99,
            ],
        ],
        'start_date' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'label' => 'Start Date',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'date',
                'default' => 0,
            ],
        ],
        'end_date' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'label' => 'End Date',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'date',
                'default' => 0,
            ],
        ],
        'status' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'label' => 'Status',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_academics_domain_model_project_status',
                'items' => [['', 0]],
                'default' => 0,
            ],
        ],
        'funding_program' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'label' => 'Funding Program',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_academics_domain_model_funding_program',
                'items' => [['', 0]],
                'default' => 0,
            ],
        ],
        'grant_agreement_number' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'label' => 'Grant Agreement Number',
            'config' => ['type' => 'input', 'eval' => 'trim'],
        ],
        'website' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'label' => 'Website',
            'config' => ['type' => 'input', 'eval' => 'trim', 'renderType' => 'inputLink'],
        ],
        'coordinator' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'label' => 'Project Coordinator',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_academics_domain_model_person',
                'items' => [['', 0]],
                'default' => 0,
            ],
        ],
        'persons' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'label' => 'Team Members',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_academics_domain_model_project_person',
                'foreign_field' => 'project',
                'foreign_sortby' => 'sorting',
                'maxitems' => 9999,
                'appearance' => [
                    'collapseAll' => true,
                    'expandSingle' => true,
                    'useSortable' => true,
                    'showNewRecordLink' => true,
                    'newRecordLinkTitle' => 'Add Team Member',
                    'levelLinksPosition' => 'top',
                ],
            ],
        ],
        'partners' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'label' => 'Partners',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_academics_domain_model_partner',
                'MM' => 'tx_academics_domain_model_project_partner_mm',
                'size' => 10,
                'maxitems' => 9999,
            ],
        ],
        'objectives' => [
            'exclude' => true,
            'label' => 'Objectives',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 10,
                'enableRichtext' => true,
            ],
        ],
        'outcomes' => [
            'exclude' => true,
            'label' => 'Outcomes',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 10,
                'enableRichtext' => true,
            ],
        ],
        'logo' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'label' => 'Logo',
            'config' => [
                'type' => 'file',
                'allowed' => 'common-image-types',
                'maxitems' => 1,
            ],
        ],
        'main_image' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'label' => 'Main Image',
            'config' => [
                'type' => 'file',
                'allowed' => 'common-image-types',
                'maxitems' => 1,
            ],
        ],
        'organizations' => [
            'exclude' => true,
            'label' => 'Participating Organizations',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_academics_domain_model_organization',
                'foreign_table_where' => 'AND {#tx_academics_domain_model_organization}.{#sys_language_uid} IN (-1,0) ORDER BY title',
                'MM' => 'tx_academics_domain_model_project_organization_mm',
                'size' => 10,
                'minitems' => 0,
                'maxitems' => 99,
            ],
        ],
        // CERIF-compatible fields
        'local_budget' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'label' => 'Local Budget (EUR)',
            'description' => 'Budget allocated to local institution',
            'config' => [
                'type' => 'number',
                'format' => 'decimal',
                'default' => 0,
            ],
        ],
        'total_budget' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'label' => 'Total Budget (EUR)',
            'description' => 'Total project budget',
            'config' => [
                'type' => 'number',
                'format' => 'decimal',
                'default' => 0,
            ],
        ],
        'keywords' => [
            'exclude' => true,
            'label' => 'Keywords',
            'description' => 'Comma-separated keywords (CERIF: cfProjKeyw)',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 3,
                'eval' => 'trim',
            ],
        ],
        'project_type' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'label' => 'Project Type',
            'description' => 'Classification type (Research, Development, etc.)',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_academics_domain_model_project_type',
                'items' => [['-- Select Type --', 0]],
                'default' => 0,
            ],
        ],
        'scientific_fields' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'label' => 'Scientific Fields',
            'description' => 'OECD FOS classification (CERIF: cfProj_Class)',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_academics_domain_model_scientific_field',
                'MM' => 'tx_academics_domain_model_project_scientific_field_mm',
                'size' => 5,
                'maxitems' => 9999,
            ],
        ],
    ],
];
