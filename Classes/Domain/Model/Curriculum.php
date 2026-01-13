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

namespace RootBa\Academics\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class Curriculum extends AbstractEntity
{
    protected string $title = '';
    protected int $year = 0;
    protected string $uuid = '';
    protected string $note = '';
    protected bool $isActive = false;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RootBa\Academics\Domain\Model\CurriculumSemester>
     */
    protected ?ObjectStorage $semesters = null;

    public function __construct()
    {
        $this->semesters = new ObjectStorage();
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function setYear(int $year): void
    {
        $this->year = $year;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): void
    {
        $this->uuid = $uuid;
    }

    public function getNote(): string
    {
        return $this->note;
    }

    public function setNote(string $note): void
    {
        $this->note = $note;
    }

    public function isIsActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RootBa\Academics\Domain\Model\CurriculumSemester>
     */
    public function getSemesters(): ?ObjectStorage
    {
        return $this->semesters;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RootBa\Academics\Domain\Model\CurriculumSemester> $semesters
     */
    public function setSemesters(ObjectStorage $semesters): void
    {
        $this->semesters = $semesters;
    }

    /**
     * Returns semesters grouped by academic year (based on semester number).
     * Year 1 = Sem 1, 2
     * Year 2 = Sem 3, 4
     * etc.
     * 
     * @return array<int, array<\RootBa\Academics\Domain\Model\CurriculumSemester>>
     */
    public function getSemestersByYear(): array
    {
        $grouped = [];
        if ($this->semesters) {
            foreach ($this->semesters as $semester) {
                $year = (int)ceil($semester->getSemesterNumber() / 2);
                if ($year < 1) $year = 1;
                $grouped[$year][] = $semester;
            }
        }
        ksort($grouped);
        
        // Sort semesters within years
        foreach ($grouped as &$sems) {
            usort($sems, fn($a, $b) => $a->getSemesterNumber() <=> $b->getSemesterNumber());
        }
        
        return $grouped;
    }
}
