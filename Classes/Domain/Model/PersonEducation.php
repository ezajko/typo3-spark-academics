<?php

declare(strict_types=1);

namespace EtfUnsa\SparkAcademics\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class PersonEducation extends AbstractEntity
{
    protected string $year = '';
    protected string $qualification = '';
    protected string $institution = '';

    public function getYear(): string
    {
        return $this->year;
    }

    public function setYear(string $year): void
    {
        $this->year = $year;
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
}
