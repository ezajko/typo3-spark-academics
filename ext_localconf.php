<?php

defined('TYPO3') or die();

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'SparkAcademics',
    'Pi1',
    [
        \EtfUnsa\SparkAcademics\Controller\AcademicController::class => 'list, show, listAll, listSelected, listFiltered',
    ],
    // non-cacheable actions
    [
        \EtfUnsa\SparkAcademics\Controller\AcademicController::class => 'list, listAll, listSelected, listFiltered',
    ]
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'SparkAcademics',
    'Pi1',
    [
        \EtfUnsa\SparkAcademics\Controller\AcademicController::class => 'list, listAll, listSelected, listFiltered',
    ],
    // non-cacheable actions
    [
        \EtfUnsa\SparkAcademics\Controller\AcademicController::class => 'list, listAll, listSelected, listFiltered',
    ]
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'SparkAcademics',
    'Pi2',
    [
        \EtfUnsa\SparkAcademics\Controller\AcademicController::class => 'show',
    ],
    [
        \EtfUnsa\SparkAcademics\Controller\AcademicController::class => 'show',
    ]
);
