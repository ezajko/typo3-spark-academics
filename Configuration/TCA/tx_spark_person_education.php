<?php

/**
 * TCA configuration for tx_spark_person_education table
 * IRRE child table for Person education records (degrees, qualifications)
 *
 * @author Ernedin Zajko <ezajko@root.ba>
 */

return [
    'ctrl' => [
        'title' => 'LLL:EXT:spark_academics/Resources/Private/Language/locallang.xlf:person.education',
        'label' => 'qualification',
        'label_alt' => 'institution, year',
        'label_alt_force' => true,
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'delete' => 'deleted',
        'hideTable' => true,
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'searchFields' => 'qualification,institution,year',
        'iconfile' => 'EXT:core/Resources/Public/Icons/T3Icons/content/content-special-menu.svg',
        'security' => [
            'ignorePageTypeRestriction' => true,
        ],
    ],
    'types' => [
        '1' => [
            'showitem' => '
                degree_type, qualification, field_of_study,
                --linebreak--, institution, year,
                --linebreak--, thesis_title,
                hidden
            ',
        ],
    ],
    'columns' => [
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
                'default' => 0,
            ],
        ],
        'person' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'degree_type' => [
            'exclude' => true,
            'label' => 'LLL:EXT:spark_academics/Resources/Private/Language/locallang.xlf:education.degree_type',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['LLL:EXT:spark_academics/Resources/Private/Language/locallang.xlf:education.degree_type.bachelor', 'bachelor'],
                    ['LLL:EXT:spark_academics/Resources/Private/Language/locallang.xlf:education.degree_type.master', 'master'],
                    ['LLL:EXT:spark_academics/Resources/Private/Language/locallang.xlf:education.degree_type.phd', 'phd'],
                    ['LLL:EXT:spark_academics/Resources/Private/Language/locallang.xlf:education.degree_type.postdoc', 'postdoc'],
                    ['LLL:EXT:spark_academics/Resources/Private/Language/locallang.xlf:education.degree_type.other', 'other'],
                ],
                'default' => 'bachelor',
            ],
        ],
        'qualification' => [
            'exclude' => true,
            'label' => 'LLL:EXT:spark_academics/Resources/Private/Language/locallang.xlf:education.qualification',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'eval' => 'trim',
                'required' => true,
            ],
        ],
        'institution' => [
            'exclude' => true,
            'label' => 'LLL:EXT:spark_academics/Resources/Private/Language/locallang.xlf:education.institution',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'eval' => 'trim',
                'required' => true,
            ],
        ],
        'year' => [
            'exclude' => true,
            'label' => 'LLL:EXT:spark_academics/Resources/Private/Language/locallang.xlf:education.year',
            'config' => [
                'type' => 'input',
                'size' => 10,
                'eval' => 'trim',
            ],
        ],
        'field_of_study' => [
            'exclude' => true,
            'label' => 'LLL:EXT:spark_academics/Resources/Private/Language/locallang.xlf:education.field_of_study',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'eval' => 'trim',
            ],
        ],
        'thesis_title' => [
            'exclude' => true,
            'label' => 'LLL:EXT:spark_academics/Resources/Private/Language/locallang.xlf:education.thesis_title',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'eval' => 'trim',
            ],
        ],
    ],
];
