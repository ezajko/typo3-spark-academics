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

use EtfUnsa\SparkAcademics\Domain\Model\Dto\PersonDemand;

/**
 * Trait for creating PersonDemand objects from settings
 * 
 * @author Ernedin Zajko <ezajko@root.ba>
 */
trait PersonDemandTrait
{
    /**
     * Create a PersonDemand from settings and request filter
     * 
     * @param array $settings Plugin settings from FlexForm/TypoScript
     * @param array $requestFilter Filter values from frontend request
     * @return PersonDemand
     */
    public function createPersonDemand(array $settings, array $requestFilter = []): PersonDemand
    {
        $demand = new PersonDemand();
        $this->applyCommonSettings($demand, $settings, $requestFilter);
        
        // Merge settings filter with request filter (request takes priority)
        $filter = array_merge($settings['filter'] ?? [], $requestFilter);
        
        // Organization filter
        if (!empty($filter['organization'])) {
            $demand->setOrganization((int)$filter['organization']);
        }
        
        // Academic Rank filter
        if (!empty($filter['academicRank'])) {
            $demand->setAcademicRank((int)$filter['academicRank']);
        }
        
        // Academic Title filter
        if (!empty($filter['academicTitle'])) {
            $demand->setAcademicTitle((int)$filter['academicTitle']);
        }
        
        // Is Academic filter
        if (isset($filter['isAcademic']) && $filter['isAcademic'] !== '') {
            $demand->setIsAcademic((bool)$filter['isAcademic']);
        }
        
        // Staff Status filter
        if (!empty($filter['staffStatus'])) {
            $demand->setStaffStatus($filter['staffStatus']);
        }
        
        // Backend User filter (for permission-based filtering)
        if (!empty($filter['backendUser'])) {
            $demand->setBackendUser((int)$filter['backendUser']);
        }
        
        return $demand;
    }
}
