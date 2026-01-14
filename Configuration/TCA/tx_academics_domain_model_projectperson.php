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
        'title' => 'Project Staff',
        'label' => 'person',
        'label_alt' => 'role',
        'label_alt_force' => true,
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
        ],
        'searchFields' => 'role',
        'iconfile' => 'EXT:academics/Resources/Public/Icons/Extension.svg',
        'hideTable' => true,
    ],
    'types' => [
        '1' => [
            'showitem' => '
                --palette--;;general,
            '
        ],
    ],
    'palettes' => [
        'general' => [
            'showitem' => 'person, --linebreak--, role, hidden',
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
                'foreign_table' => 'tx_academics_domain_model_projectperson',
                'foreign_table_where' => 'AND {#tx_academics_domain_model_projectperson}.{#pid}=###CURRENT_PID### AND {#tx_academics_domain_model_projectperson}.{#sys_language_uid} IN (-1,0)',
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
        'role' => [
            'exclude' => true,
            'label' => 'Role/Status',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['-- Select Role --', ''],
                    ['Coordinator', 'coordinator'],
                    ['Researcher', 'researcher'],
                    ['Partner', 'partner'],
                    ['Administrative', 'administrative'],
                ],
            ],
        ],
        'person' => [
            'exclude' => true,
            'label' => 'Person',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_academics_domain_model_person',
                'foreign_table_where' => 'ORDER BY last_name, first_name',
                'items' => [['-- Select Person --', 0]],
                'minitems' => 1,
            ],
        ],
        'project' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
    ],
];
