<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Requests\CallKw\Timesheets;

use CodebarAg\Odoo\Requests\CallKw\CallKwRequest;

class DeleteTimesheetRequest extends CallKwRequest
{
    public function __construct(private readonly int $id) {}

    protected function getModel(): string
    {
        return 'account.analytic.line';
    }

    protected function getOdooMethod(): string
    {
        return 'unlink';
    }

    protected function getArgs(): array
    {
        return [[$this->id]];
    }

    protected function getKwargs(): array
    {
        return [];
    }
}
