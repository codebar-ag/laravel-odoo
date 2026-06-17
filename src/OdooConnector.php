<?php

declare(strict_types=1);

namespace CodebarAg\Odoo;

use CodebarAg\Odoo\Dto\Timesheets\CreateTimesheetDto;
use CodebarAg\Odoo\Dto\Timesheets\UpdateTimesheetDto;
use CodebarAg\Odoo\Requests\Api\Employees\GetEmployeeByUserIdRequest;
use CodebarAg\Odoo\Requests\Api\Fields\GetFieldsRequest;
use CodebarAg\Odoo\Requests\Api\Permissions\GetPermissionsRequest;
use CodebarAg\Odoo\Requests\Api\Projects\GetProjectsRequest;
use CodebarAg\Odoo\Requests\Api\Tasks\GetAllTasksRequest;
use CodebarAg\Odoo\Requests\Api\Tasks\GetTasksByProjectRequest;
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
use CodebarAg\Odoo\Responses\Api\Employees\EmployeeResponse;
use CodebarAg\Odoo\Responses\Api\Fields\FieldsResponse;
use CodebarAg\Odoo\Responses\Api\Permissions\PermissionsResponse;
use CodebarAg\Odoo\Responses\Api\Projects\ProjectsResponse;
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
    public function __construct(
        private readonly string $baseUrl,
        private readonly ?string $apiKey = null,
        private readonly ?string $db = null,
        private readonly int $maxRedirects = 5,
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
        return UserContextResponse::fromResponse($this->send(new GetUserContextRequest()));
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
}
