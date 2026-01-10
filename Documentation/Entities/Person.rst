.. include:: /Includes.rst.txt

======
Person
======

The **Person** entity represents any individual affiliated with the institution, including academic staff (professors, assistants), researchers, and administrative staff.

Fields
======

Basic Info
----------
* **Name**: First and Last name.
* **Titles**: Academic Title (e.g., Prof. Dr.) and Academic Rank (e.g., Full Professor).
* **Gender**: For statistical purposes.

Academic Profile
----------------
* **Biography**: Rich text biography or PDF upload.
* **Research Interests**: HTML text describing research focus.
* **Teaching**: HTML text describing teaching philosophy or list of courses.
* **Consultations**: Specific office hours.
* **Keywords**: Comma-separated tags for research areas (used in search).

Identifiers & Links
-------------------
* **Profiles**: Links to Google Scholar, ResearchGate, ORCID, Scopus, etc.
* **CV**: Uploadable PDF CV.

Relations
---------
* **Primary Department**: Main affiliation.
* **Other Affiliations**: Multiple departments, labs, or chairs.
* **Courses**: Courses taught by this person.
* **Projects**: Projects where this person is a member or coordinator.

Localization Behavior
=====================
Key identifiers, names, contact info, and relations are **shared** across languages. Only biography, research interests, and descriptive texts need translation.
