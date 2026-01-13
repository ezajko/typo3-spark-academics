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
 * Repository for Course entities
 * 
 * Supports filtering by organization, study cycle, category, and scientific field.
 * Text search across title, acronym, and code.
 * 
 * @author Ernedin Zajko <ezajko@root.ba>
 */
class CourseRepository extends AbstractRepository
{
    /**
     * Fields to search in for text queries
     */
    protected array $searchFields = [
        'title',
        'acronym',
        'code',
    ];

    /**
     * Default ordering: by title
     */
    protected $defaultOrderings = [
        'title' => QueryInterface::ORDER_ASCENDING,
    ];
}
