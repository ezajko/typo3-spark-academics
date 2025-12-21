<?php

declare(strict_types=1);

namespace EtfUnsa\SparkAcademics\Domain\Model;

use TYPO3\CMS\Beuser\Domain\Model\BackendUser;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * Person
 */
class Person extends AbstractEntity
{
    /**
     * @var string
     */
    protected string $firstName = '';

    /**
     * @var string
     */
    protected string $lastName = '';

    /**
     * @var string
     */
    protected string $biography = '';

    /**
     * @var string
     */
    protected string $office = '';

    /**
     * @var string
     */
    protected string $phone = '';

    /**
     * @var string
     */
    protected string $email = '';

    /**
     * @var string
     */
    protected string $website = '';

    /**
     * @var string
     */
    protected string $googleScholar = '';

    /**
     * @var string
     */
    protected string $researchGate = '';

    /**
     * @var string
     */
    protected string $github = '';

    /**
     * @var string
     */
    protected string $orcid = '';

    /**
     * @var string
     */
    protected string $linkedin = '';

    /**
     * @var \TYPO3\CMS\Extbase\Domain\Model\FileReference|null
     */
    protected ?FileReference $image = null;

    /**
     * @var \TYPO3\CMS\Extbase\Domain\Model\FileReference|null
     */
    protected ?FileReference $cv = null;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<BackendUser>
     */
    protected $beUsers;

    public function __construct()
    {
        $this->beUsers = new ObjectStorage();
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getBiography(): string
    {
        return $this->biography;
    }

    public function setBiography(string $biography): void
    {
        $this->biography = $biography;
    }

    public function getOffice(): string
    {
        return $this->office;
    }

    public function setOffice(string $office): void
    {
        $this->office = $office;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getWebsite(): string
    {
        return $this->website;
    }

    public function setWebsite(string $website): void
    {
        $this->website = $website;
    }

    public function getGoogleScholar(): string
    {
        return $this->googleScholar;
    }

    public function setGoogleScholar(string $googleScholar): void
    {
        $this->googleScholar = $googleScholar;
    }

    public function getResearchGate(): string
    {
        return $this->researchGate;
    }

    public function setResearchGate(string $researchGate): void
    {
        $this->researchGate = $researchGate;
    }

    public function getGithub(): string
    {
        return $this->github;
    }

    public function setGithub(string $github): void
    {
        $this->github = $github;
    }

    public function getOrcid(): string
    {
        return $this->orcid;
    }

    public function setOrcid(string $orcid): void
    {
        $this->orcid = $orcid;
    }

    public function getLinkedin(): string
    {
        return $this->linkedin;
    }

    public function setLinkedin(string $linkedin): void
    {
        $this->linkedin = $linkedin;
    }

    public function getImage(): ?FileReference
    {
        return $this->image;
    }

    public function setImage(?FileReference $image): void
    {
        $this->image = $image;
    }

    public function getCv(): ?FileReference
    {
        return $this->cv;
    }

    public function setCv(?FileReference $cv): void
    {
        $this->cv = $cv;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<BackendUser>
     */
    public function getBeUsers(): ObjectStorage
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
}
