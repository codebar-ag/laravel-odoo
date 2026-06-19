<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Responses\Api\Contacts;

use CodebarAg\Odoo\Responses\OdooResponse;

class SearchContactResponse extends OdooResponse
{
    /** @return array<int> */
    public function ids(): array
    {
        if ($this->failed()) {
            return [];
        }

        /** @var array<int> */
        return array_values(array_filter($this->response->json(), 'is_int'));
    }
}
