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

$t = 'tx_academics_domain_model_organizationtype';
if (isset($GLOBALS['TCA'][$t])) {
    // Enable Faker for the table
    $GLOBALS['TCA'][$t]['ctrl']['faker'] = true;

    // Column-level Faker configuration using Property classes
    // Words requires 'min' and 'max' keys
    $GLOBALS['TCA'][$t]['columns']['title']['faker'] = \GeorgRinger\Faker\Property\Words::getSettings(['min' => 2, 'max' => 3]);
    $GLOBALS['TCA'][$t]['columns']['type']['faker'] = \GeorgRinger\Faker\Property\RandomElement::getSettings([
        'elements' => ['department', 'chair', 'research_lab', 'research_group', 'center', 'institute']
    ]);
}
unset($t);

