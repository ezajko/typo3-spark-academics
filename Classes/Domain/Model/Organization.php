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
use TYPO3\CMS\Extbase\Domain\Model\FileReference;

class Organization extends AbstractEntity
{
    protected string $title = '';
    protected string $acronym = '';
    protected string $uuid = '';
    protected string $description = '';
    
    // Links & Contact
    protected string $email = '';
    protected string $phone = '';
    protected string $website = '';
    protected string $address = '';
    protected string $room = '';
    
    // Social Media
    protected string $facebook = '';
    protected string $twitter = '';
    protected string $linkedin = '';
    protected string $instagram = '';
    
    // Detailed Info
    protected string $researchFocus = '';
    protected string $mission = '';
    protected string $vision = '';
    
    // Page Links
    protected int $landingPage = 0;    // Internal TYPO3 page for organization presentation
    protected string $homePage = '';   // External homepage URL (organization's own website)
    
    // Relations
    protected ?OrganizationType $type = null;
    protected ?Organization $parent = null;
    protected ?Person $headPerson = null;
    
    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RootBa\Academics\Domain\Model\Organization>
     */
    protected ?ObjectStorage $children = null;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RootBa\Academics\Domain\Model\Person>
     */
    protected ?ObjectStorage $primaryMembers = null;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RootBa\Academics\Domain\Model\Person>
     */
    protected ?ObjectStorage $secondaryMembers = null;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RootBa\Academics\Domain\Model\Course>
     */
    protected ?ObjectStorage $courses = null;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RootBa\Academics\Domain\Model\Project>
     */
    protected ?ObjectStorage $projects = null;

    protected ?FileReference $logo = null;
    protected ?FileReference $mainImage = null;

    public function __construct()
    {
        $this->children = new ObjectStorage();
        $this->primaryMembers = new ObjectStorage();
        $this->secondaryMembers = new ObjectStorage();
        $this->courses = new ObjectStorage();
        $this->projects = new ObjectStorage();
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
    
    public function getType(): ?OrganizationType
    {
        return $this->type;
    }

    public function setType(?OrganizationType $type): void
    {
        $this->type = $type;
    }

    public function getParent(): ?Organization
    {
        return $this->parent;
    }

    public function setParent(?Organization $parent): void
    {
        $this->parent = $parent;
    }
    
    public function getChildren(): ?ObjectStorage
    {
        return $this->children;
    }

    public function setChildren(ObjectStorage $children): void
    {
        $this->children = $children;
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

    public function getLandingPage(): int
    {
        return $this->landingPage;
    }

    public function setLandingPage(int $landingPage): void
    {
        $this->landingPage = $landingPage;
    }

    public function getHomePage(): string
    {
        return $this->homePage;
    }

    public function setHomePage(string $homePage): void
    {
        $this->homePage = $homePage;
    }

    public function getHeadPerson(): ?Person
    {
        return $this->headPerson;
    }

    public function setHeadPerson(?Person $headPerson): void
    {
        $this->headPerson = $headPerson;
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
    
    public function getPrimaryMembers(): ?ObjectStorage
    {
        return $this->primaryMembers;
    }

    public function setPrimaryMembers(ObjectStorage $primaryMembers): void
    {
        $this->primaryMembers = $primaryMembers;
    }

    public function getSecondaryMembers(): ?ObjectStorage
    {
        return $this->secondaryMembers;
    }

    public function setSecondaryMembers(ObjectStorage $secondaryMembers): void
    {
        $this->secondaryMembers = $secondaryMembers;
    }

    public function getCourses(): ?ObjectStorage
    {
        return $this->courses;
    }

    public function setCourses(ObjectStorage $courses): void
    {
        $this->courses = $courses;
    }

    public function getProjects(): ?ObjectStorage
    {
        return $this->projects;
    }

    public function setProjects(ObjectStorage $projects): void
    {
        $this->projects = $projects;
    }
}
