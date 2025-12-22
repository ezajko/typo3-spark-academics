<?php

declare(strict_types=1);

namespace EtfUnsa\SparkAcademics\Domain\Model;

use TYPO3\CMS\Beuser\Domain\Model\BackendUser;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class Person extends AbstractEntity
{
    protected string $firstName = '';
    protected string $lastName = '';
    protected string $biography = '';
    
    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<BackendUser>
     */
    protected $beUsers = null;

    protected string $contactOffice = '';
    protected string $phoneOffice = '';
    protected string $phoneMobile = '';
    protected string $contactEmail = '';
    protected string $contactWebsite = '';

    protected string $profileGoogleScholar = '';
    protected string $profileResearchGate = '';
    protected string $profileGithub = '';
    protected string $profileOrcid = '';
    protected string $profileLinkedin = '';

    /**
     * @var \TYPO3\CMS\Extbase\Domain\Model\FileReference|null
     */
    protected ?FileReference $mediaImage = null;

    /**
     * @var \TYPO3\CMS\Extbase\Domain\Model\FileReference|null
     */
    protected ?FileReference $biographyFilePdf = null;

    public function __construct() {
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

    public function getContactOffice(): string
    {
        return $this->contactOffice;
    }

    public function setContactOffice(string $contactOffice): void
    {
        $this->contactOffice = $contactOffice;
    }

    public function getPhoneOffice(): string
    {
        return $this->phoneOffice;
    }

    public function setPhoneOffice(string $phoneOffice): void
    {
        $this->phoneOffice = $phoneOffice;
    }

    public function getPhoneMobile(): string
    {
        return $this->phoneMobile;
    }

    public function setPhoneMobile(string $phoneMobile): void
    {
        $this->phoneMobile = $phoneMobile;
    }

    public function getContactEmail(): string
    {
        return $this->contactEmail;
    }

    public function setContactEmail(string $contactEmail): void
    {
        $this->contactEmail = $contactEmail;
    }

    public function getContactWebsite(): string
    {
        return $this->contactWebsite;
    }

    public function setContactWebsite(string $contactWebsite): void
    {
        $this->contactWebsite = $contactWebsite;
    }

    public function getProfileGoogleScholar(): string
    {
        return $this->profileGoogleScholar;
    }

    public function setProfileGoogleScholar(string $profileGoogleScholar): void
    {
        $this->profileGoogleScholar = $profileGoogleScholar;
    }

    public function getProfileResearchGate(): string
    {
        return $this->profileResearchGate;
    }

    public function setProfileResearchGate(string $profileResearchGate): void
    {
        $this->profileResearchGate = $profileResearchGate;
    }

    public function getProfileGithub(): string
    {
        return $this->profileGithub;
    }

    public function setProfileGithub(string $profileGithub): void
    {
        $this->profileGithub = $profileGithub;
    }

    public function getProfileOrcid(): string
    {
        return $this->profileOrcid;
    }

    public function setProfileOrcid(string $profileOrcid): void
    {
        $this->profileOrcid = $profileOrcid;
    }

    public function getProfileLinkedin(): string
    {
        return $this->profileLinkedin;
    }

    public function setProfileLinkedin(string $profileLinkedin): void
    {
        $this->profileLinkedin = $profileLinkedin;
    }

    public function getMediaImage(): ?FileReference
    {
        return $this->mediaImage;
    }

    public function setMediaImage(?FileReference $mediaImage): void
    {
        $this->mediaImage = $mediaImage;
    }

    public function getBiographyFilePdf(): ?FileReference
    {
        return $this->biographyFilePdf;
    }

    public function setBiographyFilePdf(?FileReference $biographyFilePdf): void
    {
        $this->biographyFilePdf = $biographyFilePdf;
    }
}
