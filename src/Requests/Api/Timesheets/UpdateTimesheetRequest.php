<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Requests\Api\Timesheets;

use CodebarAg\Odoo\Dto\CallKw\Timesheets\UpdateTimesheetDto;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class UpdateTimesheetRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(private readonly UpdateTimesheetDto $dto)
    {
    }

    public function resolveEndpoint(): string
    {
        return "/json/2/account.analytic.line/{$this->dto->id}/write";
    }

    /** @return array<string, mixed> */
    protected function defaultBody(): array
    {
        return ['vals' => $this->dto->values];
    }
}
