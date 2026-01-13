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
    $ll = 'LLL:EXT:spark_academics/Resources/Private/Language/locallang_db.xlf:site.configuration.';
    
    // ==========================================================================
    // 1. Entity Detail/Storage PIDs
    // ==========================================================================
    $pidConfig = [
        'type' => 'link',
        'allowedTypes' => ['page'],
        'default' => '',
    ];
    
    // Detail PIDs
    $detailPidFields = [
        'sparkAcademic_person_detail_pid',
        'sparkAcademic_organization_detail_pid',
        'sparkAcademic_course_detail_pid',
        'sparkAcademic_studyprogram_detail_pid',
        'sparkAcademic_project_detail_pid',
    ];

    // Storage PIDs
    $storagePidFields = [
        'sparkAcademic_person_storage_pid',
        'sparkAcademic_organization_storage_pid',
        'sparkAcademic_course_storage_pid',
        'sparkAcademic_studyprogram_storage_pid',
        'sparkAcademic_project_storage_pid',
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
    
    $GLOBALS['SiteConfiguration']['site']['palettes']['sparkAcademic_detail_pids'] = [
        'label' => $ll . 'sparkAcademic_detail_pids',
        'showitem' => implode(', ', $detailPidFields),
    ];
    $GLOBALS['SiteConfiguration']['site']['palettes']['sparkAcademic_storage_pids'] = [
        'label' => $ll . 'sparkAcademic_storage_pids',
        'showitem' => implode(', ', $storagePidFields),
    ];

    $GLOBALS['SiteConfiguration']['site_language']['palettes']['sparkAcademic_detail_pids'] = [
        'label' => $ll . 'sparkAcademic_detail_pids',
        'showitem' => implode(', ', $detailPidFields),
    ];
    $GLOBALS['SiteConfiguration']['site_language']['palettes']['sparkAcademic_storage_pids'] = [
        'label' => $ll . 'sparkAcademic_storage_pids',
        'showitem' => implode(', ', $storagePidFields),
    ];
    
    // ==========================================================================
    // 2. Entity Permission Groups
    // ==========================================================================
    $permissionFields = [
        'sparkAcademic_person_permission_groups',
        'sparkAcademic_organization_permission_groups',
        'sparkAcademic_course_permission_groups',
        'sparkAcademic_project_permission_groups',
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
    
    $GLOBALS['SiteConfiguration']['site']['palettes']['sparkAcademic_permissions'] = [
        'label' => $ll . 'sparkAcademic_permissions',
        'showitem' => implode(', ', $permissionFields),
    ];
    
    // ==========================================================================
    // 3. User Home Configuration
    // ==========================================================================
    $userHomeFields = [
        'sparkAcademic_users_home_storage_uid',
        'sparkAcademic_users_home_base_path',
    ];
    
    foreach ($userHomeFields as $field) {
        $GLOBALS['SiteConfiguration']['site']['columns'][$field] = [
            'label' => $ll . $field,
            'config' => ($field === 'sparkAcademic_users_home_storage_uid') ? [
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
    
    $GLOBALS['SiteConfiguration']['site']['palettes']['sparkAcademic_users_home'] = [
        'label' => $ll . 'sparkAcademic_users_home',
        'showitem' => implode(', ', $userHomeFields),
    ];
    
    // ==========================================================================
    // Add "Spark Academics" Tab with all palettes
    // ==========================================================================
    $sparkAcademicsTab = ', --div--;' . $ll . 'sparkAcademic_pids_tab'
        . ', --palette--;;sparkAcademic_detail_pids'
        . ', --palette--;;sparkAcademic_storage_pids'
        . ', --palette--;;sparkAcademic_permissions'
        . ', --palette--;;sparkAcademic_users_home';
    
    $GLOBALS['SiteConfiguration']['site']['types']['0']['showitem'] .= $sparkAcademicsTab;
    
    // Add localized PIDs to site_language
    $GLOBALS['SiteConfiguration']['site_language']['types']['1']['showitem'] .= 
        ', --div--;' . $ll . 'sparkAcademic_pids_tab'
        . ', --palette--;;sparkAcademic_detail_pids'
        . ', --palette--;;sparkAcademic_storage_pids';

})();
