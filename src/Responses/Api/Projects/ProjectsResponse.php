<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Responses\Api\Projects;

use CodebarAg\Odoo\Dto\Projects\ProjectDto;
use CodebarAg\Odoo\Responses\OdooResponse;

class ProjectsResponse extends OdooResponse
{
    /** @return array<ProjectDto> */
    public function projects(): array
    {
        if ($this->failed()) {
            return [];
        }

        $result = $this->response->json();

        $projects = [];
        foreach ($result as $item) {
            if (\is_array($item)) {
                $projects[] = ProjectDto::fromArray($item);
            }
        }

        return $projects;
    }
}
