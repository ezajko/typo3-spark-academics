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

use EtfUnsa\SparkAcademics\Domain\Repository\PersonRepository;
use EtfUnsa\SparkAcademics\Service\OrcidService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

class SyncOrcidCommand extends Command
{
    protected PersonRepository $personRepository;
    protected OrcidService $orcidService;
    protected PersistenceManager $persistenceManager;

    public function __construct(
        PersonRepository $personRepository,
        OrcidService $orcidService,
        PersistenceManager $persistenceManager
    ) {
        $this->personRepository = $personRepository;
        $this->orcidService = $orcidService;
        $this->persistenceManager = $persistenceManager;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Syncs publications from ORCID for all Persons with an ORCID ID');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Syncing ORCID Publications');

        $persons = $this->personRepository->findAll();
        $total = $persons->count();
        $io->progressStart($total);

        $syncedCount = 0;
        $skippedCount = 0;
        $errorCount = 0;

        foreach ($persons as $person) {
            $orcidId = $person->getProfileOrcid();
            $name = $person->getFirstName() . ' ' . $person->getLastName();

            if (empty($orcidId)) {
                $skippedCount++;
                $io->progressAdvance();
                continue;
            }

            try {
                // Determine PID for storage (use person's PID)
                // Note: Service relies on repositories which respect storage PIDs generally,
                // but new records will use repository defaults unless set.
                // The service implementation handles basic adding.
                
                $result = $this->orcidService->syncPublicationsForPerson($person);
                
                if ($result['status'] === 'success') {
                    $syncedCount++;
                    // Optional: $io->note("Synced $name: {$result['imported']} new, {$result['updated']} updated.");
                } else {
                    $errorCount++;
                    $io->warning("Error syncing $name ($orcidId): " . ($result['message'] ?? 'Unknown error'));
                }

                // Persist periodically to avoid memory issues and ensure data safety
                $this->persistenceManager->persistAll();

            } catch (\Exception $e) {
                 $errorCount++;
                 $io->error("Exception for $name: " . $e->getMessage());
            }

            $io->progressAdvance();
        }

        $io->progressFinish();

        $io->success("Sync Completed.\nProcessed: $total\nSynced: $syncedCount\nSkipped (No ORCID): $skippedCount\nErrors: $errorCount");

        return Command::SUCCESS;
    }
}
