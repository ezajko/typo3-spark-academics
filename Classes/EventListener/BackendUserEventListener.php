<?php

declare(strict_types=1);

namespace EtfUnsa\SparkAcademics\EventListener;

use EtfUnsa\SparkAcademics\Service\HomeFolderService;
use TYPO3\CMS\Core\DataHandling\Event\AfterRecordCreationEvent;
use TYPO3\CMS\Core\DataHandling\Event\AfterRecordUpdateEvent;

class BackendUserEventListener
{
    protected HomeFolderService $homeFolderService;

    public function __construct(HomeFolderService $homeFolderService)
    {
        $this->homeFolderService = $homeFolderService;
    }

    public function afterRecordCreation(AfterRecordCreationEvent $event): void
    {
        if ($event->getTable() === 'be_users') {
            $this->homeFolderService->ensureHomeFolder((int)$event->getRecordId(), $event->getRecordRow());
        }
    }

    public function afterRecordUpdate(AfterRecordUpdateEvent $event): void
    {
        if ($event->getTable() === 'be_users') {
            $this->homeFolderService->ensureHomeFolder((int)$event->getRecordId(), $event->getRecordRow());
        }
    }
}
