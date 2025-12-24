<?php

declare(strict_types=1);

namespace EtfUnsa\SparkAcademics\Controller;

use EtfUnsa\SparkAcademics\Domain\Model\Dto\Demand;
use EtfUnsa\SparkAcademics\Domain\Repository\AbstractRepository;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

abstract class AbstractFrontendController extends ActionController
{
    protected ?AbstractRepository $repository = null;

    protected function resolveDetailPid(string $entityType): int
    {
        // 1. FlexForm override
        if (!empty($this->settings['detailPid'])) {
            return (int)$this->settings['detailPid'];
        }

        // 2. Site Configuration / Settings fallback
        /** @var Site $site */
        $site = $this->request->getAttribute('site');
        
        // Normalize entity type to config key suffix
        $keyMap = [
            'researchlab' => 'lab',
            'lab' => 'lab',
            'researchgroup' => 'group',
            'group' => 'group',
            'department' => 'dept',
            'dept' => 'dept',
            'studyprogram' => 'program',
            'program' => 'program',
        ];
        
        $normalizedType = strtolower($entityType);
        $configSuffix = $keyMap[$normalizedType] ?? $normalizedType;

        $fieldName = 'academic_pid_' . $configSuffix . '_detail';
        $pidValue = null;

        // Check Site Configuration (config.yaml)
        $siteConfig = $site->getConfiguration();
        if (isset($siteConfig[$fieldName])) {
            $pidValue = $siteConfig[$fieldName];
        }

        // Check Site Settings (settings.yaml - TYPO3 v12+)
        if (!$pidValue && method_exists($site, 'getSettings')) {
            $siteSettings = $site->getSettings();
            if (isset($siteSettings[$fieldName])) {
                $pidValue = $siteSettings[$fieldName];
            }
        }

        if ($pidValue) {
            // Handle t3:// link syntax
            if (is_string($pidValue) && strpos($pidValue, 't3://page?uid=') === 0) {
                return (int)str_replace('t3://page?uid=', '', $pidValue);
            }
            return (int)$pidValue;
        }

        return 0;
    }

    /**
     * Create a Demand object from current settings.
     * This can be overridden in child controllers for specific filter logic.
     */
    protected function createDemandFromSettings(): Demand
    {
        $demand = new Demand();
        
        // Logical operator from settings if available
        if (isset($this->settings['filter']['operator'])) {
            $demand->setLogicalOperator($this->settings['filter']['operator']);
        }

        return $demand;
    }

    /**
     * Helper to get a list of UIDs from settings (e.g., for 'selected' mode)
     */
    protected function getItemsFromSelection(string $settingName = 'items'): ?array
    {
        $uids = $this->settings[$settingName] ?? '';
        if (empty($uids)) {
            return null;
        }
        
        $uidList = GeneralUtility::intExplode(',', $uids, true);
        if (empty($uidList)) {
            return null;
        }

        $demand = new Demand();
        $demand->addFilter('uid', $uidList, 'in');
        
        return $this->repository->findByDemand($demand)->toArray();
    }
}
