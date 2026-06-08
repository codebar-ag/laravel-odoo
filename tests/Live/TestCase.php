<?php

namespace CodebarAg\Odoo\Tests\Live;

use CodebarAg\Odoo\OdooConnector;
use CodebarAg\Odoo\OdooServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Saloon\Config;
use Saloon\Http\Faking\MockClient;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        MockClient::destroyGlobal();
        Config::allowStrayRequests();

        if ($this->resolveEnvValue('ODOO_URL') === '') {
            $this->markTestSkipped('Live tests require ODOO_URL to be set in .env.testing');
        }
    }

    protected function getPackageProviders($app): array
    {
        return [
            OdooServiceProvider::class,
        ];
    }

    public function connector(): OdooConnector
    {
        return new OdooConnector(
            baseUrl: $this->resolveEnvValue('ODOO_URL'),
            db: $this->resolveEnvValue('ODOO_DB'),
        );
    }

    public function login(): string
    {
        return $this->resolveEnvValue('ODOO_LOGIN');
    }

    public function password(): string
    {
        return $this->resolveEnvValue('ODOO_PASSWORD');
    }

    private function resolveEnvValue(string $key): string
    {
        $value = env($key);
        if (is_string($value) && $value !== '') {
            return $value;
        }

        $envFile = realpath(__DIR__.'/../../.env.testing');
        if ($envFile && is_readable($envFile)) {
            foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
                if (str_starts_with(trim($line), '#') || ! str_contains($line, '=')) {
                    continue;
                }
                [$envKey, $envValue] = explode('=', $line, 2);
                if (trim($envKey) === $key) {
                    return trim($envValue, " \t\n\r\0\x0B\"'");
                }
            }
        }

        return '';
    }
}
