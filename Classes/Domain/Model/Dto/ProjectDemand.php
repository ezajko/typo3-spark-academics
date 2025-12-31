<?php

declare(strict_types=1);

namespace EtfUnsa\SparkAcademics\Domain\Model\Dto;

class ProjectDemand extends Demand
{
    protected ?string $search = null;
    protected ?int $projectStatus = null;
    protected ?int $projectType = null;
    protected ?int $fundingProgram = null;
    protected ?int $scientificField = null;
    protected ?int $department = null;
    protected ?int $researchLab = null;
    protected ?int $researchGroup = null;
    protected ?int $chair = null;
    protected ?\DateTime $dateFrom = null;
    protected ?\DateTime $dateTo = null;

    public function getSearch(): ?string
    {
        return $this->search;
    }

    public function setSearch(?string $search): void
    {
        $this->search = $search;
    }

    public function getProjectStatus(): ?int
    {
        return $this->projectStatus;
    }

    public function setProjectStatus(?int $projectStatus): void
    {
        $this->projectStatus = $projectStatus;
    }

    public function getProjectType(): ?int
    {
        return $this->projectType;
    }

    public function setProjectType(?int $projectType): void
    {
        $this->projectType = $projectType;
    }

    public function getFundingProgram(): ?int
    {
        return $this->fundingProgram;
    }

    public function setFundingProgram(?int $fundingProgram): void
    {
        $this->fundingProgram = $fundingProgram;
    }

    public function getScientificField(): ?int
    {
        return $this->scientificField;
    }

    public function setScientificField(?int $scientificField): void
    {
        $this->scientificField = $scientificField;
    }

    public function getDateFrom(): ?\DateTime
    {
        return $this->dateFrom;
    }

    public function setDateFrom(?\DateTime $dateFrom): void
    {
        $this->dateFrom = $dateFrom;
    }

    public function getDateTo(): ?\DateTime
    {
        return $this->dateTo;
    }

    public function setDateTo(?\DateTime $dateTo): void
    {
        $this->dateTo = $dateTo;
    }

    public function getDepartment(): ?int
    {
        return $this->department;
    }

    public function setDepartment(?int $department): void
    {
        $this->department = $department;
    }

    public function getResearchLab(): ?int
    {
        return $this->researchLab;
    }

    public function setResearchLab(?int $researchLab): void
    {
        $this->researchLab = $researchLab;
    }

    public function getResearchGroup(): ?int
    {
        return $this->researchGroup;
    }

    public function setResearchGroup(?int $researchGroup): void
    {
        $this->researchGroup = $researchGroup;
    }

    public function getChair(): ?int
    {
        return $this->chair;
    }

    public function setChair(?int $chair): void
    {
        $this->chair = $chair;
    }
}
