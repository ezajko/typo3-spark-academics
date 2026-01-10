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
