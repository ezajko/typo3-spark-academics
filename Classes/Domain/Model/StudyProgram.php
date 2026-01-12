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

/**
 * StudyProgram domain model
 * Represents an academic study program (Bachelor, Master, PhD, etc.)
 * 
 * @author Ernedin Zajko <ezajko@root.ba>
 */
class StudyProgram extends AbstractEntity
{
    protected string $title = '';
    protected string $acronym = '';
    protected string $uuid = '';
    protected string $description = '';
    protected int $landingPage = 0;

    /**
     * Organizations (unified, replaces deprecated departments/chairs)
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EtfUnsa\SparkAcademics\Domain\Model\Organization>
     */
    protected ?ObjectStorage $organizations = null;

    protected ?StudyCycle $studyCycle = null;
    protected ?ScientificField $scientificField = null;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EtfUnsa\SparkAcademics\Domain\Model\StudyType>
     */
    protected ?ObjectStorage $studyTypes = null;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EtfUnsa\SparkAcademics\Domain\Model\ModeOfStudy>
     */
    protected ?ObjectStorage $modesOfStudy = null;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EtfUnsa\SparkAcademics\Domain\Model\Language>
     */
    protected ?ObjectStorage $languages = null;
    protected int $durationSemesters = 0;
    protected int $durationYears = 0;
    protected int $ectsCredits = 0;
    protected string $qualificationTitle = '';

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<BackendUser>
     */
    protected ?ObjectStorage $beUsers = null;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EtfUnsa\SparkAcademics\Domain\Model\Person>
     */
    protected ?ObjectStorage $persons = null;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EtfUnsa\SparkAcademics\Domain\Model\Curriculum>
     */
    protected ?ObjectStorage $curricula = null;

    public function __construct()
    {
        $this->beUsers = new ObjectStorage();
        $this->persons = new ObjectStorage();
        $this->organizations = new ObjectStorage();
        $this->studyTypes = new ObjectStorage();
        $this->modesOfStudy = new ObjectStorage();
        $this->languages = new ObjectStorage();
        $this->curricula = new ObjectStorage();
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

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<BackendUser>
     */
    public function getBeUsers(): ?ObjectStorage
    {
        return $this->beUsers;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<BackendUser> $beUsers
     */
    public function setBeUsers(ObjectStorage $beUsers): void
    {
        $this->beUsers = $beUsers;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EtfUnsa\SparkAcademics\Domain\Model\Person>
     */
    public function getPersons(): ?ObjectStorage
    {
        return $this->persons;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EtfUnsa\SparkAcademics\Domain\Model\Person> $persons
     */
    public function setPersons(ObjectStorage $persons): void
    {
        $this->persons = $persons;
    }

    public function getLandingPage(): int
    {
        return $this->landingPage;
    }

    public function setLandingPage(int $landingPage): void
    {
        $this->landingPage = $landingPage;
    }

    /**
     * Organizations (unified, replaces deprecated departments/chairs)
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EtfUnsa\SparkAcademics\Domain\Model\Organization>
     */
    public function getOrganizations(): ?ObjectStorage
    {
        return $this->organizations;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EtfUnsa\SparkAcademics\Domain\Model\Organization> $organizations
     */
    public function setOrganizations(ObjectStorage $organizations): void
    {
        $this->organizations = $organizations;
    }

    public function addOrganization(Organization $organization): void
    {
        $this->organizations->attach($organization);
    }

    public function removeOrganization(Organization $organization): void
    {
        $this->organizations->detach($organization);
    }

    public function getStudyCycle(): ?StudyCycle
    {
        return $this->studyCycle;
    }

    public function setStudyCycle(?StudyCycle $studyCycle): void
    {
        $this->studyCycle = $studyCycle;
    }

    public function getScientificField(): ?ScientificField
    {
        return $this->scientificField;
    }

    public function setScientificField(?ScientificField $scientificField): void
    {
        $this->scientificField = $scientificField;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EtfUnsa\SparkAcademics\Domain\Model\StudyType>
     */
    public function getStudyTypes(): ?ObjectStorage
    {
        return $this->studyTypes;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EtfUnsa\SparkAcademics\Domain\Model\StudyType> $studyTypes
     */
    public function setStudyTypes(ObjectStorage $studyTypes): void
    {
        $this->studyTypes = $studyTypes;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EtfUnsa\SparkAcademics\Domain\Model\ModeOfStudy>
     */
    public function getModesOfStudy(): ?ObjectStorage
    {
        return $this->modesOfStudy;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EtfUnsa\SparkAcademics\Domain\Model\ModeOfStudy> $modesOfStudy
     */
    public function setModesOfStudy(ObjectStorage $modesOfStudy): void
    {
        $this->modesOfStudy = $modesOfStudy;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EtfUnsa\SparkAcademics\Domain\Model\Language>
     */
    public function getLanguages(): ?ObjectStorage
    {
        return $this->languages;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EtfUnsa\SparkAcademics\Domain\Model\Language> $languages
     */
    public function setLanguages(ObjectStorage $languages): void
    {
        $this->languages = $languages;
    }

    public function getDurationSemesters(): int
    {
        return $this->durationSemesters;
    }

    public function setDurationSemesters(int $durationSemesters): void
    {
        $this->durationSemesters = $durationSemesters;
    }

    public function getDurationYears(): int
    {
        return $this->durationYears;
    }

    public function setDurationYears(int $durationYears): void
    {
        $this->durationYears = $durationYears;
    }

    public function getEctsCredits(): int
    {
        return $this->ectsCredits;
    }

    public function setEctsCredits(int $ectsCredits): void
    {
        $this->ectsCredits = $ectsCredits;
    }

    public function getQualificationTitle(): string
    {
        return $this->qualificationTitle;
    }

    public function setQualificationTitle(string $qualificationTitle): void
    {
        $this->qualificationTitle = $qualificationTitle;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EtfUnsa\SparkAcademics\Domain\Model\Curriculum>
     */
    public function getCurricula(): ?ObjectStorage
    {
        return $this->curricula;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EtfUnsa\SparkAcademics\Domain\Model\Curriculum> $curricula
     */
    public function setCurricula(ObjectStorage $curricula): void
    {
        $this->curricula = $curricula;
    }
}
