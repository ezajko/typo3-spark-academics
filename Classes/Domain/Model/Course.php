<?php

declare(strict_types=1);

namespace EtfUnsa\SparkAcademics\Domain\Model;

use TYPO3\CMS\Beuser\Domain\Model\BackendUser;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Annotation\ORM\Cascade;

/**
 * Course - container entity with versioned syllabi
 * 
 * @author Ernedin Zajko <ezajko@root.ba>
 */
class Course extends AbstractEntity
{
    // Basic Info
    protected string $code = '';
    protected string $title = '';
    protected string $acronym = '';
    protected string $uuid = '';
    protected string $description = '';
    protected string $coursewareUrl = '';

    // Organization
    protected ?Department $department = null;
    protected ?Chair $chair = null;

    // Notes
    protected string $notes = '';

    /**
     * Syllabi versions (IRRE inline)
     * @var ObjectStorage<CourseSyllabus>
     * @Cascade("remove")
     */
    protected ?ObjectStorage $syllabi = null;

    /**
     * External similar courses
     * @var ObjectStorage<ExternalCourse>
     */
    protected ?ObjectStorage $externalCourses = null;

    /**
     * Backend users (editors)
     * @var ObjectStorage<BackendUser>
     */
    protected ?ObjectStorage $beUsers = null;

    /**
     * Associated persons
     * @var ObjectStorage<Person>
     */
    protected ?ObjectStorage $persons = null;

    public function __construct()
    {
        $this->syllabi = new ObjectStorage();
        $this->externalCourses = new ObjectStorage();
        $this->beUsers = new ObjectStorage();
        $this->persons = new ObjectStorage();
    }

    // Basic Info
    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getAcronym(): string
    {
        return $this->acronym;
    }

    public function setAcronym(string $acronym): void
    {
        $this->acronym = $acronym;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): void
    {
        $this->uuid = $uuid;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getCoursewareUrl(): string
    {
        return $this->coursewareUrl;
    }

    public function setCoursewareUrl(string $coursewareUrl): void
    {
        $this->coursewareUrl = $coursewareUrl;
    }

    // Organization
    public function getDepartment(): ?Department
    {
        return $this->department;
    }

    public function setDepartment(?Department $department): void
    {
        $this->department = $department;
    }

    public function getChair(): ?Chair
    {
        return $this->chair;
    }

    public function setChair(?Chair $chair): void
    {
        $this->chair = $chair;
    }

    // Notes
    public function getNotes(): string
    {
        return $this->notes;
    }

    public function setNotes(string $notes): void
    {
        $this->notes = $notes;
    }

    // Syllabi
    /** @return ObjectStorage<CourseSyllabus> */
    public function getSyllabi(): ?ObjectStorage
    {
        return $this->syllabi;
    }

    public function setSyllabi(ObjectStorage $syllabi): void
    {
        $this->syllabi = $syllabi;
    }

    public function addSyllabus(CourseSyllabus $syllabus): void
    {
        $this->syllabi->attach($syllabus);
    }

    public function removeSyllabus(CourseSyllabus $syllabus): void
    {
        $this->syllabi->detach($syllabus);
    }

    /**
     * Get the latest syllabus version (sorted by validFrom or crdate descending)
     */
    public function getLatestSyllabus(): ?CourseSyllabus
    {
        if ($this->syllabi === null || $this->syllabi->count() === 0) {
            return null;
        }

        $sorted = $this->getSortedSyllabi();
        return $sorted[0] ?? null;
    }

    /**
     * Get all syllabi sorted by academic year descending (newest first)
     * 
     * @return CourseSyllabus[]
     */
    public function getSortedSyllabi(): array
    {
        $syllabiArray = $this->syllabi->toArray();
        usort($syllabiArray, function (CourseSyllabus $a, CourseSyllabus $b) {
            // Sort by academic year descending (newest first)
            $yearDiff = strcmp($b->getAcademicYear(), $a->getAcademicYear());
            return $yearDiff !== 0 ? $yearDiff : ($b->getUid() <=> $a->getUid());
        });
        return $syllabiArray;
    }

    // External Courses
    /** @return ObjectStorage<ExternalCourse> */
    public function getExternalCourses(): ?ObjectStorage
    {
        return $this->externalCourses;
    }

    public function setExternalCourses(ObjectStorage $externalCourses): void
    {
        $this->externalCourses = $externalCourses;
    }

    // Backend Users
    /** @return ObjectStorage<BackendUser> */
    public function getBeUsers(): ?ObjectStorage
    {
        return $this->beUsers;
    }

    public function setBeUsers(ObjectStorage $beUsers): void
    {
        $this->beUsers = $beUsers;
    }

    // Persons
    /** @return ObjectStorage<Person> */
    public function getPersons(): ?ObjectStorage
    {
        return $this->persons;
    }

    public function setPersons(ObjectStorage $persons): void
    {
        $this->persons = $persons;
    }
}
