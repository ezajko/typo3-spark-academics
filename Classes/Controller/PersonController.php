<?php

declare(strict_types=1);

namespace EtfUnsa\SparkAcademics\Controller;

use EtfUnsa\SparkAcademics\Domain\Model\Person;
use EtfUnsa\SparkAcademics\Domain\Repository\PersonRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class PersonController extends ActionController
{
    protected ?PersonRepository $personRepository = null;

    public function injectPersonRepository(PersonRepository $personRepository): void
    {
        $this->personRepository = $personRepository;
    }

    public function listAction(): void
    {
        $mode = $this->settings['mode'] ?? 'all';
        $persons = null;

        if ($mode === 'selected') {
            $uids = $this->settings['persons'] ?? '';
            if (!empty($uids)) {
                $uidList = GeneralUtility::intExplode(',', $uids, true);
                if (!empty($uidList)) {
                    // Quick way to fetch by UIDs. 
                    // Assuming generic query matching.
                    $query = $this->personRepository->createQuery();
                    $query->matching($query->in('uid', $uidList));
                    // Maintain order if needed? Not critical for now.
                    $persons = $query->execute();
                }
            }
        } else {
            $persons = $this->personRepository->findAll();
        }

        $this->view->assign('persons', $persons);
        $this->view->assign('mode', $mode);
    }

    public function showAction(Person $person): void
    {
        $this->view->assign('person', $person);
    }
}
