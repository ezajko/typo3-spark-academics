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
     */
    #[Cascade(['remove'])]
    protected ?\TYPO3\CMS\Extbase\Persistence\ObjectStorage $syllabi = null;

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

    public function __construct()
    {
        $this->syllabi = new ObjectStorage();
        $this->externalCourses = new ObjectStorage();
        $this->beUsers = new ObjectStorage();
        $this->persons = new ObjectStorage();
    }

    // ... (rest of methods)

    // Persons
    /** @return ObjectStorage<CoursePerson> */
    public function getPersons(): ?ObjectStorage
    {
        return $this->persons;
    }

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
}
