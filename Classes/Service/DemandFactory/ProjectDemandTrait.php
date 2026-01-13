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

namespace RootBa\Academics\Service\DemandFactory;

use RootBa\Academics\Domain\Model\Dto\ProjectDemand;

/**
 * Trait for creating ProjectDemand objects from settings
 * 
 * @author Ernedin Zajko <ezajko@root.ba>
 */
trait ProjectDemandTrait
{
    /**
     * Create a ProjectDemand from settings and request filter
     * 
     * @param array $settings Plugin settings from FlexForm/TypoScript
     * @param array $requestFilter Filter values from frontend request
     * @return ProjectDemand
     */
    public function createProjectDemand(array $settings, array $requestFilter = []): ProjectDemand
    {
        $demand = new ProjectDemand();
        $this->applyCommonSettings($demand, $settings, $requestFilter);
        
        // Merge settings filter with request filter (request takes priority)
        $filter = array_merge($settings['filter'] ?? [], $requestFilter);
        
        // Project Status filter
        if (!empty($filter['projectStatus'])) {
            $demand->setProjectStatus((int)$filter['projectStatus']);
        }
        
        // Project Type filter
        if (!empty($filter['projectType'])) {
            $demand->setProjectType((int)$filter['projectType']);
        }
        
        // Funding Program filter
        if (!empty($filter['fundingProgram'])) {
            $demand->setFundingProgram((int)$filter['fundingProgram']);
        }
        
        // Scientific Field filter
        if (!empty($filter['scientificField'])) {
            $demand->setScientificField((int)$filter['scientificField']);
        }
        
        // Organization filter
        if (!empty($filter['organization'])) {
            $demand->setOrganization((int)$filter['organization']);
        }
        
        // Date range filters
        if (!empty($filter['dateFrom'])) {
            $demand->setDateFrom(new \DateTime($filter['dateFrom']));
        }
        if (!empty($filter['dateTo'])) {
            $demand->setDateTo(new \DateTime($filter['dateTo']));
        }
        
        // Backend User filter (for permission-based filtering)
        if (!empty($filter['backendUser'])) {
            $demand->setBackendUser((int)$filter['backendUser']);
        }
        
        // Selected Projects (explicit selection from FlexForm group field)
        if (!empty($settings['select']['project'])) {
            $selectedUids = $this->parseGroupFieldUids($settings['select']['project']);
            $demand->setSelectedUids($selectedUids);
        }
        
        return $demand;
    }
}
