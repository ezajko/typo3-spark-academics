<?php

declare(strict_types=1);

namespace EtfUnsa\SparkAcademics\Domain\Model;

use TYPO3\CMS\Beuser\Domain\Model\BackendUser;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;

class Project extends AbstractEntity
{
    protected string $title = '';
    protected string $acronym = '';
    protected string $uuid = '';
    protected string $description = '';
    protected string $objectives = '';
    protected string $outcomes = '';
    protected int $landingPage = 0;
    protected ?\DateTime $startDate = null;
    protected ?\DateTime $endDate = null;
    protected ?ProjectStatus $status = null;
    protected ?FundingProgram $fundingProgram = null;
    protected string $grantAgreementNumber = '';
    protected string $website = '';
    protected ?Person $coordinator = null;
    protected ?FileReference $logo = null;
    protected ?FileReference $mainImage = null;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<BackendUser>
     */
    protected ?ObjectStorage $beUsers = null;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EtfUnsa\SparkAcademics\Domain\Model\Person>
     */
    protected ?ObjectStorage $persons = null;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EtfUnsa\SparkAcademics\Domain\Model\Partner>
     */
    protected ?ObjectStorage $partners = null;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EtfUnsa\SparkAcademics\Domain\Model\Department>
     */
    protected ?ObjectStorage $departments = null;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EtfUnsa\SparkAcademics\Domain\Model\Chair>
     */
    protected ?ObjectStorage $chairs = null;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EtfUnsa\SparkAcademics\Domain\Model\ResearchLab>
     */
    protected ?ObjectStorage $researchLabs = null;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EtfUnsa\SparkAcademics\Domain\Model\ResearchGroup>
     */
    protected ?ObjectStorage $researchGroups = null;

    public function __construct()
    {
        $this->beUsers = new ObjectStorage();
        $this->persons = new ObjectStorage();
        $this->partners = new ObjectStorage();
        $this->departments = new ObjectStorage();
        $this->chairs = new ObjectStorage();
        $this->researchLabs = new ObjectStorage();
        $this->researchGroups = new ObjectStorage();
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

    public function getObjectives(): string
    {
        return $this->objectives;
    }

    public function setObjectives(string $objectives): void
    {
        $this->objectives = $objectives;
    }

    public function getOutcomes(): string
    {
        return $this->outcomes;
    }

    public function setOutcomes(string $outcomes): void
    {
        $this->outcomes = $outcomes;
    }

    public function getLandingPage(): int
    {
        return $this->landingPage;
    }

    public function setLandingPage(int $landingPage): void
    {
        $this->landingPage = $landingPage;
    }

    public function getStartDate(): ?\DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTime $startDate): void
    {
        $this->startDate = $startDate;
    }

    public function getEndDate(): ?\DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTime $endDate): void
    {
        $this->endDate = $endDate;
    }

    public function getStatus(): ?ProjectStatus
    {
        return $this->status;
    }

    public function setStatus(?ProjectStatus $status): void
    {
        $this->status = $status;
    }

    public function getFundingProgram(): ?FundingProgram
    {
        return $this->fundingProgram;
    }

    public function setFundingProgram(?FundingProgram $fundingProgram): void
    {
        $this->fundingProgram = $fundingProgram;
    }

    public function getGrantAgreementNumber(): string
    {
        return $this->grantAgreementNumber;
    }

    public function setGrantAgreementNumber(string $grantAgreementNumber): void
    {
        $this->grantAgreementNumber = $grantAgreementNumber;
    }

    public function getWebsite(): string
    {
        return $this->website;
    }

    public function setWebsite(string $website): void
    {
        $this->website = $website;
    }

    public function getCoordinator(): ?Person
    {
        return $this->coordinator;
    }

    public function setCoordinator(?Person $coordinator): void
    {
        $this->coordinator = $coordinator;
    }

    public function getLogo(): ?FileReference
    {
        return $this->logo;
    }

    public function setLogo(?FileReference $logo): void
    {
        $this->logo = $logo;
    }

    public function getMainImage(): ?FileReference
    {
        return $this->mainImage;
    }

    public function setMainImage(?FileReference $mainImage): void
    {
        $this->mainImage = $mainImage;
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

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EtfUnsa\SparkAcademics\Domain\Model\Partner>
     */
    public function getPartners(): ?ObjectStorage
    {
        return $this->partners;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EtfUnsa\SparkAcademics\Domain\Model\Partner> $partners
     */
    public function setPartners(ObjectStorage $partners): void
    {
        $this->partners = $partners;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EtfUnsa\SparkAcademics\Domain\Model\Department>
     */
    public function getDepartments(): ?ObjectStorage
    {
        return $this->departments;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EtfUnsa\SparkAcademics\Domain\Model\Department> $departments
     */
    public function setDepartments(ObjectStorage $departments): void
    {
        $this->departments = $departments;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EtfUnsa\SparkAcademics\Domain\Model\Chair>
     */
    public function getChairs(): ?ObjectStorage
    {
        return $this->chairs;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EtfUnsa\SparkAcademics\Domain\Model\Chair> $chairs
     */
    public function setChairs(ObjectStorage $chairs): void
    {
        $this->chairs = $chairs;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EtfUnsa\SparkAcademics\Domain\Model\ResearchLab>
     */
    public function getResearchLabs(): ?ObjectStorage
    {
        return $this->researchLabs;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EtfUnsa\SparkAcademics\Domain\Model\ResearchLab> $researchLabs
     */
    public function setResearchLabs(ObjectStorage $researchLabs): void
    {
        $this->researchLabs = $researchLabs;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EtfUnsa\SparkAcademics\Domain\Model\ResearchGroup>
     */
    public function getResearchGroups(): ?ObjectStorage
    {
        return $this->researchGroups;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EtfUnsa\SparkAcademics\Domain\Model\ResearchGroup> $researchGroups
     */
    public function setResearchGroups(ObjectStorage $researchGroups): void
    {
        $this->researchGroups = $researchGroups;
    }
}
