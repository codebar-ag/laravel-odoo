<?php

declare(strict_types=1);

namespace CodebarAg\Odoo;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class OdooServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('laravel-odoo');
    }
}
