<?php

return [
    'spark_academics_person' => [
        'parent' => 'web',
        'position' => ['after' => 'web_info'],
        'access' => 'user,group',
        'workspaces' => 'live',
        'path' => '/module/web/spark-academics-person',
        'labels' => 'LLL:EXT:spark_academics/Resources/Private/Language/locallang_mod.xlf',
        'extensionName' => 'SparkAcademics',
        'controllerActions' => [
            \EtfUnsa\SparkAcademics\Controller\Backend\PersonController::class => [
                'list', 'edit', 'update'
            ],
        ],
    ],
];
