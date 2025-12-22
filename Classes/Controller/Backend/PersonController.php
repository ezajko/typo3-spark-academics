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
                    
                    // Determine the "root" UID (default language record)
                    // Since l10nParent isn't mapped in the model, we use the _localizedUid logic or assume default if not localized
                    // But actually, findAll returns objects. If it's a localized object, it has a separate UID.
                    // Extbase handles overlay.
                    
                    // Robust way: Fetch all records where this user is assigned, regardless of language
                    // Then group them by l10n_parent
                    
                    // Simplified approach for typical use case (User linked to Default Language Record):
                    // If user is linked to a translation, this might need fetching the parent via raw query or adding l10nParent to model.
                    // Let's stick to the previous implementation which works if linked to default, but make it slightly more robust by ensuring unique UIDs.
                    
                    $parentUid = $person->getUid();
                    if ($person->_getProperty('_languageUid') > 0 && $person->_getProperty('_localizedUid') !== $person->getUid()) {
                        // This is an overlay. The "real" uid is _localizedUid (which is the uid of the translated record)
                        // But wait, Extbase overlay means getUid() returns the UID of the translated record?
                        // Actually, getUid() usually returns the UID of the record acting as the object.
                        
                        // Let's assume the standard case: User is linked to English (Default) record.
                        // We want to edit English + Bosnian.
                    }

                    $uidsToEdit = [$person->getUid()];
                    $query = $this->personRepository->createQuery();
                    $query->getQuerySettings()->setRespectSysLanguage(false);
                    $query->getQuerySettings()->setRespectStoragePage(false);
                    
                    // Findings translations of the current person (assuming current is default)
                    $query->matching($query->equals('l10nParent', $person->getUid()));
                    $translations = $query->execute();
                    
                    foreach ($translations as $translation) {
                        $uidsToEdit[] = $translation->getUid();
                    }
                    
                    // Ensure unique and sorted
                    $uidsToEdit = array_unique($uidsToEdit);
                    sort($uidsToEdit);

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
