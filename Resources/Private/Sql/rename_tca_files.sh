#!/bin/bash
# Rename all TCA files from tx_spark_* to tx_academics_*

cd packages/spark-academics/Configuration/TCA || exit 1

# Rename main TCA files
git mv tx_spark_academic_rank.php tx_academics_academicrank.php
git mv tx_spark_academic_title.php tx_academics_academictitle.php
git mv tx_spark_course.php tx_academics_course.php
git mv tx_spark_course_category.php tx_academics_coursecategory.php
git mv tx_spark_course_group.php tx_academics_coursegroup.php
git mv tx_spark_course_person.php tx_academics_courseperson.php
git mv tx_spark_course_status.php tx_academics_coursestatus.php
git mv tx_spark_course_syllabus.php tx_academics_coursesyllabus.php
git mv tx_spark_curriculum.php tx_academics_curriculum.php
git mv tx_spark_curriculum_semester.php tx_academics_curriculumsemester.php
git mv tx_spark_external_course.php tx_academics_externalcourse.php
git mv tx_spark_funding_program.php tx_academics_fundingprogram.php
git mv tx_spark_language.php tx_academics_language.php
git mv tx_spark_mode_of_study.php tx_academics_modeofstudy.php
git mv tx_spark_organization.php tx_academics_organization.php
git mv tx_spark_organization_type.php tx_academics_organizationtype.php
git mv tx_spark_partner.php tx_academics_partner.php
git mv tx_spark_person.php tx_academics_person.php
git mv tx_spark_person_education.php tx_academics_personeducation.php
git mv tx_spark_person_mentoring.php tx_academics_personmentoring.php
git mv tx_spark_person_type.php tx_academics_persontype.php
git mv tx_spark_project.php tx_academics_project.php
git mv tx_spark_project_person.php tx_academics_projectperson.php
git mv tx_spark_project_status.php tx_academics_projectstatus.php
git mv tx_spark_project_type.php tx_academics_projecttype.php
git mv tx_spark_publication.php tx_academics_publication.php
git mv tx_spark_scientific_field.php tx_academics_scientificfield.php
git mv tx_spark_sdg.php tx_academics_sdg.php
git mv tx_spark_study_cycle.php tx_academics_studycycle.php
git mv tx_spark_study_program.php tx_academics_studyprogram.php
git mv tx_spark_study_type.php tx_academics_studytype.php
git mv tx_spark_teaching_method.php tx_academics_teachingmethod.php

echo "TCA files renamed successfully!"
