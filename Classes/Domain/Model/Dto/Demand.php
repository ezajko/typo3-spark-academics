<?php

declare(strict_types=1);

namespace EtfUnsa\SparkAcademics\Domain\Model\Dto;

class Demand
{
    protected array $filters = [];
    protected string $logicalOperator = 'AND'; // OR | AND

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
        return !empty($this->filters);
    }
}
