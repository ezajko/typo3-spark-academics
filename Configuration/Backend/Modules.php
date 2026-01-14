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
        'labels' => 'LLL:EXT:academics/Resources/Private/Language/locallang_mod.xlf:academic_category',
        'iconIdentifier' => 'content-user',
        'position' => ['before' => 'site'],
        'access' => 'user',
        'workspaces' => '*',
    ],
    'academics_person' => [
        'parent' => 'academic',
        'position' => ['top'],
        'access' => 'user',
        'workspaces' => '*',
        'path' => '/module/academic/academics-person',
        'labels' => 'LLL:EXT:academics/Resources/Private/Language/locallang_mod.xlf:person',
        'extensionName' => 'Academics',
        'controllerActions' => [
            \RootBa\Academics\Controller\Backend\PersonController::class => [
                'list', 'edit', 'update'
            ],
        ],
    ],
    'academics_organization' => [
        'parent' => 'academic',
        'access' => 'user',
        'workspaces' => '*',
        'path' => '/module/academic/academics-organization',
        'labels' => 'LLL:EXT:academics/Resources/Private/Language/locallang_mod.xlf:organization',
        'extensionName' => 'Academics',
        'controllerActions' => [
            \RootBa\Academics\Controller\Backend\OrganizationController::class => [
                'list', 'edit', 'update'
            ],
        ],
    ],
    'academics_course' => [
        'parent' => 'academic',
        'access' => 'user',
        'workspaces' => '*',
        'path' => '/module/academic/academics-course',
        'labels' => 'LLL:EXT:academics/Resources/Private/Language/locallang_mod.xlf:course',
        'extensionName' => 'Academics',
        'controllerActions' => [
            \RootBa\Academics\Controller\Backend\CourseController::class => [
                'list', 'edit', 'update'
            ],
        ],
    ],
    'academics_program' => [
        'parent' => 'academic',
        'access' => 'user',
        'workspaces' => '*',
        'path' => '/module/academic/academics-program',
        'labels' => 'LLL:EXT:academics/Resources/Private/Language/locallang_mod.xlf:program',
        'extensionName' => 'Academics',
        'controllerActions' => [
            \RootBa\Academics\Controller\Backend\StudyProgramController::class => [
                'list', 'edit', 'update'
            ],
        ],
    ],
    'academics_project' => [
        'parent' => 'academic',
        'access' => 'user',
        'workspaces' => '*',
        'path' => '/module/academic/academics-project',
        'labels' => 'LLL:EXT:academics/Resources/Private/Language/locallang_mod.xlf:project',
        'extensionName' => 'Academics',
        'controllerActions' => [
            \RootBa\Academics\Controller\Backend\ProjectController::class => [
                'list', 'edit', 'update'
            ],
        ],
    ],
];
