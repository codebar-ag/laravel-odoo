<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Responses\Api\Tasks;

use CodebarAg\Odoo\Dto\Tasks\TaskDto;
use CodebarAg\Odoo\Responses\OdooResponse;

class TasksResponse extends OdooResponse
{
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
