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
 * Repository for Person entities
 * 
 * Supports filtering by organization, academic rank/title, and text search
 * across name and email fields.
 * 
 * @author Ernedin Zajko <ezajko@root.ba>
 */
class PersonRepository extends AbstractRepository
{
    /**
     * Fields to search in for text queries
     */
    protected array $searchFields = [
        'firstName',
        'lastName',
        'contactEmail',
    ];

    /**
     * Default ordering: by last name, then first name
     */
    protected $defaultOrderings = [
        'lastName' => QueryInterface::ORDER_ASCENDING,
        'firstName' => QueryInterface::ORDER_ASCENDING,
    ];
}
