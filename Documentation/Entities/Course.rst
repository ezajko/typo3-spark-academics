.. include:: /Includes.rst.txt

======
Course
======

The **Course** entity represents a specific subject taught at the institution. It serves as a container for year-specific Syllabi versions.

Fields
======

General
-------
* **Title**: Name of the course.
* **Code**: Unique identifier (e.g., "RI-101").
* **Acronym**: Short name.
* **Description**: Brief overview.

Syllabus
--------
This is the core component. A Course can have multiple **Syllabus** records (one per academic year).
The active syllabus displays:

* **ECTS**: Credit value.
* **Workload**: Breakdown of hours (Lectures, Exercises, Lab, Seminar).
* **Content**: Thematic units, literature, assessment methods.
* **Learning Outcomes**: What students will learn.

Organization
------------
* **Department/Chair**: Responsible organizational unit.
* **Staff**: Associated professors and assistants.

Localization Behavior
=====================
Course code, acronym, and organizational links are **shared**. Title, description, and syllabus content are **translatable**.
