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
 * TCA configuration for tx_academics_domain_model_person table
 * Represents academic personnel with their profiles, affiliations, and contact information.
 *
 * @author Ernedin Zajko <ezajko@root.ba>
 */

return [
    'ctrl' => [
        'title' => 'LLL:EXT:academics/Resources/Private/Language/locallang.xlf:person',
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
        'default_sortby' => 'ORDER BY last_name ASC, first_name ASC',
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
                --div--;LLL:EXT:academics/Resources/Private/Language/locallang.xlf:person.tab.general,
                    --palette--;;name, path, gender,
                    --palette--;;status,
                    --palette--;;affiliation,
                    additional_organizations,
                --div--;LLL:EXT:academics/Resources/Private/Language/locallang.xlf:person.tab.biography,
                    biography, biography_file_pdf,
                --div--;LLL:EXT:academics/Resources/Private/Language/locallang.xlf:person.tab.contact,
                    contact_office, phone_office, phone_mobile, contact_email, contact_website,
                --div--;LLL:EXT:academics/Resources/Private/Language/locallang.xlf:person.tab.education,
                    education,
                --div--;LLL:EXT:academics/Resources/Private/Language/locallang.xlf:person.tab.mentoring,
                    mentoring,
                --div--;LLL:EXT:academics/Resources/Private/Language/locallang.xlf:person.tab.research,
                    research_interests, keywords,
                --div--;LLL:EXT:academics/Resources/Private/Language/locallang.xlf:person.tab.teaching,
                    teaching, courses, study_programs,
                --div--;LLL:EXT:academics/Resources/Private/Language/locallang.xlf:person.tab.consultations,
                    consultation_hours,
                --div--;LLL:EXT:academics/Resources/Private/Language/locallang.xlf:person.tab.profiles,
                    profile_google_scholar, profile_research_gate, profile_github, profile_orcid, profile_linkedin, scopus_id, researcher_id,
                --div--;Publications,
                    publications,
                --div--;LLL:EXT:academics/Resources/Private/Language/locallang.xlf:person.tab.media,
                    media_image,
                --div--;Administration,
                    be_users,
                    projects,
                --div--;LLL:EXT:academics/Resources/Private/Language/locallang.xlf:person.tab.access,
                    --palette--;;hidden,
                    --palette--;;access,
            ',
        ],
    ],
    'palettes' => [
        // Name palette: first and last name side by side
        'name' => [
            'showitem' => 'first_name, last_name',
        ],
        // Affiliation palette: academic title, rank, type, and primary organization
        'affiliation' => [
            'showitem' => 'academic_title, academic_rank, person_type, primary_organization',
        ],
        'status' => [
            'showitem' => 'is_academic, is_council_member, staff_status',
        ],
        'hidden' => [
            'showitem' => 'hidden',
        ],
        'access' => [
            'showitem' => 'starttime, endtime',
        ],
    ],
    'columns' => [
        // =====================================================================
        // System Fields
        // =====================================================================
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
                'foreign_table' => 'tx_academics_domain_model_person',
                'foreign_table_where' => 'AND tx_academics_domain_model_person.pid=###CURRENT_PID### AND tx_academics_domain_model_person.sys_language_uid IN (-1,0)',
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

        // =====================================================================
        // Basic Information
        // =====================================================================
        'first_name' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'l10n_display' => 'defaultAsReadonly',
            'label' => 'LLL:EXT:academics/Resources/Private/Language/locallang.xlf:person.first_name',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'required' => true,
            ],
        ],
        'last_name' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'l10n_display' => 'defaultAsReadonly',
            'label' => 'LLL:EXT:academics/Resources/Private/Language/locallang.xlf:person.last_name',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'required' => true,
            ],
        ],
        'path' => [
            'exclude' => true,
            'label' => 'LLL:EXT:academics/Resources/Private/Language/locallang.xlf:person.path',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
            ],
        ],
        'gender' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'label' => 'LLL:EXT:academics/Resources/Private/Language/locallang.xlf:person.gender',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['LLL:EXT:academics/Resources/Private/Language/locallang.xlf:person.gender.0', 0],
                    ['LLL:EXT:academics/Resources/Private/Language/locallang.xlf:person.gender.1', 1],
                    ['LLL:EXT:academics/Resources/Private/Language/locallang.xlf:person.gender.2', 2],
                    ['LLL:EXT:academics/Resources/Private/Language/locallang.xlf:person.gender.9', 9],
                ],
                'default' => 0,
            ],
        ],

        // =====================================================================
        // Academic Affiliation (on General tab)
        // =====================================================================
        'academic_title' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'label' => 'LLL:EXT:academics/Resources/Private/Language/locallang_db.xlf:tx_academics_domain_model_academic_title',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_academics_domain_model_academic_title',
                'items' => [
                    ['', 0],
                ],
                'default' => 0,
            ],
        ],
        'academic_rank' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'label' => 'LLL:EXT:academics/Resources/Private/Language/locallang_db.xlf:tx_academics_domain_model_academic_rank',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_academics_domain_model_academic_rank',
                'items' => [
                    ['', 0],
                ],
                'default' => 0,
            ],
        ],
        'person_type' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'label' => 'Person Type',
            'description' => 'Staff type (internal, visiting professor, guest, etc.)',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_academics_domain_model_person_type',
                'items' => [
                    ['', 0],
                ],
                'default' => 0,
            ],
        ],

        // =====================================================================
        // Biography & Media
        // =====================================================================
        'biography' => [
            'exclude' => true,
            'label' => 'LLL:EXT:academics/Resources/Private/Language/locallang.xlf:person.biography',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
                'richtextConfiguration' => 'default',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
            ],
        ],
        'media_image' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'label' => 'LLL:EXT:academics/Resources/Private/Language/locallang.xlf:person.media_image',
            'config' => [
                'type' => 'file',
                'allowed' => 'common-image-types',
                'maxitems' => 1,
                'appearance' => [
                    'fileUploadAllowed' => false,
                ],
            ],
        ],
        'biography_file_pdf' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'label' => 'LLL:EXT:academics/Resources/Private/Language/locallang.xlf:person.biography_file_pdf',
            'config' => [
                'type' => 'file',
                'allowed' => 'pdf',
                'maxitems' => 1,
                'appearance' => [
                    'fileUploadAllowed' => false,
                ],
            ],
        ],

        // =====================================================================
        // Contact Information
        // =====================================================================
        'contact_office' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'label' => 'LLL:EXT:academics/Resources/Private/Language/locallang.xlf:person.contact_office',
            'config' => [
                'type' => 'input',
                'size' => 30,
            ],
        ],
        'phone_office' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'label' => 'LLL:EXT:academics/Resources/Private/Language/locallang.xlf:person.phone_office',
            'config' => [
                'type' => 'input',
                'size' => 30,
            ],
        ],
        'phone_mobile' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'label' => 'LLL:EXT:academics/Resources/Private/Language/locallang.xlf:person.phone_mobile',
            'config' => [
                'type' => 'input',
                'size' => 30,
            ],
        ],
        'contact_email' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'label' => 'LLL:EXT:academics/Resources/Private/Language/locallang.xlf:person.contact_email',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,email',
            ],
        ],
        'contact_website' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'label' => 'LLL:EXT:academics/Resources/Private/Language/locallang.xlf:person.contact_website',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputLink',
            ],
        ],

        // =====================================================================
        // Education (IRRE)
        // =====================================================================
        'education' => [
            'exclude' => true,
            'label' => 'LLL:EXT:academics/Resources/Private/Language/locallang.xlf:person.education',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_academics_domain_model_person_education',
                'foreign_field' => 'person',
                'appearance' => [
                    'collapseAll' => true,
                    'expandSingle' => true,
                ],
            ],
        ],

        // =====================================================================
        // Mentoring (IRRE)
        // =====================================================================
        'mentoring' => [
            'exclude' => true,
            'label' => 'LLL:EXT:academics/Resources/Private/Language/locallang.xlf:person.mentoring',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_academics_domain_model_person_mentoring',
                'foreign_field' => 'person',
                'appearance' => [
                    'collapseAll' => true,
                    'expandSingle' => true,
                    'newRecordLinkTitle' => 'LLL:EXT:academics/Resources/Private/Language/locallang.xlf:mentoring.add_new',
                ],
            ],
        ],

        // =====================================================================
        // Research & Teaching
        // =====================================================================
        'research_interests' => [
            'exclude' => true,
            'label' => 'LLL:EXT:academics/Resources/Private/Language/locallang.xlf:person.research_interests',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
                'richtextConfiguration' => 'default',
                'fieldControl' => [
                    'fullScreenRichtext' => [
                        'disabled' => false,
                    ],
                ],
                'cols' => 40,
                'rows' => 5,
                'eval' => 'trim',
            ],
        ],
        'keywords' => [
            'exclude' => true,
            'label' => 'LLL:EXT:academics/Resources/Private/Language/locallang.xlf:person.keywords',
            'description' => 'LLL:EXT:academics/Resources/Private/Language/locallang.xlf:person.keywords.description',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 2,
                'eval' => 'trim',
            ],
        ],
        'consultation_hours' => [
            'exclude' => true,
            'label' => 'LLL:EXT:academics/Resources/Private/Language/locallang.xlf:person.consultation_hours',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
                'richtextConfiguration' => 'default',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
            ],
        ],
        'teaching' => [
            'exclude' => true,
            'label' => 'LLL:EXT:academics/Resources/Private/Language/locallang.xlf:person.teaching',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
                'richtextConfiguration' => 'default',
                'fieldControl' => [
                    'fullScreenRichtext' => [
                        'disabled' => false,
                    ],
                ],
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
            ],
        ],

        // =====================================================================
        // Academic Profiles & Identifiers
        // =====================================================================
        'profile_google_scholar' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'label' => 'LLL:EXT:academics/Resources/Private/Language/locallang.xlf:person.profile_google_scholar',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'profile_research_gate' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'label' => 'LLL:EXT:academics/Resources/Private/Language/locallang.xlf:person.profile_research_gate',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'profile_github' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'label' => 'LLL:EXT:academics/Resources/Private/Language/locallang.xlf:person.profile_github',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'profile_orcid' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'label' => 'LLL:EXT:academics/Resources/Private/Language/locallang.xlf:person.profile_orcid',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'profile_linkedin' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'label' => 'LLL:EXT:academics/Resources/Private/Language/locallang.xlf:person.profile_linkedin',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'scopus_id' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'label' => 'LLL:EXT:academics/Resources/Private/Language/locallang.xlf:person.scopus_id',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'researcher_id' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'label' => 'LLL:EXT:academics/Resources/Private/Language/locallang.xlf:person.researcher_id',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],

        // =====================================================================
        // Backend User Association
        // =====================================================================
        'be_users' => [
            'exclude' => true,
            'label' => 'LLL:EXT:academics/Resources/Private/Language/locallang.xlf:person.be_users',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'be_users',
                'MM' => 'tx_academics_domain_model_person_beuser_mm',
                'size' => 5,
                'autoSizeMax' => 30,
                'maxitems' => 9999,
                'multiple' => 0,
            ],
        ],

        // =====================================================================
        // Organizational Relations (read-only, managed from other entities)
        // =====================================================================
        // Organizational Relations
        'primary_organization' => [
            'exclude' => true,
            'label' => 'Primary Organization (e.g. Department)',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_academics_domain_model_organization',
                'foreign_table_where' => 'AND {#tx_academics_domain_model_organization}.{#sys_language_uid} IN (-1,0) ORDER BY title',
                'items' => [['-- Select Organization --', 0]],
            ],
        ],
        'additional_organizations' => [
            'exclude' => true,
            'label' => 'Additional Affiliations',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_academics_domain_model_organization',
                'foreign_table_where' => 'AND {#tx_academics_domain_model_organization}.{#sys_language_uid} IN (-1,0) ORDER BY title',
                'MM' => 'tx_academics_domain_model_person_organization_mm',
                'size' => 10,
                'minitems' => 0,
                'maxitems' => 99,
            ],
        ],
        // Staff Status & Roles
        'is_academic' => [
            'exclude' => true,
            'label' => 'Academic Staff',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        'label' => 'Is Academic Staff?',
                    ],
                ],
            ],
        ],
        'is_council_member' => [
            'exclude' => true,
            'label' => 'Council Member',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        'label' => 'Is Council Member?',
                    ],
                ],
            ],
        ],
        'staff_status' => [
            'exclude' => true,
            'label' => 'Staff Status',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['Active', 'active'],
                    ['Inactive', 'inactive'],
                    ['Sabbatical', 'sabbatical'],
                    ['Retired', 'retired'],
                ],
                'default' => 'active',
            ],
        ],
        'courses' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'label' => 'LLL:EXT:academics/Resources/Private/Language/locallang.xlf:person.courses',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_academics_domain_model_course',
                'MM' => 'tx_academics_domain_model_person_course_mm',
                'MM_opposite_field' => 'persons',
                'size' => 10,
                'maxitems' => 99,
                'readOnly' => 1,
            ],
        ],
        'study_programs' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'label' => 'LLL:EXT:academics/Resources/Private/Language/locallang.xlf:person.study_programs',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_academics_domain_model_study_program',
                'MM' => 'tx_academics_domain_model_person_study_program_mm',
                'MM_opposite_field' => 'persons',
                'size' => 10,
                'maxitems' => 99,
                'readOnly' => 1,
            ],
        ],
        'projects' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'label' => 'LLL:EXT:academics/Resources/Private/Language/locallang.xlf:person.projects',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_academics_domain_model_project',
                'MM' => 'tx_academics_domain_model_person_project_mm',
                'MM_opposite_field' => 'persons',
                'size' => 10,
                'maxitems' => 99,
                'readOnly' => 1,
            ],
        ],
        'publications' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'label' => 'LLL:EXT:academics/Resources/Private/Language/locallang.xlf:person.publications',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_academics_domain_model_publication',
                'foreign_field' => 'authors', // Wait, authors is M:N, inline needs MM table adjustment or different config
                'MM' => 'tx_academics_domain_model_person_publication_mm',
                'appearance' => [
                    'collapseAll' => true,
                    'expandSingle' => true,
                    'useSortable' => false,
                    'newRecordLinkTitle' => 'LLL:EXT:academics/Resources/Private/Language/locallang.xlf:person.publications.add_new',
                    'levelLinksPosition' => 'top',
                    'showSynchronizationLink' => 1,
                    'showAllLocalizationLink' => 1,
                    'showPossibleLocalizationRecords' => 1,
                    'showRemovedLocalizationRecords' => 1,
                ],
            ],
        ],
    ],
];
