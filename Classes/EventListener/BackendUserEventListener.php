<?php

/*
 * This file is part of the "Spark Academics" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) Ernedin Zajko <ezajko@root.ba>
 */

declare(strict_types=1);

namespace RootBa\Academics\EventListener;

use RootBa\Academics\Service\HomeFolderService;
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
