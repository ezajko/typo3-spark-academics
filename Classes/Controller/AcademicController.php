<?php

declare(strict_types=1);

namespace EtfUnsa\SparkAcademics\Controller;

use EtfUnsa\SparkAcademics\Domain\Model\Dto\Demand;
use EtfUnsa\SparkAcademics\Domain\Model\Chair;
use EtfUnsa\SparkAcademics\Domain\Model\Course;
use EtfUnsa\SparkAcademics\Domain\Model\Department;
use EtfUnsa\SparkAcademics\Domain\Model\Person;
use EtfUnsa\SparkAcademics\Domain\Model\Project;
use EtfUnsa\SparkAcademics\Domain\Model\ResearchGroup;
use EtfUnsa\SparkAcademics\Domain\Model\ResearchLab;
use EtfUnsa\SparkAcademics\Domain\Model\StudyProgram;
use EtfUnsa\SparkAcademics\Domain\Repository\ChairRepository;
use EtfUnsa\SparkAcademics\Domain\Repository\CourseRepository;
use EtfUnsa\SparkAcademics\Domain\Repository\DepartmentRepository;
use EtfUnsa\SparkAcademics\Domain\Repository\PersonRepository;
use EtfUnsa\SparkAcademics\Domain\Repository\ProjectRepository;
use EtfUnsa\SparkAcademics\Domain\Repository\ResearchGroupRepository;
use EtfUnsa\SparkAcademics\Domain\Repository\ResearchLabRepository;
use EtfUnsa\SparkAcademics\Domain\Repository\StudyProgramRepository;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use Psr\Http\Message\ResponseInterface;
use EtfUnsa\SparkAcademics\Service\DemandService;

class AcademicController extends ActionController
{
    protected DepartmentRepository $departmentRepository;
    protected ResearchLabRepository $researchLabRepository;
    protected ResearchGroupRepository $researchGroupRepository;
    protected ChairRepository $chairRepository;
    protected CourseRepository $courseRepository;
    protected StudyProgramRepository $studyProgramRepository;
    protected ProjectRepository $projectRepository;
    protected PersonRepository $personRepository;
    protected DemandService $demandService;

    public function __construct(
        DepartmentRepository $departmentRepository,
        ResearchLabRepository $researchLabRepository,
        ResearchGroupRepository $researchGroupRepository,
        ChairRepository $chairRepository,
        CourseRepository $courseRepository,
        StudyProgramRepository $studyProgramRepository,
        ProjectRepository $projectRepository,
        PersonRepository $personRepository,
        DemandService $demandService
    ) {
        $this->departmentRepository = $departmentRepository;
        $this->researchLabRepository = $researchLabRepository;
        $this->researchGroupRepository = $researchGroupRepository;
        $this->chairRepository = $chairRepository;
        $this->courseRepository = $courseRepository;
        $this->studyProgramRepository = $studyProgramRepository;
        $this->projectRepository = $projectRepository;
        $this->personRepository = $personRepository;
        $this->demandService = $demandService;
    }

    /**
     * Generic list action (usually findAll)
     */
    public function listAction(): ResponseInterface
    {
        return $this->dispatchListAction($this->settings);
    }

    public function listAllAction(): ResponseInterface
    {
        return $this->dispatchListAction($this->settings);
    }

    public function listSelectedAction(): ResponseInterface
    {
        return $this->dispatchListAction($this->settings);
    }

    public function listFilteredAction(): ResponseInterface
    {
        return $this->dispatchListAction($this->settings);
    }

    protected function dispatchListAction(array $settings): ResponseInterface
    {
        $entityType = $settings['entityType'] ?? 'department';
        
        // Resolve uids from generic "settings.select.{type}" if not already set
        if (empty($settings['uids'])) {
            $suffix = strtolower($entityType);
            $settings['uids'] = $settings['select'][$suffix] 
                                ?? $settings['select.' . $suffix] 
                                ?? '';
        }

        $repoField = $this->getRepositoryFieldName($entityType);
        
        $demand = $this->demandService->createFromSettings($settings);
        $items = $this->$repoField->findByDemand($demand);

        $this->view->assign('items', $items);
        $this->view->assign('entityType', $entityType);
        $this->view->assign('detailPid', $this->resolveDetailPid($entityType));

        return $this->htmlResponse();
    }

    public function showAction(
        ?Department $department = null,
        ?ResearchLab $lab = null,
        ?ResearchGroup $group = null,
        ?Chair $chair = null,
        ?Course $course = null,
        ?StudyProgram $program = null,
        ?Project $project = null,
        ?Person $person = null
    ): ResponseInterface {
        $args = array_filter(get_defined_vars());
        $item = reset($args); // Get first non-null argument
        $entityType = ucfirst(key($args));

        if (!$item) {
             // Try to resolve from settings (Static Selection)
             $entityType = $this->settings['entityType'] ?? '';
             if ($entityType) {
                 $suffix = strtolower($entityType);
                 $uid = $this->settings['select'][$suffix] ?? $this->settings['select.' . $suffix] ?? 0;
                 
                 if ($uid) {
                     $repoField = $this->getRepositoryFieldName($entityType);
                     if (property_exists($this, $repoField)) {
                         $item = $this->$repoField->findByUid((int)$uid);
                     }
                 }
                 // If item found, update entityType to Clean Class Name for consistency
                 if ($item) {
                     $entityType = (new \ReflectionClass($item))->getShortName();
                 }
             }
        }
        
        if (!$item) { 
             $item = null;
             $entityType = 'unknown';
        } else if (!isset($entityType) || $entityType === 'unknown') {
             // Fallback if entityType wasn't set above (though it should be)
             $entityType = (new \ReflectionClass($item))->getShortName();
        }

        $this->view->assign('item', $item);
        $this->view->assign('entityType', $entityType);
        $this->view->assign('detailPid', $this->resolveDetailPid($entityType));

        return $this->htmlResponse();
    }

    protected function getRepositoryFieldName(string $entityType): string
    {
        return lcfirst($entityType) . 'Repository';
    }

    protected function resolveDetailPid(string $entityType): int
    {
        // 1. FlexForm override
        if (!empty($this->settings['detailPid'])) {
            return (int)$this->settings['detailPid'];
        }

        // 2. Site Configuration / Settings fallback
        /** @var Site $site */
        $site = $this->request->getAttribute('site');
        
        $configSuffix = strtolower($entityType);

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


}
