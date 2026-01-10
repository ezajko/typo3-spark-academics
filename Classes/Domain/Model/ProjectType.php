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
 * ProjectType Model
 * Represents project classification types (e.g., Research, Development, Infrastructure)
 * CERIF: cfProj_Class semantic layer
 * 
 * @author Ernedin Zajko <ezajko@root.ba>
 */
class ProjectType extends AbstractEntity
{
    /**
     * Project type title (e.g., "Research Project", "Development", "Infrastructure")
     */
    protected string $title = '';

    /**
     * Optional description of the project type
     */
    protected string $description = '';

    /**
     * Optional color for visual distinction in UI
     */
    protected string $color = '';

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

    public function getColor(): string
    {
        return $this->color;
    }

    public function setColor(string $color): void
    {
        $this->color = $color;
    }
}
