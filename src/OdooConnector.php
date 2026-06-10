<?php

declare(strict_types=1);

namespace CodebarAg\Odoo;

use CodebarAg\Odoo\Dto\Auth\Authenticate2FADto;
use CodebarAg\Odoo\Dto\Auth\AuthenticateDto;
use CodebarAg\Odoo\Dto\Auth\AuthenticatePasskeyDto;
use CodebarAg\Odoo\Requests\Auth\Authenticate2FARequest;
use CodebarAg\Odoo\Requests\Auth\AuthenticatePasskeyRequest;
use CodebarAg\Odoo\Requests\Auth\AuthenticateRequest;
use CodebarAg\Odoo\Requests\Auth\GetPasskeyOptionsRequest;
use CodebarAg\Odoo\Requests\Auth\GetTotpPageRequest;
use CodebarAg\Odoo\Responses\Auth\AuthResponse;
use CodebarAg\Odoo\Responses\Auth\PasskeyOptionsResponse;
use GuzzleHttp\Cookie\CookieJar;
use Saloon\Http\Connector;

class OdooConnector extends Connector
{
    public function __construct(
        private readonly string $baseUrl,
        private readonly ?string $apiKey = null,
        private readonly ?string $db = null,
    ) {
    }

    /** @return array<string, mixed> */
    protected function defaultConfig(): array
    {
        return [
            'cookies' => new CookieJar(),
            'allow_redirects' => ['max' => 5, 'track_redirects' => true],
        ];
    }

    public function resolveBaseUrl(): string
    {
        return $this->baseUrl;
    }

    public function login(AuthenticateDto $dto): AuthResponse
    {
        return AuthResponse::fromResponse(
            $this->send(new AuthenticateRequest($dto->toArray()))
        );
    }

    public function verifyTotp(Authenticate2FADto $dto): AuthResponse
    {
        $pageHtml = $this->send(new GetTotpPageRequest())->body();
        preg_match('/csrf_token:\s*"([^"]+)"/', $pageHtml, $matches);
        $csrfToken = $matches[1] ?? '';

        return AuthResponse::fromResponse(
            $this->send(new Authenticate2FARequest(array_merge($dto->toArray(), ['csrf_token' => $csrfToken])))
        );
    }

    public function getPasskeyOptions(): PasskeyOptionsResponse
    {
        return PasskeyOptionsResponse::fromResponse(
            $this->send(new GetPasskeyOptionsRequest())
        );
    }

    public function loginWithPasskey(AuthenticatePasskeyDto $dto): AuthResponse
    {
        return AuthResponse::fromResponse(
            $this->send(new AuthenticatePasskeyRequest($dto->toArray()))
        );
    }

    public function getDb(): string
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

    // Session

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

    // User

    public function getUser(): Response
    {
        return $this->send(new GetUserRequest());
    }

    // Employees

    /** @param array<string> $fields */
    public function getEmployeeByUserId(int $userId, array $fields = []): Response
    {
        return $this->send(new GetEmployeeByUserIdRequest($userId, $fields));
    }

    // Fields

    /** @param array<string> $attributes */
    public function getFields(string $model, array $attributes = []): Response
    {
        return $this->send(new GetFieldsRequest($model, $attributes));
    }

    public function getAllFields(): Response
    {
        return $this->send(new GetAllFieldsRequest());
    }

    // Permissions

    public function getPermissions(string $model, string $operation): Response
    {
        return $this->send(new GetPermissionsRequest($model, $operation));
    }

    // Projects

    /**
     * @param  array<string>  $fields
     * @param  array<mixed>  $domain
     */
    public function getProjects(array $fields = [], array $domain = [], int $limit = 100): Response
    {
        return $this->send(new GetProjectsRequest($fields, $domain, $limit));
    }

    // Tasks

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

    // Timesheets

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
