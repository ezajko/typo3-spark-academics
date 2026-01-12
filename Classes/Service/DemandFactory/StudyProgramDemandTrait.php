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

namespace EtfUnsa\SparkAcademics\Service\DemandFactory;

use EtfUnsa\SparkAcademics\Domain\Model\Dto\StudyProgramDemand;

/**
 * Trait for creating StudyProgramDemand objects from settings
 * 
 * @author Ernedin Zajko <ezajko@root.ba>
 */
trait StudyProgramDemandTrait
{
    /**
     * Create a StudyProgramDemand from settings and request filter
     * 
     * @param array $settings Plugin settings from FlexForm/TypoScript
     * @param array $requestFilter Filter values from frontend request
     * @return StudyProgramDemand
     */
    public function createStudyProgramDemand(array $settings, array $requestFilter = []): StudyProgramDemand
    {
        $demand = new StudyProgramDemand();
        $this->applyCommonSettings($demand, $settings, $requestFilter);
        
        // Merge settings filter with request filter (request takes priority)
        $filter = array_merge($settings['filter'] ?? [], $requestFilter);
        
        // Organization filter
        if (!empty($filter['organization'])) {
            $demand->setOrganization((int)$filter['organization']);
        }
        
        // Study Cycle filter
        if (!empty($filter['studyCycle'])) {
            $demand->setStudyCycle((int)$filter['studyCycle']);
        }
        
        // Study Type filter
        if (!empty($filter['studyType'])) {
            $demand->setStudyType((int)$filter['studyType']);
        }
        
        // Mode of Study filter
        if (!empty($filter['modeOfStudy'])) {
            $demand->setModeOfStudy((int)$filter['modeOfStudy']);
        }
        
        // Language filter
        if (!empty($filter['language'])) {
            $demand->setLanguage((int)$filter['language']);
        }
        
        return $demand;
    }
}
