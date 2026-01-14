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
 * Console command to generate test courses with syllabi
 * 
 * Generates sample courses linked to existing lookup tables
 * for testing and demonstration purposes.
 * 
 * Usage:
 *   ddev typo3 academics:generate-courses --pid=199 --count=30
 * 
 * @author Ernedin Zajko <ezajko@root.ba>
 */
#[AsCommand(
    name: 'academics:generate-courses',
    description: 'Generate test courses with syllabi for testing purposes',
)]
class GenerateTestCoursesCommand extends Command
{
    // ETF-specific course name prefixes
    private const COURSE_PREFIXES = [
        'Introduction to', 'Advanced', 'Fundamentals of', 'Applied', 
        'Digital', 'Modern', 'Computational', 'Engineering', 'Technical',
        'Analysis of', 'Design of', 'Theory of', 'Practical', 'Industrial'
    ];

    // ETF-related course topics
    private const COURSE_TOPICS = [
        'Programming', 'Algorithms', 'Data Structures', 'Databases',
        'Computer Networks', 'Operating Systems', 'Software Engineering',
        'Artificial Intelligence', 'Machine Learning', 'Cybersecurity',
        'Electronics', 'Digital Systems', 'Signal Processing',
        'Power Systems', 'Control Systems', 'Telecommunications',
        'Automation', 'Robotics', 'Embedded Systems', 'Microcontrollers',
        'Computer Architecture', 'Web Technologies', 'Mobile Applications',
        'Cloud Computing', 'Internet of Things', 'Mathematics',
        'Physics', 'Statistics', 'Technical Writing', 'Project Management'
    ];

