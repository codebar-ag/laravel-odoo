<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Responses\Api\Fields;

use CodebarAg\Odoo\Dto\CallKw\Fields\FieldDto;
use CodebarAg\Odoo\Responses\OdooResponse;
use Saloon\Http\Response;

class FieldsResponse extends OdooResponse
{
    private function __construct(Response $response)
    {
        parent::__construct($response);
    }

    public static function fromResponse(Response $response): self
    {
        return new self($response);
    }

    /** @return array<string, FieldDto> */
    public function fields(): array
    {
        if ($this->failed()) {
            return [];
        }

        $result = $this->response->json();

        if (! \is_array($result)) {
            return [];
        }

        $fields = [];
        foreach ($result as $name => $data) {
            if (\is_string($name) && \is_array($data)) {
                $fields[$name] = FieldDto::fromArray($name, $data);
            }
        }

        return $fields;
    }
}
