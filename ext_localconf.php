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
    'Pi2',
    [
        \EtfUnsa\SparkAcademics\Controller\AcademicController::class => 'show',
    ],
    [
        \EtfUnsa\SparkAcademics\Controller\AcademicController::class => 'show',
    ]
);

// Configure excluded parameters for cHash calculation to allow GET filters without 404
$GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'tx_sparkacademics_pi1';
$GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'tx_sparkacademics_pi1[__referrer]';
$GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'tx_sparkacademics_pi1[__trustedProperties]';

// Disable 404 on cHash error to allow Filter GET requests with unmapped Route arguments
$GLOBALS['TYPO3_CONF_VARS']['FE']['pageNotFoundOnCHashError'] = false;
