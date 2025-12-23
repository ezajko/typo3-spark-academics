<?php

declare(strict_types=1);

namespace EtfUnsa\SparkAcademics\Controller;

use EtfUnsa\SparkAcademics\Domain\Model\Chair;
use EtfUnsa\SparkAcademics\Domain\Model\Course;
use EtfUnsa\SparkAcademics\Domain\Model\Department;
use EtfUnsa\SparkAcademics\Domain\Model\Project;
use EtfUnsa\SparkAcademics\Domain\Model\ResearchGroup;
use EtfUnsa\SparkAcademics\Domain\Model\ResearchLab;
use EtfUnsa\SparkAcademics\Domain\Model\StudyProgram;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class AcademicController extends ActionController
{
    public function showAction(
        ?Department $department = null,
        ?ResearchLab $lab = null,
        ?ResearchGroup $group = null,
        ?Chair $chair = null,
        ?Course $course = null,
        ?StudyProgram $program = null,
        ?Project $project = null
    ): ResponseInterface {
        $this->view->assign('department', $department);
        $this->view->assign('lab', $lab);
        $this->view->assign('group', $group);
        $this->view->assign('chair', $chair);
        $this->view->assign('course', $course);
        $this->view->assign('program', $program);
        $this->view->assign('project', $project);

        $item = $department ?? $lab ?? $group ?? $chair ?? $course ?? $program ?? $project;
        $this->view->assign('item', $item);

        return $this->htmlResponse();
    }
}
