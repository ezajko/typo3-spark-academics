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

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EtfUnsa\SparkAcademics\Domain\Model\Department>
     */
    protected ObjectStorage $departments;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EtfUnsa\SparkAcademics\Domain\Model\ResearchLab>
     */
    protected ObjectStorage $laboratories;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EtfUnsa\SparkAcademics\Domain\Model\ResearchGroup>
     */
    protected ObjectStorage $groups;

    protected ?Department $primaryDepartment = null;
    protected ?AcademicTitle $academicTitle = null;
    protected ?AcademicRank $academicRank = null;

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
        $this->departments = new ObjectStorage();
        $this->laboratories = new ObjectStorage();
        $this->groups = new ObjectStorage();
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

    public function getDepartments(): ObjectStorage
    {
        return $this->departments;
    }

    public function setDepartments(ObjectStorage $departments): void
    {
        $this->departments = $departments;
    }

    public function getLaboratories(): ObjectStorage
    {
        return $this->laboratories;
    }

    public function setLaboratories(ObjectStorage $laboratories): void
    {
        $this->laboratories = $laboratories;
    }

    public function getGroups(): ObjectStorage
    {
        return $this->groups;
    }

    public function setGroups(ObjectStorage $groups): void
    {
        $this->groups = $groups;
    }

    public function getPrimaryDepartment(): ?Department
    {
        return $this->primaryDepartment;
    }

    public function setPrimaryDepartment(?Department $primaryDepartment): void
    {
        $this->primaryDepartment = $primaryDepartment;
    }

    public function getAcademicTitle(): ?AcademicTitle
    {
        return $this->academicTitle;
    }

    public function setAcademicTitle(?AcademicTitle $academicTitle): void
    {
        $this->academicTitle = $academicTitle;
    }

    public function getAcademicRank(): ?AcademicRank
    {
        return $this->academicRank;
    }

    public function setAcademicRank(?AcademicRank $academicRank): void
    {
        $this->academicRank = $academicRank;
    }
}
