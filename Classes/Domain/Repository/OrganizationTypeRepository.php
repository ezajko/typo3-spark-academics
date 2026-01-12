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

namespace EtfUnsa\SparkAcademics\Domain\Repository;

/**
 * Repository for OrganizationType entities
 * 
 * @author Ernedin Zajko <ezajko@root.ba>
 */
class OrganizationTypeRepository extends AbstractRepository
{
    /** @var array<string> Fields to search in */
    protected array $searchFields = ['title'];

    /** @var array<string, string> Default ordering */
    protected $defaultOrderings = [
        'title' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING,
    ];
}
