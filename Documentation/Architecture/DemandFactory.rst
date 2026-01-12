# DemandFactory Architecture

This document describes the DemandFactory pattern used for creating entity-specific filter/query demand objects.

## Overview

The DemandFactory pattern provides a clean, extensible way to create filter criteria for different entity types. Each entity has its own Demand DTO class that extends `AbstractDemand`, and a corresponding trait in the factory that knows how to create it.

## Architecture

```
Service/
├── DemandFactory.php              # Main factory class, uses all traits
└── DemandFactory/
    ├── PersonDemandTrait.php      # createPersonDemand()
    ├── ProjectDemandTrait.php     # createProjectDemand()
    ├── CourseDemandTrait.php      # createCourseDemand()
    ├── StudyProgramDemandTrait.php # createStudyProgramDemand()
    └── OrganizationDemandTrait.php # createOrganizationDemand()

Domain/Model/Dto/
├── AbstractDemand.php             # Base class with common properties
├── PersonDemand.php
├── ProjectDemand.php
├── CourseDemand.php
├── StudyProgramDemand.php
└── OrganizationDemand.php
```

## Usage

### In Controllers

```php
// Inject DemandFactory
public function __construct(
    PersonRepository $personRepository,
    DemandFactory $demandFactory
) {
    $this->personRepository = $personRepository;
    $this->demandFactory = $demandFactory;
}

// Create demand from settings and request filter
$requestFilter = $this->request->getArgument('filter');
$demand = $this->demandFactory->createDemand('Person', $settings, $requestFilter);

// Or directly call entity-specific method
$demand = $this->demandFactory->createPersonDemand($settings, $requestFilter);

// Execute query
$items = $this->personRepository->findByDemand($demand);
```

### Common Properties (AbstractDemand)

All demands inherit these properties:

| Property | Type | Description |
|----------|------|-------------|
| `search` | string | Text search term |
| `orderings` | array | Sort order (property => direction) |
| `limit` | int | Maximum results (0 = unlimited) |
| `offset` | int | Pagination offset |

Each demand also implements:
- `hasFilters()` - Check if any entity-specific filters are active
- `getFilterProperties()` - Get filter values as associative array

## Entity-Specific Demands

### PersonDemand

| Property | Type | Description |
|----------|------|-------------|
| `organization` | int | Primary organization UID |
| `academicRank` | int | Academic rank UID |
| `academicTitle` | int | Academic title UID |
| `isAcademic` | bool&#124;null | Filter by academic status |
| `staffStatus` | string | Staff status value |

### ProjectDemand

| Property | Type | Description |
|----------|------|-------------|
| `projectStatus` | int | Project status UID |
| `projectType` | int | Project type UID |
| `fundingProgram` | int | Funding program UID |
| `scientificField` | int | Scientific field UID |
| `organization` | int | Organization UID |
| `dateFrom` | DateTime | Filter projects active after this date |
| `dateTo` | DateTime | Filter projects active before this date |

### CourseDemand

| Property | Type | Description |
|----------|------|-------------|
| `organization` | int | Organization UID |
| `studyCycle` | int | Study cycle UID (via syllabus) |
| `courseCategory` | int | Course category UID (via syllabus) |
| `scientificField` | int | Scientific field UID (via syllabus) |

### StudyProgramDemand

| Property | Type | Description |
|----------|------|-------------|
| `organization` | int | Organization UID |
| `studyCycle` | int | Study cycle UID |
| `studyType` | int | Study type UID |
| `modeOfStudy` | int | Mode of study UID |
| `language` | int | Language UID |

### OrganizationDemand

| Property | Type | Description |
|----------|------|-------------|
| `type` | int | Organization type UID |
| `parent` | int | Parent organization UID |

## Adding a New Entity Type

To add support for a new entity type (e.g., `Publication`):

### Step 1: Create the Demand DTO

```php
// Classes/Domain/Model/Dto/PublicationDemand.php
namespace EtfUnsa\SparkAcademics\Domain\Model\Dto;

class PublicationDemand extends AbstractDemand
{
    protected int $author = 0;
    protected int $publicationType = 0;
    protected int $year = 0;
    
    // Getters/setters for each property...
    
    public function hasFilters(): bool
    {
        return $this->author > 0 
            || $this->publicationType > 0 
            || $this->year > 0;
    }
    
    public function getFilterProperties(): array
    {
        return [
            'authors' => $this->author,  // M:N relation
            'publicationType' => $this->publicationType,
            'publicationYear' => $this->year,
        ];
    }
}
```

### Step 2: Create the Trait

```php
// Classes/Service/DemandFactory/PublicationDemandTrait.php
namespace EtfUnsa\SparkAcademics\Service\DemandFactory;

use EtfUnsa\SparkAcademics\Domain\Model\Dto\PublicationDemand;

trait PublicationDemandTrait
{
    public function createPublicationDemand(array $settings, array $requestFilter = []): PublicationDemand
    {
        $demand = new PublicationDemand();
        $this->applyCommonSettings($demand, $settings, $requestFilter);
        
        $filter = array_merge($settings['filter'] ?? [], $requestFilter);
        
        if (!empty($filter['author'])) {
            $demand->setAuthor((int)$filter['author']);
        }
        if (!empty($filter['publicationType'])) {
            $demand->setPublicationType((int)$filter['publicationType']);
        }
        if (!empty($filter['year'])) {
            $demand->setYear((int)$filter['year']);
        }
        
        return $demand;
    }
}
```

### Step 3: Update DemandFactory

```php
// Add the use statement
use PublicationDemandTrait;

// Add to createDemand() match
'Publication' => $this->createPublicationDemand($settings, $requestFilter),
```

### Step 4: Create Repository

```php
// Classes/Domain/Repository/PublicationRepository.php
class PublicationRepository extends AbstractRepository
{
    protected array $searchFields = ['title', 'abstract', 'keywords'];
}
```

The repository will automatically use `AbstractRepository::findByDemand()` which handles all constraint building dynamically.

## Repository Integration

Repositories extend `AbstractRepository` which provides:

- `findByDemand(AbstractDemand $demand)` - Generic demand-based query
- `searchFields` property - Fields to search in for text queries
- Automatic M:N relation handling for plural properties
- Date range handling for `dateFrom`/`dateTo` properties
