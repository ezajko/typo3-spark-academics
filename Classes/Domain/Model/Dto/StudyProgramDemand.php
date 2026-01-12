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
 * DTO for StudyProgram filtering criteria
 * 
 * Supports filtering by organization, study cycle, type, mode, and language.
 * Used by StudyProgramRepository and backend/frontend controllers.
 * 
 * @author Ernedin Zajko <ezajko@root.ba>
 */
class StudyProgramDemand extends AbstractDemand
{
    /**
     * Filter by organization
     */
    protected int $organization = 0;

    /**
     * Filter by study cycle
     */
    protected int $studyCycle = 0;

    /**
     * Filter by study type
     */
    protected int $studyType = 0;

    /**
     * Filter by mode of study
     */
    protected int $modeOfStudy = 0;

    /**
     * Filter by language
     */
    protected int $language = 0;

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
    // Study Type
    // =========================================================================

    public function getStudyType(): int
    {
        return $this->studyType;
    }

    public function setStudyType(int $studyType): self
    {
        $this->studyType = $studyType;
        return $this;
    }

    // =========================================================================
    // Mode of Study
    // =========================================================================

    public function getModeOfStudy(): int
    {
        return $this->modeOfStudy;
    }

    public function setModeOfStudy(int $modeOfStudy): self
    {
        $this->modeOfStudy = $modeOfStudy;
        return $this;
    }

    // =========================================================================
    // Language
    // =========================================================================

    public function getLanguage(): int
    {
        return $this->language;
    }

    public function setLanguage(int $language): self
    {
        $this->language = $language;
        return $this;
    }

    // =========================================================================
    // AbstractDemand Implementation
    // =========================================================================

    public function hasFilters(): bool
    {
        return $this->organization > 0
            || $this->studyCycle > 0
            || $this->studyType > 0
            || $this->modeOfStudy > 0
            || $this->language > 0;
    }

    public function getFilterProperties(): array
    {
        $filters = [];
        
        if ($this->organization > 0) {
            $filters['organizations'] = $this->organization; // M:N
        }
        if ($this->studyCycle > 0) {
            $filters['studyCycle'] = $this->studyCycle;
        }
        if ($this->studyType > 0) {
            $filters['studyTypes'] = $this->studyType; // M:N
        }
        if ($this->modeOfStudy > 0) {
            $filters['modesOfStudy'] = $this->modeOfStudy; // M:N
        }
        if ($this->language > 0) {
            $filters['languages'] = $this->language; // M:N
        }
        
        return $filters;
    }
}
