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

namespace RootBa\Academics\Service;

use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\SingletonInterface;

class BackendPermissionService implements SingletonInterface
{
    protected SiteFinder $siteFinder;
    protected \TYPO3\CMS\Core\Configuration\ExtensionConfiguration $extensionConfiguration;

    public function __construct(
        SiteFinder $siteFinder,
        \TYPO3\CMS\Core\Configuration\ExtensionConfiguration $extensionConfiguration
    ) {
        $this->siteFinder = $siteFinder;
        $this->extensionConfiguration = $extensionConfiguration;
    }

    /**
     * Checks if the current backend user has privileges to view all records of a specific type.
     * Returns true if user is Admin OR belongs to one of the configured permission groups defined in Site Configuration
     * OR in Extension Configuration (fallback).
     *
     * @param string $configKey The configuration key (e.g., 'academics_project_permission_groups')
     */
    public function canViewAllRecords(string $configKey): bool
    {
        $beUser = $GLOBALS['BE_USER'];

        // 1. Is Admin?
        if ($beUser->isAdmin()) {
            return true;
        }

        $userGroups = $beUser->userGroupsUID; // Array of group UIDs

        // 2. Check Site Configurations
        try {
            $sites = $this->siteFinder->getAllSites();
            foreach ($sites as $site) {
                $config = $site->getConfiguration();
                $permGroupsCsv = $config[$configKey] ?? '';

                if (!empty($permGroupsCsv)) {
                    $permGroups = GeneralUtility::intExplode(',', $permGroupsCsv, true);
                    if (array_intersect($permGroups, $userGroups)) {
                        return true;
                    }
                }
            }
        } catch (\Exception $e) {
            // Site context might not be available or other error
        }

        // 3. Fallback: Extension Configuration
        // We check 'spark_academics' configuration for the same key
        try {
            $extConfig = $this->extensionConfiguration->get('spark_academics');
            $permGroupsCsv = $extConfig[$configKey] ?? '';

            if (!empty($permGroupsCsv)) {
                $permGroups = GeneralUtility::intExplode(',', $permGroupsCsv, true);
                if (array_intersect($permGroups, $userGroups)) {
                    return true;
                }
            }
        } catch (\Exception $e) {
            // Ignore
        }

        return false;
    }
}
