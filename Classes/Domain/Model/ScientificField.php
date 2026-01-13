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
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * ScientificField Model
 * Represents scientific fields/areas of research following OECD FOS (Fields of Science) classification
 * CERIF: cfClass (semantic layer for research domain classification)
 * 
 * OECD FOS Major Fields:
 * 1. Natural Sciences
 * 2. Engineering and Technology
 * 3. Medical and Health Sciences
 * 4. Agricultural and Veterinary Sciences
 * 5. Social Sciences
 * 6. Humanities and the Arts
 * 
 * @author Ernedin Zajko <ezajko@root.ba>
 */
class ScientificField extends AbstractEntity
{
    /**
     * Field title (e.g., "Computer and Information Sciences")
     */
    protected string $title = '';

    /**
     * Field description
     */
    protected string $description = '';

    /**
     * OECD FOS classification code (e.g., "1.2" for Computer Sciences)
     * Format: Major.Minor (e.g., "1", "1.1", "1.2")
     */
    protected string $code = '';

    /**
     * Hierarchy level (1 = major field, 2 = minor/sub-field)
     */
    protected int $level = 1;

    /**
     * Parent field for hierarchical structure
     * NULL for top-level (major) fields
     */
    protected ?ScientificField $parent = null;

    /**
     * Child fields (sub-fields)
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RootBa\Academics\Domain\Model\ScientificField>
     */
    protected ?ObjectStorage $children = null;

    /**
     * UUID for unique identification
     */
    protected string $uuid = '';

    public function __construct()
    {
        $this->children = new ObjectStorage();
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

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function setLevel(int $level): void
    {
        $this->level = $level;
    }

    public function getParent(): ?ScientificField
    {
        return $this->parent;
    }

    public function setParent(?ScientificField $parent): void
    {
        $this->parent = $parent;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RootBa\Academics\Domain\Model\ScientificField>
     */
    public function getChildren(): ?ObjectStorage
    {
        return $this->children;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RootBa\Academics\Domain\Model\ScientificField> $children
     */
    public function setChildren(ObjectStorage $children): void
    {
        $this->children = $children;
    }

    public function addChild(ScientificField $child): void
    {
        $this->children->attach($child);
    }

    public function removeChild(ScientificField $child): void
    {
        $this->children->detach($child);
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): void
    {
        $this->uuid = $uuid;
    }

    /**
     * Returns full hierarchical path (e.g., "Natural Sciences > Computer Sciences")
     */
    public function getFullPath(): string
    {
        if ($this->parent !== null) {
            return $this->parent->getFullPath() . ' > ' . $this->title;
        }
        return $this->title;
    }

    /**
     * Check if this is a major (top-level) field
     */
    public function isMajorField(): bool
    {
        return $this->level === 1;
    }

    /**
     * Check if this is a minor (sub-level) field
     */
    public function isMinorField(): bool
    {
        return $this->level > 1;
    }
}
