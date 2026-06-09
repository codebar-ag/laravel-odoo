<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Facades;

use CodebarAg\Odoo\OdooConnector;
use Illuminate\Support\Facades\Facade;

/**
 * @see OdooConnector
 *
 * @method static \CodebarAg\Odoo\Responses\Auth\AuthResponse login(\CodebarAg\Odoo\Dto\Auth\AuthenticateDto $dto)
 * @method static \CodebarAg\Odoo\Responses\Auth\AuthResponse verifyTotp(\CodebarAg\Odoo\Dto\Auth\Authenticate2FADto $dto)
 */
class Odoo extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return OdooConnector::class;
    }
}
