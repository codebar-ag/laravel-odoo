<?php

declare(strict_types=1);

namespace CodebarAg\Odoo;

use CodebarAg\Odoo\Dto\BankAccounts\CreateBankAccountDto;
use CodebarAg\Odoo\Dto\BankAccounts\UpdateBankAccountDto;
use CodebarAg\Odoo\Dto\Contacts\CreateContactDto;
use CodebarAg\Odoo\Dto\Contacts\UpdateContactDto;
use CodebarAg\Odoo\Dto\Projects\CreateProjectDto;
use CodebarAg\Odoo\Dto\Projects\UpdateProjectDto;
use CodebarAg\Odoo\Dto\Tasks\CreateTaskDto;
use CodebarAg\Odoo\Dto\Tasks\UpdateTaskDto;
use CodebarAg\Odoo\Dto\Timesheets\CreateTimesheetDto;
use CodebarAg\Odoo\Dto\Timesheets\UpdateTimesheetDto;
use CodebarAg\Odoo\Requests\Api\BankAccounts\BankAccountFields;
use CodebarAg\Odoo\Requests\Api\BankAccounts\CreateBankAccountRequest;
use CodebarAg\Odoo\Requests\Api\BankAccounts\DeleteBankAccountRequest;
use CodebarAg\Odoo\Requests\Api\BankAccounts\GetBankAccountsRequest;
use CodebarAg\Odoo\Requests\Api\BankAccounts\ReadBankAccountRequest;
use CodebarAg\Odoo\Requests\Api\BankAccounts\SearchBankAccountRequest;
use CodebarAg\Odoo\Requests\Api\BankAccounts\SearchCountBankAccountRequest;
use CodebarAg\Odoo\Requests\Api\BankAccounts\UpdateBankAccountRequest;
use CodebarAg\Odoo\Requests\Api\Contacts\CreateContactRequest;
use CodebarAg\Odoo\Requests\Api\Contacts\DeleteContactRequest;
use CodebarAg\Odoo\Requests\Api\Contacts\NameSearchContactRequest;
use CodebarAg\Odoo\Requests\Api\Contacts\SearchContactRequest;
use CodebarAg\Odoo\Requests\Api\Contacts\SearchCountContactRequest;
use CodebarAg\Odoo\Requests\Api\Contacts\UpdateContactRequest;
use CodebarAg\Odoo\Requests\Api\Employees\GetEmployeeByUserIdRequest;
use CodebarAg\Odoo\Requests\Api\Fields\GetFieldsRequest;
use CodebarAg\Odoo\Requests\Api\Permissions\GetPermissionsRequest;
use CodebarAg\Odoo\Requests\Api\Projects\CreateProjectRequest;
use CodebarAg\Odoo\Requests\Api\Projects\DeleteProjectRequest;
use CodebarAg\Odoo\Requests\Api\Projects\GetProjectsRequest;
use CodebarAg\Odoo\Requests\Api\Projects\UpdateProjectRequest;
use CodebarAg\Odoo\Requests\Api\Tasks\CreateTaskRequest;
use CodebarAg\Odoo\Requests\Api\Tasks\DeleteTaskRequest;
use CodebarAg\Odoo\Requests\Api\Tasks\GetAllTasksRequest;
use CodebarAg\Odoo\Requests\Api\Tasks\GetTasksByProjectRequest;
use CodebarAg\Odoo\Requests\Api\Tasks\UpdateTaskRequest;
use CodebarAg\Odoo\Requests\Api\Timesheets\CreateTimesheetRequest;
use CodebarAg\Odoo\Requests\Api\Timesheets\DeleteTimesheetRequest;
use CodebarAg\Odoo\Requests\Api\Timesheets\GetTimesheetEntriesLastDaysRequest;
use CodebarAg\Odoo\Requests\Api\Timesheets\GetTimesheetEntriesRequest;
use CodebarAg\Odoo\Requests\Api\Timesheets\ReadTimesheetRequest;
use CodebarAg\Odoo\Requests\Api\Timesheets\UpdateTimesheetRequest;
use CodebarAg\Odoo\Requests\Api\User\GetUserByIdRequest;
use CodebarAg\Odoo\Requests\Api\User\GetUserContextRequest;
use CodebarAg\Odoo\Requests\Api\User\GetUserRequest;
use CodebarAg\Odoo\Requests\Session\Database\GetDatabasesRequest;
use CodebarAg\Odoo\Requests\Session\Health\HealthRequest;
use CodebarAg\Odoo\Requests\Session\Version\GetOdooVersionRequest;
use CodebarAg\Odoo\Responses\Api\BankAccounts\BankAccountsResponse;
use CodebarAg\Odoo\Responses\Api\BankAccounts\CreateBankAccountResponse;
use CodebarAg\Odoo\Responses\Api\BankAccounts\MutateBankAccountResponse;
use CodebarAg\Odoo\Responses\Api\BankAccounts\SearchBankAccountResponse;
use CodebarAg\Odoo\Responses\Api\BankAccounts\SearchCountBankAccountResponse;
use CodebarAg\Odoo\Responses\Api\Contacts\CreateContactResponse;
use CodebarAg\Odoo\Responses\Api\Contacts\MutateContactResponse;
use CodebarAg\Odoo\Responses\Api\Contacts\NameSearchContactResponse;
use CodebarAg\Odoo\Responses\Api\Contacts\SearchContactResponse;
use CodebarAg\Odoo\Responses\Api\Contacts\SearchCountContactResponse;
use CodebarAg\Odoo\Responses\Api\Employees\EmployeeResponse;
use CodebarAg\Odoo\Responses\Api\Fields\FieldsResponse;
use CodebarAg\Odoo\Responses\Api\Permissions\PermissionsResponse;
use CodebarAg\Odoo\Responses\Api\Projects\CreateProjectResponse;
use CodebarAg\Odoo\Responses\Api\Projects\MutateProjectResponse;
use CodebarAg\Odoo\Responses\Api\Projects\ProjectsResponse;
use CodebarAg\Odoo\Responses\Api\Tasks\CreateTaskResponse;
use CodebarAg\Odoo\Responses\Api\Tasks\MutateTaskResponse;
use CodebarAg\Odoo\Responses\Api\Tasks\TasksResponse;
use CodebarAg\Odoo\Responses\Api\Timesheets\CreateTimesheetResponse;
use CodebarAg\Odoo\Responses\Api\Timesheets\MutateTimesheetResponse;
use CodebarAg\Odoo\Responses\Api\Timesheets\TimesheetEntriesResponse;
use CodebarAg\Odoo\Responses\Api\Timesheets\TimesheetResponse;
use CodebarAg\Odoo\Responses\Api\User\UserContextResponse;
use CodebarAg\Odoo\Responses\Api\User\UserResponse;
use CodebarAg\Odoo\Responses\Session\DatabasesResponse;
use CodebarAg\Odoo\Responses\Session\HealthResponse;
use CodebarAg\Odoo\Responses\Session\VersionResponse;
use Saloon\Http\Connector;

