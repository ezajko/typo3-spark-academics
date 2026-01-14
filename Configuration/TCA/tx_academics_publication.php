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
 * TCA configuration for tx_spark_publication
 */
return [
    'ctrl' => [
        'title' => 'LLL:EXT:academics/Resources/Private/Language/locallang_db.xlf:tx_spark_publication',
        'label' => 'title',
        'label_alt' => 'publication_year',
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
        'searchFields' => 'title,journal_title,doi,orcid_put_code',
        'iconfile' => 'EXT:core/Resources/Public/Icons/T3Icons/content/content-text.svg' // Placeholder icon
    ],
    'types' => [
        '1' => [
            'showitem' => '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    publication_type, title,
                    --palette--;;bibliographic,
                    author_list, authors,
                --div--;Details,
                    doi, access_url, citation_text,
                --div--;Reference,
                    orcid_put_code, is_featured,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    --palette--;;hidden,
                    --palette--;;access,
            ',
        ],
    ],
    'palettes' => [
        'bibliographic' => [
            'showitem' => 'publication_year, journal_title, --linebreak--, volume, issue, pages',
            'label' => 'Bibliographic Information'
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
                'foreign_table' => 'tx_academics_domain_model_domain_model_publication',
                'foreign_table_where' => 'AND tx_spark_publication.pid=###CURRENT_PID### AND tx_spark_publication.sys_language_uid IN (-1,0)',
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

        // Core Fields
        'title' => [
            'exclude' => true,
            'label' => 'Publication Title',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'eval' => 'trim,required',
            ],
        ],
        'publication_type' => [
            'exclude' => true,
            'label' => 'Publication Type',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['Journal Article', 'journal-article'],
                    ['Conference Paper', 'conference-paper'],
                    ['Book', 'book'],
                    ['Book Chapter', 'book-chapter'],
                    ['Dissertation', 'dissertation'],
                    ['Report', 'report'],
                    ['Other', 'other'],
                ],
                'default' => 'journal-article',
            ],
        ],
        'publication_year' => [
            'exclude' => true,
            'label' => 'Year',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int,trim',
            ],
        ],
        'journal_title' => [
            'exclude' => true,
            'label' => 'Journal / Conference Name',
            'config' => [
                'type' => 'input',
                'size' => 40,
                'eval' => 'trim',
            ],
        ],
        'volume' => [
            'exclude' => true,
            'label' => 'Volume',
            'config' => [
                'type' => 'input',
                'size' => 10,
                'eval' => 'trim',
            ],
        ],
        'issue' => [
            'exclude' => true,
            'label' => 'Issue',
            'config' => [
                'type' => 'input',
                'size' => 10,
                'eval' => 'trim',
            ],
        ],
        'pages' => [
            'exclude' => true,
            'label' => 'Pages',
            'config' => [
                'type' => 'input',
                'size' => 15,
                'eval' => 'trim',
            ],
        ],

        // Identifiers
        'doi' => [
            'exclude' => true,
            'label' => 'DOI',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'access_url' => [
            'exclude' => true,
            'label' => 'URL',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputLink',
            ],
        ],
        'citation_text' => [
            'exclude' => true,
            'label' => 'Citation (APA/BibTeX)',
            'config' => [
                'type' => 'text',
                'rows' => 3,
                'eval' => 'trim',
            ],
        ],
        'orcid_put_code' => [
            'exclude' => true,
            'label' => 'ORCID Put-Code (System ID)',
            'config' => [
                'type' => 'input',
                'size' => 20,
                'readOnly' => 1, // Managed by Sync
            ],
        ],
        'is_featured' => [
            'exclude' => true,
            'label' => 'Selected Publication (Featured)',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'default' => 0,
            ],
        ],
        'author_list' => [
            'exclude' => true,
            'label' => 'Author List (Textual)',
            'description' => 'Full list of authors as displayed in the publication (e.g. "Smith J., Doe A.")',
            'config' => [
                'type' => 'text',
                'rows' => 2,
                'eval' => 'trim',
            ],
        ],

        // Relations
        'authors' => [
            'exclude' => true,
            'label' => 'Authors (Persons)',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_academics_domain_model_domain_model_person',
                'MM' => 'tx_academics_domain_model_domain_model_person_publication_mm',
                'MM_opposite_field' => 'publications', // Will add this to Person TCA
                'size' => 10,
                'autoSizeMax' => 30,
                'maxitems' => 9999,
                'multiple' => 0,
            ],
        ],
    ],
];
