<?php

declare(strict_types=1);

namespace EtfUnsa\SparkAcademics\Controller\Backend;

use EtfUnsa\SparkAcademics\Domain\Repository\PersonRepository;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use Psr\Http\Message\ResponseInterface;

class PersonController extends ActionController
{
    protected PersonRepository $personRepository;
    protected ModuleTemplateFactory $moduleTemplateFactory;

    public function __construct(
        PersonRepository $personRepository,
        ModuleTemplateFactory $moduleTemplateFactory
    ) {
        $this->personRepository = $personRepository;
        $this->moduleTemplateFactory = $moduleTemplateFactory;
    }

    public function listAction(): ResponseInterface
    {
        $currentBeUser = $this->getCurrentBeUser();
        $persons = $this->personRepository->findAll(); // For now, we need to filter these
        
        // Filter persons where current be_user is in be_users MM relation
        // In a real scenario, we would use a custom repository method
        $filteredPersons = [];
        foreach ($persons as $person) {
            foreach ($person->getBeUsers() as $beUser) {
                if ($beUser->getUid() === $currentBeUser['uid']) {
                    $filteredPersons[] = $person;
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
