<?php

return [
    'academic' => [
        'labels' => 'LLL:EXT:spark_academics/Resources/Private/Language/locallang_mod.xlf:academic_category',
        'iconIdentifier' => 'content-user',
        'position' => ['before' => 'site'],
        'access' => 'user,group',
    ],
    'spark_academics_person' => [
        'parent' => 'academic',
        'position' => ['top'],
        'access' => 'user,group',
        'workspaces' => 'live',
        'path' => '/module/academic/spark-academics-person',
        'labels' => 'LLL:EXT:spark_academics/Resources/Private/Language/locallang_mod.xlf',
        'extensionName' => 'SparkAcademics',
        'controllerActions' => [
            \EtfUnsa\SparkAcademics\Controller\Backend\PersonController::class => [
                'list', 'edit', 'update'
            ],
        ],
    ],
];
