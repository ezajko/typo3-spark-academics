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

/**
 * Repository for PersonType entities
 * 
 * @author Ernedin Zajko <ezajko@root.ba>
 */
class PersonTypeRepository extends AbstractRepository
{
    /**
     * Fields to search in for text queries
     */
    protected array $searchFields = [
        'title',
        'abbreviation',
    ];
}
