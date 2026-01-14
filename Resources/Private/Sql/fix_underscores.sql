-- =====================================================
-- Fix: Add underscores between CamelCase words
-- TYPO3 converts AcademicRank → academic_rank
-- =====================================================

-- Two-word models (add underscore)
RENAME TABLE tx_academics_domain_model_academicrank TO tx_academics_domain_model_academic_rank;
RENAME TABLE tx_academics_domain_model_academictitle TO tx_academics_domain_model_academic_title;
RENAME TABLE tx_academics_domain_model_persontype TO tx_academics_domain_model_person_type;
RENAME TABLE tx_academics_domain_model_organizationtype TO tx_academics_domain_model_organization_type;
RENAME TABLE tx_academics_domain_model_coursecategory TO tx_academics_domain_model_course_category;
RENAME TABLE tx_academics_domain_model_coursegroup TO tx_academics_domain_model_course_group;
RENAME TABLE tx_academics_domain_model_coursestatus TO tx_academics_domain_model_course_status;
RENAME TABLE tx_academics_domain_model_coursesyllabus TO tx_academics_domain_model_course_syllabus;
RENAME TABLE tx_academics_domain_model_teachingmethod TO tx_academics_domain_model_teaching_method;
RENAME TABLE tx_academics_domain_model_externalcourse TO tx_academics_domain_model_external_course;
RENAME TABLE tx_academics_domain_model_curriculumsemester TO tx_academics_domain_model_curriculum_semester;
RENAME TABLE tx_academics_domain_model_studycycle TO tx_academics_domain_model_study_cycle;
RENAME TABLE tx_academics_domain_model_studytype TO tx_academics_domain_model_study_type;
RENAME TABLE tx_academics_domain_model_modeofstudy TO tx_academics_domain_model_mode_of_study;
RENAME TABLE tx_academics_domain_model_projectstatus TO tx_academics_domain_model_project_status;
RENAME TABLE tx_academics_domain_model_projecttype TO tx_academics_domain_model_project_type;
RENAME TABLE tx_academics_domain_model_fundingprogram TO tx_academics_domain_model_funding_program;
RENAME TABLE tx_academics_domain_model_personeducation TO tx_academics_domain_model_person_education;
RENAME TABLE tx_academics_domain_model_personmentoring TO tx_academics_domain_model_person_mentoring;
RENAME TABLE tx_academics_domain_model_courseperson TO tx_academics_domain_model_course_person;
RENAME TABLE tx_academics_domain_model_projectperson TO tx_academics_domain_model_project_person;
RENAME TABLE tx_academics_domain_model_scientificfield TO tx_academics_domain_model_scientific_field;
RENAME TABLE tx_academics_domain_model_studyprogram TO tx_academics_domain_model_study_program;

-- Total: 23 tables with underscores added
