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

namespace RootBa\Academics\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\QueryInterface;

/**
 * Repository for StudyProgram entities
 * 
 * Supports filtering by organization, cycle, type, mode, and language.
 * Text search across title, acronym, and description.
 * 
 * @author Ernedin Zajko <ezajko@root.ba>
 */
class StudyProgramRepository extends AbstractRepository
{
    /**
     * Fields to search in for text queries
     */
    protected array $searchFields = [
        'title',
        'acronym',
        'description',
    ];

    /**
     * Default ordering: by title
     */
    protected $defaultOrderings = [
        'title' => QueryInterface::ORDER_ASCENDING,
    ];
}
