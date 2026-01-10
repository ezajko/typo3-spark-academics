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

namespace EtfUnsa\SparkAcademics\Service;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Psr\Log\LoggerInterface;

/**
 * Service to automatically manage content translations
 */
class TranslationService
{
    protected ConnectionPool $connectionPool;
    protected SiteFinder $siteFinder;
    protected LoggerInterface $logger;

    public function __construct(
        ConnectionPool $connectionPool,
        SiteFinder $siteFinder,
        LoggerInterface $logger
    ) {
        $this->connectionPool = $connectionPool;
        $this->siteFinder = $siteFinder;
        $this->logger = $logger;
    }

    /**
     * Ensures that translation records exist for a given record in all available site languages.
     * WARNING: This uses low-level Database queries to create stubs. 
     * For full DataHandler consistency, one might prefer DataHandler commands, but this is simpler for automation.
     */
    public function ensureTranslations(string $table, int $uid, int $pid): void
    {
        // 1. Get all languages for the site where this record resides
        try {
            // Find site by PID
            $site = $this->siteFinder->getSiteByPageId($pid);
        } catch (\Exception $e) {
            // PID might be root or broken
            return;
        }

        $languages = $site->getLanguages();
        
        foreach ($languages as $language) {
            $langId = $language->getLanguageId();
            if ($langId <= 0) {
                continue; // Skip default language
            }

            // 2. Check if translation exists for this language
            if ($this->translationExists($table, $uid, $langId)) {
                continue;
            }

            // 3. Create Translation Stub
            $this->createTranslationStub($table, $uid, $pid, $langId);
        }
    }

    private function translationExists(string $table, int $l10nParent, int $sysLanguageUid): bool
    {
        $queryBuilder = $this->connectionPool->getQueryBuilderForTable($table);
        $count = $queryBuilder
            ->count('uid')
            ->from($table)
            ->where(
                $queryBuilder->expr()->eq('l10n_parent', $queryBuilder->createNamedParameter($l10nParent, Connection::PARAM_INT)),
                $queryBuilder->expr()->eq('sys_language_uid', $queryBuilder->createNamedParameter($sysLanguageUid, Connection::PARAM_INT)),
                $queryBuilder->expr()->eq('deleted', $queryBuilder->createNamedParameter(0, Connection::PARAM_INT))
            )
            ->executeQuery()
            ->fetchOne();

        return (bool)$count;
    }

    private function createTranslationStub(string $table, int $parentUid, int $pid, int $langId): void
    {
        try {
            // We use DataHandler to create the translation properly (handling copy of fields, etc if configured)
            // But DataHandler in an EventListener can be tricky due to recursion.
            // Let's stick to a simple DB insert for the STUB, and let TYPO3 handle the rest when the user edits it.
            // Important: TYPO3 expects 'l10n_parent' and 'sys_language_uid'.
            // Also we should set 'pid' to the same as parent.
            
            // NOTE: A raw DB insert will NOT copy the "shared" fields.
            // If typical TYPO3 behavior is expected (shared fields visible immediately), we might need DataHandler 'copyToLanguage'.
            // Using DataHandler is safer for consistency.
            
            $cmd = [
                $table => [
                    $parentUid => [
                        'localize' => $langId
                    ]
                ]
            ];

            $dataHandler = GeneralUtility::makeInstance(DataHandler::class);
            $dataHandler->start([], $cmd);
            $dataHandler->process_cmdmap();
            
            // Check errors?
            if (!empty($dataHandler->errorLog)) {
                 $this->logger->warning('TranslationService: DataHandler errors: ' . implode(', ', $dataHandler->errorLog));
            }

        } catch (\Exception $e) {
            $this->logger->error('TranslationService: Failed to create translation for ' . $table . ':' . $parentUid . '. ' . $e->getMessage());
        }
    }
}
