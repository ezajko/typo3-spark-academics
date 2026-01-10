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
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class CourseGroup extends AbstractEntity
{
    protected string $title = '';
    protected string $type = 'mandatory'; // mandatory, elective
    protected int $requiredCounts = 0;
    protected string $color = '';

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EtfUnsa\SparkAcademics\Domain\Model\Course>
     */
    protected ?ObjectStorage $courses = null;

    public function __construct()
    {
        $this->courses = new ObjectStorage();
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getRequiredCounts(): int
    {
        return $this->requiredCounts;
    }

    public function setRequiredCounts(int $requiredCounts): void
    {
        $this->requiredCounts = $requiredCounts;
    }

    public function getColor(): string
    {
        return $this->color;
    }

    public function setColor(string $color): void
    {
        $this->color = $color;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EtfUnsa\SparkAcademics\Domain\Model\Course>
     */
    public function getCourses(): ?ObjectStorage
    {
        return $this->courses;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EtfUnsa\SparkAcademics\Domain\Model\Course> $courses
     */
    public function setCourses(ObjectStorage $courses): void
    {
        $this->courses = $courses;
    }
}
