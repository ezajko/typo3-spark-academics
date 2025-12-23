<?php

defined('TYPO3') or die();

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'SparkAcademics',
    'Pi1',
    'Academic Profiles: Universal (Legacy)',
    'content-user'
);
$pluginSignature = 'sparkacademics_pi1';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    $pluginSignature,
    'FILE:EXT:spark_academics/Configuration/FlexForms/Person/Universal.xml'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'SparkAcademics',
    'Pi2',
    'Academic Profiles: Person Detail (Show)',
    'content-user'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'SparkAcademics',
    'Pi3',
    'Academic Profiles: Selected List',
    'content-user'
);
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['sparkacademics_pi3'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    'sparkacademics_pi3',
    'FILE:EXT:spark_academics/Configuration/FlexForms/Person/ListSelected.xml'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'SparkAcademics',
    'Pi4',
    'Academic Profiles: All List',
    'content-user'
);
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['sparkacademics_pi4'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    'sparkacademics_pi4',
    'FILE:EXT:spark_academics/Configuration/FlexForms/Person/ListAll.xml'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'SparkAcademics',
    'Pi5',
    'Academic Profiles: Filtered List',
    'content-user'
);
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['sparkacademics_pi5'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    'sparkacademics_pi5',
    'FILE:EXT:spark_academics/Configuration/FlexForms/Person/ListFiltered.xml'
);
// Academic Entities Plugins
$academicEntities = [
    'Dept' => 'Department',
    'Lab' => 'Research Lab',
    'Group' => 'Research Group',
    'Chair' => 'Chair',
    'Course' => 'Course',
    'Program' => 'Study Program',
    'Project' => 'Project',
    'Person' => 'Person'
];

foreach ($academicEntities as $piPrefix => $label) {
    // List Plugin
    $listName = $piPrefix . 'List';
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'SparkAcademics',
        $listName,
        'Academic: ' . $label . ' List',
        'content-user'
    );
    $listSignature = 'sparkacademics_' . strtolower($listName);
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$listSignature] = 'pi_flexform';
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
        $listSignature,
        'FILE:EXT:spark_academics/Configuration/FlexForms/Academic/List.xml'
    );

    // Show Plugin
    $showName = $piPrefix . 'Show';
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'SparkAcademics',
        $showName,
        'Academic: ' . $label . ' Detail',
        'content-user'
    );
}
