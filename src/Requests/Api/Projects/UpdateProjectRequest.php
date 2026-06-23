<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Requests\Api\Projects;

use CodebarAg\Odoo\Dto\Projects\UpdateProjectDto;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class UpdateProjectRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(private readonly UpdateProjectDto $dto) {}

    public function resolveEndpoint(): string
    {
        return '/json/2/project.project/write';
    }

    /** @return array<string, mixed> */
    protected function defaultBody(): array
    {
        return [
            'ids' => [$this->dto->id],
            'vals' => $this->dto->toArray(),
        ];
    }
}
