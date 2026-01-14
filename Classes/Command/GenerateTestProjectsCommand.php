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

namespace RootBa\Academics\Command;

use Faker\Factory as FakerFactory;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Console command to generate test projects with faker data
 * 
 * Generates sample projects linked to existing lookup tables
 * for testing and demonstration purposes.
 * 
 * Usage:
 *   ddev typo3 academics:generate-projects --pid=123 --count=30
 * 
 * @author Ernedin Zajko <ezajko@root.ba>
 */
#[AsCommand(
    name: 'academics:generate-projects',
    description: 'Generate test projects with faker data for testing purposes',
)]
class GenerateTestProjectsCommand extends Command
{
    private const PROJECT_PREFIXES = [
        'Advanced', 'Innovative', 'Smart', 'Digital', 'Sustainable', 
        'Integrated', 'Enhanced', 'Next-Gen', 'Collaborative', 'Open',
        'European', 'Regional', 'Cross-border', 'Multidisciplinary', 'Applied'
    ];

    private const PROJECT_TOPICS = [
        'Machine Learning', 'Artificial Intelligence', 'IoT Solutions', 
        'Renewable Energy', 'Smart Cities', 'Cybersecurity', 'Big Data Analytics',
        'Cloud Computing', 'Blockchain Technology', 'Quantum Computing',
        'Robotics', 'Autonomous Systems', 'Digital Transformation', 
        'Green Technology', 'Healthcare Innovation', 'Educational Technology',
        'Bioinformatics', 'Materials Science', 'Energy Efficiency', 
        'Transportation Systems', 'Water Management', 'Agriculture Tech',
        'Financial Technology', 'Social Innovation', 'Cultural Heritage'
    ];

