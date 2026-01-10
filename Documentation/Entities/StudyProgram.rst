.. include:: /Includes.rst.txt

=============
Study Program
=============

The **Study Program** entity defines a degree program (e.g., "Computer Science BSc").

Fields
======

Overview
--------
* **Title**: Name of the program.
* **Qualification**: Title awarded (e.g., "Bachelor of Engineering").
* **Cycle**: First (Bachelor), Second (Master), Third (PhD).
* **Duration**: Years and Semesters.
* **ECTS**: Total credits.

Curriculum
----------
The program contains a list of **Curriculum** versions.
Each curriculum defines:
* **Years/Semesters**: Structure of the program.
* **Course Groups**: Mandatory and Elective groups of courses per semester.

Localization Behavior
=====================
Duration, ECTS, Cycle, and structural relations are **shared**. Titles, descriptions, and qualification titles are **translatable**.
