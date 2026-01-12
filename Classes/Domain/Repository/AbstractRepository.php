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

namespace EtfUnsa\SparkAcademics\Domain\Repository;

use EtfUnsa\SparkAcademics\Domain\Model\Dto\AbstractDemand;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * Abstract base repository for academic entities
 * 
 * Provides common functionality for all entity repositories including:
 * - Storaging page ignore (global queries)
 * - Language-aware queries
 * - Generic findByDemand with AbstractDemand support
 * - Search across configurable fields
 * 
 * @author Ernedin Zajko <ezajko@root.ba>
 */
abstract class AbstractRepository extends Repository
{
    /**
     * Fields to search in for text queries
     * Override in child classes to customize
     */
    protected array $searchFields = ['title'];

    /**
     * Filter property mappings for special cases
     * Maps demand property names to actual model property paths
     * Override in child classes for entity-specific mappings
     */
    protected array $filterMappings = [];

    /**
     * Initialize repository with default query settings
     */
    public function initializeObject(): void
    {
        /** @var Typo3QuerySettings $querySettings */
        $querySettings = GeneralUtility::makeInstance(Typo3QuerySettings::class);
        
        // Default settings for academic repositories:
        // We set respectStoragePage to false so that items can be fetched globally 
        // without requiring a specific storagePid to be set in TypoScript.
        $querySettings->setRespectStoragePage(false);
        
        // Language settings: respect the current language to avoid duplicate records
        // Fallback behavior is handled by site configuration
        $querySettings->setRespectSysLanguage(true);
        
        $this->setDefaultQuerySettings($querySettings);
    }

    /**
     * Find entities matching the demand criteria
     * 
     * This method dynamically builds query constraints based on the demand's
     * filter properties. It supports equals, contains (for M:N), and like (for search).
     * 
     * @param AbstractDemand $demand The demand object with filter criteria
     * @return QueryResultInterface Matching entities
     */
    public function findByDemand(AbstractDemand $demand): QueryResultInterface
    {
        $query = $this->createQuery();
        $constraints = [];

        // Apply filter properties from demand
        if ($demand->hasFilters()) {
            $filters = $demand->getFilterProperties();
            foreach ($filters as $property => $value) {
                $constraint = $this->buildConstraint($query, $property, $value);
                if ($constraint !== null) {
                    $constraints[] = $constraint;
                }
            }
        }

        // Apply search across configured fields
        if ($demand->hasSearch()) {
            $searchConstraint = $this->buildSearchConstraint($query, $demand->getSearch());
            if ($searchConstraint !== null) {
                $constraints[] = $searchConstraint;
            }
        }

        // Combine all constraints with AND
        if (!empty($constraints)) {
            $query->matching($query->logicalAnd(...$constraints));
        }

        // Apply orderings
        if (!empty($demand->getOrderings())) {
            $orderings = [];
            foreach ($demand->getOrderings() as $property => $direction) {
                $orderings[$property] = strtoupper($direction) === 'DESC' 
                    ? QueryInterface::ORDER_DESCENDING 
                    : QueryInterface::ORDER_ASCENDING;
            }
            $query->setOrderings($orderings);
        }

        // Apply limit and offset
        if ($demand->getLimit() > 0) {
            $query->setLimit($demand->getLimit());
        }
        if ($demand->getOffset() > 0) {
            $query->setOffset($demand->getOffset());
        }

        return $query->execute();
    }

    /**
     * Build a query constraint for a single filter property
     * 
     * Handles different value types and operators:
     * - 0 or empty: skip (no filter)
     * - DateTime: special date handling
     * - Property with 'contains' mapping: M:N relation
     * - Default: equals comparison
     * 
     * @param QueryInterface $query The query object
     * @param string $property Property name (may include mapping)
     * @param mixed $value Filter value
     * @return object|null Query constraint or null to skip
     */
    protected function buildConstraint(QueryInterface $query, string $property, $value): ?object
    {
        // Skip empty values (0, '', null)
        if ($value === null || $value === '' || $value === 0) {
            return null;
        }

        // Apply filter mappings if defined
        $actualProperty = $this->filterMappings[$property] ?? $property;

        // Handle special properties
        if ($property === 'dateFrom' && $value instanceof \DateTime) {
            // Project must end AFTER the filter start date
            return $query->greaterThanOrEqual('endDate', $value->getTimestamp());
        }
        if ($property === 'dateTo' && $value instanceof \DateTime) {
            // Project must start BEFORE the filter end date
            return $query->lessThanOrEqual('startDate', $value->getTimestamp());
        }

        // Check if property indicates M:N relation (plural name or mapped to contains)
        if ($this->isContainsProperty($actualProperty)) {
            return $query->contains($actualProperty, $value);
        }

        // Default: equals comparison
        return $query->equals($actualProperty, $value);
    }

    /**
     * Build search constraint across all searchFields
     * 
     * @param QueryInterface $query The query object
     * @param string $search Search term
     * @return object|null OR constraint across all search fields
     */
    protected function buildSearchConstraint(QueryInterface $query, string $search): ?object
    {
        if (empty($this->searchFields)) {
            return null;
        }

        $searchConstraints = [];
        foreach ($this->searchFields as $field) {
            $searchConstraints[] = $query->like($field, '%' . $search . '%');
        }

        return count($searchConstraints) > 1 
            ? $query->logicalOr(...$searchConstraints)
            : $searchConstraints[0];
    }

    /**
     * Check if a property should use 'contains' (M:N) instead of 'equals'
     * 
     * @param string $property Property name
     * @return bool True if property is M:N relation
     */
    protected function isContainsProperty(string $property): bool
    {
        // Properties ending with 's' (plural) or containing '.' (nested) often indicate M:N
        // Override in child classes for specific behavior
        $containsProperties = [
            'scientificFields',
            'organizations',
            'additionalOrganizations',
            'studyTypes',
            'modesOfStudy',
            'languages',
            'beUsers',
        ];

        return in_array($property, $containsProperties, true);
    }

    /**
     * Get the search fields for this repository
     * 
     * @return array<string> Field names to search in
     */
    public function getSearchFields(): array
    {
        return $this->searchFields;
    }
}
