<?php

declare(strict_types=1);

namespace CodebarAg\Odoo;

use CodebarAg\Odoo\Dto\Timesheets\CreateTimesheetDto;
use CodebarAg\Odoo\Dto\Timesheets\UpdateTimesheetDto;
use CodebarAg\Odoo\Requests\Api\Employees\GetEmployeeByUserIdRequest;
use CodebarAg\Odoo\Requests\Api\Fields\GetAllFieldsRequest;
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
use CodebarAg\Odoo\Requests\Api\User\GetUserRequest;
use CodebarAg\Odoo\Requests\Session\Database\GetDatabasesRequest;
use CodebarAg\Odoo\Requests\Session\Health\HealthRequest;
use CodebarAg\Odoo\Requests\Session\Version\GetOdooVersionRequest;
use Saloon\Http\Connector;
use Saloon\Http\Response;

class OdooConnector extends Connector
{
    public function __construct(
        private readonly string $baseUrl,
        private readonly ?string $apiKey = null,
        private readonly ?string $db = null,
    ) {
    }

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
                'max' => 5,
                'track_redirects' => true,
            ],
        ];
    }

    public function health(): Response
    {
        return $this->send(new HealthRequest());
    }

    public function version(): Response
    {
        return $this->send(new GetOdooVersionRequest());
    }

    public function databases(): Response
    {
        return $this->send(new GetDatabasesRequest());
    }

    public function getUser(): Response
    {
        return $this->send(new GetUserRequest());
    }

    /** @param array<string> $fields */
    public function getEmployeeByUserId(int $userId, array $fields = []): Response
    {
        return $this->send(new GetEmployeeByUserIdRequest($userId, $fields));
    }

    /** @param array<string> $attributes */
    public function getFields(string $model, array $attributes = []): Response
    {
        return $this->send(new GetFieldsRequest($model, $attributes));
    }

    public function getAllFields(): Response
    {
        return $this->send(new GetAllFieldsRequest());
    }

    public function getPermissions(string $model, string $operation): Response
    {
        return $this->send(new GetPermissionsRequest($model, $operation));
    }

    /**
     * @param  array<string>  $fields
     * @param  array<mixed>  $domain
     */
    public function getProjects(array $fields = [], array $domain = [], int $limit = 100): Response
    {
        return $this->send(new GetProjectsRequest($fields, $domain, $limit));
    }

    /**
     * @param  array<string>  $fields
     * @param  array<mixed>  $domain
     */
    public function getAllTasks(array $fields = [], array $domain = [], int $limit = 100): Response
    {
        return $this->send(new GetAllTasksRequest($fields, $domain, $limit));
    }

    /** @param array<string> $fields */
    public function getTasksByProject(int $projectId, array $fields = []): Response
    {
        return $this->send(new GetTasksByProjectRequest($projectId, $fields));
    }

    /**
     * @param  array<string>  $fields
     * @param  array<mixed>  $domain
     */
    public function getTimesheetEntries(array $fields = [], array $domain = [], int $limit = 100): Response
    {
        return $this->send(new GetTimesheetEntriesRequest($fields, $domain, $limit));
    }

    /** @param array<string> $fields */
    public function getTimesheetEntriesLastDays(int $days, array $fields = []): Response
    {
        return $this->send(new GetTimesheetEntriesLastDaysRequest($days, $fields));
    }

    /** @param array<string> $fields */
    public function readTimesheet(int $id, array $fields = []): Response
    {
        return $this->send(new ReadTimesheetRequest($id, $fields));
    }

    public function createTimesheet(CreateTimesheetDto $dto): Response
    {
        return $this->send(new CreateTimesheetRequest($dto));
    }

    public function updateTimesheet(UpdateTimesheetDto $dto): Response
    {
        return $this->send(new UpdateTimesheetRequest($dto));
    }

    public function deleteTimesheet(int $id): Response
    {
        return $this->send(new DeleteTimesheetRequest($id));
    }

    /** @return array<string, Response> */
    public function syncAll(): array
    {
        return [
            'projects' => $this->getProjects(),
            'tasks' => $this->getAllTasks(),
            'timesheets' => $this->getTimesheetEntries(),
        ];
    }
}
