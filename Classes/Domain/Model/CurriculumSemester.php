<?php

declare(strict_types=1);

namespace EtfUnsa\SparkAcademics\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class CurriculumSemester extends AbstractEntity
{
    protected string $title = '';
    protected int $semesterNumber = 1;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EtfUnsa\SparkAcademics\Domain\Model\CourseGroup>
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
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EtfUnsa\SparkAcademics\Domain\Model\CourseGroup>
     */
    public function getCourseGroups(): ?ObjectStorage
    {
        return $this->courseGroups;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EtfUnsa\SparkAcademics\Domain\Model\CourseGroup> $courseGroups
     */
    public function setCourseGroups(ObjectStorage $courseGroups): void
    {
        $this->courseGroups = $courseGroups;
    }
}
