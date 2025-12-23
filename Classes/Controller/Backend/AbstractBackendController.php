<?php

declare(strict_types=1);

namespace EtfUnsa\SparkAcademics\Controller\Backend;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\RepositoryInterface;

abstract class AbstractBackendController extends ActionController
{
    protected ModuleTemplateFactory $moduleTemplateFactory;
    protected UriBuilder $backendUriBuilder;
    protected RepositoryInterface $repository;
    protected string $tableName = '';

    public function __construct(
        ModuleTemplateFactory $moduleTemplateFactory,
        UriBuilder $backendUriBuilder
    ) {
        $this->moduleTemplateFactory = $moduleTemplateFactory;
        $this->backendUriBuilder = $backendUriBuilder;
    }

    public function listAction(): ResponseInterface
    {
        $currentBeUser = $this->getCurrentBeUser();
        
        // Use createQuery to bypass storage PID check if needed, 
        // but generally we want to find records assigned to this user.
        $items = $this->repository->findAll();
        
        $userItems = [];
        $firstItem = null;

        foreach ($items as $item) {
            $editors = $item->getBeUsers();
            if ($editors === null) continue;

            foreach ($editors as $beUser) {
                if ($beUser->getUid() === $currentBeUser['uid']) {
                    if ($firstItem === null) {
                        $firstItem = $item;
                    }

                    $uidsToEdit = [$item->getUid()];
                    
                    // Basic translation support (Extbase doesn't easily expose l10nParent in models by default)
                    // In a production environment, we might want to fetch all translations of this record.
                    // For now, we allow editing the specific record the user is assigned to.
                    
                    $returnUrl = (string)$this->backendUriBuilder->buildUriFromRoute($this->request->getAttribute('module')->getIdentifier());
                    $editUrl = (string)$this->backendUriBuilder->buildUriFromRoute('record_edit', [
                        'edit' => [
                            $this->tableName => [
                                implode(',', $uidsToEdit) => 'edit'
                            ]
                        ],
                        'returnUrl' => $returnUrl
                    ]);
                    
                    $userItems[] = [
                        'item' => $item,
                        'editUrl' => $editUrl
                    ];
                    break;
                }
            }
        }

        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        $moduleTemplate->assign('items', $userItems);
        $moduleTemplate->assign('firstItem', $firstItem);

        // Render generic template if specific one doesn't exist? 
        // Better to have individual templates for clarity.
        return $moduleTemplate->renderResponse($this->getTemplatePath());
    }

    protected function getCurrentBeUser(): array
    {
        return $GLOBALS['BE_USER']->user;
    }

    abstract protected function getTemplatePath(): string;
}