    protected function configure(): void
    {
        $this
            ->setHelp('Generates test course records with syllabus data, linked to existing lookup tables.')
            ->addOption(
                'pid',
                'p',
                InputOption::VALUE_REQUIRED,
                'Page ID (PID) where course records will be stored',
            )
            ->addOption(
                'count',
                'c',
                InputOption::VALUE_REQUIRED,
                'Number of courses to generate',
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
        
        $io->title('Test Course Generator');
        $io->text("Generating $count test courses with syllabi in PID: $pid");
        
        // Load lookup table data
        $lookups = $this->loadLookupData();
        
        if (empty($lookups['categories']) || empty($lookups['cycles']) || empty($lookups['statuses'])) {
            $io->error('No lookup data found. Please run academics:seed first.');
            return Command::FAILURE;
        }
        
        $io->text('Found lookup data:');
        $io->listing([
            count($lookups['categories']) . ' course categories',
            count($lookups['cycles']) . ' study cycles',
            count($lookups['statuses']) . ' course statuses',
            count($lookups['methods']) . ' teaching methods',
            count($lookups['languages']) . ' languages',
            count($lookups['sdgs']) . ' SDG goals',
            count($lookups['fields']) . ' scientific fields',
            count($lookups['organizations']) . ' organizations',
        ]);
        
        $faker = FakerFactory::create('en_US');
        $courseConnection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('tx_academics_course');
        $syllabusConnection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('tx_academics_course_syllabus');
        
        $io->progressStart($count);
        
        for ($i = 0; $i < $count; $i++) {
            // Create Course
            $course = $this->generateCourse($faker, $lookups, $pid, $i);
            $courseConnection->insert('tx_academics_course', $course);
            $courseUid = (int)$courseConnection->lastInsertId('tx_academics_course');
            
            // Create 1-3 Syllabus versions
            $numSyllabi = $faker->numberBetween(1, 3);
            for ($s = 0; $s < $numSyllabi; $s++) {
                $syllabus = $this->generateSyllabus($faker, $lookups, $pid, $courseUid, $s);
                $syllabusConnection->insert('tx_academics_course_syllabus', $syllabus);
                $syllabusUid = (int)$syllabusConnection->lastInsertId('tx_academics_course_syllabus');
                
                // Add M:N relations for SDG and Teaching Methods
                $this->addSdgRelations($syllabusUid, $lookups['sdgs'], $faker);
                $this->addTeachingMethodRelations($syllabusUid, $lookups['methods'], $faker);
            }
            
            // Update syllabi count on course
            $courseConnection->update('tx_academics_course', ['syllabi' => $numSyllabi], ['uid' => $courseUid]);
            
            $io->progressAdvance();
        }
        
        $io->progressFinish();
        $io->success("Successfully generated $count test courses with syllabi!");
        
        return Command::SUCCESS;
    }

    /**
     * Load existing lookup table data
     */
    private function loadLookupData(): array
    {
        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
        
        $categories = $connectionPool->getConnectionForTable('tx_academics_course_category')
            ->select(['uid'], 'tx_academics_course_category', ['deleted' => 0])
            ->fetchAllAssociative();
        
        $cycles = $connectionPool->getConnectionForTable('tx_academics_study_cycle')
            ->select(['uid'], 'tx_academics_study_cycle', ['deleted' => 0])
            ->fetchAllAssociative();
        
        $statuses = $connectionPool->getConnectionForTable('tx_academics_course_status')
            ->select(['uid'], 'tx_academics_course_status', ['deleted' => 0])
            ->fetchAllAssociative();
        
        $methods = $connectionPool->getConnectionForTable('tx_academics_teaching_method')
            ->select(['uid'], 'tx_academics_teaching_method', ['deleted' => 0])
            ->fetchAllAssociative();
        
        $languages = $connectionPool->getConnectionForTable('tx_academics_language')
            ->select(['uid'], 'tx_academics_language', ['deleted' => 0])
            ->fetchAllAssociative();
        
        $sdgs = $connectionPool->getConnectionForTable('tx_academics_sdg')
            ->select(['uid'], 'tx_academics_sdg', ['deleted' => 0])
            ->fetchAllAssociative();
        
        $fields = $connectionPool->getConnectionForTable('tx_academics_scientific_field')
            ->select(['uid'], 'tx_academics_scientific_field', ['deleted' => 0])
            ->fetchAllAssociative();
        
        $organizations = $connectionPool->getConnectionForTable('tx_academics_organization')
            ->select(['uid'], 'tx_academics_organization', ['deleted' => 0])
            ->fetchAllAssociative();
        
        return [
            'categories' => array_column($categories, 'uid'),
            'cycles' => array_column($cycles, 'uid'),
            'statuses' => array_column($statuses, 'uid'),
            'methods' => array_column($methods, 'uid'),
            'languages' => array_column($languages, 'uid'),
            'sdgs' => array_column($sdgs, 'uid'),
            'fields' => array_column($fields, 'uid'),
            'organizations' => array_column($organizations, 'uid'),
        ];
    }

    /**
     * Generate a single course record
     */
    private function generateCourse($faker, array $lookups, int $pid, int $index): array
    {
        $prefix = self::COURSE_PREFIXES[array_rand(self::COURSE_PREFIXES)];
        $topic = self::COURSE_TOPICS[array_rand(self::COURSE_TOPICS)];
        $title = "$prefix $topic";
        
        // Generate course code (e.g., IE-101, CS-201)
        $codes = ['IE', 'CS', 'EE', 'TE', 'AU', 'RI'];
        $codePrefix = $codes[array_rand($codes)];
        $codeNumber = $faker->numberBetween(100, 499);
        $code = "$codePrefix-$codeNumber";
        
        // Generate acronym from title
        $words = explode(' ', $topic);
        $acronym = '';
        foreach (array_slice($words, 0, 3) as $word) {
            $acronym .= strtoupper(substr($word, 0, 1));
        }
        
        return [
            'pid' => $pid,
            'code' => $code,
            'title' => $title,
            'acronym' => $acronym,
            'description' => '<p>' . $faker->paragraph(3) . '</p>',
            'courseware_url' => $faker->boolean(60) ? 'https://moodle.etf.unsa.ba/course/' . strtolower($acronym) : '',
            'organization' => !empty($lookups['organizations']) ? $lookups['organizations'][array_rand($lookups['organizations'])] : 0,
            'notes' => $faker->boolean(30) ? '<p>' . $faker->sentence() . '</p>' : '',
            'tstamp' => time(),
            'crdate' => time(),
        ];
    }

    /**
     * Generate a syllabus record for a course
     */
    private function generateSyllabus($faker, array $lookups, int $pid, int $courseUid, int $versionIndex): array
    {
        // Academic year based on version index (older versions = older years)
        $currentYear = (int)date('Y');
        $startYear = $currentYear - $versionIndex;
        $academicYear = "$startYear/" . ($startYear + 1);
        
        $versionLabel = $versionIndex === 0 ? 'Current' : "v" . ($versionIndex + 1) . ".0";
        
        // Contact hours (realistic distribution)
        $hoursLecture = $faker->randomElement([30, 45, 60, 75, 90]);
        $hoursExercise = $faker->randomElement([0, 15, 30, 45]);
        $hoursLab = $faker->randomElement([0, 15, 30, 45]);
        $hoursSeminar = $faker->randomElement([0, 15]);
        $hoursPractice = $faker->randomElement([0, 30, 60]);
        $hoursTotal = $hoursLecture + $hoursExercise + $hoursLab + $hoursSeminar + $hoursPractice;
        $hoursSelfStudy = $faker->numberBetween($hoursTotal, $hoursTotal * 2);
        
        // ECTS (typically 3-8 for regular courses)
        $ects = $faker->randomElement([3, 4, 5, 6, 7, 8]);
        
        return [
            'pid' => $pid,
            'course' => $courseUid,
            'version_label' => $versionLabel,
            'academic_year' => $academicYear,
            'valid_from' => strtotime("$startYear-10-01"),
            'scientific_field' => !empty($lookups['fields']) ? $lookups['fields'][array_rand($lookups['fields'])] : 0,
            'course_category' => $lookups['categories'][array_rand($lookups['categories'])],
            'study_cycle' => $lookups['cycles'][array_rand($lookups['cycles'])],
            'course_status' => $lookups['statuses'][array_rand($lookups['statuses'])],
            'language' => $lookups['languages'][array_rand($lookups['languages'])],
            'ects' => $ects,
            'hours_lecture' => $hoursLecture,
            'hours_exercise' => $hoursExercise,
            'hours_seminar' => $hoursSeminar,
            'hours_lab' => $hoursLab,
            'hours_practice' => $hoursPractice,
            'hours_total' => $hoursTotal,
            'hours_self_study' => $hoursSelfStudy,
            'course_objectives' => '<p>' . $faker->paragraph(3) . '</p><p>' . $faker->paragraph(2) . '</p>',
            'thematic_units' => '<ol><li>' . implode('</li><li>', $faker->sentences(8)) . '</li></ol>',
            'outcomes_knowledge' => '<ul><li>' . implode('</li><li>', $faker->sentences(4)) . '</li></ul>',
            'outcomes_skills' => '<ul><li>' . implode('</li><li>', $faker->sentences(3)) . '</li></ul>',
            'outcomes_competencies' => '<ul><li>' . implode('</li><li>', $faker->sentences(2)) . '</li></ul>',
            'assessment_methods' => '<p><strong>Midterm:</strong> 30%<br><strong>Final:</strong> 40%<br><strong>Homework:</strong> 15%<br><strong>Project:</strong> 15%</p>',
            'prerequisites_description' => $faker->boolean(70) ? '<p>' . $faker->sentence() . '</p>' : '',
            'literature_required' => '<ol><li>' . implode('</li><li>', $faker->sentences(3)) . '</li></ol>',
            'literature_supplementary' => '<ol><li>' . implode('</li><li>', $faker->sentences(2)) . '</li></ol>',
            'tstamp' => time(),
            'crdate' => time(),
        ];
    }

    /**
     * Add M:N relations to SDG goals
     */
    private function addSdgRelations(int $syllabusUid, array $sdgUids, $faker): void
    {
        if (empty($sdgUids)) {
            return;
        }
        
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_academics_coursesyllabus_sdg_mm');
        
        // Add 1-4 random SDG goals
        $numSdgs = $faker->numberBetween(1, 4);
        $selectedSdgs = $faker->randomElements($sdgUids, min($numSdgs, count($sdgUids)));
        
        foreach ($selectedSdgs as $sorting => $sdgUid) {
            $connection->insert('tx_academics_coursesyllabus_sdg_mm', [
                'uid_local' => $syllabusUid,
                'uid_foreign' => $sdgUid,
                'sorting' => $sorting,
                'sorting_foreign' => $sorting,
            ]);
        }
        
        // Update syllabus sdg_goals count
        $syllabusConnection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_academics_course_syllabus');
        $syllabusConnection->update(
            'tx_academics_course_syllabus',
            ['sdg_goals' => count($selectedSdgs)],
            ['uid' => $syllabusUid]
        );
    }

    /**
     * Add M:N relations to teaching methods
     */
    private function addTeachingMethodRelations(int $syllabusUid, array $methodUids, $faker): void
    {
        if (empty($methodUids)) {
            return;
        }
        
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_academics_coursesyllabus_teachingmethod_mm');
        
        // Add 2-5 random teaching methods
        $numMethods = $faker->numberBetween(2, 5);
        $selectedMethods = $faker->randomElements($methodUids, min($numMethods, count($methodUids)));
        
        foreach ($selectedMethods as $sorting => $methodUid) {
            $connection->insert('tx_academics_coursesyllabus_teachingmethod_mm', [
                'uid_local' => $syllabusUid,
                'uid_foreign' => $methodUid,
                'sorting' => $sorting,
                'sorting_foreign' => $sorting,
            ]);
        }
        
        // Update syllabus teaching_methods count
        $syllabusConnection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_academics_course_syllabus');
        $syllabusConnection->update(
            'tx_academics_course_syllabus',
            ['teaching_methods' => count($selectedMethods)],
            ['uid' => $syllabusUid]
        );
    }
}
