<?php

declare(strict_types=1);

namespace EtfUnsa\SparkAcademics\Domain\Model\Dto;

class Demand
{
    protected array $filters = [];
    protected string $logicalOperator = 'AND'; // OR | AND
    protected string $search = '';

    public function addFilter(string $propertyName, $value, string $operator = 'equals'): self
    {
        $this->filters[] = [
            'property' => $propertyName,
            'value' => $value,
            'operator' => $operator
        ];
        return $this;
    }

    public function getFilters(): array
    {
        return $this->filters;
    }

    public function setLogicalOperator(string $operator): self
    {
        $this->logicalOperator = strtoupper($operator);
        return $this;
    }

    public function getLogicalOperator(): string
    {
        return $this->logicalOperator;
    }

    public function hasFilters(): bool
    {
        return !empty($this->filters) || !empty($this->search);
    }

    public function setSearch(string $search): self
    {
        $this->search = $search;
        return $this;
    }

    public function getSearch(): string
    {
        return $this->search;
    }
}
