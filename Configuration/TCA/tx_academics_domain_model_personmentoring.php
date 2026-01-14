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
 * TCA configuration for tx_academics_domain_model_personmentoring table
 * IRRE child table for Person mentoring records (mentored students)
 *
 * @author Ernedin Zajko <ezajko@root.ba>
 */

return [
    'ctrl' => [
        'title' => 'LLL:EXT:academics/Resources/Private/Language/locallang.xlf:person.mentoring',
        'label' => 'student_name',
        'label_alt' => 'thesis_type, year',
        'label_alt_force' => true,
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'delete' => 'deleted',
        'sortby' => 'sorting',
        'default_sortby' => 'ORDER BY year DESC',
        'hideTable' => true,
        // Localization support
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'iconfile' => 'EXT:core/Resources/Public/Icons/T3Icons/content/content-user.svg',
        'security' => [
            'ignorePageTypeRestriction' => true,
        ],
    ],
    'types' => [
        '1' => [
            'showitem' => '
                student_name, thesis_type, thesis_title, year, role, notes, hidden
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
        'student_name' => [
            'exclude' => true,
            'label' => 'LLL:EXT:academics/Resources/Private/Language/locallang.xlf:mentoring.student_name',
            'config' => [
                'type' => 'input',
                'size' => 40,
                'eval' => 'trim',
                'required' => true,
            ],
        ],
        'thesis_type' => [
            'exclude' => true,
            'label' => 'LLL:EXT:academics/Resources/Private/Language/locallang.xlf:mentoring.thesis_type',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['LLL:EXT:academics/Resources/Private/Language/locallang.xlf:mentoring.thesis_type.bachelor', 'bachelor'],
                    ['LLL:EXT:academics/Resources/Private/Language/locallang.xlf:mentoring.thesis_type.master', 'master'],
                    ['LLL:EXT:academics/Resources/Private/Language/locallang.xlf:mentoring.thesis_type.phd', 'phd'],
                    ['LLL:EXT:academics/Resources/Private/Language/locallang.xlf:mentoring.thesis_type.other', 'other'],
                ],
                'default' => 'bachelor',
            ],
        ],
        'thesis_title' => [
            'exclude' => true,
            'label' => 'LLL:EXT:academics/Resources/Private/Language/locallang.xlf:mentoring.thesis_title',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'eval' => 'trim',
            ],
        ],
        'year' => [
            'exclude' => true,
            'label' => 'LLL:EXT:academics/Resources/Private/Language/locallang.xlf:mentoring.year',
            'config' => [
                'type' => 'input',
                'size' => 10,
                'eval' => 'trim',
            ],
        ],
        'role' => [
            'exclude' => true,
            'label' => 'LLL:EXT:academics/Resources/Private/Language/locallang.xlf:mentoring.role',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['LLL:EXT:academics/Resources/Private/Language/locallang.xlf:mentoring.role.mentor', 'mentor'],
                    ['LLL:EXT:academics/Resources/Private/Language/locallang.xlf:mentoring.role.comentor', 'comentor'],
                    ['LLL:EXT:academics/Resources/Private/Language/locallang.xlf:mentoring.role.committee', 'committee'],
                ],
                'default' => 'mentor',
            ],
        ],
        'notes' => [
            'exclude' => true,
            'label' => 'LLL:EXT:academics/Resources/Private/Language/locallang.xlf:mentoring.notes',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 3,
                'eval' => 'trim',
            ],
        ],
    ],
];
