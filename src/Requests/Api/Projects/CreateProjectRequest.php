<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Requests\Api\Projects;

use CodebarAg\Odoo\Dto\Projects\CreateProjectDto;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class CreateProjectRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(private readonly CreateProjectDto $dto) {}

    public function resolveEndpoint(): string
    {
        return '/json/2/project.project/create';
    }

    /** @return array<string, mixed> */
    protected function defaultBody(): array
    {
        return ['vals_list' => $this->dto->toArray()];
    }
}
