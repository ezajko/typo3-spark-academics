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

namespace RootBa\Academics\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * AcademicRank Model
 * Represents academic/scientific ranks (e.g., Assistant, Docent, Professor)
 * 
 * @author Ernedin Zajko <ezajko@root.ba>
 */
class AcademicRank extends AbstractEntity
{
    /**
     * Full title of the rank (e.g., "Redovni profesor")
     */
    protected string $title = '';

    /**
     * Abbreviation (e.g., "prof.")
     */
    protected string $abbreviation = '';

    /**
     * English title (e.g., "Full Professor")
     */
    protected string $titleEn = '';

    /**
     * Description or notes about this rank
     */
    protected string $description = '';

    /**
     * Sorting order (lower = higher rank)
     */
    protected int $sorting = 0;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getAbbreviation(): string
    {
        return $this->abbreviation;
    }

    public function setAbbreviation(string $abbreviation): void
    {
        $this->abbreviation = $abbreviation;
    }

    public function getTitleEn(): string
    {
        return $this->titleEn;
    }

    public function setTitleEn(string $titleEn): void
    {
        $this->titleEn = $titleEn;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getSorting(): int
    {
        return $this->sorting;
    }

    public function setSorting(int $sorting): void
    {
        $this->sorting = $sorting;
    }

    /**
     * Returns display label with abbreviation if available
     */
    public function getDisplayLabel(): string
    {
        if (!empty($this->abbreviation)) {
            return $this->abbreviation . ' - ' . $this->title;
        }
        return $this->title;
    }
}
