<?php

declare(strict_types=1);

namespace EtfUnsa\SparkAcademics\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * Course Category (difficulty level)
 * 
 * @author Ernedin Zajko <ezajko@root.ba>
 */
class CourseCategory extends AbstractEntity
{
    /** @var string Category title */
    protected string $title = '';

    /** @var string Category code */
    protected string $code = '';

    /** @var string Description */
    protected string $description = '';

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }
}
