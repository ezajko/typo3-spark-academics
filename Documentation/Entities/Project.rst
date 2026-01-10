.. include:: /Includes.rst.txt

=======
Project
=======

The **Project** entity manages research and development projects. It is designed to be compatible with CERIF (Common European Research Information Format) standards.

Fields
======

General
-------
* **Title**: Full project title.
* **Acronym**: Short identifier (e.g., "HORIZON-2020").
* **Description**: Detailed HTML abstract.
* **Objectives**: Specific research goals.
* **Outcomes**: Expected results/deliverables.

Financial & Admin
-----------------
* **Type**: Project type (National, EU, Bilateral, etc.).
* **Status**: Active, Completed, Submitted.
* **Funding Program**: Source of funding (e.g., Horizon Europe).
* **Reference**: Grant Agreement Number.
* **Budget**: Total budget and Local (institution) share.
* **Duration**: Start and End dates.

Team
----
* **Coordinator**: Person leading the project (internal).
* **Members**: List of internal staff members.
* **Partners**: List of partner institutions.

Localization Behavior
=====================
Dates, budgets, numbers, partners, and team members are **shared**. Project title, description, objectives, and outcomes are **translatable**.
