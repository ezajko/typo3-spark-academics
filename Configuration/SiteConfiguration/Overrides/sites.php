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

})();
