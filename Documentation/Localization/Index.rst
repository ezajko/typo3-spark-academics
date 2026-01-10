.. include:: /Includes.rst.txt

============
Localization
============

Spark Academics uses a specialized localization strategy to ensure data consistency and reduce maintenance effort.

Shared Fields Strategy
======================

In standard TYPO3, translating a record creates a copy where fields can be modified independently. However, for academic data, many fields (dates, numbers, relations) must remain identical across all languages.

To address this, we use ``l10n_mode = exclude`` in the TCA configuration for:

* **Relations**: Links to Departments, Partners, People.
* **Dates**: Start/End times, deadlines.
* **Numbers**: Budgets, ECTS credits.
* **Files**: Images, Logos.
* **Identifiers**: Codes, Acronyms, UUIDs.

How it works
------------

1.  **Backend**: When you translate a record (e.g., a Project), these excluded fields are **not shown** in the translation form. You only see translatable text fields (Title, Description).
2.  **Frontend**: When displaying the translated record, TYPO3 automatically falls back to the value from the **default language** for these excluded fields.

Benefits
--------

*   **Consistency**: Changing a project start date in the default language automatically updates all translations.
*   **Efficiency**: Translators only focus on text content.
*   **Completeness**: No more "missing" relations on translated pages because someone forgot to re-select the partners.
