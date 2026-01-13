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

namespace RootBa\Academics\Service;

use RootBa\Academics\Domain\Model\Dto\AbstractDemand;
use RootBa\Academics\Service\DemandFactory\CourseDemandTrait;
use RootBa\Academics\Service\DemandFactory\OrganizationDemandTrait;
use RootBa\Academics\Service\DemandFactory\PersonDemandTrait;
use RootBa\Academics\Service\DemandFactory\ProjectDemandTrait;
use RootBa\Academics\Service\DemandFactory\StudyProgramDemandTrait;

/**
 * Factory for creating entity-specific Demand objects
 * 
 * This service creates properly configured Demand DTOs from plugin settings
 * and request parameters. It uses traits for entity-specific logic to keep
 * the code modular and easy to extend.
 * 
 * Usage:
 *   $demand = $demandFactory->createDemand('Person', $settings, $requestFilter);
 *   // or directly:
 *   $demand = $demandFactory->createPersonDemand($settings, $requestFilter);
 * 
 * To add a new entity type:
 *   1. Create EntityDemand DTO extending AbstractDemand
 *   2. Create EntityDemandTrait with createEntityDemand() method
 *   3. Add 'use EntityDemandTrait' here
 *   4. Add case to createDemand() match expression
 * 
 * @author Ernedin Zajko <ezajko@root.ba>
 */
class DemandFactory
{
    use PersonDemandTrait;
    use ProjectDemandTrait;
    use CourseDemandTrait;
    use StudyProgramDemandTrait;
    use OrganizationDemandTrait;

    /**
     * Create a demand object for the specified entity type
     * 
     * @param string $entityType Entity type (Person, Project, Course, StudyProgram, Organization)
     * @param array $settings Plugin settings from FlexForm/TypoScript
     * @param array $requestFilter Filter values from frontend request
     * @return AbstractDemand The appropriate demand object
     * @throws \InvalidArgumentException If entity type is unknown
     */
    public function createDemand(string $entityType, array $settings, array $requestFilter = []): AbstractDemand
    {
        return match ($entityType) {
            'Person' => $this->createPersonDemand($settings, $requestFilter),
            'Project' => $this->createProjectDemand($settings, $requestFilter),
            'Course' => $this->createCourseDemand($settings, $requestFilter),
            'StudyProgram' => $this->createStudyProgramDemand($settings, $requestFilter),
            'Organization' => $this->createOrganizationDemand($settings, $requestFilter),
            default => throw new \InvalidArgumentException(
                sprintf('Unknown entity type "%s". Supported types: Person, Project, Course, StudyProgram, Organization', $entityType)
            ),
        };
    }

    /**
     * Apply common settings to any demand object
     * 
     * This method is called by all entity-specific traits to set common properties.
     * 
     * @param AbstractDemand $demand The demand object to configure
     * @param array $settings Plugin settings
     * @param array $requestFilter Request filter values
     */
    protected function applyCommonSettings(AbstractDemand $demand, array $settings, array $requestFilter): void
    {
        // Search (request filter takes priority)
        if (!empty($requestFilter['search'])) {
            $demand->setSearch((string)$requestFilter['search']);
        }

        // Pagination from view settings
        $view = $settings['view'] ?? [];
        $pagination = $view['pagination'] ?? [];
        
        if (!empty($pagination['limit'])) {
            $demand->setLimit((int)$pagination['limit']);
        }

        // Orderings (if specified in settings)
        if (!empty($settings['orderings'])) {
            $demand->setOrderings($settings['orderings']);
        }
    }

    /**
     * Parse UIDs from FlexForm group field value
     * 
     * Group fields in TYPO3 store values as "table_uid,table_uid" or just "uid,uid"
     * This method extracts the UIDs from such strings.
     * 
     * @param string $value The group field value (e.g., "tx_spark_person_1,tx_spark_person_2" or "1,2")
     * @return int[] Array of UIDs
     */
    protected function parseGroupFieldUids(string $value): array
    {
        if (empty($value)) {
            return [];
        }
        
        $uids = [];
        $parts = explode(',', $value);
        
        foreach ($parts as $part) {
            $part = trim($part);
            // Extract UID - could be "table_uid" or just "uid"
            if (preg_match('/_(\d+)$/', $part, $matches)) {
                $uids[] = (int)$matches[1];
            } elseif (is_numeric($part)) {
                $uids[] = (int)$part;
            }
        }
        
        return array_filter($uids);
    }

    /**
     * Get list of supported entity types
     * 
     * @return array<string> List of entity type names
     */
    public function getSupportedEntityTypes(): array
    {
        return [
            'Person',
            'Project',
            'Course',
            'StudyProgram',
            'Organization',
        ];
    }

    /**
     * Check if an entity type is supported
     * 
     * @param string $entityType Entity type to check
     * @return bool True if supported
     */
    public function isEntityTypeSupported(string $entityType): bool
    {
        return in_array($entityType, $this->getSupportedEntityTypes(), true);
    }
}
