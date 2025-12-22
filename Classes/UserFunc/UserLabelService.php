<?php

declare(strict_types=1);

namespace EtfUnsa\SparkAcademics\UserFunc;

class UserLabelService
{
    public function getPersonLabel(array &$parameters): void
    {
        $record = $parameters['row'];
        $label = $record['first_name'] . ' ' . $record['last_name'];
        
        // If it's a new record or empty
        if (trim($label) === '') {
            $label = 'New Person';
        }

        $parameters['title'] = $label;
    }
}
