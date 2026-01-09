<?php

declare(strict_types=1);

defined('TYPO3') || die();

(static function (): void {
    $ll = 'LLL:EXT:spark_academics/Resources/Private/Language/locallang_db.xlf:';

    $academicPidConfig = [
        'type' => 'link',
        'allowedTypes' => ['page'],
        'default' => '',
    ];

    $academicPidFields = [
        'academic_pid_person_detail',
        'academic_pid_dept_detail',
        'academic_pid_lab_detail',
        'academic_pid_group_detail',
        'academic_pid_chair_detail',
        'academic_pid_course_detail',
        'academic_pid_program_detail',
        'academic_pid_project_detail',
        'academic_pid_course_storage',
        'academic_pid_project_storage',
        'academic_pid_person_storage',
        'academic_pid_program_storage',
    ];

    foreach ($academicPidFields as $field) {
        $GLOBALS['SiteConfiguration']['site']['columns'][$field] = [
            'label' => $ll . 'site.configuration.' . $field,
            'config' => $academicPidConfig,
        ];
        // Also register for site_language
        $GLOBALS['SiteConfiguration']['site_language']['columns'][$field] = [
            'label' => $ll . 'site.configuration.' . $field,
            'config' => $academicPidConfig,
        ];
    }

    $GLOBALS['SiteConfiguration']['site']['palettes']['sparkAcademicPids'] = [
        'showitem' => implode(', ', $academicPidFields),
    ];
    $GLOBALS['SiteConfiguration']['site_language']['palettes']['sparkAcademicPidsLocalized'] = [
        'label' => $ll . 'site.configuration.academicPids',
        'showitem' => implode(', ', $academicPidFields),
    ];

    // Add to existing PIDS tab using palette
    $GLOBALS['SiteConfiguration']['site']['types']['0']['showitem'] = str_replace(
        '--palette--;;sparkPids',
        '--palette--;;sparkPids, --palette--;;sparkAcademicPids',
        $GLOBALS['SiteConfiguration']['site']['types']['0']['showitem']
    );

    $GLOBALS['SiteConfiguration']['site_language']['types']['1']['showitem'] = str_replace(
        '--palette--;;sparkPidsLocalized',
        '--palette--;;sparkPidsLocalized, --palette--;;sparkAcademicPidsLocalized',
        $GLOBALS['SiteConfiguration']['site_language']['types']['1']['showitem']
    );

    // Entity Permission Groups
    $permissionFields = [
        'spark_perm_project_groups' => 'Project',
        'spark_perm_person_groups' => 'Person',
        'spark_perm_org_groups' => 'Organizational Units (Dept, Chair, Lab, Group)',
        'spark_perm_study_groups' => 'Study (Course, StudyProgram)',
    ];

    $permissionPaletteItems = [];

    foreach ($permissionFields as $field => $label) {
        $GLOBALS['SiteConfiguration']['site']['columns'][$field] = [
            'label' => $label . ' Editor Groups (User Group UIDs)',
            'description' => 'Users in these groups can view ALL ' . $label . ' records.',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
            ],
        ];
        $permissionPaletteItems[] = $field;
    }

    $GLOBALS['SiteConfiguration']['site']['palettes']['sparkPermissions'] = [
        'showitem' => implode(', ', $permissionPaletteItems),
    ];

    // Add to existing PIDS tab using palette
    $GLOBALS['SiteConfiguration']['site']['types']['0']['showitem'] .= ', --div--;Spark Permissions, --palette--;;sparkPermissions';

    // User Home Configuration
    $userHomeFields = [
        'spark_home_storage_uid' => [
            'label' => 'User Home: Storage UID (sys_file_storage)',
            'description' => 'UID of the File Storage where user folders will be created (Default: 1 used for fileadmin)',
            'config' => [
                'type' => 'number',
                'default' => 1,
            ],
        ],
        'spark_home_path' => [
            'label' => 'User Home: Base Path',
            'description' => 'Path relative to the storage root (e.g. user_homes/). Must end with /',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'default' => 'user_homes/',
            ]
        ]
    ];

    $userHomePaletteItems = [];
    foreach ($userHomeFields as $field => $config) {
        $GLOBALS['SiteConfiguration']['site']['columns'][$field] = $config;
        $userHomePaletteItems[] = $field;
    }

    $GLOBALS['SiteConfiguration']['site']['palettes']['sparkUserHomes'] = [
        'showitem' => implode(', ', $userHomePaletteItems),
    ];

    $GLOBALS['SiteConfiguration']['site']['types']['0']['showitem'] .= ', --div--;User Homes, --palette--;;sparkUserHomes';

})();
