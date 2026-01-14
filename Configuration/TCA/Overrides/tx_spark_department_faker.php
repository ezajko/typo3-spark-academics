<?php

/*
 * This file is part of the "Spark Academics" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) Ernedin Zajko <ezajko@root.ba>
 */

/**
 * Faker configuration for tx_spark_department TCA
 * Enables fake data generation using georgringer/faker extension
 *
 * @author Ernedin Zajko <ezajko@root.ba>
 */

defined('TYPO3') or die();

use GeorgRinger\Faker\Property\RandomElement;
use GeorgRinger\Faker\Property\Text;
use GeorgRinger\Faker\Property\SafeEmail;
use GeorgRinger\Faker\Property\PhoneNumber;
use GeorgRinger\Faker\Property\Url;

// Enable faker for the table
$GLOBALS['TCA']['tx_academics_department']['ctrl']['faker'] = true;

// Configure faker options for individual fields
$GLOBALS['TCA']['tx_academics_department']['columns']['title']['faker'] = RandomElement::getSettings([
    'array' => [
        'Odsjek za automatiku i elektroniku',
        'Odsjek za elektroenergetiku',
        'Odsjek za računarstvo i informatiku',
        'Odsjek za telekomunikacije',
        'Odsjek za matematiku i fiziku',
    ],
]);

$GLOBALS['TCA']['tx_academics_department']['columns']['acronym']['faker'] = RandomElement::getSettings([
    'array' => ['AiE', 'EEN', 'RI', 'TK', 'MF'],
]);

$GLOBALS['TCA']['tx_academics_department']['columns']['description']['faker'] = Text::getSettings([
    'min' => 100,
    'max' => 300,
]);

$GLOBALS['TCA']['tx_academics_department']['columns']['email']['faker'] = SafeEmail::getSettings();

$GLOBALS['TCA']['tx_academics_department']['columns']['phone']['faker'] = PhoneNumber::getSettings();

$GLOBALS['TCA']['tx_academics_department']['columns']['website']['faker'] = Url::getSettings();
