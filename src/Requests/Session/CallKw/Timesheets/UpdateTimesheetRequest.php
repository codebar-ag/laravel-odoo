<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Requests\CallKw\Timesheets;

use CodebarAg\Odoo\Dto\CallKw\Timesheets\UpdateTimesheetDto;
use CodebarAg\Odoo\Requests\CallKw\CallKwRequest;

class UpdateTimesheetRequest extends CallKwRequest
{
    public function __construct(private readonly UpdateTimesheetDto $dto)
    {
    }

    protected function getModel(): string
    {
        return 'account.analytic.line';
    }

    protected function getOdooMethod(): string
    {
        return 'write';
    }

    protected function getArgs(): array
    {
        return [[$this->dto->id], $this->dto->values];
    }

    protected function getKwargs(): array
    {
        return [];
    }
}
