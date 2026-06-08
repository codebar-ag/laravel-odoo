<?php

declare(strict_types=1);

namespace CodebarAg\Odoo;

use CodebarAg\Odoo\Dto\Auth\Authenticate2FADto;
use CodebarAg\Odoo\Dto\Auth\AuthenticateDto;
use CodebarAg\Odoo\Requests\Session\Auth\BasicAuth\AuthenticateRequest;
use CodebarAg\Odoo\Requests\Session\Auth\TwoFactor\Authenticate2FARequest;
use CodebarAg\Odoo\Requests\Session\Auth\TwoFactor\GetTotpPageRequest;
use CodebarAg\Odoo\Responses\Auth\AuthResponse;
use GuzzleHttp\Cookie\CookieJar;
use Saloon\Http\Connector;

class OdooConnector extends Connector
{
    /** @return array<string, mixed> */
    protected function defaultConfig(): array
    {
        return [
            'cookies' => new CookieJar,
            'allow_redirects' => [
                'max' => 5,
                'track_redirects' => true,
            ],
        ];
    }

    // Session

    // database

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
}
