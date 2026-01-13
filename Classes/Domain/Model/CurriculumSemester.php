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

class CurriculumSemester extends AbstractEntity
{
    protected string $title = '';
    protected int $semesterNumber = 1;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RootBa\Academics\Domain\Model\CourseGroup>
     */
    protected ?ObjectStorage $courseGroups = null;

    public function __construct()
    {
        $this->courseGroups = new ObjectStorage();
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getSemesterNumber(): int
    {
        return $this->semesterNumber;
    }

    public function setSemesterNumber(int $semesterNumber): void
    {
        $this->semesterNumber = $semesterNumber;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RootBa\Academics\Domain\Model\CourseGroup>
     */
    public function getCourseGroups(): ?ObjectStorage
    {
        return $this->courseGroups;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RootBa\Academics\Domain\Model\CourseGroup> $courseGroups
     */
    public function setCourseGroups(ObjectStorage $courseGroups): void
    {
        $this->courseGroups = $courseGroups;
    }
}
