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

namespace EtfUnsa\SparkAcademics\Domain\Model\Dto;

/**
 * DTO for Person filtering criteria
 * 
 * Supports filtering by organization, academic rank/title, and staff attributes.
 * Used by PersonRepository and backend/frontend controllers.
 * 
 * @author Ernedin Zajko <ezajko@root.ba>
 */
class PersonDemand extends AbstractDemand
{
    /**
     * Filter by primary organization
     */
    protected int $primaryOrganization = 0;

    /**
     * Filter by additional organization (M:N relation)
     */
    protected int $additionalOrganization = 0;

    /**
     * Filter by academic rank
     */
    protected int $academicRank = 0;

    /**
     * Filter by academic title
     */
    protected int $academicTitle = 0;

    /**
     * Filter by person type (internal, visiting, etc.)
     */
    protected int $personType = 0;

    /**
     * Filter by academic status (true = only academic staff, false = only non-academic)
     */
    protected ?bool $isAcademic = null;

    /**
     * Filter by staff status (active, inactive, sabbatical, etc.)
     */
    protected string $staffStatus = '';

    /**
     * Filter by backend user (for permission-based filtering)
     */
    protected int $backendUser = 0;

    // =========================================================================
    // Organization
    // =========================================================================

    public function getPrimaryOrganization(): int
    {
        return $this->primaryOrganization;
    }

    public function setPrimaryOrganization(int $primaryOrganization): self
    {
        $this->primaryOrganization = $primaryOrganization;
        return $this;
    }

    public function getAdditionalOrganization(): int
    {
        return $this->additionalOrganization;
    }

    public function setAdditionalOrganization(int $additionalOrganization): self
    {
        $this->additionalOrganization = $additionalOrganization;
        return $this;
    }

    // =========================================================================
    // Academic Rank
    // =========================================================================

    public function getAcademicRank(): int
    {
        return $this->academicRank;
    }

    public function setAcademicRank(int $academicRank): self
    {
        $this->academicRank = $academicRank;
        return $this;
    }

    // =========================================================================
    // Academic Title
    // =========================================================================

    public function getAcademicTitle(): int
    {
        return $this->academicTitle;
    }

    public function setAcademicTitle(int $academicTitle): self
    {
        $this->academicTitle = $academicTitle;
        return $this;
    }

    // =========================================================================
    // Person Type
    // =========================================================================

    public function getPersonType(): int
    {
        return $this->personType;
    }

    public function setPersonType(int $personType): self
    {
        $this->personType = $personType;
        return $this;
    }

    // =========================================================================
    // Is Academic
    // =========================================================================

    public function getIsAcademic(): ?bool
    {
        return $this->isAcademic;
    }

    public function setIsAcademic(?bool $isAcademic): self
    {
        $this->isAcademic = $isAcademic;
        return $this;
    }

    // =========================================================================
    // Staff Status
    // =========================================================================

    public function getStaffStatus(): string
    {
        return $this->staffStatus;
    }

    public function setStaffStatus(string $staffStatus): self
    {
        $this->staffStatus = $staffStatus;
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
        return $this->primaryOrganization > 0
            || $this->additionalOrganization > 0
            || $this->academicRank > 0
            || $this->academicTitle > 0
            || $this->personType > 0
            || $this->isAcademic !== null
            || !empty($this->staffStatus)
            || $this->backendUser > 0;
    }

    public function getFilterProperties(): array
    {
        $filters = [];
        
        if ($this->primaryOrganization > 0) {
            $filters['primaryOrganization'] = $this->primaryOrganization;
        }
        if ($this->additionalOrganization > 0) {
            $filters['additionalOrganizations'] = $this->additionalOrganization; // M:N relation
        }
        if ($this->academicRank > 0) {
            $filters['academicRank'] = $this->academicRank;
        }
        if ($this->academicTitle > 0) {
            $filters['academicTitle'] = $this->academicTitle;
        }
        if ($this->personType > 0) {
            $filters['personType'] = $this->personType;
        }
        if ($this->isAcademic !== null) {
            $filters['isAcademic'] = $this->isAcademic;
        }
        if (!empty($this->staffStatus)) {
            $filters['staffStatus'] = $this->staffStatus;
        }
        if ($this->backendUser > 0) {
            $filters['beUsers'] = $this->backendUser;
        }
        
        return $filters;
    }
}
