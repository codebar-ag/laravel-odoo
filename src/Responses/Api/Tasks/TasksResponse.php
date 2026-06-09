<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Responses\Api\Tasks;

use CodebarAg\Odoo\Dto\Tasks\TaskDto;
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

        $result = $this->response->json();

        $tasks = [];
        foreach ($result as $item) {
            if (\is_array($item)) {
                $tasks[] = TaskDto::fromArray($item);
            }
        }

        return $tasks;
    }
}
