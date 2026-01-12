<?php

declare(strict_types=1);

namespace EtfUnsa\SparkAcademics\Backend\Form;

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\TypoScript\TypoScriptService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;

/**
 * ItemsProcFunc for FlexForm dynamic select options
 * 
 * Provides methods to populate FlexForm select fields with
 * options from TypoScript configuration.
 * 
 * @author Ernedin Zajko <ezajko@root.ba>
 */
class FlexFormItemsProcFunc
{
    /**
     * Returns available viewTypes for the selected entityType
     * 
     * Reads viewTypes from TypoScript setup:
     * plugin.tx_sparkacademics_pi1.settings.view.viewTypes.{EntityType}
     * 
     * @param array $config FlexForm configuration array
     * @return array Modified configuration with items
     */
    public function getViewTypesForEntity(array &$config): void
    {
        // Get current FlexForm values
        $flexFormData = $config['flexParentDatabaseRow']['pi_flexform'] ?? [];
        
        // Try to get entityType from FlexForm
        $entityType = $this->getFlexFormValue($flexFormData, 'settings.entityType', 'sDEF') ?? 'Organization';
        
        // Get viewTypes from TypoScript
        $viewTypes = $this->getViewTypesFromTypoScript($entityType, (int)($config['flexParentDatabaseRow']['pid'] ?? 0));
        
        // Add items to config
        foreach ($viewTypes as $value => $label) {
            $config['items'][] = [
                'label' => $label,
                'value' => $value,
            ];
        }
    }
    
    /**
     * Get viewTypes from TypoScript configuration
     * 
     * @param string $entityType The entity type
     * @param int $pid The page ID
     * @return array Array of viewType => label pairs
     */
    protected function getViewTypesFromTypoScript(string $entityType, int $pid): array
    {
        // Default viewTypes if TypoScript not available
        $defaultViewTypes = [
            'Default' => 'Default',
            'List' => 'List (Table)',
        ];
        
        try {
            // Get TypoScript setup for the page
            $pageTs = BackendUtility::getPagesTSconfig($pid);
            $setup = $pageTs['tx_sparkacademics.']['settings.']['view.']['viewTypes.'][$entityType . '.'] ?? null;
            
            if (!empty($setup)) {
                $viewTypes = [];
                foreach ($setup as $key => $label) {
                    // Skip sub-arrays (keys ending with .)
                    if (!str_ends_with($key, '.')) {
                        $viewTypes[$key] = $label;
                    }
                }
                return $viewTypes ?: $defaultViewTypes;
            }
        } catch (\Exception $e) {
            // Fall back to defaults on error
        }
        
        // Fallback: return entity-specific defaults
        return $this->getDefaultViewTypes($entityType);
    }
    
    /**
     * Get default viewTypes for an entity type
     * 
     * @param string $entityType The entity type
     * @return array Array of viewType => label pairs
     */
    protected function getDefaultViewTypes(string $entityType): array
    {
        $defaults = [
            'Organization' => [
                'Default' => 'Default (Cards)',
                'List' => 'List (Table)',
            ],
            'Person' => [
                'Default' => 'Default (Grid)',
                'List' => 'List (Table)',
                'Card' => 'Card',
                'Simple' => 'Simple',
                'Debug' => 'Debug',
            ],
            'Course' => [
                'Default' => 'Default (Grid)',
                'List' => 'List (Table)',
                'Card' => 'Card',
                'Simple' => 'Simple',
                'Debug' => 'Debug',
            ],
            'StudyProgram' => [
                'Default' => 'Default (Grid)',
                'List' => 'List (Table)',
                'Card' => 'Card',
                'Simple' => 'Simple',
                'Debug' => 'Debug',
            ],
            'Project' => [
                'Default' => 'Default (Grid)',
                'Simple' => 'Simple',
                'Debug' => 'Debug',
            ],
        ];
        
        return $defaults[$entityType] ?? $defaults['Organization'];
    }
    
    /**
     * Extract a value from FlexForm XML data
     * 
     * @param array|string $flexFormData FlexForm data (XML string or parsed array)
     * @param string $fieldPath Field path (e.g., 'settings.entityType')
     * @param string $sheet Sheet name (e.g., 'sDEF')
     * @return string|null The value or null if not found
     */
    protected function getFlexFormValue($flexFormData, string $fieldPath, string $sheet = 'sDEF'): ?string
    {
        // Parse XML if string
        if (is_string($flexFormData) && !empty($flexFormData)) {
            $flexFormData = GeneralUtility::xml2array($flexFormData);
        }
        
        if (!is_array($flexFormData)) {
            return null;
        }
        
        // Navigate to the value
        $path = $flexFormData['data'][$sheet]['lDEF'][$fieldPath]['vDEF'] ?? null;
        
        return $path;
    }
}
