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
        'title' => 'Organization',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'sortby' => 'sorting',
        'default_sortby' => 'ORDER BY title ASC',
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
        'searchFields' => 'title,acronym,description',
        'iconfile' => 'EXT:academics/Resources/Public/Icons/Extension.svg',
        'faker' => true,
        'security' => [
            'ignorePageTypeRestriction' => true,
        ],
    ],
    'types' => [
        '1' => [
            'showitem' => '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    type, title, acronym, parent, head_person, landing_page, home_page, description, research_focus, mission, vision,
                --div--;Contact,
                    email, phone, website, address, room,
                --div--;Social Media,
                    facebook, twitter, linkedin, instagram,
                --div--;Relations,
                    primary_members, secondary_members, courses, projects,
                    --palette--;;filePalette,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
                    sys_language_uid, l10n_parent, l10n_diffsource,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    hidden, starttime, endtime,
            ',
        ],
    ],
    'palettes' => [
        'filePalette' => [
            'showitem' => 'logo, --linebreak--, main_image',
        ],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => ['type' => 'language'],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [['label' => '', 'value' => 0]],
                'foreign_table' => 'tx_academics_domain_model_organization',
                'foreign_table_where' => 'AND {#tx_academics_domain_model_organization}.{#pid}=###CURRENT_PID### AND {#tx_academics_domain_model_organization}.{#sys_language_uid} IN (-1,0)',
                'default' => 0,
            ],
        ],
        'l10n_diffsource' => [
            'config' => ['type' => 'passthrough'],
        ],
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.visible',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [['label' => '', 'invertStateDisplay' => true]],
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
                'range' => ['upper' => 2147483647],
            ],
        ],
        'title' => [
            'exclude' => true,
            'label' => 'Title',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required',
            ],
        ],
        'acronym' => [
            'exclude' => true,
            'label' => 'Acronym',
            'config' => [
                'type' => 'input',
                'size' => 10,
                'eval' => 'trim',
            ],
        ],
        'uuid' => [
            'exclude' => true,
            'label' => 'UUID',
            'config' => [
                'type' => 'uuid',
            ],
        ],
        'type' => [
            'exclude' => true,
            'label' => 'Organization Type',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_academics_domain_model_organizationtype',
                'foreign_table_where' => 'AND {#tx_academics_domain_model_organizationtype}.{#sys_language_uid} IN (-1,0) ORDER BY title',
                'items' => [['-- Select Type --', 0]],
            ],
        ],
        'parent' => [
            'exclude' => true,
            'label' => 'Parent Organization',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectTree',
                'foreign_table' => 'tx_academics_domain_model_organization',
                'foreign_table_where' => 'AND {#tx_academics_domain_model_organization}.{#sys_language_uid} IN (-1,0) ORDER BY title',
                'treeConfig' => [
                    'parentField' => 'parent',
                    'appearance' => [
                        'showHeader' => true,
                        'expandAll' => true,
                        'maxLevels' => 99,
                    ],
                ],
                'size' => 10,
                'autoSizeMax' => 30,
                'minitems' => 0,
                'maxitems' => 1,
            ],
        ],
        'head_person' => [
            'exclude' => true,
            'label' => 'Head Person (e.g. Dean, Head of Dept.)',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_academics_domain_model_person',
                'foreign_table_where' => 'ORDER BY last_name, first_name',
                'items' => [['-- Select Person --', 0]],
            ],
        ],
        'description' => [
            'exclude' => true,
            'label' => 'Description',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
                'richtextConfiguration' => 'default',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
            ],
        ],
        'research_focus' => [
            'exclude' => true,
            'label' => 'Research Focus',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 5,
                'eval' => 'trim',
            ],
        ],
        'mission' => [
            'exclude' => true,
            'label' => 'Mission',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 5,
                'eval' => 'trim',
            ],
        ],
        'vision' => [
            'exclude' => true,
            'label' => 'Vision',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 5,
                'eval' => 'trim',
            ],
        ],
        'landing_page' => [
            'exclude' => true,
            'label' => 'Landing Page',
            'description' => 'Internal TYPO3 page for this organization',
            'config' => [
                'type' => 'link',
                'allowedTypes' => ['page'],
                'appearance' => [
                    'browserTitle' => 'Select Landing Page',
                ],
            ],
        ],
        'home_page' => [
            'exclude' => true,
            'label' => 'Home Page URL',
            'description' => 'External homepage (organization\'s own website)',
            'config' => [
                'type' => 'link',
                'allowedTypes' => ['url'],
                'appearance' => [
                    'allowedOptions' => [],
                ],
            ],
        ],
        'email' => [
            'exclude' => true,
            'label' => 'Email',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,email',
            ],
        ],
        'phone' => [
            'exclude' => true,
            'label' => 'Phone',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'website' => [
            'exclude' => true,
            'label' => 'Website',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'address' => [
            'exclude' => true,
            'label' => 'Address',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'room' => [
            'exclude' => true,
            'label' => 'Room/Office',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'facebook' => [
            'exclude' => true,
            'label' => 'Facebook',
            'config' => ['type' => 'input', 'size' => 30, 'eval' => 'trim'],
        ],
        'twitter' => [
            'exclude' => true,
            'label' => 'Twitter',
            'config' => ['type' => 'input', 'size' => 30, 'eval' => 'trim'],
        ],
        'linkedin' => [
            'exclude' => true,
            'label' => 'LinkedIn',
            'config' => ['type' => 'input', 'size' => 30, 'eval' => 'trim'],
        ],
        'instagram' => [
            'exclude' => true,
            'label' => 'Instagram',
            'config' => ['type' => 'input', 'size' => 30, 'eval' => 'trim'],
        ],
        'logo' => [
            'exclude' => true,
            'label' => 'Logo',
            'config' => [
                'type' => 'file',
                'maxitems' => 1,
                'allowed' => 'common-image-types',
            ],
        ],
        'main_image' => [
            'exclude' => true,
            'label' => 'Main Image',
            'config' => [
                'type' => 'file',
                'maxitems' => 1,
                'allowed' => 'common-image-types',
            ],
        ],
        // Two-way relations
        'primary_members' => [
            'exclude' => true,
            'label' => 'Primary Members',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_academics_domain_model_person',
                'foreign_field' => 'primary_organization',
                'maxitems' => 9999,
                'appearance' => [
                    'collapseAll' => true,
                    'expandSingle' => true,
                    'useSortable' => false,
                    'showNewRecordLink' => false, // Usually managed from Person side
                    'levelLinksPosition' => 'top',
                ],
            ],
        ],
        'secondary_members' => [
            'exclude' => true,
            'label' => 'Affiliated Members',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_academics_domain_model_person',
                'MM' => 'tx_academics_domain_model_personorganization_mm',
                'MM_opposite_field' => 'additional_organizations',
                'size' => 10,
                'maxitems' => 9999,
                'readOnly' => 1, // Managed from Person side usually
            ],
        ],
        'courses' => [
            'exclude' => true,
            'label' => 'Courses',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_academics_domain_model_course',
                'foreign_field' => 'organization',
                'maxitems' => 9999,
                'appearance' => [
                    'collapseAll' => true,
                    'expandSingle' => true,
                    'useSortable' => true,
                    'showNewRecordLink' => false, // Managed from Course side
                ],
            ],
        ],
        'projects' => [
            'exclude' => true,
            'label' => 'Projects',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_academics_domain_model_project',
                'MM' => 'tx_academics_domain_model_projectorganization_mm',
                'MM_opposite_field' => 'organizations',
                'size' => 10,
                'maxitems' => 9999,
                'readOnly' => 1, // Managed from Project side usually
            ],
        ],
    ],
];
