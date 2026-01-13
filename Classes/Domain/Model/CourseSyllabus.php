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

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use DateTime;

/**
 * Course Syllabus - versioned syllabus for a Course
 * Similar to DocumentVersion for Document in spark-dms
 * 
 * @author Ernedin Zajko <ezajko@root.ba>
 */
class CourseSyllabus extends AbstractEntity
{
    // Version Info
    protected string $uuid = '';
    protected string $versionLabel = '';
    protected string $academicYear = '';
    protected ?DateTime $validFrom = null;
    protected ?Course $course = null;

    // Classification
    protected ?ScientificField $scientificField = null;
    protected ?CourseCategory $courseCategory = null;
    protected ?StudyCycle $studyCycle = null;
    protected ?CourseStatus $courseStatus = null;
    protected ?Language $language = null;
    protected int $ects = 0;

    // Contact Hours
    protected int $hoursLecture = 0;
    protected int $hoursExercise = 0;
    protected int $hoursSeminar = 0;
    protected int $hoursLab = 0;
    protected int $hoursPractice = 0;
    protected int $hoursTotal = 0;
    protected int $hoursSelfStudy = 0;

    // Learning
    protected string $courseObjectives = '';
    protected string $thematicUnits = '';
    protected string $outcomesKnowledge = '';
    protected string $outcomesSkills = '';
    protected string $outcomesCompetencies = '';

    // Methods
    /** @var ObjectStorage<SDG> */
    protected ?ObjectStorage $sdgGoals = null;

    /** @var ObjectStorage<TeachingMethod> */
    protected ?ObjectStorage $teachingMethods = null;

    protected string $assessmentMethods = '';

    // Prerequisites
    protected string $prerequisitesDescription = '';

    /** @var ObjectStorage<Course> */
    protected ?ObjectStorage $prerequisiteCourses = null;

    // Literature
    protected string $literatureRequired = '';
    protected string $literatureSupplementary = '';

    public function __construct()
    {
        $this->sdgGoals = new ObjectStorage();
        $this->teachingMethods = new ObjectStorage();
        $this->prerequisiteCourses = new ObjectStorage();
    }

