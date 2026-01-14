<?php

/*
 * This file is part of the "Spark Academics" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) Ernedin Zajko <ezajko@root.ba>
 */

defined('TYPO3') or die();

$t = 'tx_academics_domain_model_domain_model_organization';
if (isset($GLOBALS['TCA'][$t])) {
    // Enable Faker for the table
    $GLOBALS['TCA'][$t]['ctrl']['faker'] = true;

    // Column-level Faker configuration using Property classes
    // Words requires 'min' and 'max', Text requires 'from' and 'to'
    $GLOBALS['TCA'][$t]['columns']['title']['faker'] = \GeorgRinger\Faker\Property\Words::getSettings(['min' => 2, 'max' => 4]);
    $GLOBALS['TCA'][$t]['columns']['acronym']['faker'] = \GeorgRinger\Faker\Property\Text::getSettings(['from' => 3, 'to' => 6]);
    $GLOBALS['TCA'][$t]['columns']['description']['faker'] = \GeorgRinger\Faker\Property\RealText::getSettings(['maxNbChars' => 200]);
    $GLOBALS['TCA'][$t]['columns']['email']['faker'] = \GeorgRinger\Faker\Property\SafeEmail::getSettings([]);
    $GLOBALS['TCA'][$t]['columns']['phone']['faker'] = \GeorgRinger\Faker\Property\PhoneNumber::getSettings([]);
    $GLOBALS['TCA'][$t]['columns']['website']['faker'] = \GeorgRinger\Faker\Property\Url::getSettings([]);
    $GLOBALS['TCA'][$t]['columns']['address']['faker'] = \GeorgRinger\Faker\Property\StreetAddress::getSettings([]);
    $GLOBALS['TCA'][$t]['columns']['room']['faker'] = \GeorgRinger\Faker\Property\Text::getSettings(['from' => 5, 'to' => 10]);
    // type relation - use Relation property to pick from existing organization_type records
    $GLOBALS['TCA'][$t]['columns']['type']['faker'] = \GeorgRinger\Faker\Property\Relation::getSettings([
        'table' => 'tx_academics_domain_model_domain_model_organization_type',
        'pid' => 'current',
        'min' => 1,
        'max' => 1
    ]);
}
unset($t);

