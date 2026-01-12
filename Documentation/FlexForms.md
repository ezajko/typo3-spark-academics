# FlexForm Configuration

This document describes the FlexForm configuration for the Academic plugin, including List and Detail views.

## Overview

The Academic plugin uses FlexForms to configure:
- **Entity Type** - Which entity to display (Organization, Person, Course, StudyProgram, Project)
- **View Type** - How to render the entity (Default, Simple, List, Card, Debug)
- **Filters** - Pre-filter entities by various criteria
- **Pagination** - Items per page, maximum items

## Plugin Modes

### List Plugin (`sparkacademics_list`)

Displays a list of entities with filtering and pagination support.

**Configuration Files:**
- `Configuration/FlexForms/Academic/List.xml`

### Detail Plugin (`sparkacademics_detail`)

Displays a single entity detail view.

**Configuration Files:**
- `Configuration/FlexForms/Academic/Detail.xml`

---

## FlexForm Structure

Both List and Detail FlexForms use a **per-entity sheet structure**:

1. **sDEF (General)** - Entity type selection, common settings
2. **sOrganization** - Organization-specific settings (visible when entityType=Organization)
3. **sPerson** - Person-specific settings (visible when entityType=Person)
4. **sCourse** - Course-specific settings (visible when entityType=Course)
5. **sStudyProgram** - Study Program-specific settings (visible when entityType=StudyProgram)
6. **sProject** - Project-specific settings (visible when entityType=Project)

Each entity sheet contains:
- **Entity selector** (`settings.select.<entity>`) - Select specific record(s)
- **View type** (`settings.view.viewType`) - Choose display template
- **Filters** (List only) - Pre-filter by related entities

---

## Configuration Guide

### Setting Up a List View

1. Add the **Academic List** plugin to a page
2. In the plugin settings, select **Entity Type** (e.g., "Person")
3. A new tab will appear for that entity (e.g., "Person" tab)
4. Configure:
   - **View Type**: Default, List, Card, Simple, or Debug
   - **Filters**: Pre-select Organization, Academic Rank, etc.
   - **Specific Records**: Optionally select specific records to display
5. In the **Pagination** tab:
   - **Items per Page**: Number of items per page (0 = no pagination)
   - **Maximum Items**: Limit total results (0 = unlimited)
6. In the **Frontend Filter** tab:
   - **Enable Frontend Filtering**: Allow visitors to filter
   - **Filter Layout**: Inline, Sidebar, or Collapsible
   - **Show Search/Reset**: Toggle search field and reset button

### Setting Up a Detail View

1. Add the **Academic Detail** plugin to a page
2. In the plugin settings, select **Entity Type**
3. A new tab will appear for that entity
4. Configure:
   - **Select Record**: Choose the specific record to display
   - **View Type**: Default, Simple, or Debug
5. In the **General** tab:
   - **List Page**: Set the page for "back to list" links

---

## View Types

Each entity supports different view types for rendering:

### Organization
| View Type | Description |
|-----------|-------------|
| Default   | Full organization card with logo, description |
| List      | Table view with basic info |
| Simple    | Minimal display |
| Debug     | Shows all properties for debugging |

### Person
| View Type | Description |
|-----------|-------------|
| Default   | Grid layout with avatar, name, title |
| List      | Table view |
| Card      | Individual card per person |
| Simple    | Name and basic info only |
| Debug     | All properties |

### Course
| View Type | Description |
|-----------|-------------|
| Default   | Grid with course code, title |
| List      | Table view |
| Card      | Course card |
| Simple    | Minimal |
| Debug     | All properties |

### Study Program
| View Type | Description |
|-----------|-------------|
| Default   | Full program display |
| List      | Table |
| Card      | Program card |
| Simple    | Minimal |
| Debug     | All properties |

### Project
| View Type | Description |
|-----------|-------------|
| Default   | Full project display with logo |
| Simple    | Minimal |
| Debug     | All properties |

---

## Settings Reference

### Common Settings (sDEF)

| Setting | Type | Description |
|---------|------|-------------|
| `settings.entityType` | select | Entity to display |
| `settings.detailPid` | page | Detail page for links (List only) |
| `settings.listPid` | page | List page for back links (Detail only) |

### View Settings

| Setting | Type | Description |
|---------|------|-------------|
| `settings.view.viewType` | select | Template variant |
| `settings.view.pagination.itemsPerPage` | number | Items per page |
| `settings.view.pagination.limit` | number | Max items |

### Filter Settings (List only)

| Setting | Entity | Description |
|---------|--------|-------------|
| `settings.filter.organizationType` | Organization | Filter by type |
| `settings.filter.organization` | Person, Course, StudyProgram, Project | Filter by organization |
| `settings.filter.academicRank` | Person | Filter by rank |
| `settings.filter.studyCycle` | Course, StudyProgram | Filter by cycle |
| `settings.filter.courseCategory` | Course | Filter by category |
| `settings.filter.projectStatus` | Project | Filter by status |
| `settings.filter.projectType` | Project | Filter by type |
| `settings.filter.fundingProgram` | Project | Filter by funding |

### Select Settings

| Setting | Description |
|---------|-------------|
| `settings.select.organization` | Select specific organization(s) |
| `settings.select.person` | Select specific person(s) |
| `settings.select.course` | Select specific course(s) |
| `settings.select.studyprogram` | Select specific program(s) |
| `settings.select.project` | Select specific project(s) |

---

## Template Paths

Templates are resolved based on entity type and view type:

**List Templates:**
```
Resources/Private/Partials/Academic/Entities/{EntityType}/List/{ViewType}.html
```

**Detail (Show) Templates:**
```
Resources/Private/Partials/Academic/Entities/{EntityType}/Show/{ViewType}.html
```

Example paths:
- `Partials/Academic/Entities/Person/List/Default.html`
- `Partials/Academic/Entities/Person/Show/Default.html`
- `Partials/Academic/Entities/Organization/List/Card.html`

---

## Adding Custom View Types

To add a new view type for an entity:

1. Add the option to the FlexForm (`List.xml` and/or `Detail.xml`):
   ```xml
   <numIndex index="5">
       <label>My Custom View</label>
       <value>MyCustom</value>
   </numIndex>
   ```

2. Create the template file:
   ```
   Partials/Academic/Entities/{EntityType}/List/MyCustom.html
   ```

3. Clear TYPO3 caches

---

## Troubleshooting

### View Type Not Working
- Ensure the template file exists at the correct path
- Check that `settings.view.viewType` is being passed to the partial
- Clear all caches after FlexForm changes

### Entity Sheet Not Showing
- Verify `entityType` is selected in the General tab
- Check the `displayCond` in FlexForm XML matches the entity value

### Settings Not Available
- After changing entity type, settings from previous entity may persist
- Re-save the plugin to reset FlexForm data
