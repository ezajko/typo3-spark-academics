# Spark Academics

Academic content management package for TYPO3, providing comprehensive entity models for Persons, Projects, Courses, and Study Programs.

## Overview

Spark Academics is a specialized TYPO3 extension designed for academic institutions. It provides a robust data model compatible with CERIO/CERIF standards to manage:

- **Academic Staff (Persons)**: Professors, assistants, researchers.
- **Research Projects**: Funded projects, research groups, labs.
- **Teaching (Courses)**: Syllabi, course materials, ECTS credits.
- **Study Programs**: Curricula, degrees, cycles.

## Features

- **Rich Data Models**: Detailed TCA configurations for all academic entities.
- **CERIF Compatibility**: Fields mapped to Common European Research Information Format.
- **Localization**: Smart inheritance of shared data (dates, relations) across languages.
- **Frontend Templates**: Ready-to-use Fluid templates for listing and detailed views.
- **REST API**: Extbase-based architecture ready for API integration.

## Installation

1.  Require the package via Composer:
    ```bash
    composer require etf-unsa/spark-academics
    ```
2.  Activate the extension in TYPO3 Extension Manager.
3.  Include the static TypoScript template "Spark Academics".

## Documentation

Full documentation is available in the `Documentation` folder of this extension.

### Entities
- [Persons](Documentation/Entities/Person.rst)
- [Projects](Documentation/Entities/Project.rst)
- [Courses](Documentation/Entities/Course.rst)
- [Study Programs](Documentation/Entities/StudyProgram.rst)

## License

Proprietary / Closed Source.
