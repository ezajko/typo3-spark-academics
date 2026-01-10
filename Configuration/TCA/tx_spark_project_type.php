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
 * TCA Configuration for ProjectType
 * CERIF: cfProj_Class (semantic layer for project classification)
 * 
 * @author Ernedin Zajko <ezajko@root.ba>
 */

defined('TYPO3') or die();

return [
    'ctrl' => [
        'title' => 'Project Type',
        'label' => 'title',
        'label_alt' => 'abbreviation',
        'label_alt_force' => true,
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'sortby' => 'sorting',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'searchFields' => 'title,abbreviation,description',
        'iconfile' => 'EXT:spark_academics/Resources/Public/Icons/Extension.svg',
        'default_sortby' => 'sorting ASC',
    ],
    'types' => [
        '1' => [
            'showitem' => '
                --div--;General,
                    hidden, title, abbreviation, description,
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
        'title' => [
            'exclude' => false,
            'label' => 'Title',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'max' => 255,
                'eval' => 'trim',
                'required' => true,
            ],
        ],
        'abbreviation' => [
            'exclude' => true,
            'label' => 'Abbreviation',
            'config' => [
                'type' => 'input',
                'size' => 20,
                'max' => 50,
                'eval' => 'trim',
            ],
        ],
        'description' => [
            'exclude' => true,
            'label' => 'Description',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 5,
                'eval' => 'trim',
            ],
        ],
    ],
];
