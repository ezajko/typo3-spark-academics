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

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * PersonEducation domain model
 * Represents an education entry (degree, qualification) linked to a Person
 *
 * @author Ernedin Zajko <ezajko@root.ba>
 */
class PersonEducation extends AbstractEntity
{
    /** @var string Year of graduation/completion */
    protected string $year = '';

    /** @var string Type of degree (e.g., Bachelor, Master, PhD) */
    protected string $degreeType = '';

    /** @var string Qualification/degree name (e.g., BSc Computer Science) */
    protected string $qualification = '';

    /** @var string Name of the institution */
    protected string $institution = '';

    /** @var string Field of study / specialization */
    protected string $fieldOfStudy = '';

    /** @var string Thesis title (optional) */
    protected string $thesisTitle = '';

    // =========================================================================
    // Getters/Setters
    // =========================================================================

    public function getYear(): string
    {
        return $this->year;
    }

    public function setYear(string $year): void
    {
        $this->year = $year;
    }

    public function getDegreeType(): string
    {
        return $this->degreeType;
    }

    public function setDegreeType(string $degreeType): void
    {
        $this->degreeType = $degreeType;
    }

    public function getQualification(): string
    {
        return $this->qualification;
    }

    public function setQualification(string $qualification): void
    {
        $this->qualification = $qualification;
    }

    public function getInstitution(): string
    {
        return $this->institution;
    }

    public function setInstitution(string $institution): void
    {
        $this->institution = $institution;
    }

    public function getFieldOfStudy(): string
    {
        return $this->fieldOfStudy;
    }

    public function setFieldOfStudy(string $fieldOfStudy): void
    {
        $this->fieldOfStudy = $fieldOfStudy;
    }

    public function getThesisTitle(): string
    {
        return $this->thesisTitle;
    }

    public function setThesisTitle(string $thesisTitle): void
    {
        $this->thesisTitle = $thesisTitle;
    }
}
