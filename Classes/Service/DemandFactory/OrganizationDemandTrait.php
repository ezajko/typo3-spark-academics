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

use RootBa\Academics\Domain\Model\Dto\OrganizationDemand;

/**
 * Trait for creating OrganizationDemand objects from settings
 * 
 * @author Ernedin Zajko <ezajko@root.ba>
 */
trait OrganizationDemandTrait
{
    /**
     * Create an OrganizationDemand from settings and request filter
     * 
     * @param array $settings Plugin settings from FlexForm/TypoScript
     * @param array $requestFilter Filter values from frontend request
     * @return OrganizationDemand
     */
    public function createOrganizationDemand(array $settings, array $requestFilter = []): OrganizationDemand
    {
        $demand = new OrganizationDemand();
        $this->applyCommonSettings($demand, $settings, $requestFilter);
        
        // Merge settings filter with request filter (request takes priority)
        $filter = array_merge($settings['filter'] ?? [], $requestFilter);
        
        // Type filter
        if (!empty($filter['organizationType']) || !empty($filter['type'])) {
            $demand->setType((int)($filter['organizationType'] ?? $filter['type']));
        }
        
        // Parent filter
        if (!empty($filter['parentOrganization']) || !empty($filter['parent'])) {
            $demand->setParent((int)($filter['parentOrganization'] ?? $filter['parent']));
        }
        
        // Selected Organizations (explicit selection from FlexForm group field)
        if (!empty($settings['select']['organization'])) {
            $selectedUids = $this->parseGroupFieldUids($settings['select']['organization']);
            $demand->setSelectedUids($selectedUids);
        }
        
        return $demand;
    }
}
