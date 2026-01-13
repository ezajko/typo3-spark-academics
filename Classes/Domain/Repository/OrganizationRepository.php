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
 * Repository for Organization entities
 * 
 * @author Ernedin Zajko <ezajko@root.ba>
 */
class OrganizationRepository extends AbstractRepository
{
    /** @var array<string> Fields to search in */
    protected array $searchFields = ['title', 'acronym', 'description'];

    /** @var array<string, string> Default ordering */
    protected $defaultOrderings = [
        'sorting' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING,
    ];
}
