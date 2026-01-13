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
    // =========================================================================
    // Basic Info
    // =========================================================================
    
    protected string $code = '';
    protected string $title = '';
    protected string $acronym = '';
    protected string $uuid = '';
    protected string $description = '';
    protected string $coursewareUrl = '';

    // =========================================================================
    // Organization
    // =========================================================================
    
    protected ?Organization $organization = null;

    // =========================================================================
    // Notes
    // =========================================================================
    
    protected string $notes = '';

    // =========================================================================
    // Relations
    // =========================================================================

    /**
     * Syllabi versions (IRRE inline)
     * @var ObjectStorage<CourseSyllabus>
     */
    #[Cascade(['remove'])]
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
     * Associated persons (with roles)
     * @var ObjectStorage<CoursePerson>
     */
    #[Cascade(['remove'])]
    protected ?ObjectStorage $persons = null;

    // =========================================================================
    // Constructor
    // =========================================================================

    public function __construct()
    {
        $this->syllabi = new ObjectStorage();
        $this->externalCourses = new ObjectStorage();
        $this->beUsers = new ObjectStorage();
        $this->persons = new ObjectStorage();
    }

    // =========================================================================
    // Basic Info Getters/Setters
    // =========================================================================

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

    public function getNotes(): string
    {
        return $this->notes;
    }

    public function setNotes(string $notes): void
    {
        $this->notes = $notes;
    }

    // =========================================================================
    // Organization Getters/Setters
    // =========================================================================

    public function getOrganization(): ?Organization
    {
        return $this->organization;
    }

    public function setOrganization(?Organization $organization): void
    {
        $this->organization = $organization;
    }

    // =========================================================================
    // Syllabi Getters/Setters
    // =========================================================================

    /**
     * @return ObjectStorage<CourseSyllabus>
     */
    public function getSyllabi(): ?ObjectStorage
    {
        return $this->syllabi;
    }

    /**
     * @param ObjectStorage<CourseSyllabus> $syllabi
     */
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

    // =========================================================================
    // External Courses Getters/Setters
    // =========================================================================

    /**
     * @return ObjectStorage<ExternalCourse>
     */
    public function getExternalCourses(): ?ObjectStorage
    {
        return $this->externalCourses;
    }

    /**
     * @param ObjectStorage<ExternalCourse> $externalCourses
     */
    public function setExternalCourses(ObjectStorage $externalCourses): void
    {
        $this->externalCourses = $externalCourses;
    }

    public function addExternalCourse(ExternalCourse $externalCourse): void
    {
        $this->externalCourses->attach($externalCourse);
    }

    public function removeExternalCourse(ExternalCourse $externalCourse): void
    {
        $this->externalCourses->detach($externalCourse);
    }

    // =========================================================================
    // Backend Users Getters/Setters
    // =========================================================================

    /**
     * @return ObjectStorage<BackendUser>
     */
    public function getBeUsers(): ?ObjectStorage
    {
        return $this->beUsers;
    }

    /**
     * @param ObjectStorage<BackendUser> $beUsers
     */
    public function setBeUsers(ObjectStorage $beUsers): void
    {
        $this->beUsers = $beUsers;
    }

    // =========================================================================
    // Persons Getters/Setters
    // =========================================================================

    /**
     * @return ObjectStorage<CoursePerson>
     */
    public function getPersons(): ?ObjectStorage
    {
        return $this->persons;
    }

    /**
     * @param ObjectStorage<CoursePerson> $persons
     */
    public function setPersons(ObjectStorage $persons): void
    {
        $this->persons = $persons;
    }

    public function addPerson(CoursePerson $person): void
    {
        $this->persons->attach($person);
    }

    public function removePerson(CoursePerson $person): void
    {
        $this->persons->detach($person);
    }

    // =========================================================================
    // Helper Methods
    // =========================================================================

    /**
     * Get the latest syllabus version (sorted by validFrom descending)
     */
    public function getLatestSyllabus(): ?CourseSyllabus
    {
        if ($this->syllabi === null || $this->syllabi->count() === 0) {
            return null;
        }

        $syllabiArray = $this->syllabi->toArray();
        usort($syllabiArray, function (CourseSyllabus $a, CourseSyllabus $b) {
            $aDate = $a->getValidFrom() ?? new \DateTime('1970-01-01');
            $bDate = $b->getValidFrom() ?? new \DateTime('1970-01-01');
            return $bDate <=> $aDate;
        });

        return $syllabiArray[0] ?? null;
    }
}
