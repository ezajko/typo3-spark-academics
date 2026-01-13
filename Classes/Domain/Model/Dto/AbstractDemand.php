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

namespace RootBa\Academics\Domain\Model\Dto;

/**
 * Abstract base class for entity-specific Demand DTOs
 * 
 * Provides common filtering, searching, and pagination properties
 * that all entity demands share.
 * 
 * @author Ernedin Zajko <ezajko@root.ba>
 */
abstract class AbstractDemand
{
    /**
     * Search term for text-based filtering
     */
    protected string $search = '';

    /**
     * Ordering array (property => direction)
     * Example: ['lastName' => 'ASC', 'firstName' => 'ASC']
     */
    protected array $orderings = [];

    /**
     * Maximum number of results (0 = unlimited)
     */
    protected int $limit = 0;

    /**
     * Offset for pagination
     */
    protected int $offset = 0;

    /**
     * Selected UIDs for explicit item selection (from FlexForm select fields)
     * @var int[]
     */
    protected array $selectedUids = [];

    // =========================================================================
    // Search
    // =========================================================================

    public function getSearch(): string
    {
        return $this->search;
    }

    public function setSearch(string $search): self
    {
        $this->search = $search;
        return $this;
    }

    public function hasSearch(): bool
    {
        return !empty($this->search);
    }

    // =========================================================================
    // Orderings
    // =========================================================================

    public function getOrderings(): array
    {
        return $this->orderings;
    }

    public function setOrderings(array $orderings): self
    {
        $this->orderings = $orderings;
        return $this;
    }

    public function addOrdering(string $property, string $direction = 'ASC'): self
    {
        $this->orderings[$property] = strtoupper($direction);
        return $this;
    }

    // =========================================================================
    // Pagination
    // =========================================================================

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function setLimit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function setOffset(int $offset): self
    {
        $this->offset = $offset;
        return $this;
    }

    // =========================================================================
    // Selected UIDs (explicit item selection)
    // =========================================================================

    /**
     * @return int[]
     */
    public function getSelectedUids(): array
    {
        return $this->selectedUids;
    }

    /**
     * @param int[] $selectedUids
     */
    public function setSelectedUids(array $selectedUids): self
    {
        $this->selectedUids = array_filter(array_map('intval', $selectedUids));
        return $this;
    }

    public function hasSelectedUids(): bool
    {
        return !empty($this->selectedUids);
    }

    // =========================================================================
    // Abstract Methods
    // =========================================================================

    /**
     * Check if this demand has any active filters (excluding search)
     * Each entity demand should implement this based on its specific properties
     */
    abstract public function hasFilters(): bool;

    /**
     * Get all filter properties as associative array
     * Used by AbstractRepository.findByDemand() for dynamic constraint building
     * 
     * @return array<string, mixed> Property name => value pairs
     */
    abstract public function getFilterProperties(): array;
}
