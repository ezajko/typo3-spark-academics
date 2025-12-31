<?php

declare(strict_types=1);

namespace EtfUnsa\SparkAcademics\Domain\Model\Dto;

class PersonDemand
{
    protected string $search = '';
    protected int $academicTitle = 0;
    protected int $academicRank = 0;
    protected int $department = 0;
    protected int $backendUser = 0;

    public function getSearch(): string
    {
        return $this->search;
    }

    public function setSearch(string $search): void
    {
        $this->search = $search;
    }

    public function getAcademicTitle(): int
    {
        return $this->academicTitle;
    }

    public function setAcademicTitle(int $academicTitle): void
    {
        $this->academicTitle = $academicTitle;
    }

    public function getAcademicRank(): int
    {
        return $this->academicRank;
    }

    public function setAcademicRank(int $academicRank): void
    {
        $this->academicRank = $academicRank;
    }

    public function getDepartment(): int
    {
        return $this->department;
    }

    public function setDepartment(int $department): void
    {
        $this->department = $department;
    }

    public function getBackendUser(): int
    {
        return $this->backendUser;
    }

    public function setBackendUser(int $backendUser): void
    {
        $this->backendUser = $backendUser;
    }
}
