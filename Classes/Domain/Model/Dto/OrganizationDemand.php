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
 * DTO for Organization filtering criteria
 * 
 * Supports filtering by organization type and parent organization.
 * Used by OrganizationRepository and backend/frontend controllers.
 * 
 * @author Ernedin Zajko <ezajko@root.ba>
 */
class OrganizationDemand extends AbstractDemand
{
    /**
     * Filter by organization type
     */
    protected int $type = 0;

    /**
     * Filter by parent organization
     */
    protected int $parent = 0;

    // =========================================================================
    // Type
    // =========================================================================

    public function getType(): int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;
        return $this;
    }

    // =========================================================================
    // Parent
    // =========================================================================

    public function getParent(): int
    {
        return $this->parent;
    }

    public function setParent(int $parent): self
    {
        $this->parent = $parent;
        return $this;
    }

    // =========================================================================
    // AbstractDemand Implementation
    // =========================================================================

    public function hasFilters(): bool
    {
        return $this->type > 0 || $this->parent > 0;
    }

    public function getFilterProperties(): array
    {
        $filters = [];
        
        if ($this->type > 0) {
            $filters['type'] = $this->type;
        }
        if ($this->parent > 0) {
            $filters['parent'] = $this->parent;
        }
        
        return $filters;
    }
}
