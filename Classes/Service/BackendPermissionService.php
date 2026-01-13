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

namespace EtfUnsa\SparkAcademics\Service;

use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\SingletonInterface;

class BackendPermissionService implements SingletonInterface
{
    protected SiteFinder $siteFinder;

    public function __construct(SiteFinder $siteFinder)
    {
        $this->siteFinder = $siteFinder;
    }

    /**
     * Checks if the current backend user has privileges to view all records of a specific type.
     * Returns true if user is Admin OR belongs to one of the configured permission groups defined in Site Configuration.
     *
     * @param string $configKey The configuration key in site settings (e.g., 'sparkAcademic_project_permission_groups')
     */
    public function canViewAllRecords(string $configKey): bool
    {
        $beUser = $GLOBALS['BE_USER'];

        // 1. Is Admin?
        if ($beUser->isAdmin()) {
            return true;
        }

        // 2. Check Site Configurations
        try {
            $sites = $this->siteFinder->getAllSites();
            $userGroups = $beUser->userGroupsUID; // Array of group UIDs

            foreach ($sites as $site) {
                $config = $site->getConfiguration();
                $permGroupsCsv = $config[$configKey] ?? '';

                if (empty($permGroupsCsv)) {
                    continue;
                }

                $permGroups = GeneralUtility::intExplode(',', $permGroupsCsv, true);
                
                if (array_intersect($permGroups, $userGroups)) {
                    return true;
                }
            }

        } catch (\Exception $e) {
            // Site context might not be available or other error
            return false;
        }

        return false;
    }
}
