[![Latest Version on Packagist](https://img.shields.io/packagist/v/codebar-ag/laravel-odoo.svg?style=flat-square)](https://packagist.org/packages/codebar-ag/laravel-odoo)
[![Total Downloads](https://img.shields.io/packagist/dt/codebar-ag/laravel-odoo.svg?style=flat-square)](https://packagist.org/packages/codebar-ag/laravel-odoo)
[![GitHub Tests](https://github.com/codebar-ag/laravel-odoo/actions/workflows/run-tests-pest.yml/badge.svg?branch=main)](https://github.com/codebar-ag/laravel-odoo/actions/workflows/run-tests-pest.yml)
[![GitHub Code Style](https://github.com/codebar-ag/laravel-odoo/actions/workflows/fix-php-code-style-issues-pint.yml/badge.svg?branch=main)](https://github.com/codebar-ag/laravel-odoo/actions/workflows/fix-php-code-style-issues-pint.yml)

A Laravel package to authenticate with the Odoo 19 API. Supports standard login, two-factor authentication (TOTP), and passkey login. Built on [Saloon](https://docs.saloon.dev/).

## Requirements

- PHP ^8.4
- Laravel ^13.0

## Installation

```bash
composer require codebar-ag/laravel-odoo
```

## Usage

Instantiate the connector with your Odoo base URL and database name. All connection details are passed at runtime — nothing is read from env or config — so you can load them from your database, session, or anywhere else.

```php
use CodebarAg\Odoo\OdooConnector;

$connector = new OdooConnector(
    baseUrl: 'https://mycompany.odoo.com',
    db: 'mycompany_prod',
);
```

The connector uses a persistent cookie jar internally, so the session cookie is automatically forwarded between all requests (login → TOTP → subsequent calls). You do not need to manage cookies manually.

---

## Authentication flows

### Standard login (no 2FA)

```php
use CodebarAg\Odoo\Dto\Auth\AuthenticateDto;

$response = $connector->login(new AuthenticateDto(
    db: 'mycompany_prod',
    login: 'admin@mycompany.com',
    password: 'secret',
));

$dto = $response->dto();
// $dto->uid      — authenticated user ID
// $dto->login    — login email
// $dto->db       — database name
```

### Two-factor authentication (TOTP) — Odoo 19

Odoo 19 uses a two-step flow for accounts with 2FA enabled:

**Step 1 — Login with credentials**

Call `login()` as normal. When 2FA is enabled on the account, Odoo returns `uid: null` (not an error — the session is pending TOTP verification).

```php
use CodebarAg\Odoo\Dto\Auth\AuthenticateDto;

$loginResponse = $connector->login(new AuthenticateDto(
    db: 'mycompany_prod',
    login: 'admin@mycompany.com',
    password: 'secret',
));

// $loginResponse->dto()->uid === null  →  2FA verification required
```

**Step 2 — Verify TOTP code**

Pass the 6-digit code from the authenticator app. Internally the connector fetches the CSRF token required by Odoo's form-based TOTP endpoint (`/web/login/totp`) before submitting.

```php
use CodebarAg\Odoo\Dto\Auth\Authenticate2FADto;

$totpResponse = $connector->verifyTotp(new Authenticate2FADto(
    totpToken: '123456',
));

$dto = $totpResponse->dto();
// $dto->uid      — authenticated user ID
// $dto->login    — login email
// $dto->db       — database name
```

> **Note:** Odoo 19 handles TOTP via an HTTP form POST (not JSON-RPC). The endpoint is `/web/login/totp` and requires a CSRF token tied to the current session. The connector handles this automatically — the GET to fetch the CSRF token and the subsequent POST both use the same cookie jar, so the tokens always match.

### Passkeys

Get a WebAuthn challenge, then submit the signed assertion:

```php
use CodebarAg\Odoo\Dto\Auth\AuthenticatePasskeyDto;

// Step 1 — get challenge options from Odoo
$options = $connector->getPasskeyOptions()->dto();
// $options->challenge, $options->rpId, $options->allowCredentials, ...

// Step 2 — submit signed assertion (values come from the browser WebAuthn API)
$response = $connector->loginWithPasskey(new AuthenticatePasskeyDto(
    id: 'credentialId',
    rawId: 'rawCredentialId',
    type: 'public-key',
    response: [
        'clientDataJSON'    => '...',
        'authenticatorData' => '...',
        'signature'         => '...',
        'userHandle'        => '...',
    ],
));

$dto = $response->dto();
// $dto->uid, $dto->login, ...
```

---

## Response API

Every response object exposes:

```php
$response->successful();  // bool
$response->failed();      // bool
$response->status();      // int  — HTTP status code
$response->body();        // string — raw response body
$response->error();       // ?string — error message on failure
$response->errorCode();   // ?string — error code on failure
$response->dto();         // typed DTO, or null on failure
```

---

## Testing

```bash
composer test
```

For live API tests against a real Odoo instance, create `.env.testing` with:

```ini
ODOO_URL=https://mycompany.odoo.com
ODOO_DB=mycompany_prod
ODOO_LOGIN=admin@mycompany.com
ODOO_PASSWORD=secret
```

Then run: `./vendor/bin/pest --group=live`

---

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [codebar AG](https://github.com/codebar-ag)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