    // Version Info
    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): void
    {
        $this->uuid = $uuid;
    }

    public function getVersionLabel(): string
    {
        return $this->versionLabel;
    }

    public function setVersionLabel(string $versionLabel): void
    {
        $this->versionLabel = $versionLabel;
    }

    public function getAcademicYear(): string
    {
        return $this->academicYear;
    }

    public function setAcademicYear(string $academicYear): void
    {
        $this->academicYear = $academicYear;
    }

    public function getValidFrom(): ?DateTime
    {
        return $this->validFrom;
    }

    public function setValidFrom(?DateTime $validFrom): void
    {
        $this->validFrom = $validFrom;
    }

    public function getCourse(): ?Course
    {
        return $this->course;
    }

    public function setCourse(?Course $course): void
    {
        $this->course = $course;
    }

    // Classification
    public function getScientificField(): ?ScientificField
    {
        return $this->scientificField;
    }

    public function setScientificField(?ScientificField $scientificField): void
    {
        $this->scientificField = $scientificField;
    }

    public function getCourseCategory(): ?CourseCategory
    {
        return $this->courseCategory;
    }

    public function setCourseCategory(?CourseCategory $courseCategory): void
    {
        $this->courseCategory = $courseCategory;
    }

    public function getStudyCycle(): ?StudyCycle
    {
        return $this->studyCycle;
    }

    public function setStudyCycle(?StudyCycle $studyCycle): void
    {
        $this->studyCycle = $studyCycle;
    }

    public function getCourseStatus(): ?CourseStatus
    {
        return $this->courseStatus;
    }

    public function setCourseStatus(?CourseStatus $courseStatus): void
    {
        $this->courseStatus = $courseStatus;
    }

    public function getLanguage(): ?Language
    {
        return $this->language;
    }

    public function setLanguage(?Language $language): void
    {
        $this->language = $language;
    }

    public function getEcts(): int
    {
        return $this->ects;
    }

    public function setEcts(int $ects): void
    {
        $this->ects = $ects;
    }

    // Contact Hours
    public function getHoursLecture(): int
    {
        return $this->hoursLecture;
    }

    public function setHoursLecture(int $hoursLecture): void
    {
        $this->hoursLecture = $hoursLecture;
    }

    public function getHoursExercise(): int
    {
        return $this->hoursExercise;
    }

    public function setHoursExercise(int $hoursExercise): void
    {
        $this->hoursExercise = $hoursExercise;
    }

    public function getHoursSeminar(): int
    {
        return $this->hoursSeminar;
    }

    public function setHoursSeminar(int $hoursSeminar): void
    {
        $this->hoursSeminar = $hoursSeminar;
    }

    public function getHoursLab(): int
    {
        return $this->hoursLab;
    }

    public function setHoursLab(int $hoursLab): void
    {
        $this->hoursLab = $hoursLab;
    }

    public function getHoursPractice(): int
    {
        return $this->hoursPractice;
    }

    public function setHoursPractice(int $hoursPractice): void
    {
        $this->hoursPractice = $hoursPractice;
    }

    public function getHoursTotal(): int
    {
        return $this->hoursTotal;
    }

    public function setHoursTotal(int $hoursTotal): void
    {
        $this->hoursTotal = $hoursTotal;
    }

    public function getHoursSelfStudy(): int
    {
        return $this->hoursSelfStudy;
    }

    public function setHoursSelfStudy(int $hoursSelfStudy): void
    {
        $this->hoursSelfStudy = $hoursSelfStudy;
    }

    // Learning
    public function getCourseObjectives(): string
    {
        return $this->courseObjectives;
    }

    public function setCourseObjectives(string $courseObjectives): void
    {
        $this->courseObjectives = $courseObjectives;
    }

    public function getThematicUnits(): string
    {
        return $this->thematicUnits;
    }

    public function setThematicUnits(string $thematicUnits): void
    {
        $this->thematicUnits = $thematicUnits;
    }

    public function getOutcomesKnowledge(): string
    {
        return $this->outcomesKnowledge;
    }

    public function setOutcomesKnowledge(string $outcomesKnowledge): void
    {
        $this->outcomesKnowledge = $outcomesKnowledge;
    }

    public function getOutcomesSkills(): string
    {
        return $this->outcomesSkills;
    }

    public function setOutcomesSkills(string $outcomesSkills): void
    {
        $this->outcomesSkills = $outcomesSkills;
    }

    public function getOutcomesCompetencies(): string
    {
        return $this->outcomesCompetencies;
    }

    public function setOutcomesCompetencies(string $outcomesCompetencies): void
    {
        $this->outcomesCompetencies = $outcomesCompetencies;
    }

    // Methods
    /** @return ObjectStorage<SDG> */
    public function getSdgGoals(): ?ObjectStorage
    {
        return $this->sdgGoals;
    }

    public function setSdgGoals(ObjectStorage $sdgGoals): void
    {
        $this->sdgGoals = $sdgGoals;
    }

    /** @return ObjectStorage<TeachingMethod> */
    public function getTeachingMethods(): ?ObjectStorage
    {
        return $this->teachingMethods;
    }

    public function setTeachingMethods(ObjectStorage $teachingMethods): void
    {
        $this->teachingMethods = $teachingMethods;
    }

    public function getAssessmentMethods(): string
    {
        return $this->assessmentMethods;
    }

    public function setAssessmentMethods(string $assessmentMethods): void
    {
        $this->assessmentMethods = $assessmentMethods;
    }

    // Prerequisites
    public function getPrerequisitesDescription(): string
    {
        return $this->prerequisitesDescription;
    }

    public function setPrerequisitesDescription(string $prerequisitesDescription): void
    {
        $this->prerequisitesDescription = $prerequisitesDescription;
    }

    /** @return ObjectStorage<Course> */
    public function getPrerequisiteCourses(): ?ObjectStorage
    {
        return $this->prerequisiteCourses;
    }

    public function setPrerequisiteCourses(ObjectStorage $prerequisiteCourses): void
    {
        $this->prerequisiteCourses = $prerequisiteCourses;
    }

    // Literature
    public function getLiteratureRequired(): string
    {
        return $this->literatureRequired;
    }

    public function setLiteratureRequired(string $literatureRequired): void
    {
        $this->literatureRequired = $literatureRequired;
    }

    public function getLiteratureSupplementary(): string
    {
        return $this->literatureSupplementary;
    }

    public function setLiteratureSupplementary(string $literatureSupplementary): void
    {
        $this->literatureSupplementary = $literatureSupplementary;
    }

    /**
     * Get display label for syllabus version
     */
    public function getDisplayLabel(): string
    {
        if (!empty($this->versionLabel)) {
            return $this->versionLabel . ' (' . $this->academicYear . ')';
        }
        return $this->academicYear;
    }

    /**
     * Check if this is the latest syllabus version
     */
    public function getIsLatest(): bool
    {
        if ($this->course === null) {
            return false;
        }
        $latest = $this->course->getLatestSyllabus();
        return $latest !== null && $latest->getUid() === $this->getUid();
    }
}
