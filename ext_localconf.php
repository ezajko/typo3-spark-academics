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



\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptSetup(
    '@import "EXT:academics/Configuration/TypoScript/setup.typoscript"'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
    '@import "EXT:academics/Configuration/PageTS/ContentElement/Element/Academic.tsconfig"'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Academics',
    'List',
    [
        \RootBa\Academics\Controller\AcademicController::class => 'list, show, listAll, listSelected, listFiltered',
    ],
    // non-cacheable actions
    [
        \RootBa\Academics\Controller\AcademicController::class => 'list, listAll, listSelected, listFiltered',
    ],
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);



\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Academics',
    'Show',
    [
        \RootBa\Academics\Controller\AcademicController::class => 'show',
    ],
    [
        \RootBa\Academics\Controller\AcademicController::class => 'show',
    ],
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);

// Configure excluded parameters for cHash calculation to allow GET filters without 404
// Exclude entire plugin namespace and all filter subparameters
$GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'tx_academics_list[filter]';
$GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'tx_academics_list[filter][search]';
$GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'tx_academics_list[filter][primaryOrganization]';
$GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'tx_academics_list[filter][additionalOrganization]';
$GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'tx_academics_list[filter][academicRank]';
$GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'tx_academics_list[filter][academicTitle]';
$GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'tx_academics_list[filter][organization]';
$GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'tx_academics_list[filter][projectStatus]';
$GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'tx_academics_list[filter][projectType]';
$GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'tx_academics_list[filter][studyCycle]';
$GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'tx_academics_list[__referrer]';
$GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'tx_academics_list[__trustedProperties]';
$GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'tx_academics_list[currentPage]';

// Disable 404 on cHash error to allow Filter GET requests with unmapped Route arguments
$GLOBALS['TYPO3_CONF_VARS']['FE']['pageNotFoundOnCHashError'] = false;

