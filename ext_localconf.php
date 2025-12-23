<?php

defined('TYPO3') or die();

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'SparkAcademics',
    'Pi1',
    [
        \EtfUnsa\SparkAcademics\Controller\PersonController::class => 'list, show',
    ],
    // non-cacheable actions
    [
        \EtfUnsa\SparkAcademics\Controller\PersonController::class => 'list',
    ]
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'SparkAcademics',
    'Pi2',
    [
        \EtfUnsa\SparkAcademics\Controller\PersonController::class => 'show',
    ],
    // non-cacheable actions
    [
    ]
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'SparkAcademics',
    'Pi3',
    [
        \EtfUnsa\SparkAcademics\Controller\PersonController::class => 'listSelected',
    ],
    // non-cacheable actions
    [
        \EtfUnsa\SparkAcademics\Controller\PersonController::class => 'listSelected',
    ]
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'SparkAcademics',
    'Pi4',
    [
        \EtfUnsa\SparkAcademics\Controller\PersonController::class => 'listAll',
    ],
    // non-cacheable actions
    [
        \EtfUnsa\SparkAcademics\Controller\PersonController::class => 'listAll',
    ]
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'SparkAcademics',
    'Pi5',
    [
        \EtfUnsa\SparkAcademics\Controller\PersonController::class => 'listFiltered',
    ],
    // non-cacheable actions
    [
        \EtfUnsa\SparkAcademics\Controller\PersonController::class => 'listFiltered',
    ]
);
