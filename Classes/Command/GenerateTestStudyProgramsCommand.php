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

use Faker\Factory as FakerFactory;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Crypto\Randomness\Generator;

/**
 * Console command to generate test study programs with curricula
 * 
 * Usage:
 *   ddev typo3 academics:generate-programs --pid=210 --count=5
 * 
 * @author Ernedin Zajko <ezajko@root.ba>
 */
#[AsCommand(
    name: 'academics:generate-programs',
    description: 'Generate test study programs with curricula structure',
)]
class GenerateTestStudyProgramsCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->setHelp('Generates test study program records with deep nested curriculum structure.')
            ->addOption(
                'pid',
                'p',
                InputOption::VALUE_REQUIRED,
                'Page ID (PID) where program records will be stored',
            )
            ->addOption(
                'count',
                'c',
                InputOption::VALUE_REQUIRED,
                'Number of programs to generate',
                5
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
        
        $io->title('Test Study Program Generator');
        $io->text("Generating $count test programs with curricula in PID: $pid");
        
        // Load lookup data
        $lookups = $this->loadLookupData();
        
        if (empty($lookups['organizations'])) {
            $io->error('No Organizations found. Please run basic seeding first.');
            return Command::FAILURE;
        }

        $faker = FakerFactory::create('en_US');
        $connPool = GeneralUtility::makeInstance(ConnectionPool::class);
        $programConn = $connPool->getConnectionForTable('tx_spark_study_program');
        $curriculumConn = $connPool->getConnectionForTable('tx_spark_curriculum');
        $semesterConn = $connPool->getConnectionForTable('tx_spark_curriculum_semester');
        $groupConn = $connPool->getConnectionForTable('tx_spark_course_group');
        
        $io->progressStart($count);
        
        for ($i = 0; $i < $count; $i++) {
            // 1. Create Study Program
            $program = $this->generateStudyProgram($faker, $lookups, $pid);
            $programConn->insert('tx_spark_study_program', $program);
            $programUid = (int)$programConn->lastInsertId('tx_spark_study_program');
            
            // Add Relations (Multi-select)
            $this->addRelations($programUid, 'organization', $lookups['organizations'], 1, 3, $faker);
            $this->addRelations($programUid, 'studytype', $lookups['study_types'], 1, 1, $faker);
            $this->addRelations($programUid, 'modeofstudy', $lookups['modes_of_study'], 1, 2, $faker);
            $this->addRelations($programUid, 'language', $lookups['languages'], 1, 2, $faker);

            // 2. Create Curriculum (e.g. 2024/2025)
            $curriculum = [
                'pid' => $pid,
                'study_program' => $programUid, // Parent Relation
                'title' => 'Curriculum 2024/2025',
                'year' => 2024,
                'uuid' => $faker->uuid(),
                'is_active' => 1,
                'tstamp' => time(),
                'crdate' => time(),
            ];
            $curriculumConn->insert('tx_spark_curriculum', $curriculum);
            $curriculumUid = (int)$curriculumConn->lastInsertId('tx_spark_curriculum');

            // 3. Create Semesters (e.g. 2 semesters)
            for ($sem = 1; $sem <= 2; $sem++) {
                $semester = [
                    'pid' => $pid,
                    'curriculum' => $curriculumUid, // Parent Relation
                    'title' => "Semester $sem",
                    'semester_number' => $sem,
                    'tstamp' => time(),
                    'crdate' => time(),
                ];
                $semesterConn->insert('tx_spark_curriculum_semester', $semester);
                $semesterUid = (int)$semesterConn->lastInsertId('tx_spark_curriculum_semester');

                // 4. Create Course Groups (Slots)
                
                // Group A: Mandatory
                $groupM = [
                    'pid' => $pid,
                    'curriculum_semester' => $semesterUid, // Parent Relation
                    'title' => 'Mandatory Courses',
                    'type' => 'mandatory',
                    'required_counts' => 0, // All
                    'color' => '#FF0000',
                    'tstamp' => time(),
                    'crdate' => time(),
                ];
                $groupConn->insert('tx_spark_course_group', $groupM);
                $groupMUid = (int)$groupConn->lastInsertId('tx_spark_course_group');
                // Assign 3-5 random courses
                $this->addCourseRelations($groupMUid, $lookups['courses'], 3, 5, $faker);

                // Group B: Elective
                $groupE = [
                    'pid' => $pid,
                    'curriculum_semester' => $semesterUid, // Parent Relation
                    'title' => 'Elective Block A',
                    'type' => 'elective',
                    'required_counts' => 1, // Select 1
                    'color' => '#00FF00',
                    'tstamp' => time(),
                    'crdate' => time(),
                ];
                $groupConn->insert('tx_spark_course_group', $groupE);
                $groupEUid = (int)$groupConn->lastInsertId('tx_spark_course_group');
                // Assign 2-4 random courses
                $this->addCourseRelations($groupEUid, $lookups['courses'], 2, 4, $faker);
            }
            
            $io->progressAdvance();
        }
        
        $io->progressFinish();
        $io->success("Successfully generated $count study programs with detailed curricula!");
        
        return Command::SUCCESS;
    }

    private function loadLookupData(): array
    {
        $connPool = GeneralUtility::makeInstance(ConnectionPool::class);
        
        $fetchUids = fn($table) => array_column(
            $connPool->getConnectionForTable($table)
                ->select(['uid'], $table, ['deleted' => 0])
                ->fetchAllAssociative(),
            'uid'
        );

        return [
            'organizations' => $fetchUids('tx_spark_organization'),
            'cycles' => $fetchUids('tx_spark_study_cycle'),
            'fields' => $fetchUids('tx_spark_scientific_field'),
            'languages' => $fetchUids('tx_spark_language'),
            'study_types' => $fetchUids('tx_spark_study_type'),
            'modes_of_study' => $fetchUids('tx_spark_mode_of_study'),
            'courses' => $fetchUids('tx_spark_course'),
        ];
    }

    private function generateStudyProgram($faker, array $lookups, int $pid): array
    {
        $title = $faker->randomElement(['Computer Science', 'Electrical Engineering', 'Data Science', 'Software Engineering', 'Robotics']) . ' ' . $faker->year();
        $acronym = strtoupper(substr($title, 0, 3));
        
        return [
            'pid' => $pid,
            'title' => $title,
            'acronym' => $acronym,
            'uuid' => $faker->uuid(),
            'description' => '<p>' . $faker->paragraph() . '</p>',
            'study_cycle' => !empty($lookups['cycles']) ? $lookups['cycles'][array_rand($lookups['cycles'])] : 0,
            'scientific_field' => !empty($lookups['fields']) ? $lookups['fields'][array_rand($lookups['fields'])] : 0,
            'duration_semesters' => $faker->randomElement([6, 8]),
            'duration_years' => $faker->randomElement([3, 4]),
            'ects_credits' => $faker->randomElement([180, 240]),
            'qualification_title' => 'Bachelor of ' . $title,
            'tstamp' => time(),
            'crdate' => time(),
        ];
    }

    private function addRelations(int $localUid, string $type, array $foreignUids, int $min, int $max, $faker): void
    {
        if (empty($foreignUids)) {
            return;
        }
        
        $tableName = 'tx_spark_studyprogram_' . $type . '_mm';
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable($tableName);
        
        $count = $faker->numberBetween($min, $max);
        $selected = $faker->randomElements($foreignUids, min($count, count($foreignUids)));

        foreach ($selected as $sorting => $foreignUid) {
            $connection->insert($tableName, [
                'uid_local' => $localUid,
                'uid_foreign' => $foreignUid,
                'sorting' => $sorting,
                'sorting_foreign' => $sorting,
            ]);
        }
    }

    private function addCourseRelations(int $groupUid, array $courseUids, int $min, int $max, $faker): void
    {
        if (empty($courseUids)) {
            return;
        }

        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('tx_spark_coursegroup_course_mm');
        
        $count = $faker->numberBetween($min, $max);
        $selected = $faker->randomElements($courseUids, min($count, count($courseUids)));

        foreach ($selected as $sorting => $foreignUid) {
            $connection->insert('tx_spark_coursegroup_course_mm', [
                'uid_local' => $groupUid,
                'uid_foreign' => $foreignUid,
                'sorting' => $sorting,
                'sorting_foreign' => $sorting,
            ]);
        }
        // Update courses count cache if needed, but Extbase persistence handles counts dynamically usually.
    }
}
