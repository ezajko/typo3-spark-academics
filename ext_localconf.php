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
    'Academics',
    'Pi1',
    [
        \RootBa\Academics\Controller\AcademicController::class => 'list, show, listAll, listSelected, listFiltered',
    ],
    // non-cacheable actions
    [
        \RootBa\Academics\Controller\AcademicController::class => 'list, listAll, listSelected, listFiltered',
    ]
);



\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Academics',
    'Pi2',
    [
        \RootBa\Academics\Controller\AcademicController::class => 'show',
    ],
    [
        \RootBa\Academics\Controller\AcademicController::class => 'show',
    ]
);

// Configure excluded parameters for cHash calculation to allow GET filters without 404
// Exclude entire plugin namespace and all filter subparameters
$GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'tx_sparkacademics_pi1[filter]';
$GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'tx_sparkacademics_pi1[filter][search]';
$GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'tx_sparkacademics_pi1[filter][primaryOrganization]';
$GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'tx_sparkacademics_pi1[filter][additionalOrganization]';
$GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'tx_sparkacademics_pi1[filter][academicRank]';
$GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'tx_sparkacademics_pi1[filter][academicTitle]';
$GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'tx_sparkacademics_pi1[filter][organization]';
$GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'tx_sparkacademics_pi1[filter][projectStatus]';
$GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'tx_sparkacademics_pi1[filter][projectType]';
$GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'tx_sparkacademics_pi1[filter][studyCycle]';
$GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'tx_sparkacademics_pi1[__referrer]';
$GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'tx_sparkacademics_pi1[__trustedProperties]';
$GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'tx_sparkacademics_pi1[currentPage]';

// Disable 404 on cHash error to allow Filter GET requests with unmapped Route arguments
$GLOBALS['TYPO3_CONF_VARS']['FE']['pageNotFoundOnCHashError'] = false;

