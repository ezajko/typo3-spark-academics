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

// Register List Plugin as dedicated CType (TYPO3 13+ standard)
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'Academics',
    'List',
    'Academic Profiles: List',
    'content-user',
    'academics', // Group identifier for New Content Element Wizard
    'Academic Profiles listing plugin for displaying persons, organizations, courses, projects, and study programs.'
);

// Get the generated CType for FlexForm registration
$pluginSignature = 'academics_list';
$GLOBALS['TCA']['tt_content']['types'][$pluginSignature]['showitem'] = '
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
        --palette--;;general,
        header;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:header_formlabel,
        pi_flexform,
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
        --palette--;;hidden,
        --palette--;;access,
';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:academics/Configuration/FlexForms/Academic/List.xml',
    $pluginSignature
);

// Register Show Plugin as dedicated CType
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'Academics',
    'Show',
    'Academic Profiles: Detail',
    'content-user',
    'academics',
    'Academic Profiles detail view for displaying single entity details.'
);

$pluginSignature = 'academics_show';
$GLOBALS['TCA']['tt_content']['types'][$pluginSignature]['showitem'] = '
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
        --palette--;;general,
        header;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:header_formlabel,
        pi_flexform,
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
        --palette--;;hidden,
        --palette--;;access,
';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:academics/Configuration/FlexForms/Academic/Detail.xml',
    $pluginSignature
);
