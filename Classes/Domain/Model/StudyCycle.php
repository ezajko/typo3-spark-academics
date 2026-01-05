<?php

declare(strict_types=1);

namespace EtfUnsa\SparkAcademics\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * Study Cycle (Bologna I/II/III)
 * 
 * @author Ernedin Zajko <ezajko@root.ba>
 */
class StudyCycle extends AbstractEntity
{
    /** @var string Cycle title */
    protected string $title = '';

    /** @var int Cycle level (1, 2, 3) */
    protected int $level = 1;

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

    public function getLevel(): int
    {
        return $this->level;
    }

    public function setLevel(int $level): void
    {
        $this->level = $level;
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