    protected function configure(): void
    {
        $this
            ->setHelp('Generates test project records with faker data, linked to existing lookup tables.')
            ->addOption(
                'pid',
                'p',
                InputOption::VALUE_REQUIRED,
                'Page ID (PID) where project records will be stored',
            )
            ->addOption(
                'count',
                'c',
                InputOption::VALUE_REQUIRED,
                'Number of projects to generate',
                30
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        $pid = (int)$input->getOption('pid');
        $count = (int)$input->getOption('count');
        
        if ($pid <= 0) {
            $io->error('You must specify a valid --pid option');
            return Command::FAILURE;
        }
        
        $io->title('Test Project Generator');
        $io->text("Generating $count test projects in PID: $pid");
        
        // Load lookup table data
        $lookups = $this->loadLookupData();
        
        if (empty($lookups['statuses']) || empty($lookups['types']) || empty($lookups['programs'])) {
            $io->error('No lookup data found. Please run academics:seed first.');
            return Command::FAILURE;
        }
        
        $io->text('Found lookup data:');
        $io->listing([
            count($lookups['statuses']) . ' project statuses',
            count($lookups['types']) . ' project types',
            count($lookups['programs']) . ' funding programs',
            count($lookups['fields']) . ' scientific fields',
        ]);
        
        $faker = FakerFactory::create('en_US');
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('tx_academics_project');
        
        $io->progressStart($count);
        
        for ($i = 0; $i < $count; $i++) {
            $project = $this->generateProject($faker, $lookups, $pid);
            $connection->insert('tx_academics_project', $project);
            $projectUid = (int)$connection->lastInsertId('tx_academics_project');
            
            // Add M:N relation to scientific fields
            $this->addScientificFieldRelations($projectUid, $lookups['fields'], $faker);
            
            $io->progressAdvance();
        }
        
        $io->progressFinish();
        $io->success("Successfully generated $count test projects!");
        
        return Command::SUCCESS;
    }

    /**
     * Load existing lookup table data
     */
    private function loadLookupData(): array
    {
        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
        
        $statuses = $connectionPool->getConnectionForTable('tx_academics_project_status')
            ->select(['uid'], 'tx_academics_project_status', ['deleted' => 0])
            ->fetchAllAssociative();
        
        $types = $connectionPool->getConnectionForTable('tx_academics_project_type')
            ->select(['uid'], 'tx_academics_project_type', ['deleted' => 0])
            ->fetchAllAssociative();
        
        $programs = $connectionPool->getConnectionForTable('tx_academics_funding_program')
            ->select(['uid'], 'tx_academics_funding_program', ['deleted' => 0])
            ->fetchAllAssociative();
        
        $fields = $connectionPool->getConnectionForTable('tx_academics_scientific_field')
            ->select(['uid'], 'tx_academics_scientific_field', ['deleted' => 0, 'level' => 2])
            ->fetchAllAssociative();
        
        return [
            'statuses' => array_column($statuses, 'uid'),
            'types' => array_column($types, 'uid'),
            'programs' => array_column($programs, 'uid'),
            'fields' => array_column($fields, 'uid'),
        ];
    }

    /**
     * Generate a single project record
     */
    private function generateProject($faker, array $lookups, int $pid): array
    {
        $prefix = self::PROJECT_PREFIXES[array_rand(self::PROJECT_PREFIXES)];
        $topic = self::PROJECT_TOPICS[array_rand(self::PROJECT_TOPICS)];
        $title = "$prefix $topic Research";
        
        // Generate acronym from title
        $words = explode(' ', $title);
        $acronym = '';
        foreach ($words as $word) {
            $acronym .= strtoupper(substr($word, 0, 1));
        }
        
        // Random dates (past 5 years to future 3 years)
        $startDate = $faker->dateTimeBetween('-5 years', '+1 year');
        $durationMonths = $faker->numberBetween(12, 48);
        $endDate = (clone $startDate)->modify("+{$durationMonths} months");
        
        // Generate grant agreement number
        $grantNumber = $faker->numberBetween(100000, 999999) . '-' . strtoupper($faker->randomLetter() . $faker->randomLetter());
        
        // Budget ranges
        $totalBudget = $faker->randomFloat(2, 50000, 5000000);
        $localBudget = $faker->randomFloat(2, $totalBudget * 0.1, $totalBudget * 0.4);
        
        return [
            'pid' => $pid,
            'title' => $title,
            'acronym' => $acronym,
            'description' => '<p>' . $faker->paragraph(5) . '</p><p>' . $faker->paragraph(3) . '</p>',
            'objectives' => '<ul><li>' . implode('</li><li>', $faker->sentences(5)) . '</li></ul>',
            'outcomes' => '<p>' . $faker->paragraph(3) . '</p>',
            'keywords' => implode(', ', $faker->words(6)),
            'start_date' => $startDate->getTimestamp(),
            'end_date' => $endDate->getTimestamp(),
            'total_budget' => $totalBudget,
            'local_budget' => $localBudget,
            'grant_agreement_number' => $grantNumber,
            'website' => $faker->boolean(70) ? 'https://' . strtolower($acronym) . '-project.eu' : '',
            'status' => $lookups['statuses'][array_rand($lookups['statuses'])],
            'project_type' => $lookups['types'][array_rand($lookups['types'])],
            'funding_program' => $lookups['programs'][array_rand($lookups['programs'])],
            'tstamp' => time(),
            'crdate' => time(),
        ];
    }

    /**
     * Add M:N relations to scientific fields
     */
    private function addScientificFieldRelations(int $projectUid, array $fieldUids, $faker): void
    {
        if (empty($fieldUids)) {
            return;
        }
        
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_academics_project_scientific_field_mm');
        
        // Add 1-3 random scientific fields
        $numFields = $faker->numberBetween(1, 3);
        $selectedFields = $faker->randomElements($fieldUids, min($numFields, count($fieldUids)));
        
        foreach ($selectedFields as $sorting => $fieldUid) {
            $connection->insert('tx_academics_project_scientific_field_mm', [
                'uid_local' => $projectUid,
                'uid_foreign' => $fieldUid,
                'sorting' => $sorting,
                'sorting_foreign' => $sorting,
            ]);
        }
        
        // Update project scientific_fields count
        $projectConnection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_academics_project');
        $projectConnection->update(
            'tx_academics_project',
            ['scientific_fields' => count($selectedFields)],
            ['uid' => $projectUid]
        );
    }
}
