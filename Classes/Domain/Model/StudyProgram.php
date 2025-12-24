<?php

declare(strict_types=1);

namespace EtfUnsa\SparkAcademics\Domain\Model;

use TYPO3\CMS\Beuser\Domain\Model\BackendUser;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class StudyProgram extends AbstractEntity
{
    protected string $title = '';
    protected string $acronym = '';
    protected string $uuid = '';
    protected string $description = '';
    protected int $landingPage = 0;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<BackendUser>
     */
    protected ?ObjectStorage $beUsers = null;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EtfUnsa\SparkAcademics\Domain\Model\Person>
     */
    protected ?ObjectStorage $persons = null;

    public function __construct()
    {
        $this->beUsers = new ObjectStorage();
        $this->persons = new ObjectStorage();
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
}
