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
use TYPO3\CMS\Extbase\Domain\Model\FileReference;

/**
 * Sustainable Development Goal (UN SDG)
 * 
 * @author Ernedin Zajko <ezajko@root.ba>
 */
class SDG extends AbstractEntity
{
    /** @var int SDG number (1-17) */
    protected int $number = 0;

    /** @var string Goal title */
    protected string $title = '';

    /** @var string Goal description */
    protected string $description = '';

    /** @var FileReference|null SDG icon */
    protected ?FileReference $icon = null;

    public function getNumber(): int
    {
        return $this->number;
    }

    public function setNumber(int $number): void
    {
        $this->number = $number;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getIcon(): ?FileReference
    {
        return $this->icon;
    }

    public function setIcon(?FileReference $icon): void
    {
        $this->icon = $icon;
    }
}
