<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Requests\Api\Projects;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class DeleteProjectRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(private readonly int $id) {}

    public function resolveEndpoint(): string
    {
        return '/json/2/project.project/unlink';
    }

    /** @return array<string, mixed> */
    protected function defaultBody(): array
    {
        return ['ids' => [$this->id]];
    }
}
