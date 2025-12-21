<?php

declare(strict_types=1);

namespace EtfUnsa\SparkAcademics\Controller\Backend;

use EtfUnsa\SparkAcademics\Domain\Repository\PersonRepository;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use Psr\Http\Message\ResponseInterface;

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
        
        $filteredPersons = [];
        foreach ($persons as $person) {
            foreach ($person->getBeUsers() as $beUser) {
                if ($beUser->getUid() === $currentBeUser['uid']) {
                    // Find all localized versions of this person
                    // We can use the Repository or a direct query to find records 
                    // where l10n_parent = $person->getUid()
                    $uidsToEdit = [$person->getUid()];
                    
                    // Simple manual fetching of translations for the edit link
                    // In a more robust setup, this would be part of a Service
                    $query = $this->personRepository->createQuery();
                    $query->getQuerySettings()->setRespectSysLanguage(false);
                    $query->getQuerySettings()->setRespectStoragePage(false);
                    $query->matching($query->equals('l10nParent', $person->getUid()));
                    $translations = $query->execute();
                    
                    foreach ($translations as $translation) {
                        $uidsToEdit[] = $translation->getUid();
                    }

                    // Generate edit link with multiple UIDs if translations exist
                    $returnUrl = (string)$this->backendUriBuilder->buildUriFromRoute('spark_academics_person');
                    $editUrl = (string)$this->backendUriBuilder->buildUriFromRoute('record_edit', [
                        'edit' => [
                            'tx_spark_person' => [
                                implode(',', $uidsToEdit) => 'edit'
                            ]
                        ],
                        'columnsOnly' => [
                            'tx_spark_person' => 'first_name,last_name,biography,office,phone,website,google_scholar,research_gate,github,orcid,linkedin,image,cv'
                        ],
                        'returnUrl' => $returnUrl
                    ]);
                    
                    $filteredPersons[] = [
                        'item' => $person,
                        'editUrl' => $editUrl
                    ];
                    break;
                }
            }
        }

        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        $moduleTemplate->assign('persons', $filteredPersons);
        return $moduleTemplate->renderResponse('Backend/Person/List');
    }

    protected function getCurrentBeUser(): array
    {
        return $GLOBALS['BE_USER']->user;
    }
}
