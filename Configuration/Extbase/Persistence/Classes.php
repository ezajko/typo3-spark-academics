<?php

declare(strict_types=1);

return [
    \EtfUnsa\SparkAcademics\Domain\Model\Person::class => [
        'tableName' => 'tx_spark_person',
        'properties' => [
            'firstName' => [
                'fieldName' => 'first_name'
            ],
            'lastName' => [
                'fieldName' => 'last_name'
            ],
            'beUsers' => [
                'fieldName' => 'be_users'
            ],
        ],
    ],
];
