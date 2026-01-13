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
 * Repository for Project entities
 * 
 * Supports filtering by status, type, funding, organization and date range.
 * Text search across title, acronym, description, and keywords.
 * 
 * @author Ernedin Zajko <ezajko@root.ba>
 */
class ProjectRepository extends AbstractRepository
{
    /**
     * Fields to search in for text queries
     */
    protected array $searchFields = [
        'title',
        'acronym',
        'description',
        'keywords',
        'grantAgreementNumber',
    ];

    /**
     * Default ordering: by sorting field
     */
    protected $defaultOrderings = [
        'sorting' => QueryInterface::ORDER_ASCENDING,
    ];
}
