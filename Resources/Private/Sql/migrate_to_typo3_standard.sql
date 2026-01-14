-- =====================================================
-- Academics Extension - TYPO3 Standard Table Names
-- Adds domain_model_ prefix per TYPO3 convention
-- 
-- Author: Ernedin Zajko <ezajko@root.ba>
-- Date: 2026-01-14
-- =====================================================

-- Core Domain Models
RENAME TABLE tx_academics_person TO tx_academics_domain_model_person;
RENAME TABLE tx_academics_organization TO tx_academics_domain_model_organization;
RENAME TABLE tx_academics_course TO tx_academics_domain_model_course;
RENAME TABLE tx_academics_project TO tx_academics_domain_model_project;
RENAME TABLE tx_academics_studyprogram TO tx_academics_domain_model_studyprogram;

-- Academic Metadata
RENAME TABLE tx_academics_academicrank TO tx_academics_domain_model_academicrank;
RENAME TABLE tx_academics_academictitle TO tx_academics_domain_model_academictitle;
RENAME TABLE tx_academics_persontype TO tx_academics_domain_model_persontype;
RENAME TABLE tx_academics_organizationtype TO tx_academics_domain_model_organizationtype;

-- Course Related
RENAME TABLE tx_academics_coursecategory TO tx_academics_domain_model_coursecategory;
RENAME TABLE tx_academics_coursegroup TO tx_academics_domain_model_coursegroup;
RENAME TABLE tx_academics_coursestatus TO tx_academics_domain_model_coursestatus;
RENAME TABLE tx_academics_coursesyllabus TO tx_academics_domain_model_coursesyllabus;
RENAME TABLE tx_academics_teachingmethod TO tx_academics_domain_model_teachingmethod;
RENAME TABLE tx_academics_externalcourse TO tx_academics_domain_model_externalcourse;

-- Study Program Related
RENAME TABLE tx_academics_curriculum TO tx_academics_domain_model_curriculum;
RENAME TABLE tx_academics_curriculumsemester TO tx_academics_domain_model_curriculumsemester;
RENAME TABLE tx_academics_studycycle TO tx_academics_domain_model_studycycle;
RENAME TABLE tx_academics_studytype TO tx_academics_domain_model_studytype;
RENAME TABLE tx_academics_modeofstudy TO tx_academics_domain_model_modeofstudy;

-- Project Related
RENAME TABLE tx_academics_projectstatus TO tx_academics_domain_model_projectstatus;
RENAME TABLE tx_academics_projecttype TO tx_academics_domain_model_projecttype;
RENAME TABLE tx_academics_fundingprogram TO tx_academics_domain_model_fundingprogram;

-- Person Relations
RENAME TABLE tx_academics_personeducation TO tx_academics_domain_model_personeducation;
RENAME TABLE tx_academics_personmentoring TO tx_academics_domain_model_personmentoring;

-- MM Tables (these typically don't use domain_model_ but we keep consistency)
RENAME TABLE tx_academics_courseperson TO tx_academics_domain_model_courseperson;
RENAME TABLE tx_academics_projectperson TO tx_academics_domain_model_projectperson;

-- Other Domain Models
RENAME TABLE tx_academics_partner TO tx_academics_domain_model_partner;
RENAME TABLE tx_academics_publication TO tx_academics_domain_model_publication;
RENAME TABLE tx_academics_scientificfield TO tx_academics_domain_model_scientificfield;
RENAME TABLE tx_academics_sdg TO tx_academics_domain_model_sdg;
RENAME TABLE tx_academics_language TO tx_academics_domain_model_language;

-- Total: 33 tables renamed to TYPO3 standard convention
