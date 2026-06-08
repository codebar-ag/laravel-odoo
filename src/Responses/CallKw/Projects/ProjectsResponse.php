<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Responses\CallKw\Projects;

use CodebarAg\Odoo\Dto\CallKw\Projects\ProjectDto;
use CodebarAg\Odoo\Responses\OdooResponse;
use Saloon\Http\Response;

class ProjectsResponse extends OdooResponse
{
    private function __construct(Response $response)
    {
        parent::__construct($response);
    }

    public static function fromResponse(Response $response): self
    {
        return new self($response);
    }

    /** @return array<ProjectDto> */
    public function projects(): array
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
            fn (array $item) => ProjectDto::fromArray($item),
            $result,
        );
    }
}
