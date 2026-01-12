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
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\Annotation\ORM\Cascade;

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

    // CERIF-compatible attributes
    /**
     * Local institution budget (in EUR)
     */
    protected float $localBudget = 0.0;

    /**
     * Total project budget (in EUR)
     */
    protected float $totalBudget = 0.0;

    /**
     * Keywords (comma-separated)
     * CERIF: cfProjKeyw
     */
    protected string $keywords = '';

    /**
     * Project type classification
     * CERIF: cfProj_Class (semantic layer)
     */
    protected ?ProjectType $projectType = null;

    /**
     * Scientific fields / areas of research
     * CERIF: cfProj_Class (semantic layer)
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EtfUnsa\SparkAcademics\Domain\Model\ScientificField>
     */
    protected ?ObjectStorage $scientificFields = null;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<BackendUser>
     */
    protected ?ObjectStorage $beUsers = null;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EtfUnsa\SparkAcademics\Domain\Model\ProjectPerson>
     */
    #[Cascade(['remove'])]
    protected ?ObjectStorage $persons = null;

    // ...

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EtfUnsa\SparkAcademics\Domain\Model\ProjectPerson>
     */
    public function getPersons(): ?ObjectStorage
    {
        return $this->persons;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EtfUnsa\SparkAcademics\Domain\Model\ProjectPerson> $persons
     */
    public function setPersons(ObjectStorage $persons): void
    {
        $this->persons = $persons;
    }

    public function addPerson(ProjectPerson $person): void
    {
        $this->persons->attach($person);
    }

    public function removePerson(ProjectPerson $person): void
    {
        $this->persons->detach($person);
    }

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EtfUnsa\SparkAcademics\Domain\Model\Partner>
     */
    protected ?ObjectStorage $partners = null;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EtfUnsa\SparkAcademics\Domain\Model\Organization>
     */
    protected ?ObjectStorage $organizations = null;

    public function __construct()
    {
        $this->beUsers = new ObjectStorage();
        $this->persons = new ObjectStorage();
        $this->partners = new ObjectStorage();
        $this->organizations = new ObjectStorage();
        $this->scientificFields = new ObjectStorage();
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

    // CERIF-compatible attribute getters/setters

    public function getLocalBudget(): float
    {
        return $this->localBudget;
    }

    public function setLocalBudget(float $localBudget): void
    {
        $this->localBudget = $localBudget;
    }

    public function getTotalBudget(): float
    {
        return $this->totalBudget;
    }

    public function setTotalBudget(float $totalBudget): void
    {
        $this->totalBudget = $totalBudget;
    }

    public function getKeywords(): string
    {
        return $this->keywords;
    }

    public function setKeywords(string $keywords): void
    {
        $this->keywords = $keywords;
    }

    /**
     * Returns keywords as array
     * @return array<string>
     */
    public function getKeywordsArray(): array
    {
        if (empty($this->keywords)) {
            return [];
        }
        return array_map('trim', explode(',', $this->keywords));
    }

    public function getProjectType(): ?ProjectType
    {
        return $this->projectType;
    }

    public function setProjectType(?ProjectType $projectType): void
    {
        $this->projectType = $projectType;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EtfUnsa\SparkAcademics\Domain\Model\ScientificField>
     */
    public function getScientificFields(): ?ObjectStorage
    {
        return $this->scientificFields;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EtfUnsa\SparkAcademics\Domain\Model\ScientificField> $scientificFields
     */
    public function setScientificFields(ObjectStorage $scientificFields): void
    {
        $this->scientificFields = $scientificFields;
    }

    public function addScientificField(ScientificField $scientificField): void
    {
        $this->scientificFields->attach($scientificField);
    }

    public function removeScientificField(ScientificField $scientificField): void
    {
        $this->scientificFields->detach($scientificField);
    }
}
