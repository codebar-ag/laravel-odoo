<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Requests\CallKw\Timesheets;

use CodebarAg\Odoo\Dto\CallKw\Timesheets\CreateTimesheetDto;
use CodebarAg\Odoo\Requests\CallKw\CallKwRequest;

class CreateTimesheetRequest extends CallKwRequest
{
    public function __construct(private readonly CreateTimesheetDto $dto) {}

    protected function getModel(): string
    {
        return 'account.analytic.line';
    }

    protected function getOdooMethod(): string
    {
        return 'create';
    }

    protected function getArgs(): array
    {
        return [$this->dto->toArray()];
    }

    protected function getKwargs(): array
    {
        return [];
    }
}
