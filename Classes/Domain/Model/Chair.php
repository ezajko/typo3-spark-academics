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

class Chair extends AbstractEntity
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

    protected string $email = '';
    protected string $phone = '';
    protected string $website = '';
    protected string $address = '';
    protected string $room = '';
    protected string $facebook = '';
    protected string $twitter = '';
    protected string $linkedin = '';
    protected string $instagram = '';
    protected string $researchFocus = '';
    protected string $mission = '';
    protected string $vision = '';
    protected ?Person $headPerson = null;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EtfUnsa\SparkAcademics\Domain\Model\Department>
     */
    protected ?ObjectStorage $departments = null;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EtfUnsa\SparkAcademics\Domain\Model\ResearchLab>
     */
    protected ?ObjectStorage $researchLabs = null;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EtfUnsa\SparkAcademics\Domain\Model\ResearchGroup>
     */
    protected ?ObjectStorage $researchGroups = null;

    protected ?FileReference $logo = null;
    protected ?FileReference $mainImage = null;

    public function __construct()
    {
        $this->beUsers = new ObjectStorage();
        $this->persons = new ObjectStorage();
        $this->departments = new ObjectStorage();
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

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    public function getWebsite(): string
    {
        return $this->website;
    }

    public function setWebsite(string $website): void
    {
        $this->website = $website;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    public function getRoom(): string
    {
        return $this->room;
    }

    public function setRoom(string $room): void
    {
        $this->room = $room;
    }

    public function getFacebook(): string
    {
        return $this->facebook;
    }

    public function setFacebook(string $facebook): void
    {
        $this->facebook = $facebook;
    }

    public function getTwitter(): string
    {
        return $this->twitter;
    }

    public function setTwitter(string $twitter): void
    {
        $this->twitter = $twitter;
    }

    public function getLinkedin(): string
    {
        return $this->linkedin;
    }

    public function setLinkedin(string $linkedin): void
    {
        $this->linkedin = $linkedin;
    }

    public function getInstagram(): string
    {
        return $this->instagram;
    }

    public function setInstagram(string $instagram): void
    {
        $this->instagram = $instagram;
    }

    public function getResearchFocus(): string
    {
        return $this->researchFocus;
    }

    public function setResearchFocus(string $researchFocus): void
    {
        $this->researchFocus = $researchFocus;
    }

    public function getMission(): string
    {
        return $this->mission;
    }

    public function setMission(string $mission): void
    {
        $this->mission = $mission;
    }

    public function getVision(): string
    {
        return $this->vision;
    }

    public function setVision(string $vision): void
    {
        $this->vision = $vision;
    }

    public function getHeadPerson(): ?Person
    {
        return $this->headPerson;
    }

    public function setHeadPerson(?Person $headPerson): void
    {
        $this->headPerson = $headPerson;
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
}
