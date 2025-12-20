<?php

/**
 * Extension Manager configuration for spark_academics.
 *
 * @author Ernedin Zajko <ezajko@root.ba>
 */
$EM_CONF[$_EXTKEY] = [
    'title' => 'Spark Academics',
    'description' => 'Academic specific content types and features for Spark',
    'category' => 'templates',
    'author' => 'Ernedin Zajko',
    'author_email' => 'ezajko@root.ba',
    'state' => 'alpha',
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '13.4.0-13.4.99',
            'spark_core' => '1.0.0-1.9.9',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
