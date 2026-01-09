<?php

declare(strict_types=1);

namespace EtfUnsa\SparkAcademics\Domain\Model;

use TYPO3\CMS\Beuser\Domain\Model\BackendUser;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use EtfUnsa\SparkAcademics\Domain\Model\Publication; // Added import

/**
 * Person domain model
 * Represents an academic person (staff member, researcher, etc.)
 *
 * @author Ernedin Zajko <ezajko@root.ba>
 */
class Person extends AbstractEntity
{
    // =========================================================================
    // Basic Information
    // =========================================================================
    
    /** @var string First name of the person */
    protected string $firstName = '';
    
    /** @var string Last name of the person */
    protected string $lastName = '';
    
    /** @var string URL path/slug for the person */
    protected string $path = '';
    
    /** @var int Gender (0=not specified, 1=male, 2=female, 9=other) */
    protected int $gender = 0;

    // =========================================================================
    // Academic Affiliation
    // =========================================================================
    
    /** @var Department|null Primary department affiliation */
    protected ?Department $primaryDepartment = null;
    
    /** @var AcademicTitle|null Academic title (e.g., Dr., Prof.) */
    protected ?AcademicTitle $academicTitle = null;
    
    /** @var AcademicRank|null Academic rank (e.g., Full Professor, Associate Professor) */
    protected ?AcademicRank $academicRank = null;

    // =========================================================================
    // Biography & Media
    // =========================================================================
    
    /** @var string Biography text (RTE content) */
    protected string $biography = '';
    
    /** @var FileReference|null Profile image */
    protected ?FileReference $mediaImage = null;
    
    /** @var FileReference|null Biography PDF file */
    protected ?FileReference $biographyFilePdf = null;

    // =========================================================================
    // Contact Information
    // =========================================================================
    
    /** @var string Office location */
    protected string $contactOffice = '';
    
    /** @var string Office phone number */
    protected string $phoneOffice = '';
    
    /** @var string Mobile phone number */
    protected string $phoneMobile = '';
    
    /** @var string Email address */
    protected string $contactEmail = '';
    
    /** @var string Personal website URL */
    protected string $contactWebsite = '';

    // =========================================================================
    // Education (IRRE relation)
    // =========================================================================
    
    /**
     * Education entries - IRRE relation to PersonEducation
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EtfUnsa\SparkAcademics\Domain\Model\PersonEducation>
     */
    protected ?ObjectStorage $education = null;

    // =========================================================================
    // Mentoring (IRRE relation)
    // =========================================================================
    
    /**
     * Mentored students - IRRE relation to PersonMentoring
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EtfUnsa\SparkAcademics\Domain\Model\PersonMentoring>
     */
    protected ?ObjectStorage $mentoring = null;

    // =========================================================================
    // Research & Teaching
    // =========================================================================
    
    /** @var string Research interests description */
    protected string $researchInterests = '';
    
    /** @var string Consultation hours (RTE content) */
    protected string $consultationHours = '';

    // =========================================================================
    // Academic Profiles & Identifiers
    // =========================================================================
    
    /** @var string Google Scholar profile ID */
    protected string $profileGoogleScholar = '';
    
    /** @var string ResearchGate profile ID */
    protected string $profileResearchGate = '';
    
    /** @var string GitHub username */
    protected string $profileGithub = '';
    
    /** @var string ORCID identifier */
    protected string $profileOrcid = '';
    
    /** @var string LinkedIn profile ID */
    protected string $profileLinkedin = '';
    
    /** @var string Scopus Author ID */
    protected string $scopusId = '';
    
    /** @var string Web of Science Researcher ID */
    protected string $researcherId = '';

    // =========================================================================
    // Backend User Association
    // =========================================================================
    
    /**
     * Associated backend users (M:N relation)
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<BackendUser>
     */
    protected $beUsers = null;

    // =========================================================================
    // Organizational Relations (read-only, managed from other entities)
    // =========================================================================
    
    /**
     * Departments this person belongs to
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EtfUnsa\SparkAcademics\Domain\Model\Department>
     */
    protected ?ObjectStorage $departments = null;

    /**
     * Research laboratories
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EtfUnsa\SparkAcademics\Domain\Model\ResearchLab>
     */
    protected ?ObjectStorage $laboratories = null;

    /**
     * Research groups
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EtfUnsa\SparkAcademics\Domain\Model\ResearchGroup>
     */
    protected ?ObjectStorage $groups = null;

    /**
     * Chairs (Katedre)
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EtfUnsa\SparkAcademics\Domain\Model\Chair>
     */
    protected ?ObjectStorage $chairs = null;

    /**
     * Courses taught
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EtfUnsa\SparkAcademics\Domain\Model\Course>
     */
    protected ?ObjectStorage $courses = null;

    /**
     * Study programs
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EtfUnsa\SparkAcademics\Domain\Model\StudyProgram>
     */
    protected ?ObjectStorage $studyPrograms = null;

    /**
     * Projects
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EtfUnsa\SparkAcademics\Domain\Model\Project>
     */
    protected ?ObjectStorage $projects = null;

    /**
     * Publications (M:N)
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EtfUnsa\SparkAcademics\Domain\Model\Publication>
     */
    protected ?ObjectStorage $publications = null;

    // =========================================================================
    // Constructor
    // =========================================================================

