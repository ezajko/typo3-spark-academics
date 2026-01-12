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
 * DTO for Course filtering criteria
 * 
 * Supports filtering by organization, study cycle, category, and scientific field.
 * Used by CourseRepository and backend/frontend controllers.
 * 
 * @author Ernedin Zajko <ezajko@root.ba>
 */
class CourseDemand extends AbstractDemand
{
    /**
     * Filter by organization
     */
    protected int $organization = 0;

    /**
     * Filter by study cycle (via syllabus)
     */
    protected int $studyCycle = 0;

    /**
     * Filter by course category (via syllabus)
     */
    protected int $courseCategory = 0;

    /**
     * Filter by scientific field (via syllabus)
     */
    protected int $scientificField = 0;

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
    // Study Cycle
    // =========================================================================

    public function getStudyCycle(): int
    {
        return $this->studyCycle;
    }

    public function setStudyCycle(int $studyCycle): self
    {
        $this->studyCycle = $studyCycle;
        return $this;
    }

    // =========================================================================
    // Course Category
    // =========================================================================

    public function getCourseCategory(): int
    {
        return $this->courseCategory;
    }

    public function setCourseCategory(int $courseCategory): self
    {
        $this->courseCategory = $courseCategory;
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
    // AbstractDemand Implementation
    // =========================================================================

    public function hasFilters(): bool
    {
        return $this->organization > 0
            || $this->studyCycle > 0
            || $this->courseCategory > 0
            || $this->scientificField > 0;
    }

    public function getFilterProperties(): array
    {
        $filters = [];
        
        if ($this->organization > 0) {
            $filters['organization'] = $this->organization;
        }
        if ($this->studyCycle > 0) {
            $filters['syllabi.studyCycle'] = $this->studyCycle;
        }
        if ($this->courseCategory > 0) {
            $filters['syllabi.courseCategory'] = $this->courseCategory;
        }
        if ($this->scientificField > 0) {
            $filters['syllabi.scientificField'] = $this->scientificField;
        }
        
        return $filters;
    }
}
