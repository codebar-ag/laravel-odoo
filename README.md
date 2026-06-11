## 📑 Table of Contents

- [What is Odoo?](#-what-is-odoo)
- [Requirements](#-requirements)
- [Installation](#️-installation)
- [Configuration](#-configuration)
  - [Environment Variables](#environment-variables)
- [Basic Usage](#-basic-usage)
- [API Reference](#-api-reference)
  - [Session](#session)
  - [User](#user)
  - [Employees](#employees)
  - [Fields](#fields)
  - [Permissions](#permissions)
  - [Projects](#projects)
  - [Tasks](#tasks)
  - [Timesheets](#timesheets)
  - [Sync All](#sync-all)
- [DTOs](#-dtos)
- [Testing](#-testing)

## 🛠 Requirements

| Package  | PHP          | Laravel |
|----------|--------------|---------|
| v1.0.0   | ^8.4         | ^13.0   |

## ⚙️ Installation

You can install the package via composer:

```bash
composer require codebar-ag/laravel-odoo
```

## 🔧 Configuration

Optionally publish the config file to adjust defaults:

```bash
php artisan vendor:publish --provider="CodebarAg\Odoo\OdooServiceProvider" --tag="laravel-odoo-config"
```

You can generate an API key in your Odoo user profile under **Preferences → API Keys**.

## 🚀 Basic Usage

Create an `OdooConnector` instance with your Odoo URL, API key, and optionally a database name:

```php
use CodebarAg\Odoo\OdooConnector;

$connector = new OdooConnector(
    baseUrl: 'https://your-odoo-instance.com',
    apiKey: 'your-api-key',
    db: 'your-database', // optional
);
```

Each method returns a typed response object with dedicated methods for accessing the data.

## 📖 API Reference

### Session

```php
// Check if the Odoo instance is reachable
$response = $connector->health();
$response->isHealthy(); // bool

// Get the Odoo server version
$response = $connector->version();
$response->serverVersion(); // ?string  e.g. "17"
$response->serie();         // ?string  e.g. "17.0"

// List all available databases
$response = $connector->databases();
$response->databases(); // array<string>
```

### User

```php
// Get the currently authenticated user (returns raw Saloon Response)
$response = $connector->getUser();
$response->json(); // array
```

### Employees

```php
// Get an employee by their Odoo user ID
$response = $connector->getEmployeeByUserId(
    userId: 1,
    fields: ['name', 'job_title'], // optional — omit to get all fields
);
$response->dto(); // ?EmployeeDto
```

### Fields

```php
// Get fields for a specific model
$response = $connector->getFields(
    model: 'account.move',
    attributes: ['string', 'type'], // optional — field meta-attributes to return
);
$response->fields(); // array<string, FieldDto>

// Get all fields across all models
$response = $connector->getAllFields();
$response->fields(); // array<string, FieldDto>
```

### Permissions

```php
// Check permissions for a model and operation
$response = $connector->getPermissions(
    model: 'project.project',
    operation: 'read', // read, write, create, unlink
);
$response->allowed(); // bool
```

### Projects

```php
$response = $connector->getProjects(
    fields: ['name', 'date_start', 'date'], // optional
    domain: [['active', '=', true]],        // optional Odoo domain filter
    limit: 100,                              // optional, default 100
);

/** @var array<ProjectDto> $projects */
$projects = $response->projects();
```

### Tasks

```php
// Get all tasks
$response = $connector->getAllTasks(
    fields: ['name', 'project_id', 'stage_id'], // optional
    domain: [['active', '=', true]],             // optional
    limit: 100,                                   // optional, default 100
);

/** @var array<TaskDto> $tasks */
$tasks = $response->tasks();

// Get tasks for a specific project
$response = $connector->getTasksByProject(
    projectId: 42,
    fields: ['name', 'stage_id', 'date_deadline'], // optional
);

/** @var array<TaskDto> $tasks */
$tasks = $response->tasks();
```

### Timesheets

```php
use CodebarAg\Odoo\Dto\Timesheets\CreateTimesheetDto;
use CodebarAg\Odoo\Dto\Timesheets\UpdateTimesheetDto;

// Get timesheet entries
$response = $connector->getTimesheetEntries(
    fields: ['name', 'project_id', 'task_id', 'unit_amount', 'date'], // optional
    domain: [['employee_id', '=', 5]],                                 // optional
    limit: 100,                                                         // optional
);

/** @var array<TimesheetEntryDto> $entries */
$entries = $response->entries();

// Get timesheet entries from the last N days
$response = $connector->getTimesheetEntriesLastDays(days: 7);
$entries = $response->entries(); // array<TimesheetEntryDto>

// Read a single timesheet entry
$response = $connector->readTimesheet(id: 123);
$entry = $response->dto(); // ?TimesheetEntryDto

// Create a timesheet entry
$response = $connector->createTimesheet(new CreateTimesheetDto(
    name: 'Fixed bug #456',
    projectId: 1,
    taskId: 10,
    date: '2024-06-11',
    unitAmount: 1.5,
    employeeId: 5,   // optional
    extraValues: [], // optional — extra Odoo fields (e.g. custom Studio fields)
));
$newId = $response->id(); // ?int

// Update a timesheet entry
$response = $connector->updateTimesheet(new UpdateTimesheetDto(
    id: 123,
    values: ['name' => 'Updated description', 'unit_amount' => 2.0],
));
$response->ok(); // bool

// Delete a timesheet entry
$response = $connector->deleteTimesheet(id: 123);
$response->ok(); // bool
```

### Sync All

Fetch projects, all tasks, and all timesheet entries in one call:

```php
$results = $connector->syncAll();

$projects   = $results['projects']->projects();     // array<ProjectDto>
$tasks      = $results['tasks']->tasks();           // array<TaskDto>
$timesheets = $results['timesheets']->entries();    // array<TimesheetEntryDto>
```

## 📦 DTOs

| DTO                  | Description                                    |
|----------------------|------------------------------------------------|
| `ProjectDto`         | Represents an Odoo project                     |
| `TaskDto`            | Represents an Odoo task                        |
| `TimesheetEntryDto`  | Represents a timesheet entry (read)            |
| `CreateTimesheetDto` | Payload for creating a timesheet entry         |
| `UpdateTimesheetDto` | Payload for updating a timesheet entry         |
| `EmployeeDto`        | Represents an Odoo employee                    |
| `FieldDto`           | Represents a field definition on an Odoo model |

## 🧪 Testing

```bash
composer test
```

For live integration tests against a real Odoo instance, copy `.env.testing` and fill in your credentials, then run:

```bash
composer test:live
```
