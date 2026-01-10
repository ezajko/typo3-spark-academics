<?php

/*
 * This file is part of the "Spark Academics" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) Ernedin Zajko <ezajko@root.ba>
 */

return [
    'academic' => [
        'labels' => 'LLL:EXT:spark_academics/Resources/Private/Language/locallang_mod.xlf:academic_category',
        'iconIdentifier' => 'content-user',
        'position' => ['before' => 'site'],
        'access' => 'user',
        'workspaces' => '*',
    ],
    'spark_academics_person' => [
        'parent' => 'academic',
        'position' => ['top'],
        'access' => 'user',
        'workspaces' => '*',
        'path' => '/module/academic/spark-academics-person',
        'labels' => 'LLL:EXT:spark_academics/Resources/Private/Language/locallang_mod.xlf:person',
        'extensionName' => 'SparkAcademics',
        'controllerActions' => [
            \EtfUnsa\SparkAcademics\Controller\Backend\PersonController::class => [
                'list', 'edit', 'update'
            ],
        ],
    ],
    'spark_academics_department' => [
        'parent' => 'academic',
        'access' => 'user',
        'workspaces' => '*',
        'path' => '/module/academic/spark-academics-department',
        'labels' => 'LLL:EXT:spark_academics/Resources/Private/Language/locallang_mod.xlf:department',
        'extensionName' => 'SparkAcademics',
        'controllerActions' => [
            \EtfUnsa\SparkAcademics\Controller\Backend\DepartmentController::class => [
                'list', 'edit', 'update'
            ],
        ],
    ],
    'spark_academics_lab' => [
        'parent' => 'academic',
        'access' => 'user',
        'workspaces' => '*',
        'path' => '/module/academic/spark-academics-lab',
        'labels' => 'LLL:EXT:spark_academics/Resources/Private/Language/locallang_mod.xlf:lab',
        'extensionName' => 'SparkAcademics',
        'controllerActions' => [
            \EtfUnsa\SparkAcademics\Controller\Backend\ResearchLabController::class => [
                'list', 'edit', 'update'
            ],
        ],
    ],
    'spark_academics_group' => [
        'parent' => 'academic',
        'access' => 'user',
        'workspaces' => '*',
        'path' => '/module/academic/spark-academics-group',
        'labels' => 'LLL:EXT:spark_academics/Resources/Private/Language/locallang_mod.xlf:group',
        'extensionName' => 'SparkAcademics',
        'controllerActions' => [
            \EtfUnsa\SparkAcademics\Controller\Backend\ResearchGroupController::class => [
                'list', 'edit', 'update'
            ],
        ],
    ],
    'spark_academics_chair' => [
        'parent' => 'academic',
        'access' => 'user',
        'workspaces' => '*',
        'path' => '/module/academic/spark-academics-chair',
        'labels' => 'LLL:EXT:spark_academics/Resources/Private/Language/locallang_mod.xlf:chair',
        'extensionName' => 'SparkAcademics',
        'controllerActions' => [
            \EtfUnsa\SparkAcademics\Controller\Backend\ChairController::class => [
                'list', 'edit', 'update'
            ],
        ],
    ],
    'spark_academics_course' => [
        'parent' => 'academic',
        'access' => 'user',
        'workspaces' => '*',
        'path' => '/module/academic/spark-academics-course',
        'labels' => 'LLL:EXT:spark_academics/Resources/Private/Language/locallang_mod.xlf:course',
        'extensionName' => 'SparkAcademics',
        'controllerActions' => [
            \EtfUnsa\SparkAcademics\Controller\Backend\CourseController::class => [
                'list', 'edit', 'update'
            ],
        ],
    ],
    'spark_academics_program' => [
        'parent' => 'academic',
        'access' => 'user',
        'workspaces' => '*',
        'path' => '/module/academic/spark-academics-program',
        'labels' => 'LLL:EXT:spark_academics/Resources/Private/Language/locallang_mod.xlf:program',
        'extensionName' => 'SparkAcademics',
        'controllerActions' => [
            \EtfUnsa\SparkAcademics\Controller\Backend\StudyProgramController::class => [
                'list', 'edit', 'update'
            ],
        ],
    ],
    'spark_academics_project' => [
        'parent' => 'academic',
        'access' => 'user',
        'workspaces' => '*',
        'path' => '/module/academic/spark-academics-project',
        'labels' => 'LLL:EXT:spark_academics/Resources/Private/Language/locallang_mod.xlf:project',
        'extensionName' => 'SparkAcademics',
        'controllerActions' => [
            \EtfUnsa\SparkAcademics\Controller\Backend\ProjectController::class => [
                'list', 'edit', 'update'
            ],
        ],
    ],
];
