<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Responses\CallKw\Tasks;

use CodebarAg\Odoo\Dto\CallKw\Tasks\TaskDto;
use CodebarAg\Odoo\Responses\OdooResponse;
use Saloon\Http\Response;

class TasksResponse extends OdooResponse
{
    private function __construct(Response $response)
    {
        parent::__construct($response);
    }

    public static function fromResponse(Response $response): self
    {
        return new self($response);
    }

    /** @return array<TaskDto> */
    public function tasks(): array
    {
        if ($this->failed()) {
            return [];
        }

        try {
            $result = $this->response->json('result');
        } catch (\JsonException) {
            return [];
        }

        if (! is_array($result)) {
            return [];
        }

        return array_map(
            fn (array $item) => TaskDto::fromArray($item),
            $result,
        );
    }
}
