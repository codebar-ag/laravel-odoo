<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Requests\Api\Tasks;

use CodebarAg\Odoo\Dto\Tasks\UpdateTaskDto;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class UpdateTaskRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(private readonly UpdateTaskDto $dto) {}

    public function resolveEndpoint(): string
    {
        return '/json/2/project.task/write';
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
