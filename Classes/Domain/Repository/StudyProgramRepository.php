<?php

declare(strict_types=1);

namespace EtfUnsa\SparkAcademics\Domain\Repository;

class StudyProgramRepository extends AbstractRepository
{
    protected array $searchFields = ['title', 'acronym'];
}
