<?php

declare(strict_types=1);

namespace EtfUnsa\SparkAcademics\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * PersonMentoring domain model
 * Represents a mentored student record linked to a Person (mentor)
 *
 * @author Ernedin Zajko <ezajko@root.ba>
 */
class PersonMentoring extends AbstractEntity
{
    /** @var string Name of the mentored student */
    protected string $studentName = '';

    /** @var string Type of mentorship (e.g., Bachelor, Master, PhD) */
    protected string $thesisType = '';

    /** @var string Title of the thesis/project */
    protected string $thesisTitle = '';

    /** @var string Year of completion/defense */
    protected string $year = '';

    /** @var string Role in mentorship (e.g., Mentor, Co-mentor, Committee member) */
    protected string $role = '';

    /** @var string Additional notes */
    protected string $notes = '';

    // =========================================================================
    // Getters/Setters
    // =========================================================================

    public function getStudentName(): string
    {
        return $this->studentName;
    }

    public function setStudentName(string $studentName): void
    {
        $this->studentName = $studentName;
    }

    public function getThesisType(): string
    {
        return $this->thesisType;
    }

    public function setThesisType(string $thesisType): void
    {
        $this->thesisType = $thesisType;
    }

    public function getThesisTitle(): string
    {
        return $this->thesisTitle;
    }

    public function setThesisTitle(string $thesisTitle): void
    {
        $this->thesisTitle = $thesisTitle;
    }

    public function getYear(): string
    {
        return $this->year;
    }

    public function setYear(string $year): void
    {
        $this->year = $year;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole(string $role): void
    {
        $this->role = $role;
    }

    public function getNotes(): string
    {
        return $this->notes;
    }

    public function setNotes(string $notes): void
    {
        $this->notes = $notes;
    }
}
