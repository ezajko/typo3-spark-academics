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
 * PersonType Model
 * Represents staff types (internal, visiting professor, guest, etc.)
 * 
 * @author Ernedin Zajko <ezajko@root.ba>
 */
class PersonType extends AbstractEntity
{
    /**
     * Full title of the type (e.g., "Visiting Professor")
     */
    protected string $title = '';

    /**
     * Abbreviation (e.g., "VP")
     */
    protected string $abbreviation = '';

    /**
     * English title
     */
    protected string $titleEn = '';

    /**
     * Description or notes about this type
     */
    protected string $description = '';

    /**
     * Whether this type represents external staff
     */
    protected bool $isExternal = false;

    /**
     * Sorting order
     */
    protected int $sorting = 0;

    // =========================================================================
    // Getters and Setters
    // =========================================================================

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

    public function getIsExternal(): bool
    {
        return $this->isExternal;
    }

    public function setIsExternal(bool $isExternal): void
    {
        $this->isExternal = $isExternal;
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
