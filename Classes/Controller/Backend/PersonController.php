<?php

declare(strict_types=1);

namespace EtfUnsa\SparkAcademics\Controller\Backend;

use EtfUnsa\SparkAcademics\Domain\Repository\PersonRepository;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\Template\ModuleTemplate;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class PersonController extends ActionController
{
    protected PersonRepository $personRepository;
    protected ModuleTemplateFactory $moduleTemplateFactory;
    protected UriBuilder $backendUriBuilder;

    public function __construct(
        PersonRepository $personRepository,
        ModuleTemplateFactory $moduleTemplateFactory,
        UriBuilder $backendUriBuilder
    ) {
        $this->personRepository = $personRepository;
        $this->moduleTemplateFactory = $moduleTemplateFactory;
        $this->backendUriBuilder = $backendUriBuilder;
    }

    public function listAction(): ResponseInterface
    {
        $currentBeUser = $this->getCurrentBeUser();
        $persons = $this->personRepository->findAll();
        
        $userPersons = [];
        $firstPerson = null;

        foreach ($persons as $person) {
            foreach ($person->getBeUsers() as $beUser) {
                if ($beUser->getUid() === $currentBeUser['uid']) {
                    if ($firstPerson === null) {
                        $firstPerson = $person;
                    }
                    
                    $uidsToEdit = [$person->getUid()];
                    $query = $this->personRepository->createQuery();
                    $query->getQuerySettings()->setRespectSysLanguage(false);
                    $query->getQuerySettings()->setRespectStoragePage(false);
                    $query->matching($query->equals('l10nParent', $person->getUid()));
                    $translations = $query->execute();
                    
                    foreach ($translations as $translation) {
                        $uidsToEdit[] = $translation->getUid();
                    }

                    $returnUrl = (string)$this->backendUriBuilder->buildUriFromRoute('spark_academics_person');
                    $editUrl = (string)$this->backendUriBuilder->buildUriFromRoute('record_edit', [
                        'edit' => [
                            'tx_spark_person' => [
                                implode(',', $uidsToEdit) => 'edit'
                            ]
                        ],
                        'returnUrl' => $returnUrl
                    ]);
                    
                    $userPersons[] = [
                        'item' => $person,
                        'editUrl' => $editUrl
                    ];
                    break;
                }
            }
        }

        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        
        $moduleTemplate->assign('persons', $userPersons);
        $moduleTemplate->assign('firstPerson', $firstPerson);

        return $moduleTemplate->renderResponse('Backend/Person/List');
    }

    protected function getCurrentBeUser(): array
    {
        return $GLOBALS['BE_USER']->user;
    }
}
