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
 * DTO for Project filtering criteria
 * 
 * Supports filtering by status, type, funding program, organization, and date range.
 * Used by ProjectRepository and backend/frontend controllers.
 * 
 * @author Ernedin Zajko <ezajko@root.ba>
 */
class ProjectDemand extends AbstractDemand
{
    /**
     * Filter by project status
     */
    protected int $projectStatus = 0;

    /**
     * Filter by project type
     */
    protected int $projectType = 0;

    /**
     * Filter by funding program
     */
    protected int $fundingProgram = 0;

    /**
     * Filter by scientific field
     */
    protected int $scientificField = 0;

    /**
     * Filter by organization (unified)
     */
    protected int $organization = 0;

    /**
     * Filter by date range - from
     */
    protected ?\DateTime $dateFrom = null;

    /**
     * Filter by date range - to
     */
    protected ?\DateTime $dateTo = null;

    /**
     * Filter by backend user (for permission-based filtering)
     */
    protected int $backendUser = 0;

    // =========================================================================
    // Project Status
    // =========================================================================

    public function getProjectStatus(): int
    {
        return $this->projectStatus;
    }

    public function setProjectStatus(int $projectStatus): self
    {
        $this->projectStatus = $projectStatus;
        return $this;
    }

    // =========================================================================
    // Project Type
    // =========================================================================

    public function getProjectType(): int
    {
        return $this->projectType;
    }

    public function setProjectType(int $projectType): self
    {
        $this->projectType = $projectType;
        return $this;
    }

    // =========================================================================
    // Funding Program
    // =========================================================================

    public function getFundingProgram(): int
    {
        return $this->fundingProgram;
    }

    public function setFundingProgram(int $fundingProgram): self
    {
        $this->fundingProgram = $fundingProgram;
        return $this;
    }

    // =========================================================================
    // Scientific Field
    // =========================================================================

    public function getScientificField(): int
    {
        return $this->scientificField;
    }

    public function setScientificField(int $scientificField): self
    {
        $this->scientificField = $scientificField;
        return $this;
    }

    // =========================================================================
    // Organization
    // =========================================================================

    public function getOrganization(): int
    {
        return $this->organization;
    }

    public function setOrganization(int $organization): self
    {
        $this->organization = $organization;
        return $this;
    }

    // =========================================================================
    // Date Range
    // =========================================================================

    public function getDateFrom(): ?\DateTime
    {
        return $this->dateFrom;
    }

    public function setDateFrom(?\DateTime $dateFrom): self
    {
        $this->dateFrom = $dateFrom;
        return $this;
    }

    public function getDateTo(): ?\DateTime
    {
        return $this->dateTo;
    }

    public function setDateTo(?\DateTime $dateTo): self
    {
        $this->dateTo = $dateTo;
        return $this;
    }

    // =========================================================================
    // Backend User
    // =========================================================================

    public function getBackendUser(): int
    {
        return $this->backendUser;
    }

    public function setBackendUser(int $backendUser): self
    {
        $this->backendUser = $backendUser;
        return $this;
    }

    // =========================================================================
    // AbstractDemand Implementation
    // =========================================================================

    public function hasFilters(): bool
    {
        return $this->projectStatus > 0
            || $this->projectType > 0
            || $this->fundingProgram > 0
            || $this->scientificField > 0
            || $this->organization > 0
            || $this->dateFrom !== null
            || $this->dateTo !== null
            || $this->backendUser > 0;
    }

    public function getFilterProperties(): array
    {
        $filters = [];
        
        if ($this->projectStatus > 0) {
            $filters['status'] = $this->projectStatus;
        }
        if ($this->projectType > 0) {
            $filters['projectType'] = $this->projectType;
        }
        if ($this->fundingProgram > 0) {
            $filters['fundingProgram'] = $this->fundingProgram;
        }
        if ($this->scientificField > 0) {
            $filters['scientificFields'] = $this->scientificField; // M:N
        }
        if ($this->organization > 0) {
            $filters['organizations'] = $this->organization; // M:N
        }
        if ($this->dateFrom !== null) {
            $filters['dateFrom'] = $this->dateFrom;
        }
        if ($this->dateTo !== null) {
            $filters['dateTo'] = $this->dateTo;
        }
        if ($this->backendUser > 0) {
            $filters['beUsers'] = $this->backendUser;
        }
        
        return $filters;
    }
}
