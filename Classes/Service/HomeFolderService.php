<?php

declare(strict_types=1);

namespace EtfUnsa\SparkAcademics\Service;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Psr\Log\LoggerInterface;

/**
 * Service to manage Backend User Home Folders
 */
class HomeFolderService
{
    protected ResourceFactory $resourceFactory;
    protected ConnectionPool $connectionPool;
    protected SiteFinder $siteFinder;
    protected LoggerInterface $logger;

    public function __construct(
        ResourceFactory $resourceFactory,
        ConnectionPool $connectionPool,
        SiteFinder $siteFinder,
        LoggerInterface $logger
    ) {
        $this->resourceFactory = $resourceFactory;
        $this->connectionPool = $connectionPool;
        $this->siteFinder = $siteFinder;
        $this->logger = $logger;
    }

    /**
     * Ensure a home folder and file mount exists for the given backend user
     */
    public function ensureHomeFolder(int $userId, array $userData = []): void
    {
        if ($userId <= 0) {
            return;
        }

        // 1. Get Configuration from Site
        $config = $this->getHomeFolderConfig();
        if (!$config) {
            $this->logger->warning('HomeFolderService: No Site Configuration found for Home Folders.');
            return;
        }

        $storageUid = (int)$config['storageUid'];
        $basePath = trim($config['basePath'], '/') . '/';
        
        $username = $userData['username'] ?? $this->getUsername($userId);
        if (empty($username)) {
            return;
        }

        // 2. Get/Create Folder
        try {
            $storage = $this->resourceFactory->getStorageObject($storageUid);
            // Use UID for folder name (immutable), keep username for labels
            $folderPath = $basePath . $userId;

            if (!$storage->hasFolder($folderPath)) {
                $storage->createFolder($folderPath);
            }
            $folder = $storage->getFolder($folderPath);
            
        } catch (\Exception $e) {
            $this->logger->error('HomeFolderService: Failed to create folder for user ' . $userId . '. ' . $e->getMessage());
            return;
        }

        // 3. Get/Create sys_filemount
        // We still pass username for the Mount Title
        $mountUid = $this->ensureFileMount($username, $folder->getIdentifier(), $storageUid);

        // 4. Assign Mount & TSConfig to User
        $this->updateUserRecord($userId, $mountUid, $folder->getCombinedIdentifier());
    }

    private function getHomeFolderConfig(): ?array
    {
        $sites = $this->siteFinder->getAllSites();
        foreach ($sites as $site) {
            $storageUid = $site->getAttribute('spark_home_storage_uid');
            if ($storageUid) {
                return [
                    'storageUid' => $storageUid,
                    'basePath' => $site->getAttribute('spark_home_path') ?? 'user_homes/',
                ];
            }
        }
        return null; // No config found
    }

    private function getUsername(int $userId): string
    {
        $queryBuilder = $this->connectionPool->getQueryBuilderForTable('be_users');
        return (string)$queryBuilder
            ->select('username')
            ->from('be_users')
            ->where($queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($userId, Connection::PARAM_INT)))
            ->executeQuery()
            ->fetchOne();
    }

    private function ensureFileMount(string $username, string $path, int $storageUid): int
    {
        $title = 'Home: ' . $username;
        // Construct identifier: "storageUid:/path/to/folder/"
        // Note: path usually starts with / in internal identifier
        $identifier = $storageUid . ':' . $path;
        
        $queryBuilder = $this->connectionPool->getQueryBuilderForTable('sys_filemounts');
        
        // Check if exists (by identifier)
        $existing = $queryBuilder
            ->select('uid')
            ->from('sys_filemounts')
            ->where(
                $queryBuilder->expr()->eq('identifier', $queryBuilder->createNamedParameter($identifier))
            )
            ->executeQuery()
            ->fetchOne();

        if ($existing) {
            return (int)$existing;
        }

        // Create new
        $this->connectionPool->getConnectionForTable('sys_filemounts')->insert(
            'sys_filemounts',
            [
                'title' => $title,
                'identifier' => $identifier,
                'pid' => 0, // Root level
                'read_only' => 0,
            ]
        );

        return (int)$this->connectionPool->getConnectionForTable('sys_filemounts')->lastInsertId();
    }

    private function updateUserRecord(int $userId, int $mountUid, string $combinedIdentifier): void
    {
        $queryBuilder = $this->connectionPool->getQueryBuilderForTable('be_users');
        $row = $queryBuilder
            ->select('file_mountpoints', 'tsconfig')
            ->from('be_users')
            ->where($queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($userId, Connection::PARAM_INT)))
            ->executeQuery()
            ->fetchAssociative();

        if (!$row) {
            return;
        }

        $currentMounts = GeneralUtility::intExplode(',', (string)$row['file_mountpoints'], true);
        
        // Add mount if not present
        if (!in_array($mountUid, $currentMounts, true)) {
            $currentMounts[] = $mountUid;
            $newMounts = implode(',', $currentMounts);

            // TSConfig for default upload folder
            $tsConfig = (string)$row['tsconfig'];
            $uploadFolderConfig = "options.defaultUploadFolder = " . $combinedIdentifier;
            
            // Append if not exists (simple check)
            if (strpos($tsConfig, 'options.defaultUploadFolder') === false) {
                 $tsConfig .= "\n" . $uploadFolderConfig;
            } else {
                // If it exists, we might overwrite, but regex is safer. For now, simple append acts as override in TSConfig usually.
                 $tsConfig .= "\n" . $uploadFolderConfig;
            }

            $this->connectionPool->getConnectionForTable('be_users')->update(
                'be_users',
                [
                    'file_mountpoints' => $newMounts,
                    'tsconfig' => $tsConfig
                ],
                ['uid' => $userId]
            );
        }
    }
}