class OdooConnector extends Connector
{
    private ?bool $modernBankAccountSchema = null;

    public function __construct(
        private readonly string $baseUrl,
        private readonly ?string $apiKey = null,
        private readonly ?string $db = null,
        private readonly int $maxRedirects = 5,
        private readonly float $timeout = 15,
    ) {}

    public function resolveBaseUrl(): string
    {
        return $this->baseUrl;
    }

    public function getApiKey(): ?string
    {
        return $this->apiKey;
    }

    public function getDb(): ?string
    {
        return $this->db;
    }

    /** @return array<string, string> */
    protected function defaultHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            ...($this->apiKey !== null ? ['Authorization' => "Bearer {$this->apiKey}"] : []),
            ...($this->db !== null ? ['X-Odoo-Database' => $this->db] : []),
        ];
    }

    /** @return array<string, mixed> */
    protected function defaultConfig(): array
    {
        return [
            'allow_redirects' => [
                'max' => $this->maxRedirects,
                'track_redirects' => true,
            ],
            'timeout' => $this->timeout,
        ];
    }

    public function health(): HealthResponse
    {
        return HealthResponse::fromResponse($this->send(new HealthRequest));
    }

    public function version(): VersionResponse
    {
        return VersionResponse::fromResponse($this->send(new GetOdooVersionRequest));
    }

    public function databases(): DatabasesResponse
    {
        return DatabasesResponse::fromResponse($this->send(new GetDatabasesRequest));
    }

    /**
     * @param  array<string>  $fields
     * @param  array<mixed>  $domain
     */
    public function getUser(array $fields = [], array $domain = [], int $limit = 1): UserResponse
    {
        return UserResponse::fromResponse($this->send(new GetUserRequest($fields, $domain, $limit)));
    }

    public function getUserContext(): UserContextResponse
    {
        return UserContextResponse::fromResponse($this->send(new GetUserContextRequest));
    }

    /** @param array<string> $fields */
    public function getUserById(int $uid, array $fields = [], int $limit = 1): UserResponse
    {
        return UserResponse::fromResponse($this->send(new GetUserByIdRequest($uid, $fields, $limit)));
    }

    /** @param array<string> $fields */
    public function getEmployeeByUserId(int $userId, array $fields = [], int $limit = 1): EmployeeResponse
    {
        return EmployeeResponse::fromResponse($this->send(new GetEmployeeByUserIdRequest($userId, $fields, $limit)));
    }

    /** @param array<string> $attributes */
    public function getFields(string $model, array $attributes = []): FieldsResponse
    {
        return FieldsResponse::fromResponse($this->send(new GetFieldsRequest($model, $attributes)));
    }

    public function getAllFields(): FieldsResponse
    {
        return FieldsResponse::fromResponse($this->send(new GetFieldsRequest('account.analytic.line')));
    }

    public function getPermissions(string $model, string $operation): PermissionsResponse
    {
        return PermissionsResponse::fromResponse($this->send(new GetPermissionsRequest($model, $operation)));
    }

    /**
     * @param  array<string>  $fields
     * @param  array<mixed>  $domain
     */
    public function getProjects(array $fields = [], array $domain = [], int $limit = 100): ProjectsResponse
    {
        return ProjectsResponse::fromResponse($this->send(new GetProjectsRequest($fields, $domain, $limit)));
    }

    public function createProject(CreateProjectDto $dto): CreateProjectResponse
    {
        return CreateProjectResponse::fromResponse($this->send(new CreateProjectRequest($dto)));
    }

    public function updateProject(UpdateProjectDto $dto): MutateProjectResponse
    {
        return MutateProjectResponse::fromResponse($this->send(new UpdateProjectRequest($dto)));
    }

    public function deleteProject(int $id): MutateProjectResponse
    {
        return MutateProjectResponse::fromResponse($this->send(new DeleteProjectRequest($id)));
    }

    /**
     * @param  array<string>  $fields
     * @param  array<mixed>  $domain
     */
    public function getAllTasks(array $fields = [], array $domain = [], int $limit = 100): TasksResponse
    {
        return TasksResponse::fromResponse($this->send(new GetAllTasksRequest($fields, $domain, $limit)));
    }

    /** @param array<string> $fields */
    public function getTasksByProject(int $projectId, array $fields = [], int $limit = 100, string $operator = '='): TasksResponse
    {
        return TasksResponse::fromResponse($this->send(new GetTasksByProjectRequest($projectId, $fields, $limit, $operator)));
    }

    public function createTask(CreateTaskDto $dto): CreateTaskResponse
    {
        return CreateTaskResponse::fromResponse($this->send(new CreateTaskRequest($dto)));
    }

    public function updateTask(UpdateTaskDto $dto): MutateTaskResponse
    {
        return MutateTaskResponse::fromResponse($this->send(new UpdateTaskRequest($dto)));
    }

    public function deleteTask(int $id): MutateTaskResponse
    {
        return MutateTaskResponse::fromResponse($this->send(new DeleteTaskRequest($id)));
    }

    /**
     * @param  array<string>  $fields
     * @param  array<mixed>  $domain
     */
    public function getTimesheetEntries(array $fields = [], array $domain = [], int $limit = 100): TimesheetEntriesResponse
    {
        return TimesheetEntriesResponse::fromResponse($this->send(new GetTimesheetEntriesRequest($fields, $domain, $limit)));
    }

    /** @param array<string> $fields */
    public function getTimesheetEntriesLastDays(int $days, array $fields = [], string $operator = '>='): TimesheetEntriesResponse
    {
        return TimesheetEntriesResponse::fromResponse($this->send(new GetTimesheetEntriesLastDaysRequest($days, $fields, $operator)));
    }

    /** @param array<string> $fields */
    public function readTimesheet(int $id, array $fields = []): TimesheetResponse
    {
        return TimesheetResponse::fromResponse($this->send(new ReadTimesheetRequest($id, $fields)));
    }

    public function createTimesheet(CreateTimesheetDto $dto): CreateTimesheetResponse
    {
        return CreateTimesheetResponse::fromResponse($this->send(new CreateTimesheetRequest($dto)));
    }

    public function updateTimesheet(UpdateTimesheetDto $dto): MutateTimesheetResponse
    {
        return MutateTimesheetResponse::fromResponse($this->send(new UpdateTimesheetRequest($dto)));
    }

    public function deleteTimesheet(int $id): MutateTimesheetResponse
    {
        return MutateTimesheetResponse::fromResponse($this->send(new DeleteTimesheetRequest($id)));
    }

    /** @return array{projects: ProjectsResponse, tasks: TasksResponse, timesheets: TimesheetEntriesResponse} */
    public function syncAll(): array
    {
        return [
            'projects' => $this->getProjects(),
            'tasks' => $this->getAllTasks(),
            'timesheets' => $this->getTimesheetEntries(),
        ];
    }

    public function createContact(CreateContactDto $dto): CreateContactResponse
    {
        return CreateContactResponse::fromResponse($this->send(new CreateContactRequest($dto)));
    }

    public function updateContact(UpdateContactDto $dto): MutateContactResponse
    {
        return MutateContactResponse::fromResponse($this->send(new UpdateContactRequest($dto)));
    }

    public function deleteContact(int $id): MutateContactResponse
    {
        return MutateContactResponse::fromResponse($this->send(new DeleteContactRequest($id)));
    }

    /** @param array<mixed> $domain */
    public function searchContacts(array $domain): SearchContactResponse
    {
        return SearchContactResponse::fromResponse($this->send(new SearchContactRequest($domain)));
    }

    /** @param array<mixed> $domain */
    public function searchCountContacts(array $domain = []): SearchCountContactResponse
    {
        return SearchCountContactResponse::fromResponse($this->send(new SearchCountContactRequest($domain)));
    }

    /** @param array<mixed> $domain */
    public function nameSearchContacts(string $name, array $domain = [], int $limit = 100): NameSearchContactResponse
    {
        return NameSearchContactResponse::fromResponse($this->send(new NameSearchContactRequest($name, $domain, $limit)));
    }

    /**
     * @param  array<string>  $fields
     * @param  array<mixed>  $domain
     */
    public function getBankAccounts(array $fields = [], array $domain = [], int $limit = 100): BankAccountsResponse
    {
        $fields = $fields ?: BankAccountFields::for($this->usesModernBankAccountSchema());

        return BankAccountsResponse::fromResponse($this->send(new GetBankAccountsRequest($fields, $domain, $limit)));
    }

    /** @param array<mixed> $domain */
    public function searchBankAccounts(array $domain): SearchBankAccountResponse
    {
        return SearchBankAccountResponse::fromResponse($this->send(new SearchBankAccountRequest($domain)));
    }

    /** @param array<string> $fields */
    public function readBankAccount(int $id, array $fields = []): BankAccountsResponse
    {
        $fields = $fields ?: BankAccountFields::for($this->usesModernBankAccountSchema());

        return BankAccountsResponse::fromResponse($this->send(new ReadBankAccountRequest($id, $fields)));
    }

    /** @param array<mixed> $domain */
    public function searchCountBankAccounts(array $domain = []): SearchCountBankAccountResponse
    {
        return SearchCountBankAccountResponse::fromResponse($this->send(new SearchCountBankAccountRequest($domain)));
    }

    public function createBankAccount(CreateBankAccountDto $dto): CreateBankAccountResponse
    {
        return CreateBankAccountResponse::fromResponse($this->send(new CreateBankAccountRequest($dto, $this->usesModernBankAccountSchema())));
    }

    public function updateBankAccount(UpdateBankAccountDto $dto): MutateBankAccountResponse
    {
        return MutateBankAccountResponse::fromResponse($this->send(new UpdateBankAccountRequest($dto, $this->usesModernBankAccountSchema())));
    }

    public function deleteBankAccount(int $id): MutateBankAccountResponse
    {
        return MutateBankAccountResponse::fromResponse($this->send(new DeleteBankAccountRequest($id)));
    }

    /**
     * Whether the connected Odoo uses the 19.3+ `res.partner.bank` schema
     * (`account_number` / `holder_name`, no `bank_id` / `currency_id`).
     *
     * The server version is queried once and memoised. Detection failures fall
     * back to the classic (<= 19.0) schema.
     */
    public function usesModernBankAccountSchema(): bool
    {
        if ($this->modernBankAccountSchema !== null) {
            return $this->modernBankAccountSchema;
        }

        $version = $this->version()->majorMinor();

        $modern = $version !== null
            && ($version[0] > 19 || ($version[0] === 19 && $version[1] >= 3));

        return $this->modernBankAccountSchema = $modern;
    }

    /**
     * Force the `res.partner.bank` schema dialect, bypassing version detection
     * (useful in tests or to avoid the extra version lookup).
     */
    public function withBankAccountSchema(bool $modern): static
    {
        $this->modernBankAccountSchema = $modern;

        return $this;
    }
}
