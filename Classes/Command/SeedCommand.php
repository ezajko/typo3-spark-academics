<?php

declare(strict_types=1);

namespace EtfUnsa\SparkAcademics\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Console command to seed lookup tables with initial data
 * 
 * Seeds: ScientificField (OECD FOS), AcademicRank, AcademicTitle, 
 *        ProjectStatus, ProjectType, FundingProgram
 * 
 * Usage:
 *   ddev typo3 academics:seed --pid=123
 *   ddev typo3 academics:seed --pid=123 --type=scientific-fields
 *   ddev typo3 academics:seed --pid=123 --type=academic-ranks --force
 * 
 * @author Ernedin Zajko <ezajko@root.ba>
 */
#[AsCommand(
    name: 'academics:seed',
    description: 'Seed lookup tables with initial data (ScientificField, AcademicRank, AcademicTitle, etc.)',
)]
class SeedCommand extends Command
{
    private const SEED_TYPES = [
        'all',
        'scientific-fields',
        'academic-ranks',
        'academic-titles',
        'project-status',
        'project-types',
        'funding-programs',
    ];

    protected function configure(): void
    {
        $this
            ->setHelp('Seeds lookup tables with standard data. Use --type to seed specific tables.')
            ->addOption(
                'pid',
                'p',
                InputOption::VALUE_REQUIRED,
                'Page ID (PID) where records will be stored',
            )
            ->addOption(
                'type',
                't',
                InputOption::VALUE_REQUIRED,
                'Type of data to seed: ' . implode(', ', self::SEED_TYPES),
                'all'
            )
            ->addOption(
                'force',
                'f',
                InputOption::VALUE_NONE,
                'Force seeding even if records already exist (will skip duplicates)'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        $pid = (int)$input->getOption('pid');
        $type = $input->getOption('type');
        $force = (bool)$input->getOption('force');
        
        if ($pid <= 0) {
            $io->error('You must specify a valid --pid option (parent folder for lookup tables)');
            return Command::FAILURE;
        }
        
        if (!in_array($type, self::SEED_TYPES)) {
            $io->error('Invalid type. Valid types: ' . implode(', ', self::SEED_TYPES));
            return Command::FAILURE;
        }
        
        $io->title('Academics Data Seeder');
        $io->text("Parent PID: $pid, Type: $type, Force: " . ($force ? 'Yes' : 'No'));
        $io->text('Creating folder pages for each lookup table type...');
        
        $totalInserted = 0;
        
        if ($type === 'all' || $type === 'scientific-fields') {
            $folderPid = $this->getOrCreateFolderPage($io, $pid, 'Naučne oblasti', 'Scientific Fields');
            $totalInserted += $this->seedScientificFields($io, $folderPid, $force);
        }
        
        if ($type === 'all' || $type === 'academic-ranks') {
            $folderPid = $this->getOrCreateFolderPage($io, $pid, 'Naučna zvanja', 'Academic Ranks');
            $totalInserted += $this->seedAcademicRanks($io, $folderPid, $force);
        }
        
        if ($type === 'all' || $type === 'academic-titles') {
            $folderPid = $this->getOrCreateFolderPage($io, $pid, 'Akademske titule', 'Academic Titles');
            $totalInserted += $this->seedAcademicTitles($io, $folderPid, $force);
        }
        
        if ($type === 'all' || $type === 'project-status') {
            $folderPid = $this->getOrCreateFolderPage($io, $pid, 'Statusi projekata', 'Project Status');
            $totalInserted += $this->seedProjectStatus($io, $folderPid, $force);
        }
        
        if ($type === 'all' || $type === 'project-types') {
            $folderPid = $this->getOrCreateFolderPage($io, $pid, 'Tipovi projekata', 'Project Types');
            $totalInserted += $this->seedProjectTypes($io, $folderPid, $force);
        }
        
        if ($type === 'all' || $type === 'funding-programs') {
            $folderPid = $this->getOrCreateFolderPage($io, $pid, 'Programi finansiranja', 'Funding Programs');
            $totalInserted += $this->seedFundingPrograms($io, $folderPid, $force);
        }
        
        $io->success("Seeding complete! Total records inserted: $totalInserted");
        
        return Command::SUCCESS;
    }

    /**
     * Gets or creates a folder page (doktype=254) for a specific lookup type
     */
    private function getOrCreateFolderPage(SymfonyStyle $io, int $parentPid, string $title, string $titleEn): int
    {
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('pages');
        
        // Check if folder already exists
        $existing = $connection->select(
            ['uid'],
            'pages',
            ['pid' => $parentPid, 'title' => $title, 'doktype' => 254, 'deleted' => 0]
        )->fetchAssociative();
        
        if ($existing) {
            $io->text("  → Using existing folder: $title (UID: {$existing['uid']})");
            return (int)$existing['uid'];
        }
        
        // Create new folder page
        $connection->insert('pages', [
            'pid' => $parentPid,
            'doktype' => 254, // Folder
            'title' => $title,
            'slug' => '/' . strtolower(str_replace(' ', '-', $titleEn)),
            'tstamp' => time(),
            'crdate' => time(),
        ]);
        
        $newUid = (int)$connection->lastInsertId('pages');
        $io->text("  → Created folder: $title (UID: $newUid)");
        
        return $newUid;
    }

    /**
     * Seeds OECD Fields of Science classification
     */
    private function seedScientificFields(SymfonyStyle $io, int $pid, bool $force): int
    {
        $io->section('Seeding Scientific Fields (OECD FOS)');
        
        $data = [
            // Level 1 - Major fields
            ['code' => '1', 'title' => 'Prirodne nauke', 'title_en' => 'Natural Sciences', 'level' => 1],
            ['code' => '2', 'title' => 'Inženjerstvo i tehnologija', 'title_en' => 'Engineering and Technology', 'level' => 1],
            ['code' => '3', 'title' => 'Medicinske i zdravstvene nauke', 'title_en' => 'Medical and Health Sciences', 'level' => 1],
            ['code' => '4', 'title' => 'Poljoprivredne i veterinarske nauke', 'title_en' => 'Agricultural and Veterinary Sciences', 'level' => 1],
            ['code' => '5', 'title' => 'Društvene nauke', 'title_en' => 'Social Sciences', 'level' => 1],
            ['code' => '6', 'title' => 'Humanističke nauke i umjetnost', 'title_en' => 'Humanities and the Arts', 'level' => 1],
            
            // Level 2 - Minor fields (Natural Sciences)
            ['code' => '1.1', 'title' => 'Matematika', 'title_en' => 'Mathematics', 'level' => 2],
            ['code' => '1.2', 'title' => 'Računarske i informacijske nauke', 'title_en' => 'Computer and Information Sciences', 'level' => 2],
            ['code' => '1.3', 'title' => 'Fizičke nauke', 'title_en' => 'Physical Sciences', 'level' => 2],
            ['code' => '1.4', 'title' => 'Hemijske nauke', 'title_en' => 'Chemical Sciences', 'level' => 2],
            ['code' => '1.5', 'title' => 'Nauke o Zemlji i okolišu', 'title_en' => 'Earth and Related Environmental Sciences', 'level' => 2],
            ['code' => '1.6', 'title' => 'Biološke nauke', 'title_en' => 'Biological Sciences', 'level' => 2],
            ['code' => '1.7', 'title' => 'Ostale prirodne nauke', 'title_en' => 'Other Natural Sciences', 'level' => 2],
            
            // Level 2 - Engineering and Technology
            ['code' => '2.1', 'title' => 'Građevinarstvo', 'title_en' => 'Civil Engineering', 'level' => 2],
            ['code' => '2.2', 'title' => 'Elektrotehnika, elektronika i informacijske tehnologije', 'title_en' => 'Electrical, Electronic and Information Engineering', 'level' => 2],
            ['code' => '2.3', 'title' => 'Mašinstvo', 'title_en' => 'Mechanical Engineering', 'level' => 2],
            ['code' => '2.4', 'title' => 'Hemijsko inženjerstvo', 'title_en' => 'Chemical Engineering', 'level' => 2],
            ['code' => '2.5', 'title' => 'Inženjerstvo materijala', 'title_en' => 'Materials Engineering', 'level' => 2],
            ['code' => '2.6', 'title' => 'Medicinsko inženjerstvo', 'title_en' => 'Medical Engineering', 'level' => 2],
            ['code' => '2.7', 'title' => 'Inženjerstvo okoliša', 'title_en' => 'Environmental Engineering', 'level' => 2],
            ['code' => '2.8', 'title' => 'Biotehnologija u okolišu', 'title_en' => 'Environmental Biotechnology', 'level' => 2],
            ['code' => '2.9', 'title' => 'Industrijska biotehnologija', 'title_en' => 'Industrial Biotechnology', 'level' => 2],
            ['code' => '2.10', 'title' => 'Nanotehnologija', 'title_en' => 'Nanotechnology', 'level' => 2],
            ['code' => '2.11', 'title' => 'Ostalo inženjerstvo i tehnologije', 'title_en' => 'Other Engineering and Technologies', 'level' => 2],
            
            // Level 2 - Social Sciences
            ['code' => '5.1', 'title' => 'Psihologija', 'title_en' => 'Psychology', 'level' => 2],
            ['code' => '5.2', 'title' => 'Ekonomija i biznis', 'title_en' => 'Economics and Business', 'level' => 2],
            ['code' => '5.3', 'title' => 'Obrazovne nauke', 'title_en' => 'Educational Sciences', 'level' => 2],
            ['code' => '5.4', 'title' => 'Sociologija', 'title_en' => 'Sociology', 'level' => 2],
            ['code' => '5.5', 'title' => 'Pravo', 'title_en' => 'Law', 'level' => 2],
            ['code' => '5.6', 'title' => 'Političke nauke', 'title_en' => 'Political Science', 'level' => 2],
            ['code' => '5.7', 'title' => 'Socijalna i ekonomska geografija', 'title_en' => 'Social and Economic Geography', 'level' => 2],
            ['code' => '5.8', 'title' => 'Mediji i komunikacije', 'title_en' => 'Media and Communications', 'level' => 2],
            ['code' => '5.9', 'title' => 'Ostale društvene nauke', 'title_en' => 'Other Social Sciences', 'level' => 2],
        ];
        
        return $this->insertRecords($io, 'tx_spark_scientific_field', $data, $pid, $force, 'code');
    }

    /**
     * Seeds Academic Ranks (BiH/regional system)
     */
    private function seedAcademicRanks(SymfonyStyle $io, int $pid, bool $force): int
    {
        $io->section('Seeding Academic Ranks');
        
        $data = [
            ['title' => 'Redovni profesor', 'abbreviation' => 'prof. dr.', 'title_en' => 'Full Professor', 'sorting' => 10],
            ['title' => 'Vanredni profesor', 'abbreviation' => 'v.prof. dr.', 'title_en' => 'Associate Professor', 'sorting' => 20],
            ['title' => 'Docent', 'abbreviation' => 'doc. dr.', 'title_en' => 'Assistant Professor', 'sorting' => 30],
            ['title' => 'Viši asistent', 'abbreviation' => 'v.asist.', 'title_en' => 'Senior Assistant', 'sorting' => 40],
            ['title' => 'Asistent', 'abbreviation' => 'asist.', 'title_en' => 'Assistant', 'sorting' => 50],
            ['title' => 'Profesor emeritus', 'abbreviation' => 'prof. emeritus', 'title_en' => 'Professor Emeritus', 'sorting' => 5],
            ['title' => 'Lektor', 'abbreviation' => 'lekt.', 'title_en' => 'Lecturer', 'sorting' => 60],
            ['title' => 'Viši lektor', 'abbreviation' => 'v.lekt.', 'title_en' => 'Senior Lecturer', 'sorting' => 55],
            ['title' => 'Naučni saradnik', 'abbreviation' => 'n.sar.', 'title_en' => 'Research Associate', 'sorting' => 70],
            ['title' => 'Viši naučni saradnik', 'abbreviation' => 'v.n.sar.', 'title_en' => 'Senior Research Associate', 'sorting' => 65],
            ['title' => 'Naučni savjetnik', 'abbreviation' => 'n.savj.', 'title_en' => 'Research Advisor', 'sorting' => 15],
        ];
        
        return $this->insertRecords($io, 'tx_spark_academic_rank', $data, $pid, $force, 'title');
    }

    /**
     * Seeds Academic Titles (degrees)
     */
    private function seedAcademicTitles(SymfonyStyle $io, int $pid, bool $force): int
    {
        $io->section('Seeding Academic Titles');
        
        $data = [
            ['title' => 'Doktor nauka', 'abbreviation' => 'Dr.', 'abbreviation_after' => 'PhD', 'title_en' => 'Doctor of Philosophy', 'sorting' => 10],
            ['title' => 'Magistar nauka', 'abbreviation' => 'Mr.', 'abbreviation_after' => 'MSc', 'title_en' => 'Master of Science', 'sorting' => 20],
            ['title' => 'Magistar', 'abbreviation' => 'MA', 'abbreviation_after' => 'MA', 'title_en' => 'Master of Arts', 'sorting' => 25],
            ['title' => 'Diplomirani inženjer', 'abbreviation' => 'Dipl.ing.', 'abbreviation_after' => 'BSc', 'title_en' => 'Bachelor of Science (Engineering)', 'sorting' => 30],
            ['title' => 'Bachelor', 'abbreviation' => '', 'abbreviation_after' => 'BSc', 'title_en' => 'Bachelor of Science', 'sorting' => 35],
            ['title' => 'Bachelor of Arts', 'abbreviation' => '', 'abbreviation_after' => 'BA', 'title_en' => 'Bachelor of Arts', 'sorting' => 40],
            ['title' => 'Doktor medicine', 'abbreviation' => 'Dr. med.', 'abbreviation_after' => 'MD', 'title_en' => 'Doctor of Medicine', 'sorting' => 15],
            ['title' => 'Akademik', 'abbreviation' => 'Akad.', 'abbreviation_after' => '', 'title_en' => 'Academician', 'sorting' => 5],
        ];
        
        return $this->insertRecords($io, 'tx_spark_academic_title', $data, $pid, $force, 'title');
    }

    /**
     * Seeds Project Status options
     */
    private function seedProjectStatus(SymfonyStyle $io, int $pid, bool $force): int
    {
        $io->section('Seeding Project Status');
        
        $data = [
            ['title' => 'U pripremi', 'color' => '#6c757d', 'description' => 'Project is being prepared'],
            ['title' => 'Apliciran', 'color' => '#17a2b8', 'description' => 'Application submitted'],
            ['title' => 'Odobren', 'color' => '#28a745', 'description' => 'Project approved'],
            ['title' => 'Aktivan', 'color' => '#007bff', 'description' => 'Project is active/running'],
            ['title' => 'Završen', 'color' => '#6f42c1', 'description' => 'Project completed'],
            ['title' => 'Odbijen', 'color' => '#dc3545', 'description' => 'Application rejected'],
            ['title' => 'Suspendovan', 'color' => '#fd7e14', 'description' => 'Project suspended'],
        ];
        
        return $this->insertRecords($io, 'tx_spark_project_status', $data, $pid, $force, 'title');
    }

    /**
     * Seeds Project Type classification
     */
    private function seedProjectTypes(SymfonyStyle $io, int $pid, bool $force): int
    {
        $io->section('Seeding Project Types');
        
        $data = [
            ['title' => 'Istraživački projekt', 'color' => '#007bff', 'description' => 'Research-focused project'],
            ['title' => 'Razvojni projekt', 'color' => '#28a745', 'description' => 'Development project'],
            ['title' => 'Infrastrukturni projekt', 'color' => '#6c757d', 'description' => 'Infrastructure project'],
            ['title' => 'Obrazovni projekt', 'color' => '#17a2b8', 'description' => 'Educational/training project'],
            ['title' => 'Mobilnost', 'color' => '#fd7e14', 'description' => 'Mobility/exchange project'],
            ['title' => 'Kapacitet izgradnje', 'color' => '#6f42c1', 'description' => 'Capacity building project'],
            ['title' => 'Inovacijski projekt', 'color' => '#20c997', 'description' => 'Innovation project'],
        ];
        
        return $this->insertRecords($io, 'tx_spark_project_type', $data, $pid, $force, 'title');
    }

    /**
     * Seeds common Funding Programs
     */
    private function seedFundingPrograms(SymfonyStyle $io, int $pid, bool $force): int
    {
        $io->section('Seeding Funding Programs');
        
        $data = [
            ['title' => 'Horizon Europe', 'description' => 'EU Framework Programme for Research and Innovation (2021-2027)'],
            ['title' => 'Erasmus+', 'description' => 'EU programme for education, training, youth and sport'],
            ['title' => 'COST', 'description' => 'European Cooperation in Science and Technology'],
            ['title' => 'Interreg', 'description' => 'European Territorial Cooperation'],
            ['title' => 'IPA', 'description' => 'Instrument for Pre-accession Assistance'],
            ['title' => 'DAAD', 'description' => 'German Academic Exchange Service'],
            ['title' => 'Fulbright', 'description' => 'US educational exchange program'],
            ['title' => 'Swiss NSF', 'description' => 'Swiss National Science Foundation'],
            ['title' => 'Federalno ministarstvo obrazovanja', 'description' => 'Federal Ministry of Education (Bosnia and Herzegovina)'],
            ['title' => 'Kantonalno ministarstvo obrazovanja', 'description' => 'Cantonal Ministry of Education'],
            ['title' => 'Vlastita sredstva', 'description' => 'Internal funding / Self-funded'],
        ];
        
        return $this->insertRecords($io, 'tx_spark_funding_program', $data, $pid, $force, 'title');
    }

    /**
     * Helper method to insert records into a table
     */
    private function insertRecords(SymfonyStyle $io, string $table, array $data, int $pid, bool $force, string $uniqueField): int
    {
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable($table);
        $inserted = 0;
        $skipped = 0;
        
        foreach ($data as $record) {
            // Check if record already exists
            $existing = $connection->count(
                '*',
                $table,
                [$uniqueField => $record[$uniqueField], 'pid' => $pid, 'deleted' => 0]
            );
            
            if ($existing > 0 && !$force) {
                $skipped++;
                continue;
            }
            
            if ($existing > 0 && $force) {
                // Skip duplicate even with force flag
                $skipped++;
                continue;
            }
            
            // Add system fields
            $record['pid'] = $pid;
            $record['tstamp'] = time();
            $record['crdate'] = time();
            
            $connection->insert($table, $record);
            $inserted++;
        }
        
        $io->text("  - Inserted: $inserted, Skipped: $skipped");
        
        return $inserted;
    }
}
