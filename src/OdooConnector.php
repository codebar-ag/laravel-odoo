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
        private readonly string $db,
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
}
