<?php

declare(strict_types=1);

namespace EtfUnsa\SparkAcademics\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * Course Status (Active, Inactive, Archived)
 * 
 * @author Ernedin Zajko <ezajko@root.ba>
 */
class CourseStatus extends AbstractEntity
{
    /** @var string Status title */
    protected string $title = '';

    /** @var string Status code */
    protected string $code = '';

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
}
