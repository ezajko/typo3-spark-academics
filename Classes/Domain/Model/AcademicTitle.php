<?php

declare(strict_types=1);

namespace EtfUnsa\SparkAcademics\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * AcademicTitle Model
 * Represents academic titles/degrees (e.g., Dr., Prof., Mr., BSc)
 * 
 * @author Ernedin Zajko <ezajko@root.ba>
 */
class AcademicTitle extends AbstractEntity
{
    /**
     * Full title (e.g., "Doktor nauka")
     */
    protected string $title = '';

    /**
     * Abbreviation used before name (e.g., "Dr.", "Prof. dr.")
     */
    protected string $abbreviation = '';

    /**
     * Abbreviation used after name (e.g., "PhD", "MSc", "BSc")
     */
    protected string $abbreviationAfter = '';

    /**
     * English title (e.g., "Doctor of Philosophy")
     */
    protected string $titleEn = '';

    /**
     * Description or notes
     */
    protected string $description = '';

    /**
     * Sorting order
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

    public function getAbbreviationAfter(): string
    {
        return $this->abbreviationAfter;
    }

    public function setAbbreviationAfter(string $abbreviationAfter): void
    {
        $this->abbreviationAfter = $abbreviationAfter;
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
     * Returns formatted name with title prefix and suffix
     */
    public function formatName(string $firstName, string $lastName): string
    {
        $parts = [];
        if (!empty($this->abbreviation)) {
            $parts[] = $this->abbreviation;
        }
        $parts[] = $firstName;
        $parts[] = $lastName;
        if (!empty($this->abbreviationAfter)) {
            $parts[] = ', ' . $this->abbreviationAfter;
        }
        return implode(' ', $parts);
    }
}
