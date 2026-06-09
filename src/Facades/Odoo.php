<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Facades;

use CodebarAg\Odoo\OdooConnector;
use Illuminate\Support\Facades\Facade;
class Odoo extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return OdooConnector::class;
    }
}