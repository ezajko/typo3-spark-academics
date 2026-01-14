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
 *        ProjectStatus, ProjectType, FundingProgram,
 *        SDG, CourseCategory, StudyCycle, CourseStatus, TeachingMethod, Language
 * 
 * Usage:
 *   ddev typo3 academics:seed --pid=123
 *   ddev typo3 academics:seed --pid=123 --type=sdg
 *   ddev typo3 academics:seed --pid=123 --type=course-categories --force
 * 
 * @author Ernedin Zajko <ezajko@root.ba>
 */
#[AsCommand(
    name: 'academics:seed',
    description: 'Seed lookup tables with initial data (ScientificField, AcademicRank, SDG, CourseCategory, etc.)',
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
        'sdg',
        'course-categories',
        'study-cycles',
        'course-status',
        'teaching-methods',
        'languages',
        'study-types',
        'study-modes',
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
        
        if ($type === 'all' || $type === 'sdg') {
            $folderPid = $this->getOrCreateFolderPage($io, $pid, 'SDG ciljevi', 'SDG Goals');
            $totalInserted += $this->seedSdg($io, $folderPid, $force);
        }
        
        if ($type === 'all' || $type === 'course-categories') {
            $folderPid = $this->getOrCreateFolderPage($io, $pid, 'Kategorije predmeta', 'Course Categories');
            $totalInserted += $this->seedCourseCategories($io, $folderPid, $force);
        }
        
        if ($type === 'all' || $type === 'study-cycles') {
            $folderPid = $this->getOrCreateFolderPage($io, $pid, 'Ciklusi studija', 'Study Cycles');
            $totalInserted += $this->seedStudyCycles($io, $folderPid, $force);
        }
        
        if ($type === 'all' || $type === 'course-status') {
            $folderPid = $this->getOrCreateFolderPage($io, $pid, 'Statusi predmeta', 'Course Status');
            $totalInserted += $this->seedCourseStatus($io, $folderPid, $force);
        }
        
        if ($type === 'all' || $type === 'teaching-methods') {
            $folderPid = $this->getOrCreateFolderPage($io, $pid, 'Metode nastave', 'Teaching Methods');
            $totalInserted += $this->seedTeachingMethods($io, $folderPid, $force);
        }
        
        if ($type === 'all' || $type === 'languages') {
            $folderPid = $this->getOrCreateFolderPage($io, $pid, 'Jezici', 'Languages');
            $totalInserted += $this->seedLanguages($io, $folderPid, $force);
        }

        if ($type === 'all' || $type === 'study-types') {
            $folderPid = $this->getOrCreateFolderPage($io, $pid, 'Tipovi studija', 'Study Types');
            $totalInserted += $this->seedStudyTypes($io, $folderPid, $force);
        }

        if ($type === 'all' || $type === 'study-modes') {
            $folderPid = $this->getOrCreateFolderPage($io, $pid, 'Načini studiranja', 'Study Modes');
            $totalInserted += $this->seedStudyModes($io, $folderPid, $force);
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
            // Level 1 - Major fields (English primary, TYPO3 localization for translations)
            ['code' => '1', 'title' => 'Natural Sciences', 'level' => 1],
            ['code' => '2', 'title' => 'Engineering and Technology', 'level' => 1],
            ['code' => '3', 'title' => 'Medical and Health Sciences', 'level' => 1],
            ['code' => '4', 'title' => 'Agricultural and Veterinary Sciences', 'level' => 1],
            ['code' => '5', 'title' => 'Social Sciences', 'level' => 1],
            ['code' => '6', 'title' => 'Humanities and the Arts', 'level' => 1],
            
            // Level 2 - Natural Sciences
            ['code' => '1.1', 'title' => 'Mathematics', 'level' => 2],
            ['code' => '1.2', 'title' => 'Computer and Information Sciences', 'level' => 2],
            ['code' => '1.3', 'title' => 'Physical Sciences', 'level' => 2],
            ['code' => '1.4', 'title' => 'Chemical Sciences', 'level' => 2],
            ['code' => '1.5', 'title' => 'Earth and Related Environmental Sciences', 'level' => 2],
            ['code' => '1.6', 'title' => 'Biological Sciences', 'level' => 2],
            ['code' => '1.7', 'title' => 'Other Natural Sciences', 'level' => 2],
            
            // Level 2 - Engineering and Technology
            ['code' => '2.1', 'title' => 'Civil Engineering', 'level' => 2],
            ['code' => '2.2', 'title' => 'Electrical, Electronic and Information Engineering', 'level' => 2],
            ['code' => '2.3', 'title' => 'Mechanical Engineering', 'level' => 2],
            ['code' => '2.4', 'title' => 'Chemical Engineering', 'level' => 2],
            ['code' => '2.5', 'title' => 'Materials Engineering', 'level' => 2],
            ['code' => '2.6', 'title' => 'Medical Engineering', 'level' => 2],
            ['code' => '2.7', 'title' => 'Environmental Engineering', 'level' => 2],
            ['code' => '2.8', 'title' => 'Environmental Biotechnology', 'level' => 2],
            ['code' => '2.9', 'title' => 'Industrial Biotechnology', 'level' => 2],
            ['code' => '2.10', 'title' => 'Nanotechnology', 'level' => 2],
            ['code' => '2.11', 'title' => 'Other Engineering and Technologies', 'level' => 2],
            
            // Level 2 - Social Sciences
            ['code' => '5.1', 'title' => 'Psychology', 'level' => 2],
            ['code' => '5.2', 'title' => 'Economics and Business', 'level' => 2],
            ['code' => '5.3', 'title' => 'Educational Sciences', 'level' => 2],
            ['code' => '5.4', 'title' => 'Sociology', 'level' => 2],
            ['code' => '5.5', 'title' => 'Law', 'level' => 2],
            ['code' => '5.6', 'title' => 'Political Science', 'level' => 2],
            ['code' => '5.7', 'title' => 'Social and Economic Geography', 'level' => 2],
            ['code' => '5.8', 'title' => 'Media and Communications', 'level' => 2],
            ['code' => '5.9', 'title' => 'Other Social Sciences', 'level' => 2],
        ];
        
        return $this->insertRecords($io, 'tx_academics_scientific_field', $data, $pid, $force, 'code');
    }

    /**
     * Seeds Academic Ranks (BiH/regional system)
     */
    private function seedAcademicRanks(SymfonyStyle $io, int $pid, bool $force): int
    {
        $io->section('Seeding Academic Ranks');
        
        // English primary, abbreviations are rank only (title like Dr. comes from AcademicTitle)
        $data = [
            ['title' => 'Full Professor', 'abbreviation' => 'Prof.', 'sorting' => 10],
            ['title' => 'Associate Professor', 'abbreviation' => 'Assoc. Prof.', 'sorting' => 20],
            ['title' => 'Assistant Professor', 'abbreviation' => 'Asst. Prof.', 'sorting' => 30],
            ['title' => 'Senior Teaching Assistant', 'abbreviation' => 'Sr. TA', 'sorting' => 40],
            ['title' => 'Teaching Assistant', 'abbreviation' => 'TA', 'sorting' => 50],
            ['title' => 'Professor Emeritus', 'abbreviation' => 'Prof. Em.', 'sorting' => 5],
            ['title' => 'Lecturer', 'abbreviation' => 'Lect.', 'sorting' => 60],
            ['title' => 'Senior Lecturer', 'abbreviation' => 'Sr. Lect.', 'sorting' => 55],
            ['title' => 'Research Associate', 'abbreviation' => 'Res. Assoc.', 'sorting' => 70],
            ['title' => 'Senior Research Associate', 'abbreviation' => 'Sr. Res. Assoc.', 'sorting' => 65],
            ['title' => 'Research Advisor', 'abbreviation' => 'Res. Adv.', 'sorting' => 15],
        ];
        
        return $this->insertRecords($io, 'tx_academics_academic_rank', $data, $pid, $force, 'title');
    }

    /**
     * Seeds Academic Titles (degrees)
     */
    private function seedAcademicTitles(SymfonyStyle $io, int $pid, bool $force): int
    {
        $io->section('Seeding Academic Titles');
        
        // English primary, abbreviation = before name, abbreviation_after = after name
        $data = [
            ['title' => 'Doctor of Philosophy', 'abbreviation' => 'Dr.', 'abbreviation_after' => 'PhD', 'sorting' => 10],
            ['title' => 'Master of Science', 'abbreviation' => '', 'abbreviation_after' => 'MSc', 'sorting' => 20],
            ['title' => 'Master of Arts', 'abbreviation' => '', 'abbreviation_after' => 'MA', 'sorting' => 25],
            ['title' => 'Bachelor of Science', 'abbreviation' => '', 'abbreviation_after' => 'BSc', 'sorting' => 30],
            ['title' => 'Bachelor of Arts', 'abbreviation' => '', 'abbreviation_after' => 'BA', 'sorting' => 35],
            ['title' => 'Bachelor of Engineering', 'abbreviation' => '', 'abbreviation_after' => 'BEng', 'sorting' => 32],
            ['title' => 'Doctor of Medicine', 'abbreviation' => 'Dr.', 'abbreviation_after' => 'MD', 'sorting' => 15],
            ['title' => 'Academician', 'abbreviation' => 'Acad.', 'abbreviation_after' => '', 'sorting' => 5],
        ];
        
        return $this->insertRecords($io, 'tx_academics_academic_title', $data, $pid, $force, 'title');
    }

    /**
     * Seeds Project Status options
     */
    private function seedProjectStatus(SymfonyStyle $io, int $pid, bool $force): int
    {
        $io->section('Seeding Project Status');
        
        $data = [
            ['title' => 'In Preparation', 'description' => 'Project is being prepared'],
            ['title' => 'Submitted', 'description' => 'Application submitted'],
            ['title' => 'Approved', 'description' => 'Project approved'],
            ['title' => 'Active', 'description' => 'Project is active/running'],
            ['title' => 'Completed', 'description' => 'Project completed'],
            ['title' => 'Rejected', 'description' => 'Application rejected'],
            ['title' => 'Suspended', 'description' => 'Project suspended'],
        ];
        
        return $this->insertRecords($io, 'tx_academics_project_status', $data, $pid, $force, 'title');
    }

    /**
     * Seeds Project Type classification
     */
    private function seedProjectTypes(SymfonyStyle $io, int $pid, bool $force): int
    {
        $io->section('Seeding Project Types');
        
        $data = [
            ['title' => 'Research Project', 'description' => 'Research-focused project'],
            ['title' => 'Development Project', 'description' => 'Development project'],
            ['title' => 'Infrastructure Project', 'description' => 'Infrastructure project'],
            ['title' => 'Educational Project', 'description' => 'Educational/training project'],
            ['title' => 'Mobility', 'description' => 'Mobility/exchange project'],
            ['title' => 'Capacity Building', 'description' => 'Capacity building project'],
            ['title' => 'Innovation Project', 'description' => 'Innovation project'],
        ];
        
        return $this->insertRecords($io, 'tx_academics_project_type', $data, $pid, $force, 'title');
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
            ['title' => 'Federal Ministry', 'description' => 'Federal Ministry of Education funding'],
            ['title' => 'Cantonal Ministry', 'description' => 'Cantonal Ministry of Education funding'],
            ['title' => 'Internal Funding', 'description' => 'Internal / Self-funded'],
        ];
        
        return $this->insertRecords($io, 'tx_academics_funding_program', $data, $pid, $force, 'title');
    }

    /**
     * Seeds UN Sustainable Development Goals (17 SDGs)
     */
    private function seedSdg(SymfonyStyle $io, int $pid, bool $force): int
    {
        $io->section('Seeding SDG Goals');
        
        $data = [
            ['number' => 1, 'title' => 'No Poverty', 'description' => 'End poverty in all its forms everywhere'],
            ['number' => 2, 'title' => 'Zero Hunger', 'description' => 'End hunger, achieve food security and improved nutrition'],
            ['number' => 3, 'title' => 'Good Health and Well-being', 'description' => 'Ensure healthy lives and promote well-being for all'],
            ['number' => 4, 'title' => 'Quality Education', 'description' => 'Ensure inclusive and equitable quality education'],
            ['number' => 5, 'title' => 'Gender Equality', 'description' => 'Achieve gender equality and empower all women and girls'],
            ['number' => 6, 'title' => 'Clean Water and Sanitation', 'description' => 'Ensure access to water and sanitation for all'],
            ['number' => 7, 'title' => 'Affordable and Clean Energy', 'description' => 'Ensure access to affordable, reliable energy'],
            ['number' => 8, 'title' => 'Decent Work and Economic Growth', 'description' => 'Promote decent work and economic growth'],
            ['number' => 9, 'title' => 'Industry, Innovation and Infrastructure', 'description' => 'Build resilient infrastructure and foster innovation'],
            ['number' => 10, 'title' => 'Reduced Inequalities', 'description' => 'Reduce inequality within and among countries'],
            ['number' => 11, 'title' => 'Sustainable Cities and Communities', 'description' => 'Make cities inclusive, safe, resilient and sustainable'],
            ['number' => 12, 'title' => 'Responsible Consumption and Production', 'description' => 'Ensure sustainable consumption and production patterns'],
            ['number' => 13, 'title' => 'Climate Action', 'description' => 'Take urgent action to combat climate change'],
            ['number' => 14, 'title' => 'Life Below Water', 'description' => 'Conserve and sustainably use the oceans'],
            ['number' => 15, 'title' => 'Life on Land', 'description' => 'Protect, restore and promote sustainable ecosystems'],
            ['number' => 16, 'title' => 'Peace, Justice and Strong Institutions', 'description' => 'Promote peaceful and inclusive societies'],
            ['number' => 17, 'title' => 'Partnerships for the Goals', 'description' => 'Strengthen global partnerships for sustainable development'],
        ];
        
        return $this->insertRecords($io, 'tx_academics_sdg', $data, $pid, $force, 'number');
    }

    /**
     * Seeds Course Categories (difficulty levels)
     */
    private function seedCourseCategories(SymfonyStyle $io, int $pid, bool $force): int
    {
        $io->section('Seeding Course Categories');
        
        $data = [
            ['title' => 'Core Course', 'code' => 'A', 'description' => 'Fundamental required course', 'sorting' => 10],
            ['title' => 'Elective Course', 'code' => 'B', 'description' => 'Optional elective course', 'sorting' => 20],
            ['title' => 'Specialized Course', 'code' => 'C', 'description' => 'Specialized/advanced course', 'sorting' => 30],
            ['title' => 'General Education', 'code' => 'D', 'description' => 'General education course', 'sorting' => 40],
            ['title' => 'Practical Course', 'code' => 'E', 'description' => 'Practice/internship course', 'sorting' => 50],
        ];
        
        return $this->insertRecords($io, 'tx_academics_course_category', $data, $pid, $force, 'code');
    }

    /**
     * Seeds Study Cycles (Bologna I/II/III)
     */
    private function seedStudyCycles(SymfonyStyle $io, int $pid, bool $force): int
    {
        $io->section('Seeding Study Cycles');
        
        $data = [
            ['title' => 'First Cycle (Bachelor)', 'level' => 1, 'description' => 'Undergraduate studies, 180-240 ECTS'],
            ['title' => 'Second Cycle (Master)', 'level' => 2, 'description' => 'Graduate studies, 60-120 ECTS'],
            ['title' => 'Third Cycle (Doctoral)', 'level' => 3, 'description' => 'Doctoral studies, 180 ECTS'],
        ];
        
        return $this->insertRecords($io, 'tx_academics_study_cycle', $data, $pid, $force, 'level');
    }

    /**
     * Seeds Course Status options
     */
    private function seedCourseStatus(SymfonyStyle $io, int $pid, bool $force): int
    {
        $io->section('Seeding Course Status');
        
        $data = [
            ['title' => 'Active', 'code' => 'active', 'sorting' => 10],
            ['title' => 'Inactive', 'code' => 'inactive', 'sorting' => 20],
            ['title' => 'Archived', 'code' => 'archived', 'sorting' => 30],
            ['title' => 'Draft', 'code' => 'draft', 'sorting' => 5],
        ];
        
        return $this->insertRecords($io, 'tx_academics_course_status', $data, $pid, $force, 'code');
    }

    /**
     * Seeds Teaching Methods
     */
    private function seedTeachingMethods(SymfonyStyle $io, int $pid, bool $force): int
    {
        $io->section('Seeding Teaching Methods');
        
        $data = [
            ['title' => 'Lectures', 'description' => 'Traditional classroom lectures', 'sorting' => 10],
            ['title' => 'Exercises', 'description' => 'Practical exercises and problem solving', 'sorting' => 20],
            ['title' => 'Laboratory Work', 'description' => 'Hands-on laboratory sessions', 'sorting' => 30],
            ['title' => 'Seminars', 'description' => 'Student presentations and discussions', 'sorting' => 40],
            ['title' => 'Project Work', 'description' => 'Individual or group projects', 'sorting' => 50],
            ['title' => 'E-Learning', 'description' => 'Online/distance learning', 'sorting' => 60],
            ['title' => 'Field Work', 'description' => 'On-site practical work', 'sorting' => 70],
            ['title' => 'Consultations', 'description' => 'Individual consultations with instructor', 'sorting' => 80],
            ['title' => 'Case Studies', 'description' => 'Analysis of real-world cases', 'sorting' => 90],
            ['title' => 'Workshops', 'description' => 'Interactive hands-on workshops', 'sorting' => 100],
        ];
        
        return $this->insertRecords($io, 'tx_academics_teaching_method', $data, $pid, $force, 'title');
    }

    /**
     * Seeds Languages of instruction
     */
    private function seedLanguages(SymfonyStyle $io, int $pid, bool $force): int
    {
        $io->section('Seeding Languages');
        
        $data = [
            ['title' => 'Bosnian', 'code' => 'bs', 'sorting' => 10],
            ['title' => 'Croatian', 'code' => 'hr', 'sorting' => 20],
            ['title' => 'Serbian', 'code' => 'sr', 'sorting' => 30],
            ['title' => 'English', 'code' => 'en', 'sorting' => 40],
            ['title' => 'German', 'code' => 'de', 'sorting' => 50],
            ['title' => 'French', 'code' => 'fr', 'sorting' => 60],
            ['title' => 'Turkish', 'code' => 'tr', 'sorting' => 70],
            ['title' => 'Arabic', 'code' => 'ar', 'sorting' => 80],
        ];
        
        return $this->insertRecords($io, 'tx_academics_language', $data, $pid, $force, 'code');
    }

    /**
     * Seeds Study Types (Academic, Professional, etc.)
     */
    private function seedStudyTypes(SymfonyStyle $io, int $pid, bool $force): int
    {
        $io->section('Seeding Study Types');

        $data = [
            ['title' => 'Academic (University)', 'description' => 'Academic university study program', 'sorting' => 10],
            ['title' => 'Professional (Vocational)', 'description' => 'Professional vocational study program', 'sorting' => 20],
            ['title' => 'Interdisciplinary', 'description' => 'Interdisciplinary study program', 'sorting' => 30],
            ['title' => 'Specialist', 'description' => 'Specialist study program', 'sorting' => 40],
        ];

        return $this->insertRecords($io, 'tx_academics_study_type', $data, $pid, $force, 'title');
    }

    /**
     * Seeds Modes of Study (Full-time, Part-time, DL)
     */
    private function seedStudyModes(SymfonyStyle $io, int $pid, bool $force): int
    {
        $io->section('Seeding Study Modes');

        $data = [
            ['title' => 'Full-time', 'description' => 'Regular full-time study', 'sorting' => 10],
            ['title' => 'Part-time', 'description' => 'Part-time study (work and study)', 'sorting' => 20],
            ['title' => 'Distance Learning', 'description' => 'Distance learning / E-learning', 'sorting' => 30],
        ];

        return $this->insertRecords($io, 'tx_academics_mode_of_study', $data, $pid, $force, 'title');
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
