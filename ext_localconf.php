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
        \EtfUnsa\SparkAcademics\Controller\PersonController::class => '',
    ]
);
