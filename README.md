<img src="https://banners.beyondco.de/Laravel%20Odoo.png?theme=light&packageManager=composer+require&packageName=codebar-ag%2Flaravel-odoo&pattern=circuitBoard&style=style_1&description=A+simple+way+to+interact+with+the+Odoo+API+in+Laravel&md=1&showWatermark=0&fontSize=175px&images=server">

[![Latest Version on Packagist](https://img.shields.io/packagist/v/codebar-ag/laravel-odoo.svg?style=flat-square)](https://packagist.org/packages/codebar-ag/laravel-odoo)
[![Total Downloads](https://img.shields.io/packagist/dt/codebar-ag/laravel-odoo.svg?style=flat-square)](https://packagist.org/packages/codebar-ag/laravel-odoo)
[![GitHub-Tests](https://github.com/codebar-ag/laravel-odoo/actions/workflows/run-tests.yml/badge.svg?branch=main)](https://github.com/codebar-ag/laravel-odoo/actions/workflows/run-tests.yml)
[![GitHub Code Style](https://github.com/codebar-ag/laravel-odoo/actions/workflows/fix-php-code-style-issues.yml/badge.svg?branch=main)](https://github.com/codebar-ag/laravel-odoo/actions/workflows/fix-php-code-style-issues.yml)
[![PHPStan](https://github.com/codebar-ag/laravel-odoo/actions/workflows/phpstan.yml/badge.svg)](https://github.com/codebar-ag/laravel-odoo/actions/workflows/phpstan.yml)
[![Dependency Review](https://github.com/codebar-ag/laravel-odoo/actions/workflows/dependency-review.yml/badge.svg)](https://github.com/codebar-ag/laravel-odoo/actions/workflows/dependency-review.yml)

This package was developed to give you a quick start to communicate with the
Odoo external API from Laravel. It wraps the most common endpoints — sessions,
users, employees, projects, tasks, timesheets, contacts and bank accounts — behind
a clean, typed connector built on [Saloon](https://docs.saloon.dev), plus generic
calls for any other Odoo model.

⚠️ This package is not designed as a replacement of the official Odoo external API. See the [Odoo documentation](https://www.odoo.com/documentation) if you need further functionality. ⚠️

## 📑 Table of Contents

<!-- TOC -->
- [What is Odoo?](#-what-is-odoo)
- [Requirements](#-requirements)
- [Installation](#️-installation)
- [Configuration](#-configuration)
  - [Environment Variables](#environment-variables)
- [Basic Usage](#-basic-usage)
  - [Using the Facade](#using-the-facade)
  - [Responses](#responses)
  - [Connector helpers](#connector-helpers)
- [API Reference](#-api-reference)
  - [Session](#session)
  - [User](#user)
  - [Employees](#employees)
  - [Fields](#fields)
  - [Permissions](#permissions)
  - [Projects](#projects)
  - [Tasks](#tasks)
  - [Timesheets](#timesheets)
  - [Contacts](#contacts)
  - [Bank Accounts](#bank-accounts)
  - [Generic Model Calls](#generic-model-calls)
  - [Sync All](#sync-all)
  - [Request reference](#request-reference)
- [DTOs](#-dtos)
- [Testing](#-testing)
- [Changelog](#-changelog)
- [Contributing](#️-contributing)
- [Security Vulnerabilities](#-security-vulnerabilities)
- [Credits](#-credits)
- [License](#-license)
<!-- TOC -->

## 💡 What is Odoo?

Odoo is an open-source suite of business applications covering CRM, sales,
project management, timesheets, accounting, inventory and more. It exposes an
external API that lets you read and write records across all of these modules.
This package provides a typed, Laravel-friendly client for the most common Odoo
endpoints used in day-to-day integrations.

## 🛠 Requirements

| Package  | PHP          | Laravel | Saloon | saloonphp/laravel-plugin |
|----------|--------------|---------|--------|--------------------------|
| v1.11.0+ | ^8.4         | ^13.0   | ^4.5   | ^5.0                     |
| v1.0.0   | ^8.4         | ^13.0   | ^4.0   | ^4.0                     |

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

### Environment Variables

Add the following variables to your `.env` file:

```dotenv
LARAVEL_ODOO_URL=https://your-odoo-instance.com
LARAVEL_ODOO_API_KEY=your-api-key
LARAVEL_ODOO_DB=your-database
LARAVEL_ODOO_TIMEOUT=15        # optional — request timeout in seconds (default 15, 0 = no timeout)
LARAVEL_ODOO_MAX_REDIRECTS=5   # optional — max HTTP redirects to follow (default 5)
```


## 🚀 Basic Usage

Create an `OdooConnector` instance with your Odoo URL, API key, and optionally a database name:

```php
use CodebarAg\Odoo\OdooConnector;

$connector = new OdooConnector(
    baseUrl: 'https://your-odoo-instance.com',
    apiKey: 'your-api-key',
    db: 'your-database',   // optional
    maxRedirects: 5,       // optional — max HTTP redirects to follow (default 5)
    timeout: 15.0,         // optional — request timeout in seconds (default 15, 0 = no timeout)
);
```

Each method returns a typed response object with dedicated methods for accessing the data.

### Using the Facade

If you set the environment variables above (or publish and edit the config file), the package binds a pre-configured `OdooConnector` in the container, so you can resolve it or use the `Odoo` facade instead of constructing it by hand:

```php
use CodebarAg\Odoo\Facades\Odoo;

$response = Odoo::health();
$response->isHealthy(); // bool
```

The facade reads `url`, `api_key`, `db`, `timeout`, and `max_redirects` from `config/laravel-odoo.php` (configurable via their respective `LARAVEL_ODOO_*` env vars). Direct instantiation with `new OdooConnector(...)` remains fully supported — for example when you need to talk to more than one Odoo instance.

The container binding is a singleton, so the facade always talks to the same connector instance (and shares its cached bank-account schema, see below).

### Responses

Every typed response extends `CodebarAg\Odoo\Responses\OdooResponse` and shares these accessors next to its own data methods:

```php
$response->successful(); // bool — HTTP 2xx
$response->failed();     // bool — the opposite of successful()
$response->status();     // int  — HTTP status code
$response->error();      // ?string — Odoo error message (error_description, error.data.message or error.message); null when successful
$response->errorCode();  // ?string — Odoo error code (error or error.code); null when successful
$response->body();       // string — raw response body
```

The data accessors never throw on a failed response: list accessors return `[]`, `id()` / `count()` / `dto()` return `null`, and `allowed()` returns `false`.

### Connector helpers

```php
$connector->resolveBaseUrl(); // string  — the Odoo URL passed to the constructor
$connector->getApiKey();      // ?string — the API key (sent as "Authorization: Bearer <key>" when not null)
$connector->getDb();          // ?string — the database (sent as "X-Odoo-Database" header when not null)

// Bank-account schema detection (see Bank Accounts)
$connector->usesModernBankAccountSchema(); // bool
$connector->withBankAccountSchema(modern: true); // static — force the schema, skips detection
```

`usesModernBankAccountSchema()` tells you whether the connected Odoo uses the 19.3+ `res.partner.bank`
schema (`account_number` / `holder_name`, no `bank_id` / `currency_id`):

- On the first call it sends `version()` (`GET /web/version`) and reads `[major, minor]` from
  `version_info` via `VersionResponse::majorMinor()`. Both the on-premise shape (`[19, 3, …]`) and
  the SaaS shape (`["saas~19", 3, …]`) are understood.
- It returns `true` when the major version is greater than 19, or when it is 19 and the minor version is 3 or higher.
  Otherwise — including when the version cannot be read — it returns `false` (the classic ≤ 19.0 schema).
- The result is cached on the connector instance, so the version is fetched at most once.
  `withBankAccountSchema(modern: true|false)` sets that cached value directly, which skips the lookup.

`getBankAccounts()`, `readBankAccount()` (default fields), `createBankAccount()` and `updateBankAccount()` call it internally.

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
$response->majorMinor();    // ?array{0: int, 1: int}  e.g. [19, 3] (also parses SaaS "saas~19")

// List all available databases
$response = $connector->databases();
$response->databases(); // array<string>
```

### User

```php
// Get the currently authenticated user
$response = $connector->getUser(
    fields: ['name', 'email'], // optional — omit to get the default field set
    domain: [],                // optional
    limit: 1,                  // optional, default 1
);
$user = $response->dto(); // ?UserDto — first record of the result

// Get a user by their Odoo ID
$response = $connector->getUserById(
    uid: 1,
    fields: ['name', 'email'], // optional — omit to get the default field set
    limit: 1,                  // optional, default 1
);
$user = $response->dto(); // ?UserDto

// Get the authenticated user's context (res.users/context_get)
$response = $connector->getUserContext();
$context = $response->dto(); // ?UserDto — only id (uid), lang and tz are filled
```

### Employees

```php
// Get an employee by their Odoo user ID
$response = $connector->getEmployeeByUserId(
    userId: 1,
    fields: ['name', 'job_title'], // optional — omit to get the default field set
    limit: 1,                      // optional, default 1
);
$response->dto(); // ?EmployeeDto
```

### Fields

```php
// Get fields for a specific model (fields_get)
$response = $connector->getFields(
    model: 'account.move',
    attributes: ['string', 'type'], // optional — field meta-attributes to return
);
$response->fields(); // array<string, FieldDto>  keyed by field name

// Shortcut: fields of the timesheet model (account.analytic.line)
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

use CodebarAg\Odoo\Dto\Projects\CreateProjectDto;
use CodebarAg\Odoo\Dto\Projects\UpdateProjectDto;

// Create a project
$response = $connector->createProject(new CreateProjectDto(
    name: 'Website Relaunch',
    partnerId: 7,         // optional, linked contact
    userId: 2,            // optional, project manager
    allocatedHours: 40.0, // optional
    tagIds: [1, 2],       // optional many-to-many tags
    extraValues: [],      // optional, custom/studio fields
));
$id = $response->id(); // ?int

// Update a project (only provided fields are written)
$response = $connector->updateProject(new UpdateProjectDto(
    id: 42,
    name: 'Website Relaunch 2.0',
));
$response->ok(); // bool

// Delete a project
$response = $connector->deleteProject(id: 42);
$response->ok(); // bool
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
    limit: 100,                                     // optional, default 100
    operator: '=',                                  // optional domain operator, default '='
);

/** @var array<TaskDto> $tasks */
$tasks = $response->tasks();

use CodebarAg\Odoo\Dto\Tasks\CreateTaskDto;
use CodebarAg\Odoo\Dto\Tasks\UpdateTaskDto;

// Create a task
$response = $connector->createTask(new CreateTaskDto(
    name: 'Design homepage',
    projectId: 42,        // optional
    userIds: [5, 6],      // optional assignees (many-to-many)
    stageId: 1,           // optional
    dateDeadline: '2026-07-01',
    priority: '1',        // optional
    extraValues: [],      // optional, custom/studio fields
));
$id = $response->id(); // ?int

// Update a task (only provided fields are written)
$response = $connector->updateTask(new UpdateTaskDto(
    id: 42,
    name: 'Design homepage v2',
    stageId: 2,
));
$response->ok(); // bool

// Delete a task
$response = $connector->deleteTask(id: 42);
$response->ok(); // bool
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
$response = $connector->getTimesheetEntriesLastDays(
    days: 7,
    fields: ['name', 'date', 'unit_amount'], // optional
    operator: '>=',                          // optional domain operator, default '>='
);
$entries = $response->entries(); // array<TimesheetEntryDto>

// Read a single timesheet entry
$response = $connector->readTimesheet(
    id: 123,
    fields: ['name', 'unit_amount'], // optional — omit to get the default field set
);
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

// Update a timesheet entry (only provided fields are written)
$response = $connector->updateTimesheet(new UpdateTimesheetDto(
    id: 123,
    name: 'Updated description',
    unitAmount: 2.0,
    extraValues: [], // optional — extra Odoo fields
));
$response->ok(); // bool

// Delete a timesheet entry
$response = $connector->deleteTimesheet(id: 123);
$response->ok(); // bool
```

### Contacts

Create, update, delete and search operations for the `res.partner` model.

```php
use CodebarAg\Odoo\Dto\Contacts\CreateContactDto;
use CodebarAg\Odoo\Dto\Contacts\UpdateContactDto;

// Search only for matching IDs (search)
$response = $connector->searchContacts(domain: [['is_company', '=', true]]);
$ids = $response->ids(); // array<int>

// Count matching records (search_count)
$response = $connector->searchCountContacts(
    domain: [['is_company', '=', true]], // optional, default [] (all contacts)
);
$count = $response->count(); // ?int

// Search by name (name_search)
$response = $connector->nameSearchContacts(
    name: 'Acme',
    domain: [['is_company', '=', true]], // optional, default []
    limit: 10,                           // optional, default 100
);
$results = $response->results(); // array — [id, display_name] tuples as Odoo returns them

// Create a contact (create)
$response = $connector->createContact(new CreateContactDto(
    name: 'Acme AG',
    isCompany: true,                // optional
    street: 'Bahnhofstrasse 1',     // optional
    zip: '8001',                    // optional
    city: 'Zürich',                 // optional
    countryId: 43,                  // optional — res.country ID
    email: 'info@acme.example',     // optional
    phone: '+41 44 000 00 00',      // optional
    extraValues: [],                // optional — extra Odoo fields (e.g. custom Studio fields)
));
$newId = $response->id(); // ?int

// Update a contact (write) — only provided fields are written
$response = $connector->updateContact(new UpdateContactDto(
    id: 42,
    email: 'billing@acme.example',
));
$response->ok(); // bool

// Delete a contact (unlink)
$response = $connector->deleteContact(id: 42);
$response->ok(); // bool
```

`CreateContactDto` and `UpdateContactDto` share the same optional fields. `name` is required on
create; `UpdateContactDto` requires `id` and makes `name` optional. Fields left `null` are not sent,
and `extraValues` is merged into the values at the top level.

| DTO property  | Type      | Odoo field    |
|---------------|-----------|---------------|
| `name`        | `string`  | `name`        |
| `isCompany`   | `?bool`   | `is_company`  |
| `parentId`    | `?int`    | `parent_id`   |
| `type`        | `?string` | `type`        |
| `street`      | `?string` | `street`      |
| `street2`     | `?string` | `street2`     |
| `city`        | `?string` | `city`        |
| `zip`         | `?string` | `zip`         |
| `stateId`     | `?int`    | `state_id`    |
| `countryId`   | `?int`    | `country_id`  |
| `phone`       | `?string` | `phone`       |
| `mobile`      | `?string` | `mobile`      |
| `email`       | `?string` | `email`       |
| `website`     | `?string` | `website`     |
| `comment`     | `?string` | `comment`     |
| `function`    | `?string` | `function`    |
| `lang`        | `?string` | `lang`        |
| `titleId`     | `?int`    | `title`       |
| `userId`      | `?int`    | `user_id`     |
| `categoryId`  | `?int`    | `category_id` |
| `vat`         | `?string` | `vat`         |
| `ref`         | `?string` | `ref`         |
| `active`      | `?bool`   | `active`      |
| `extraValues` | `array`   | merged as-is  |

Reading contacts has no connector method. Send `ReadContactRequest` (one record by ID) or
`ReadAllContactRequest` (all records, empty domain, no limit) directly and hydrate `ContactDto`
yourself — see [Request reference](#request-reference). Both request the default fields
`id`, `name`, `email`, `phone`, `street`, `city`, `zip`, `country_id`, `is_company` and `parent_id`
unless you pass `fields`. `ContactDto` exposes `id`, `name`, `email`, `phone`, `street`, `city`,
`zip`, `countryId` / `countryName`, `isCompany` and `parentId` / `parentName`.

### Bank Accounts

CRUD and search operations for the `res.partner.bank` model.

> **Odoo 19.0 vs 19.3:** Odoo renamed the bank-account fields in 19.3 (`acc_number` →
> `account_number`, `acc_holder_name` → `holder_name`, and the `bank_id` / `currency_id`
> relations were dropped). The connector detects the server version once (cached) and
> transparently uses the right field names for reads and writes, so the API below is the
> same on both. Read DTOs always expose the classic property names (`accNumber`,
> `accHolderName`, …). To skip the version lookup, force it with
> `$connector->withBankAccountSchema(modern: true)`.

```php
use CodebarAg\Odoo\Dto\BankAccounts\CreateBankAccountDto;
use CodebarAg\Odoo\Dto\BankAccounts\UpdateBankAccountDto;

// Search + read fields in one call (search_read)
$response = $connector->getBankAccounts(
    fields: ['id', 'acc_number', 'partner_id', 'bank_id'], // optional (version-aware default)
    domain: [['partner_id', '=', 7]],                      // optional
    limit: 100,                                            // optional
);

/** @var array<BankAccountDto> $bankAccounts */
$bankAccounts = $response->bankAccounts();

// Search only for matching IDs (search)
$response = $connector->searchBankAccounts(domain: [['partner_id', '=', 7]]);
$ids = $response->ids(); // array<int>

// Read a record by ID (read)
$response = $connector->readBankAccount(id: 5);
$bankAccounts = $response->bankAccounts(); // array<BankAccountDto>

// Count matching records (search_count)
$response = $connector->searchCountBankAccounts(domain: [['partner_id', '=', 7]]);
$count = $response->count(); // ?int

// Create a bank account (create)
$response = $connector->createBankAccount(new CreateBankAccountDto(
    accNumber: 'CH9300762011623852957',
    partnerId: 7,
    accHolderName: 'Jane Doe', // optional
    bankName: 'UBS',           // optional
    bankBic: 'UBSWCHZH80A',    // optional
    bankId: 3,                 // optional — Odoo 19.0 only (ignored on 19.3)
    currencyId: 1,             // optional — Odoo 19.0 only (ignored on 19.3)
    allowOutPayment: true,     // optional
    sequence: 10,              // optional
    extraValues: [],           // optional — extra Odoo fields (e.g. custom Studio fields)
));
$newId = $response->id(); // ?int

// Update a bank account (write)
$response = $connector->updateBankAccount(new UpdateBankAccountDto(
    id: 5,
    accHolderName: 'John Doe',
));
$response->ok(); // bool

// Delete a bank account (unlink)
$response = $connector->deleteBankAccount(id: 5);
$response->ok(); // bool
```

### Generic Model Calls

Model-agnostic JSON-2 calls for any Odoo model and any public model method
(`POST /json/2/<model>/<method>`). Use them for models the package has no typed
requests for. Records come back as plain arrays exactly as Odoo returns them.

```php
// Search + read with paging and ordering (search_read)
$response = $connector->searchRead(
    model: 'sale.order',
    domain: [['partner_id', '=', 7]], // optional
    fields: ['name', 'state'],        // optional
    limit: 20,                        // optional (default 80)
    offset: 40,                       // optional (default 0)
    order: 'date_order desc',         // optional — omitted from the body when null
);
$records = $response->records(); // array<int, array<string, mixed>>

// Create a record (create) — sent as {"vals_list": [values]}
$response = $connector->create('crm.lead', ['name' => 'Portal enquiry', 'partner_id' => 7]);
$newId = $response->id(); // ?int

// Write the same values to records (write) — sent as {"ids": [...], "vals": {...}}
$response = $connector->write('res.partner', [7, 8], ['phone' => '+41 44 000 00 00']);
$response->ok(); // bool — true when Odoo answered `true`

// Call any public model method — params are sent as the JSON body unchanged
$response = $connector->callMethod('sale.order', 'action_confirm', ['ids' => [12]]);
$response = $connector->callMethod('sale.order', 'get_portal_url', [
    'ids' => [12],
    'report_type' => 'pdf',
    'download' => true,
]);
$result = $response->result(); // mixed — any decoded JSON value (true, an id, a string, a list, …)
```

The underlying requests can also be sent directly:

```php
use CodebarAg\Odoo\Requests\Api\Models\CallMethodRequest;
use CodebarAg\Odoo\Requests\Api\Models\CreateRequest;
use CodebarAg\Odoo\Requests\Api\Models\SearchReadRequest;
use CodebarAg\Odoo\Requests\Api\Models\WriteRequest;

$connector->send(new SearchReadRequest('sale.order', [['state', '=', 'sale']], ['name'], limit: 10, order: 'id desc'));
$connector->send(new CreateRequest('crm.lead', ['name' => 'Portal enquiry']));
$connector->send(new WriteRequest('res.partner', [7], ['phone' => '+41 44 000 00 00']));
$connector->send(new CallMethodRequest('sale.order', 'action_confirm', ['ids' => [12]]));
```

### Sync All

Fetch projects, all tasks, and all timesheet entries in one call:

```php
$results = $connector->syncAll();

$projects   = $results['projects']->projects();     // array<ProjectDto>
$tasks      = $results['tasks']->tasks();           // array<TaskDto>
$timesheets = $results['timesheets']->entries();    // array<TimesheetEntryDto>
```

### Request reference

Every Saloon request class shipped by the package. Request classes live under
`CodebarAg\Odoo\Requests\…` and response classes under `CodebarAg\Odoo\Responses\…`; the
namespace is given once per area. All `/json/2/…` endpoints are Odoo's JSON-2 API.
"send directly" means there is no connector method: `$connector->send(...)` returns a plain
Saloon `Response`.

| Area | Connector method | Request class | HTTP method + endpoint | Response class |
|------|------------------|---------------|------------------------|----------------|
| Session — `Requests\Session`, `Responses\Session` | `health()` | `Health\HealthRequest` | `GET /web/health` | `HealthResponse` |
| | `version()` | `Version\GetOdooVersionRequest` | `GET /web/version` | `VersionResponse` |
| | `databases()` | `Database\GetDatabasesRequest` | `POST /web/database/list` (JSON-RPC) | `DatabasesResponse` |
| User — `Api\User` | `getUser()` | `GetUserRequest` | `POST /json/2/res.users/search_read` | `UserResponse` |
| | `getUserById()` | `GetUserByIdRequest` | `POST /json/2/res.users/search_read` | `UserResponse` |
| | `getUserContext()` | `GetUserContextRequest` | `POST /json/2/res.users/context_get` | `UserContextResponse` |
| Employees — `Api\Employees` | `getEmployeeByUserId()` | `GetEmployeeByUserIdRequest` | `POST /json/2/hr.employee/search_read` | `EmployeeResponse` |
| Fields — `Api\Fields` | `getFields()`, `getAllFields()` | `GetFieldsRequest` | `POST /json/2/{model}/fields_get` | `FieldsResponse` |
| Permissions — `Api\Permissions` | `getPermissions()` | `GetPermissionsRequest` | `POST /json/2/{model}/has_access` | `PermissionsResponse` |
| Projects — `Api\Projects` | `getProjects()` | `GetProjectsRequest` | `POST /json/2/project.project/search_read` | `ProjectsResponse` |
| | `createProject()` | `CreateProjectRequest` | `POST /json/2/project.project/create` | `CreateProjectResponse` |
| | `updateProject()` | `UpdateProjectRequest` | `POST /json/2/project.project/write` | `MutateProjectResponse` |
| | `deleteProject()` | `DeleteProjectRequest` | `POST /json/2/project.project/unlink` | `MutateProjectResponse` |
| Tasks — `Api\Tasks` | `getAllTasks()` | `GetAllTasksRequest` | `POST /json/2/project.task/search_read` | `TasksResponse` |
| | `getTasksByProject()` | `GetTasksByProjectRequest` | `POST /json/2/project.task/search_read` | `TasksResponse` |
| | `createTask()` | `CreateTaskRequest` | `POST /json/2/project.task/create` | `CreateTaskResponse` |
| | `updateTask()` | `UpdateTaskRequest` | `POST /json/2/project.task/write` | `MutateTaskResponse` |
| | `deleteTask()` | `DeleteTaskRequest` | `POST /json/2/project.task/unlink` | `MutateTaskResponse` |
| Timesheets — `Api\Timesheets` | `getTimesheetEntries()` | `GetTimesheetEntriesRequest` | `POST /json/2/account.analytic.line/search_read` | `TimesheetEntriesResponse` |
| | `getTimesheetEntriesLastDays()` | `GetTimesheetEntriesLastDaysRequest` | `POST /json/2/account.analytic.line/search_read` | `TimesheetEntriesResponse` |
| | `readTimesheet()` | `ReadTimesheetRequest` | `POST /json/2/account.analytic.line/search_read` | `TimesheetResponse` |
| | `createTimesheet()` | `CreateTimesheetRequest` | `POST /json/2/account.analytic.line/create` | `CreateTimesheetResponse` |
| | `updateTimesheet()` | `UpdateTimesheetRequest` | `POST /json/2/account.analytic.line/write` | `MutateTimesheetResponse` |
| | `deleteTimesheet()` | `DeleteTimesheetRequest` | `POST /json/2/account.analytic.line/unlink` | `MutateTimesheetResponse` |
| Contacts — `Api\Contacts` | send directly | `ReadContactRequest` | `POST /json/2/res.partner/read` | Saloon `Response` |
| | send directly | `ReadAllContactRequest` | `POST /json/2/res.partner/search_read` | Saloon `Response` |
| | `searchContacts()` | `SearchContactRequest` | `POST /json/2/res.partner/search` | `SearchContactResponse` |
| | `searchCountContacts()` | `SearchCountContactRequest` | `POST /json/2/res.partner/search_count` | `SearchCountContactResponse` |
| | `nameSearchContacts()` | `NameSearchContactRequest` | `POST /json/2/res.partner/name_search` | `NameSearchContactResponse` |
| | `createContact()` | `CreateContactRequest` | `POST /json/2/res.partner/create` | `CreateContactResponse` |
| | `updateContact()` | `UpdateContactRequest` | `POST /json/2/res.partner/write` | `MutateContactResponse` |
| | `deleteContact()` | `DeleteContactRequest` | `POST /json/2/res.partner/unlink` | `MutateContactResponse` |
| Bank Accounts — `Api\BankAccounts` | `getBankAccounts()` | `GetBankAccountsRequest` | `POST /json/2/res.partner.bank/search_read` | `BankAccountsResponse` |
| | `readBankAccount()` | `ReadBankAccountRequest` | `POST /json/2/res.partner.bank/read` | `BankAccountsResponse` |
| | `searchBankAccounts()` | `SearchBankAccountRequest` | `POST /json/2/res.partner.bank/search` | `SearchBankAccountResponse` |
| | `searchCountBankAccounts()` | `SearchCountBankAccountRequest` | `POST /json/2/res.partner.bank/search_count` | `SearchCountBankAccountResponse` |
| | `createBankAccount()` | `CreateBankAccountRequest` | `POST /json/2/res.partner.bank/create` | `CreateBankAccountResponse` |
| | `updateBankAccount()` | `UpdateBankAccountRequest` | `POST /json/2/res.partner.bank/write` | `MutateBankAccountResponse` |
| | `deleteBankAccount()` | `DeleteBankAccountRequest` | `POST /json/2/res.partner.bank/unlink` | `MutateBankAccountResponse` |
| Generic models — `Api\Models` | `searchRead()` | `SearchReadRequest` | `POST /json/2/{model}/search_read` | `SearchReadResponse` |
| | `create()` | `CreateRequest` | `POST /json/2/{model}/create` | `CreateResponse` |
| | `write()` | `WriteRequest` | `POST /json/2/{model}/write` | `WriteResponse` |
| | `callMethod()` | `CallMethodRequest` | `POST /json/2/{model}/{method}` | `CallMethodResponse` |

`Api\…` areas use `CodebarAg\Odoo\Requests\Api\<Area>` for the request and
`CodebarAg\Odoo\Responses\Api\<Area>` for the response (e.g. `CodebarAg\Odoo\Requests\Api\Contacts\ReadContactRequest`).
`syncAll()` sends `GetProjectsRequest`, `GetAllTasksRequest` and `GetTimesheetEntriesRequest`.

#### Sending a request without a connector method

Requests can always be sent with `$connector->send()`. Contacts are read this way:

```php
use CodebarAg\Odoo\Dto\Contacts\ContactDto;
use CodebarAg\Odoo\Requests\Api\Contacts\ReadAllContactRequest;
use CodebarAg\Odoo\Requests\Api\Contacts\ReadContactRequest;

// Read one contact by ID: new ReadContactRequest(int $id, array $fields = [])
$response = $connector->send(new ReadContactRequest(id: 42));
$contact = ContactDto::fromArray($response->json()[0]);

// Read with your own field list
$response = $connector->send(new ReadContactRequest(id: 42, fields: ['id', 'name', 'email']));

// Read all contacts (empty domain, no limit): new ReadAllContactRequest(array $fields = [])
$response = $connector->send(new ReadAllContactRequest);
$contacts = collect($response->json())
    ->map(fn (array $record) => ContactDto::fromArray($record))
    ->all(); // array<ContactDto>
```

Requests that do have a connector method can be sent directly too, and every typed response can wrap
the Saloon response with `fromResponse()`. Note that the bank-account requests default to the classic
(≤ 19.0) field set when sent directly — the connector methods pick the version-aware set for you:

```php
use CodebarAg\Odoo\Requests\Api\BankAccounts\BankAccountFields;
use CodebarAg\Odoo\Requests\Api\BankAccounts\ReadBankAccountRequest;
use CodebarAg\Odoo\Requests\Session\Health\HealthRequest;
use CodebarAg\Odoo\Responses\Api\BankAccounts\BankAccountsResponse;

$response = BankAccountsResponse::fromResponse(
    $connector->send(new ReadBankAccountRequest(id: 5, fields: BankAccountFields::MODERN)),
);
$response->bankAccounts(); // array<BankAccountDto>

$connector->send(new HealthRequest)->json('status'); // "pass" when healthy
```

Constructor arguments of the requests without a DTO:

| Request | Constructor |
|---------|-------------|
| `HealthRequest`, `GetOdooVersionRequest`, `GetDatabasesRequest`, `GetUserContextRequest` | none |
| `GetUserRequest` | `array $fields = [], array $domain = [], int $limit = 1` |
| `GetUserByIdRequest` | `int $uid, array $fields = [], int $limit = 1` |
| `GetEmployeeByUserIdRequest` | `int $userId, array $fields = [], int $limit = 1` |
| `GetFieldsRequest` | `string $model, array $attributes = ['string', 'type', 'required', 'readonly', 'relation']` |
| `GetPermissionsRequest` | `string $model, string $operation` |
| `GetProjectsRequest`, `GetAllTasksRequest`, `GetTimesheetEntriesRequest`, `GetBankAccountsRequest` | `array $fields = [], array $domain = [], int $limit = 100` |
| `GetTasksByProjectRequest` | `int $projectId, array $fields = [], int $limit = 100, string $operator = '='` |
| `GetTimesheetEntriesLastDaysRequest` | `int $days, array $fields = [], string $operator = '>='` |
| `ReadTimesheetRequest`, `ReadContactRequest`, `ReadBankAccountRequest` | `int $id, array $fields = []` |
| `ReadAllContactRequest` | `array $fields = []` |
| `SearchContactRequest`, `SearchBankAccountRequest` | `array $domain` |
| `SearchCountContactRequest`, `SearchCountBankAccountRequest` | `array $domain = []` |
| `NameSearchContactRequest` | `string $name, array $domain = [], int $limit = 100` |
| `DeleteProjectRequest`, `DeleteTaskRequest`, `DeleteTimesheetRequest`, `DeleteContactRequest`, `DeleteBankAccountRequest` | `int $id` |
| `SearchReadRequest` | `string $model, array $domain = [], array $fields = [], int $limit = 80, int $offset = 0, ?string $order = null` |
| `CreateRequest` | `string $model, array $values` |
| `WriteRequest` | `string $model, array $ids, array $values` |
| `CallMethodRequest` | `string $model, string $modelMethod, array $params = []` |

The create/update requests take their DTO (`new CreateContactRequest(CreateContactDto $dto)`, …);
`CreateBankAccountRequest` and `UpdateBankAccountRequest` additionally take `bool $modern = false`.
An empty `$fields` array means the request's default field set (except `SearchReadRequest`, which sends
the empty list to Odoo as-is).

## 📦 DTOs

Read DTOs are built on [spatie/laravel-data](https://spatie.be/docs/laravel-data).
Odoo's relation tuples (`[id, name]`) are flattened onto paired properties
(e.g. `projectId` / `projectName`) and its `false`-means-empty sentinel is
normalised to `null`. Each DTO keeps a `fromArray()` factory for backwards
compatibility and is also a full laravel-data `Data` object (`from()`, `collect()`, …).

All DTOs live under `CodebarAg\Odoo\Dto\<Area>`. Read DTOs extend `CodebarAg\Odoo\Data\OdooData`;
create/update DTOs are payloads whose `toArray()` builds the Odoo value map (`null` fields are
omitted, `extraValues` is merged at the top level).

| DTO                    | Namespace           | Description                                          |
|------------------------|---------------------|------------------------------------------------------|
| `UserDto`              | `Dto\Users`         | Represents an Odoo user (read)                       |
| `EmployeeDto`          | `Dto\Employees`     | Represents an Odoo employee (read)                   |
| `FieldDto`             | `Dto\Fields`        | Represents a field definition on an Odoo model       |
| `ProjectDto`           | `Dto\Projects`      | Represents an Odoo project (read)                    |
| `CreateProjectDto`     | `Dto\Projects`      | Payload for creating a project                       |
| `UpdateProjectDto`     | `Dto\Projects`      | Payload for updating a project                       |
| `TaskDto`              | `Dto\Tasks`         | Represents an Odoo task (read)                       |
| `CreateTaskDto`        | `Dto\Tasks`         | Payload for creating a task                          |
| `UpdateTaskDto`        | `Dto\Tasks`         | Payload for updating a task                          |
| `TimesheetEntryDto`    | `Dto\Timesheets`    | Represents a timesheet entry (read)                  |
| `CreateTimesheetDto`   | `Dto\Timesheets`    | Payload for creating a timesheet entry               |
| `UpdateTimesheetDto`   | `Dto\Timesheets`    | Payload for updating a timesheet entry               |
| `ContactDto`           | `Dto\Contacts`      | Represents an Odoo contact / `res.partner` (read)    |
| `CreateContactDto`     | `Dto\Contacts`      | Payload for creating a contact                       |
| `UpdateContactDto`     | `Dto\Contacts`      | Payload for updating a contact                       |
| `BankAccountDto`       | `Dto\BankAccounts`  | Represents a bank account / `res.partner.bank` (read) |
| `CreateBankAccountDto` | `Dto\BankAccounts`  | Payload for creating a bank account                  |
| `UpdateBankAccountDto` | `Dto\BankAccounts`  | Payload for updating a bank account                  |

## 🧪 Testing

```bash
composer test
```

For live integration tests against a real Odoo instance, copy `phpunit.xml.dist` to `phpunit.xml`, fill in the `LARAVEL_ODOO_URL`, `LARAVEL_ODOO_API_KEY` and `LARAVEL_ODOO_DB` env values, then run:

```bash
composer test:live
```

## 📝 Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## ✏️ Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## 🧑‍💻 Security Vulnerabilities

Please review [our security policy](.github/SECURITY.md) on how to report security vulnerabilities.

## 🙏 Credits

- [Sebastian Bürgin-Fix](https://github.com/StanBarrows)
- [Tobias Brogle](https://github.com/Astro2006)
- [All Contributors](../../contributors)
- [Skeleton Repository from Spatie](https://github.com/spatie/package-skeleton-laravel)
- [Laravel Package Training from Spatie](https://spatie.be/videos/laravel-package-training)

## 🎭 License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
