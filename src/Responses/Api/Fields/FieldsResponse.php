<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Responses\Api\Fields;

use CodebarAg\Odoo\Dto\Fields\FieldDto;
use CodebarAg\Odoo\Responses\OdooResponse;

class FieldsResponse extends OdooResponse
{
    /** @return array<string, FieldDto> */
    public function fields(): array
    {
        if ($this->failed()) {
            return [];
        }

        $result = $this->response->json();

        $fields = [];
        foreach ($result as $name => $data) {
            if (\is_string($name) && \is_array($data)) {
                $fields[$name] = FieldDto::fromArray($name, $data);
            }
        }

        return $fields;
    }
}
