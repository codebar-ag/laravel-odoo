<?php

declare(strict_types=1);

namespace CodebarAg\Odoo;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class OdooServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-odoo')
            ->hasConfigFile();
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(OdooConnector::class, function (): OdooConnector {
            $url = config('laravel-odoo.url', '');
            $apiKey = config('laravel-odoo.api_key');
            $db = config('laravel-odoo.db');

            return new OdooConnector(
                baseUrl: \is_string($url) ? $url : '',
                apiKey: \is_string($apiKey) && $apiKey !== '' ? $apiKey : null,
                db: \is_string($db) && $db !== '' ? $db : null,
            );
        });
    }
}
