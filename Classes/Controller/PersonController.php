<?php

declare(strict_types=1);

namespace EtfUnsa\SparkAcademics\Controller;

use EtfUnsa\SparkAcademics\Domain\Model\Person;
use EtfUnsa\SparkAcademics\Domain\Repository\PersonRepository;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class PersonController extends ActionController
{
    protected ?PersonRepository $personRepository = null;

    public function injectPersonRepository(PersonRepository $personRepository): void
    {
        $this->personRepository = $personRepository;
    }

    public function listAction(): ResponseInterface
    {
        // Support both dot notation (array) and underscore notation (flat) from FlexForm
        $primaryDepartmentUid = (int)($this->settings['filter']['primary_department'] ?? $this->settings['filter_primary_department'] ?? 0);
        $academicRankUid = (int)($this->settings['filter']['academic_rank'] ?? $this->settings['filter_academic_rank'] ?? 0);
        $academicTitleUid = (int)($this->settings['filter']['academic_title'] ?? $this->settings['filter_academic_title'] ?? 0);

        $persons = null;

        if ($primaryDepartmentUid > 0 || $academicRankUid > 0 || $academicTitleUid > 0) {
            $persons = $this->personRepository->findByFilters(
                $primaryDepartmentUid,
                $academicRankUid,
                $academicTitleUid
            );
        } else {
            $mode = $this->settings['mode'] ?? 'all';
            if ($mode === 'selected') {
                $uids = $this->settings['persons'] ?? '';
                if (!empty($uids)) {
                    $uidList = GeneralUtility::intExplode(',', $uids, true);
                    if (!empty($uidList)) {
                        $query = $this->personRepository->createQuery();
                        $query->matching($query->in('uid', $uidList));
                        $persons = $query->execute();
                    }
                }
            } else {
                $persons = $this->personRepository->findAll();
            }
        }

        $this->view->assign('persons', $persons);
        return $this->htmlResponse();
    }

    public function listSelectedAction(): ResponseInterface
    {
        $uids = $this->settings['persons'] ?? '';
        $persons = null;

        if (!empty($uids)) {
            $uidList = GeneralUtility::intExplode(',', $uids, true);
            if (!empty($uidList)) {
                $query = $this->personRepository->createQuery();
                $query->matching($query->in('uid', $uidList));
                $persons = $query->execute();
            }
        }

        $this->view->assign('persons', $persons);
        $this->view->assign('detailPid', $this->settings['detailPid'] ?? 0);
        return $this->htmlResponse();
    }

    public function listAllAction(): ResponseInterface
    {
        $persons = $this->personRepository->findAll();
        $this->view->assign('persons', $persons);
        $this->view->assign('detailPid', $this->settings['detailPid'] ?? 0);
        return $this->htmlResponse();
    }

    public function listFilteredAction(): ResponseInterface
    {
        // Support both dot notation (array) and underscore notation (flat) from FlexForm
        $primaryDepartmentUid = (int)($this->settings['filter']['primary_department'] ?? $this->settings['filter_primary_department'] ?? 0);
        $academicRankUid = (int)($this->settings['filter']['academic_rank'] ?? $this->settings['filter_academic_rank'] ?? 0);
        $academicTitleUid = (int)($this->settings['filter']['academic_title'] ?? $this->settings['filter_academic_title'] ?? 0);

        $persons = null;
        
        if ($primaryDepartmentUid > 0 || $academicRankUid > 0 || $academicTitleUid > 0) {
            $persons = $this->personRepository->findByFilters(
                $primaryDepartmentUid,
                $academicRankUid,
                $academicTitleUid
            );
        } else {
            $persons = $this->personRepository->findAll();
        }

        $this->view->assign('persons', $persons);
        $this->view->assign('detailPid', $this->settings['detailPid'] ?? 0);
        return $this->htmlResponse();
    }

    public function showAction(Person $person): ResponseInterface
    {
        $this->view->assign('person', $person);
        return $this->htmlResponse();
    }
}
