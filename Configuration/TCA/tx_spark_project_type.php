<?php

/**
 * TCA Configuration for ProjectType
 * CERIF: cfProj_Class (semantic layer for project classification)
 * 
 * @author Ernedin Zajko <ezajko@root.ba>
 */

return [
    'ctrl' => [
        'title' => 'Project Type',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'searchFields' => 'title,description',
        'iconfile' => 'EXT:spark_academics/Resources/Public/Icons/project_type.svg',
        'default_sortby' => 'title ASC',
    ],
    'types' => [
        '1' => [
            'showitem' => '
                --div--;General,
                    title, description, color,
                --div--;Access,
                    hidden,
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
                'size' => 40,
                'max' => 255,
                'eval' => 'trim',
                'required' => true,
            ],
        ],
        'description' => [
            'exclude' => true,
            'label' => 'Description',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 5,
            ],
        ],
        'color' => [
            'exclude' => true,
            'label' => 'Color',
            'config' => [
                'type' => 'color',
            ],
        ],
    ],
];