    public function __construct()
    {
        $this->beUsers = new ObjectStorage();
        $this->education = new ObjectStorage();
        $this->mentoring = new ObjectStorage();
        $this->departments = new ObjectStorage();
        $this->laboratories = new ObjectStorage();
        $this->groups = new ObjectStorage();
        $this->chairs = new ObjectStorage();
        $this->courses = new ObjectStorage();
        $this->studyPrograms = new ObjectStorage();
        $this->projects = new ObjectStorage();
        $this->publications = new ObjectStorage();
    }

    // =========================================================================
    // Basic Information Getters/Setters
    // =========================================================================

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

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    public function getGender(): int
    {
        return $this->gender;
    }

    public function setGender(int $gender): void
    {
        $this->gender = $gender;
    }

    // =========================================================================
    // Academic Affiliation Getters/Setters
    // =========================================================================

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

    // =========================================================================
    // Biography & Media Getters/Setters
    // =========================================================================

    public function getBiography(): string
    {
        return $this->biography;
    }

    public function setBiography(string $biography): void
    {
        $this->biography = $biography;
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

    // =========================================================================
    // Contact Information Getters/Setters
    // =========================================================================

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

    // =========================================================================
    // Education Getters/Setters
    // =========================================================================

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EtfUnsa\SparkAcademics\Domain\Model\PersonEducation>
     */
    public function getEducation(): ?ObjectStorage
    {
        return $this->education;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EtfUnsa\SparkAcademics\Domain\Model\PersonEducation> $education
     */
    public function setEducation(ObjectStorage $education): void
    {
        $this->education = $education;
    }

    // =========================================================================
    // Mentoring Getters/Setters
    // =========================================================================

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EtfUnsa\SparkAcademics\Domain\Model\PersonMentoring>
     */
    public function getMentoring(): ?ObjectStorage
    {
        return $this->mentoring;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EtfUnsa\SparkAcademics\Domain\Model\PersonMentoring> $mentoring
     */
    public function setMentoring(ObjectStorage $mentoring): void
    {
        $this->mentoring = $mentoring;
    }

    // =========================================================================
    // Research & Teaching Getters/Setters
    // =========================================================================

    public function getResearchInterests(): string
    {
        return $this->researchInterests;
    }

    public function setResearchInterests(string $researchInterests): void
    {
        $this->researchInterests = $researchInterests;
    }

    public function getConsultationHours(): string
    {
        return $this->consultationHours;
    }

    public function setConsultationHours(string $consultationHours): void
    {
        $this->consultationHours = $consultationHours;
    }

    // =========================================================================
    // Academic Profiles Getters/Setters
    // =========================================================================

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

    public function getScopusId(): string
    {
        return $this->scopusId;
    }

    public function setScopusId(string $scopusId): void
    {
        $this->scopusId = $scopusId;
    }

    public function getResearcherId(): string
    {
        return $this->researcherId;
    }

    public function setResearcherId(string $researcherId): void
    {
        $this->researcherId = $researcherId;
    }

    // =========================================================================
    // Backend User Getters/Setters
    // =========================================================================

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

    // =========================================================================
    // Organizational Relations Getters/Setters
    // =========================================================================

    public function getDepartments(): ?ObjectStorage
    {
        return $this->departments;
    }

    public function setDepartments(ObjectStorage $departments): void
    {
        $this->departments = $departments;
    }

    public function getLaboratories(): ?ObjectStorage
    {
        return $this->laboratories;
    }

    public function setLaboratories(ObjectStorage $laboratories): void
    {
        $this->laboratories = $laboratories;
    }

    public function getGroups(): ?ObjectStorage
    {
        return $this->groups;
    }

    public function setGroups(ObjectStorage $groups): void
    {
        $this->groups = $groups;
    }

    public function getChairs(): ?ObjectStorage
    {
        return $this->chairs;
    }

    public function setChairs(ObjectStorage $chairs): void
    {
        $this->chairs = $chairs;
    }

    public function getCourses(): ?ObjectStorage
    {
        return $this->courses;
    }

    public function setCourses(ObjectStorage $courses): void
    {
        $this->courses = $courses;
    }

    public function getStudyPrograms(): ?ObjectStorage
    {
        return $this->studyPrograms;
    }

    public function setStudyPrograms(ObjectStorage $studyPrograms): void
    {
        $this->studyPrograms = $studyPrograms;
    }

    public function getProjects(): ?ObjectStorage
    {
        return $this->projects;
    }

    public function setProjects(ObjectStorage $projects): void
    {
        $this->projects = $projects;
    }

    public function getPublications(): ?ObjectStorage
    {
        return $this->publications;
    }

    public function setPublications(ObjectStorage $publications): void
    {
        $this->publications = $publications;
    }

    public function addPublication(Publication $publication): void
    {
        $this->publications->attach($publication);
    }

    public function removePublication(Publication $publication): void
    {
        $this->publications->detach($publication);
    }

    /**
     * Get publications sorted by Featured first, then Year descending
     * @return array<Publication>
     */
    public function getSortedPublications(): array
    {
        if ($this->publications === null) {
            return [];
        }

        $publicationsArray = $this->publications->toArray();
        
        usort($publicationsArray, function (Publication $a, Publication $b) {
            // 1. Featured first
            if ($a->isFeatured() !== $b->isFeatured()) {
                return $b->isFeatured() <=> $a->isFeatured(); // True (1) before False (0)
            }
            
            // 2. Year descending (newest first)
            if ($a->getPublicationYear() !== $b->getPublicationYear()) {
                return $b->getPublicationYear() <=> $a->getPublicationYear();
            }

            // 3. Fallback: Title
            return strcasecmp($a->getTitle(), $b->getTitle());
        });

        return $publicationsArray;
    }
}
