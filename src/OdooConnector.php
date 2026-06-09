<?php

declare(strict_types=1);

namespace CodebarAg\Odoo;

use CodebarAg\Odoo\Dto\Auth\Authenticate2FADto as NewAuthenticate2FADto;
use CodebarAg\Odoo\Dto\Auth\AuthenticateDto as NewAuthenticateDto;
use CodebarAg\Odoo\Dto\CallKw\Timesheets\CreateTimesheetDto;
use CodebarAg\Odoo\Dto\CallKw\Timesheets\UpdateTimesheetDto;
use CodebarAg\Odoo\Dto\Session\Auth\Authenticate2FADto;
use CodebarAg\Odoo\Dto\Session\Auth\AuthenticateDto;
use CodebarAg\Odoo\Dto\Session\Permissions\PermissionDto;
use CodebarAg\Odoo\Requests\Auth\Authenticate2FARequest as NewAuthenticate2FARequest;
use CodebarAg\Odoo\Requests\Auth\AuthenticateRequest as NewAuthenticateRequest;
use CodebarAg\Odoo\Requests\CallKw\Employees\GetEmployeeByUserIdRequest;
use CodebarAg\Odoo\Requests\CallKw\Fields\GetAllFieldsRequest;
use CodebarAg\Odoo\Requests\CallKw\Fields\GetFieldsRequest;
use CodebarAg\Odoo\Requests\CallKw\Projects\GetProjectsRequest;
use CodebarAg\Odoo\Requests\CallKw\Tasks\GetAllTasksRequest;
use CodebarAg\Odoo\Requests\CallKw\Tasks\GetTasksByProjectRequest;
use CodebarAg\Odoo\Requests\CallKw\Timesheets\CreateTimesheetRequest;
use CodebarAg\Odoo\Requests\CallKw\Timesheets\DeleteTimesheetRequest;
use CodebarAg\Odoo\Requests\CallKw\Timesheets\GetTimesheetEntriesLastDaysRequest;
use CodebarAg\Odoo\Requests\CallKw\Timesheets\GetTimesheetEntriesRequest;
use CodebarAg\Odoo\Requests\CallKw\Timesheets\ReadTimesheetRequest;
use CodebarAg\Odoo\Requests\CallKw\Timesheets\UpdateTimesheetRequest;
use CodebarAg\Odoo\Requests\Session\Auth\BasicAuth\AuthenticateRequest;
use CodebarAg\Odoo\Requests\Session\Auth\BasicAuth\GetPermissionsRequest;
use CodebarAg\Odoo\Requests\Session\Auth\LogoutRequest;
use CodebarAg\Odoo\Requests\Session\Auth\TwoFactor\Authenticate2FARequest;
use CodebarAg\Odoo\Requests\Session\Auth\TwoFactor\GetTotpPageRequest;
use CodebarAg\Odoo\Requests\Session\Database\GetDatabasesRequest;
use CodebarAg\Odoo\Requests\Session\Health\HealthRequest;
use CodebarAg\Odoo\Requests\Session\Version\GetOdooVersionRequest;
use CodebarAg\Odoo\Responses\Auth\AuthResponse;
use Illuminate\Support\Arr;
use Saloon\Http\Connector;
use Saloon\Http\Response;

class OdooConnector extends Connector
{
    public function __construct(
        private readonly string $baseUrl,
        private readonly string $db,
        private readonly ?string $sessionId = null,
    ) {}

    public function resolveBaseUrl(): string
    {
        return $this->baseUrl;
    }

    public function getDb(): string
    {
        return $this->db;
    }

    public function getSessionId(): ?string
    {
        return $this->sessionId;
    }

    /** @return array<string, string> */
    protected function defaultHeaders(): array
    {
        $headers = [
            'Content-Type' => 'application/json',
            'X-Odoo-Database' => $this->db,
        ];

        if ($this->sessionId !== null) {
            Arr::set($headers, 'Cookie', "session_id={$this->sessionId}");
        }

        return $headers;
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

    // Session

    public function health(): Response
    {
        return $this->send(new HealthRequest);
    }

    public function version(): Response
    {
        return $this->send(new GetOdooVersionRequest);
    }

    public function databases(): Response
    {
        return $this->send(new GetDatabasesRequest);
    }

    // Auth

    public function sessionLogin(AuthenticateDto $dto): AuthResponse
    {
        return AuthResponse::fromResponse(
            $this->send(new AuthenticateRequest($dto->toArray()))
        );
    }

    public function twoFactorLogin(Authenticate2FADto $dto): AuthResponse
    {
        $pageHtml = $this->send(new GetTotpPageRequest)->body();
        preg_match('/csrf_token:\s*"([^"]+)"/', $pageHtml, $matches);
        $csrfToken = $matches[1] ?? '';

        return AuthResponse::fromResponse(
            $this->send(new Authenticate2FARequest(array_merge($dto->toArray(), [
                'csrf_token' => $csrfToken,
            ])))
        );
    }

    public function login(NewAuthenticateDto $dto): AuthResponse
    {
        return AuthResponse::fromResponse(
            $this->send(new NewAuthenticateRequest($dto))
        );
    }

    public function verifyTotp(NewAuthenticate2FADto $dto): AuthResponse
    {
        return AuthResponse::fromResponse(
            $this->send(new NewAuthenticate2FARequest($dto))
        );
    }

    public function logout(): Response
    {
        return $this->send(new LogoutRequest);
    }

    // Employees

    public function getEmployeeByUserId(int $userId): Response
    {
        return $this->send(new GetEmployeeByUserIdRequest($userId));
    }

    // Fields

    public function getFields(string $model, array $attributes = []): Response
    {
        return $this->send(new GetFieldsRequest($model, $attributes));
    }

    public function getAllFields(): Response
    {
        return $this->send(new GetAllFieldsRequest);
    }

    // Permissions

    public function checkPermissions(string $model, string $operation): Response
    {
        return $this->send(new GetPermissionsRequest(new PermissionDto($model, $operation)));
    }

    // Projects

    public function getProjects(array $fields = [], array $domain = [], int $limit = 100): Response
    {
        return $this->send(new GetProjectsRequest($fields, $domain, $limit));
    }

    // Tasks

    public function getAllTasks(array $fields = [], array $domain = [], int $limit = 100): Response
    {
        return $this->send(new GetAllTasksRequest($fields, $domain, $limit));
    }

    public function getTasksByProject(int $projectId, array $fields = []): Response
    {
        return $this->send(new GetTasksByProjectRequest($projectId, $fields));
    }

    // Timesheets

    public function getTimesheetEntries(array $fields = [], array $domain = [], int $limit = 100): Response
    {
        return $this->send(new GetTimesheetEntriesRequest($fields, $domain, $limit));
    }

    public function getTimesheetEntriesLastDays(int $days, array $fields = []): Response
    {
        return $this->send(new GetTimesheetEntriesLastDaysRequest($days, $fields));
    }

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

    // Sync

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
