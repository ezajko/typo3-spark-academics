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
 * Faker configuration for tx_spark_person TCA
 * Enables fake data generation using georgringer/faker extension
 *
 * @author Ernedin Zajko <ezajko@root.ba>
 */

defined('TYPO3') or die();

use GeorgRinger\Faker\Property\FirstName;
use GeorgRinger\Faker\Property\LastName;
use GeorgRinger\Faker\Property\SafeEmail;
use GeorgRinger\Faker\Property\PhoneNumber;
use GeorgRinger\Faker\Property\Url;
use GeorgRinger\Faker\Property\Text;
use GeorgRinger\Faker\Property\RandomElement;
use GeorgRinger\Faker\Property\Relation;
use GeorgRinger\Faker\Property\Words;
use GeorgRinger\Faker\Property\Username;

// Enable faker for the table
$GLOBALS['TCA']['tx_academics_person']['ctrl']['faker'] = true;

// =========================================================================
// Basic Information
// =========================================================================
$GLOBALS['TCA']['tx_academics_person']['columns']['first_name']['faker'] = FirstName::getSettings();
$GLOBALS['TCA']['tx_academics_person']['columns']['last_name']['faker'] = LastName::getSettings();
$GLOBALS['TCA']['tx_academics_person']['columns']['gender']['faker'] = RandomElement::getSettings([
    'array' => [1, 2],
]);

// =========================================================================
// Academic Affiliation - with correct PIDs
// =========================================================================
$GLOBALS['TCA']['tx_academics_person']['columns']['primary_department']['faker'] = Relation::getSettings([
    'table' => 'tx_academics_department',
    'pid' => 179,
    'min' => 1,
    'max' => 1,
]);

$GLOBALS['TCA']['tx_academics_person']['columns']['academic_title']['faker'] = Relation::getSettings([
    'table' => 'tx_academics_academic_title',
    'pid' => 194,
    'min' => 1,
    'max' => 1,
]);

$GLOBALS['TCA']['tx_academics_person']['columns']['academic_rank']['faker'] = Relation::getSettings([
    'table' => 'tx_academics_academic_rank',
    'pid' => 193,
    'min' => 1,
    'max' => 1,
]);

// =========================================================================
// Biography & Contact
// =========================================================================
$GLOBALS['TCA']['tx_academics_person']['columns']['biography']['faker'] = Text::getSettings([
    'min' => 200,
    'max' => 500,
]);

$GLOBALS['TCA']['tx_academics_person']['columns']['contact_office']['faker'] = RandomElement::getSettings([
    'array' => ['A-101', 'A-202', 'B-105', 'B-210', 'C-301', 'C-415', 'D-120', 'D-225'],
]);

$GLOBALS['TCA']['tx_academics_person']['columns']['phone_office']['faker'] = PhoneNumber::getSettings();
$GLOBALS['TCA']['tx_academics_person']['columns']['phone_mobile']['faker'] = PhoneNumber::getSettings();
$GLOBALS['TCA']['tx_academics_person']['columns']['contact_email']['faker'] = SafeEmail::getSettings();
$GLOBALS['TCA']['tx_academics_person']['columns']['contact_website']['faker'] = Url::getSettings();

// =========================================================================
// Research & Teaching
// =========================================================================
$GLOBALS['TCA']['tx_academics_person']['columns']['research_interests']['faker'] = Words::getSettings([
    'min' => 5,
    'max' => 15,
]);

$GLOBALS['TCA']['tx_academics_person']['columns']['consultation_hours']['faker'] = RandomElement::getSettings([
    'array' => [
        'Ponedjeljak 10:00-12:00',
        'Utorak 14:00-16:00',
        'Srijeda 11:00-13:00',
        'Četvrtak 09:00-11:00',
        'Petak 13:00-15:00',
        'Ponedjeljak i Srijeda 10:00-12:00',
        'Utorak i Četvrtak 14:00-16:00',
    ],
]);

// =========================================================================
// Academic Profiles
// =========================================================================
$GLOBALS['TCA']['tx_academics_person']['columns']['profile_github']['faker'] = Username::getSettings();
$GLOBALS['TCA']['tx_academics_person']['columns']['profile_google_scholar']['faker'] = Url::getSettings();
$GLOBALS['TCA']['tx_academics_person']['columns']['profile_research_gate']['faker'] = Url::getSettings();
$GLOBALS['TCA']['tx_academics_person']['columns']['profile_linkedin']['faker'] = Url::getSettings();

$GLOBALS['TCA']['tx_academics_person']['columns']['profile_orcid']['faker'] = RandomElement::getSettings([
    'array' => [
        '0000-0001-2345-6789',
        '0000-0002-3456-7890',
        '0000-0003-4567-8901',
        '0000-0001-5678-9012',
        '0000-0002-6789-0123',
    ],
]);

$GLOBALS['TCA']['tx_academics_person']['columns']['scopus_id']['faker'] = RandomElement::getSettings([
    'array' => [
        '12345678901',
        '23456789012',
        '34567890123',
        '45678901234',
        '56789012345',
    ],
]);

$GLOBALS['TCA']['tx_academics_person']['columns']['researcher_id']['faker'] = RandomElement::getSettings([
    'array' => [
        'A-1234-2018',
        'B-2345-2019',
        'C-3456-2020',
        'D-4567-2021',
        'E-5678-2022',
    ],
]);
