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
    'Pi2',
    [
        \EtfUnsa\SparkAcademics\Controller\AcademicController::class => 'show',
    ],
    [
    ]
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'SparkAcademics',
    'Pi3',
    [
        \EtfUnsa\SparkAcademics\Controller\AcademicController::class => 'listSelected',
    ],
    [
        \EtfUnsa\SparkAcademics\Controller\AcademicController::class => 'listSelected',
    ]
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'SparkAcademics',
    'Pi4',
    [
        \EtfUnsa\SparkAcademics\Controller\AcademicController::class => 'listAll',
    ],
    [
        \EtfUnsa\SparkAcademics\Controller\AcademicController::class => 'listAll',
    ]
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'SparkAcademics',
    'Pi5',
    [
        \EtfUnsa\SparkAcademics\Controller\AcademicController::class => 'listFiltered',
    ],
    [
        \EtfUnsa\SparkAcademics\Controller\AcademicController::class => 'listFiltered',
    ]
);
// Academic Entities Plugins
$academicEntities = [
    'Dept' => 'department',
    'Lab' => 'lab',
    'Group' => 'group',
    'Chair' => 'chair',
    'Course' => 'course',
    'Program' => 'program',
    'Project' => 'project',
    'Person' => 'person'
];

foreach ($academicEntities as $piPrefix => $entityType) {
    // List Plugin
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'SparkAcademics',
        $piPrefix . 'List',
        [ \EtfUnsa\SparkAcademics\Controller\AcademicController::class => 'list' ],
        [ \EtfUnsa\SparkAcademics\Controller\AcademicController::class => 'list' ]
    );

    // Show Plugin
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'SparkAcademics',
        $piPrefix . 'Show',
        [ \EtfUnsa\SparkAcademics\Controller\AcademicController::class => 'show' ],
        [ \EtfUnsa\SparkAcademics\Controller\AcademicController::class => 'show' ]
    );
}
