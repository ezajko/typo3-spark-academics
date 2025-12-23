<?php

declare(strict_types=1);

namespace EtfUnsa\SparkAcademics\Controller\Backend;

use EtfUnsa\SparkAcademics\Domain\Repository\AbstractRepository;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

abstract class AbstractBackendController extends ActionController
{
    protected ModuleTemplateFactory $moduleTemplateFactory;
    protected UriBuilder $backendUriBuilder;
    protected AbstractRepository $repository;
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
        
        // Use the new abstract repository method
        $items = $this->repository->findByBackendUser((int)$currentBeUser['uid']);
        
        $userItems = [];
        $firstItem = null;

        foreach ($items as $item) {
            if ($firstItem === null) {
                $firstItem = $item;
            }

            $userItems[] = [
                'item' => $item,
                'editUrl' => $this->getEditUrl($item)
            ];
        }

        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        $moduleTemplate->assign('items', $userItems);
        $moduleTemplate->assign('firstItem', $firstItem);

        return $moduleTemplate->renderResponse($this->getTemplatePath());
    }

    /**
     * Build an edit URL that targets both the record and its translations.
     */
    protected function getEditUrl($item): string
    {
        $uidsToEdit = [$item->getUid()];
        
        // Multi-edit for translations (if l10nParent is available)
        // Note: Models need l10nParent property for this to work via Extbase.
        // For simplicity, we can also use a raw query if needed, 
        // but here we demonstrate the logic for the repository to handle it.
        $query = $this->repository->createQuery();
        $query->getQuerySettings()->setRespectSysLanguage(false);
        $query->getQuerySettings()->setRespectStoragePage(false);
        
        // We match translations pointing to this record
        $query->matching($query->equals('l10nParent', $item->getUid()));
        $translations = $query->execute();
        foreach ($translations as $translation) {
            $uidsToEdit[] = $translation->getUid();
        }
        
        $uidsToEdit = array_unique($uidsToEdit);
        sort($uidsToEdit);

        $returnUrl = (string)$this->backendUriBuilder->buildUriFromRoute($this->request->getAttribute('module')->getIdentifier());
        return (string)$this->backendUriBuilder->buildUriFromRoute('record_edit', [
            'edit' => [
                $this->tableName => [
                    implode(',', $uidsToEdit) => 'edit'
                ]
            ],
            'returnUrl' => $returnUrl
        ]);
    }

    protected function getCurrentBeUser(): array
    {
        return $GLOBALS['BE_USER']->user;
    }

    abstract protected function getTemplatePath(): string;
}
