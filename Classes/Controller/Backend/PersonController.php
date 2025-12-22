<?php

declare(strict_types=1);

namespace EtfUnsa\SparkAcademics\Controller\Backend;

use EtfUnsa\SparkAcademics\Domain\Repository\PersonRepository;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\Template\Components\Menu\MenuRegistry;
use TYPO3\CMS\Backend\Template\ModuleTemplate;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class PersonController extends ActionController
{
    protected PersonRepository $personRepository;
    protected ModuleTemplateFactory $moduleTemplateFactory;
    protected UriBuilder $backendUriBuilder;
    protected MenuRegistry $menuRegistry;

    public function __construct(
        PersonRepository $personRepository,
        ModuleTemplateFactory $moduleTemplateFactory,
        UriBuilder $backendUriBuilder,
        MenuRegistry $menuRegistry
    ) {
        $this->personRepository = $personRepository;
        $this->moduleTemplateFactory = $moduleTemplateFactory;
        $this->backendUriBuilder = $backendUriBuilder;
        $this->menuRegistry = $menuRegistry;
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
                        'columnsOnly' => [
                            'tx_spark_person' => 'first_name,last_name,biography,office,phone,website,google_scholar,research_gate,github,orcid,linkedin,image,cv'
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
        $this->registerDocHeaderMenu($moduleTemplate, $this->request->getQueryParams()['tab'] ?? 'overview');
        
        $moduleTemplate->assign('persons', $userPersons);
        $moduleTemplate->assign('firstPerson', $firstPerson);
        $moduleTemplate->assign('currentTab', $this->request->getQueryParams()['tab'] ?? 'overview');

        return $moduleTemplate->renderResponse('Backend/Person/List');
    }

    protected function registerDocHeaderMenu(ModuleTemplate $moduleTemplate, string $currentTab): void
    {
        $menu = $this->menuRegistry->makeMenu();
        $menu->setIdentifier('spark_academics_person_menu');

        $actions = [
            'overview' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.tabs.general',
            'biography' => 'LLL:EXT:spark_academics/Resources/Private/Language/locallang.xlf:person.biography',
            'contact' => 'LLL:EXT:spark_academics/Resources/Private/Language/locallang.xlf:person.tab.contact',
            'profiles' => 'LLL:EXT:spark_academics/Resources/Private/Language/locallang.xlf:person.tab.profiles',
            'media' => 'LLL:EXT:spark_academics/Resources/Private/Language/locallang.xlf:person.tab.media',
        ];

        foreach ($actions as $action => $label) {
            $item = $menu->makeMenuItem()
                ->setTitle($label)
                ->setHref((string)$this->backendUriBuilder->buildUriFromRoute('spark_academics_person', ['tab' => $action]))
                ->setActive($currentTab === $action);
            $menu->addMenuItem($item);
        }

        $moduleTemplate->getDocHeader()->getMenuRegistry()->addMenu($menu);
    }

    protected function getCurrentBeUser(): array
    {
        return $GLOBALS['BE_USER']->user;
    }
}
