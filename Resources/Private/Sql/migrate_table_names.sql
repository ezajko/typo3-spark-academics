-- =====================================================
-- Academics Extension - Table Renaming Migration
-- Renames all tables from tx_spark_* to tx_academics_*
-- 
-- Author: Ernedin Zajko <ezajko@root.ba>
-- Date: 2026-01-13
-- =====================================================

-- Core Entities (5 tables)
RENAME TABLE tx_spark_person TO tx_academics_person;
RENAME TABLE tx_spark_organization TO tx_academics_organization;
RENAME TABLE tx_spark_course TO tx_academics_course;
RENAME TABLE tx_spark_project TO tx_academics_project;
RENAME TABLE tx_spark_study_program TO tx_academics_studyprogram;

-- Academic Metadata (4 tables)
RENAME TABLE tx_spark_academic_rank TO tx_academics_academicrank;
RENAME TABLE tx_spark_academic_title TO tx_academics_academictitle;
RENAME TABLE tx_spark_person_type TO tx_academics_persontype;
RENAME TABLE tx_spark_organization_type TO tx_academics_organizationtype;

-- Course Related (6 tables)
RENAME TABLE tx_spark_course_category TO tx_academics_coursecategory;
RENAME TABLE tx_spark_course_group TO tx_academics_coursegroup;
RENAME TABLE tx_spark_course_status TO tx_academics_coursestatus;
RENAME TABLE tx_spark_course_syllabus TO tx_academics_coursesyllabus;
RENAME TABLE tx_spark_teaching_method TO tx_academics_teachingmethod;
RENAME TABLE tx_spark_external_course TO tx_academics_externalcourse;

-- Study Program Related (4 tables)
RENAME TABLE tx_spark_curriculum TO tx_academics_curriculum;
RENAME TABLE tx_spark_curriculum_semester TO tx_academics_curriculumsemester;
RENAME TABLE tx_spark_study_cycle TO tx_academics_studycycle;
RENAME TABLE tx_spark_study_type TO tx_academics_studytype;
RENAME TABLE tx_spark_mode_of_study TO tx_academics_modeofstudy;

-- Project Related (3 tables)
RENAME TABLE tx_spark_project_status TO tx_academics_projectstatus;
RENAME TABLE tx_spark_project_type TO tx_academics_projecttype;
RENAME TABLE tx_spark_funding_program TO tx_academics_fundingprogram;

-- Person Relations (2 tables)
RENAME TABLE tx_spark_person_education TO tx_academics_personeducation;
RENAME TABLE tx_spark_person_mentoring TO tx_academics_personmentoring;

-- MM Tables (2 tables)
RENAME TABLE tx_spark_course_person TO tx_academics_courseperson;
RENAME TABLE tx_spark_project_person TO tx_academics_projectperson;

-- Other (5 tables)
RENAME TABLE tx_spark_partner TO tx_academics_partner;
RENAME TABLE tx_spark_publication TO tx_academics_publication;
RENAME TABLE tx_spark_scientific_field TO tx_academics_scientificfield;
RENAME TABLE tx_spark_sdg TO tx_academics_sdg;
RENAME TABLE tx_spark_language TO tx_academics_language;

-- Total: 32 tables renamed
