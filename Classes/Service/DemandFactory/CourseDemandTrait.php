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

namespace EtfUnsa\SparkAcademics\Service\DemandFactory;

use EtfUnsa\SparkAcademics\Domain\Model\Dto\CourseDemand;

/**
 * Trait for creating CourseDemand objects from settings
 * 
 * @author Ernedin Zajko <ezajko@root.ba>
 */
trait CourseDemandTrait
{
    /**
     * Create a CourseDemand from settings and request filter
     * 
     * @param array $settings Plugin settings from FlexForm/TypoScript
     * @param array $requestFilter Filter values from frontend request
     * @return CourseDemand
     */
    public function createCourseDemand(array $settings, array $requestFilter = []): CourseDemand
    {
        $demand = new CourseDemand();
        $this->applyCommonSettings($demand, $settings, $requestFilter);
        
        // Merge settings filter with request filter (request takes priority)
        $filter = array_merge($settings['filter'] ?? [], $requestFilter);
        
        // Organization filter
        if (!empty($filter['organization'])) {
            $demand->setOrganization((int)$filter['organization']);
        }
        
        // Study Cycle filter (snake_case from frontend, camelCase from settings)
        if (!empty($filter['studyCycle']) || !empty($filter['study_cycle'])) {
            $demand->setStudyCycle((int)($filter['studyCycle'] ?? $filter['study_cycle']));
        }
        
        // Course Category filter
        if (!empty($filter['courseCategory']) || !empty($filter['course_category'])) {
            $demand->setCourseCategory((int)($filter['courseCategory'] ?? $filter['course_category']));
        }
        
        // Scientific Field filter
        if (!empty($filter['scientificField']) || !empty($filter['scientific_field'])) {
            $demand->setScientificField((int)($filter['scientificField'] ?? $filter['scientific_field']));
        }
        
        // Selected Courses (explicit selection from FlexForm group field)
        if (!empty($settings['select']['course'])) {
            $selectedUids = $this->parseGroupFieldUids($settings['select']['course']);
            $demand->setSelectedUids($selectedUids);
        }
        
        return $demand;
    }
}
