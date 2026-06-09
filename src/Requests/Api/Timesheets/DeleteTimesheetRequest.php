<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Requests\Api\Timesheets;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class DeleteTimesheetRequest extends Request
{
    protected Method $method = Method::POST;

    public function __construct(private readonly int $id)
    {
    }

    public function resolveEndpoint(): string
    {
        return "/json/2/account.analytic.line/{$this->id}/unlink";
    }
}
