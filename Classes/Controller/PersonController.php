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
        $primaryDepartmentUid = (int)($this->settings['filter']['primary_department'] ?? 0);
        $academicRankUid = (int)($this->settings['filter']['academic_rank'] ?? 0);
        $academicTitleUid = (int)($this->settings['filter']['academic_title'] ?? 0);

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

    public function showAction(Person $person): ResponseInterface
    {
        $this->view->assign('person', $person);
        return $this->htmlResponse();
    }
}
