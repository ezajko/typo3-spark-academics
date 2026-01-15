<?php

/*
 * This file is part of the "Spark Academics" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) Ernedin Zajko <ezajko@root.ba>
 */

declare(strict_types=1);

defined('TYPO3') || die();

/**
 * Spark Academics Site Configuration
 * 
 * Naming Convention:
 * - sparkAcademic_{entityType}_detail_pid   - Detail page PID
 * - sparkAcademic_{entityType}_storage_pid  - Storage folder PID
 * - sparkAcademic_{entityType}_permission_groups - Editor group UIDs
 * - sparkAcademic_users_home_*              - User home folder settings
 */
(static function (): void {
    $ll = 'LLL:EXT:academics/Resources/Private/Language/locallang_db.xlf:site.configuration.';
    
    // ==========================================================================
    // 1. Entity Detail/Storage PIDs
    // ==========================================================================
    $pidConfig = [
        'type' => 'number',
        'default' => 0,
    ];
    
    // Detail PIDs
    $detailPidFields = [
        'academics_person_detail_pid',
        'academics_organization_detail_pid',
        'academics_course_detail_pid',
        'academics_studyprogram_detail_pid',
        'academics_project_detail_pid',
    ];

    // Storage PIDs
    $storagePidFields = [
        'academics_person_storage_pid',
        'academics_organization_storage_pid',
        'academics_course_storage_pid',
        'academics_studyprogram_storage_pid',
        'academics_project_storage_pid',
    ];

    foreach (array_merge($detailPidFields, $storagePidFields) as $field) {
        $GLOBALS['SiteConfiguration']['site']['columns'][$field] = [
            'label' => $ll . $field,
            'config' => $pidConfig,
        ];
        $GLOBALS['SiteConfiguration']['site_language']['columns'][$field] = [
            'label' => $ll . $field,
            'config' => $pidConfig,
        ];
    }
    
    $GLOBALS['SiteConfiguration']['site']['palettes']['academics_detail_pids'] = [
        'label' => $ll . 'academics_detail_pids',
        'showitem' => implode(', ', $detailPidFields),
    ];
    $GLOBALS['SiteConfiguration']['site']['palettes']['academics_storage_pids'] = [
        'label' => $ll . 'academics_storage_pids',
        'showitem' => implode(', ', $storagePidFields),
    ];

    $GLOBALS['SiteConfiguration']['site_language']['palettes']['academics_detail_pids'] = [
        'label' => $ll . 'academics_detail_pids',
        'showitem' => implode(', ', $detailPidFields),
    ];
    $GLOBALS['SiteConfiguration']['site_language']['palettes']['academics_storage_pids'] = [
        'label' => $ll . 'academics_storage_pids',
        'showitem' => implode(', ', $storagePidFields),
    ];
    
    // ==========================================================================
    // 2. Entity Permission Groups
    // ==========================================================================
    $permissionFields = [
        'academics_person_permission_groups',
        'academics_organization_permission_groups',
        'academics_course_permission_groups',
        'academics_project_permission_groups',
    ];
    
    foreach ($permissionFields as $field) {
        $GLOBALS['SiteConfiguration']['site']['columns'][$field] = [
            'label' => $ll . $field,
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'placeholder' => '1,2,3',
            ],
        ];
    }
    
    $GLOBALS['SiteConfiguration']['site']['palettes']['academics_permissions'] = [
        'label' => $ll . 'academics_permissions',
        'showitem' => implode(', ', $permissionFields),
    ];
    
    // ==========================================================================
    // 3. User Home Configuration
    // ==========================================================================
    $userHomeFields = [
        'academics_users_home_storage_uid',
        'academics_users_home_base_path',
    ];
    
    foreach ($userHomeFields as $field) {
        $GLOBALS['SiteConfiguration']['site']['columns'][$field] = [
            'label' => $ll . $field,
            'config' => ($field === 'academics_users_home_storage_uid') ? [
                'type' => 'number',
                'default' => 1,
            ] : [
                'type' => 'input',
                'eval' => 'trim',
                'default' => 'user_homes/',
                'placeholder' => 'user_homes/',
            ],
        ];
    }
    
    $GLOBALS['SiteConfiguration']['site']['palettes']['academics_users_home'] = [
        'label' => $ll . 'academics_users_home',
        'showitem' => implode(', ', $userHomeFields),
    ];
    
    // ==========================================================================
    // Add "Spark Academics" Tab with all palettes
    // ==========================================================================
    $sparkAcademicsTab = ', --div--;' . $ll . 'academics_pids_tab'
        . ', --palette--;;academics_detail_pids'
        . ', --palette--;;academics_storage_pids'
        . ', --palette--;;academics_permissions'
        . ', --palette--;;academics_users_home';
    
    $GLOBALS['SiteConfiguration']['site']['types']['0']['showitem'] .= $sparkAcademicsTab;
    
    // Add localized PIDs to site_language
    $GLOBALS['SiteConfiguration']['site_language']['types']['1']['showitem'] .= 
        ', --div--;' . $ll . 'academics_pids_tab'
        . ', --palette--;;academics_detail_pids'
        . ', --palette--;;academics_storage_pids';

})();
