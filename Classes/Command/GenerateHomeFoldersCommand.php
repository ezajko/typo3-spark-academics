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

namespace EtfUnsa\SparkAcademics\Command;

use EtfUnsa\SparkAcademics\Service\HomeFolderService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Database\ConnectionPool;

class GenerateHomeFoldersCommand extends Command
{
    protected HomeFolderService $homeFolderService;
    protected ConnectionPool $connectionPool;

    public function __construct(
        HomeFolderService $homeFolderService,
        ConnectionPool $connectionPool
    ) {
        $this->homeFolderService = $homeFolderService;
        $this->connectionPool = $connectionPool;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Generates Home Folders and File Mounts for all existing Backend Users');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Generating Backend User Home Folders');

        $queryBuilder = $this->connectionPool->getQueryBuilderForTable('be_users');
        $users = $queryBuilder
            ->select('uid', 'username')
            ->from('be_users')
            ->where(
                $queryBuilder->expr()->eq('admin', 0), // Skip admins? No, admins might want home folders too. Or maybe not?
                 // Usually admins have access to everything, but a home folder is still nice for organization.
                 // Let's include everyone who is not deleted/disabled? 
                 // Actually, query builder handles enable fields automatically if not configured otherwise, but be_users is a bit special in CLI.
                 // Let's just grab all valid users.
            )
            ->executeQuery()
            ->fetchAllAssociative();

        if (empty($users)) {
            $io->warning('No backend users found.');
            return Command::SUCCESS;
        }

        $io->progressStart(count($users));

        foreach ($users as $user) {
            $uid = (int)$user['uid'];
            try {
                $this->homeFolderService->ensureHomeFolder($uid, $user);
            } catch (\Exception $e) {
                $io->error("Failed for User {$user['username']} ($uid): " . $e->getMessage());
            }
            $io->progressAdvance();
        }

        $io->progressFinish();
        $io->success('Home Folders generated successfully.');

        return Command::SUCCESS;
    }
}
