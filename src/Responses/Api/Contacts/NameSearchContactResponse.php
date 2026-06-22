<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Responses\Api\Contacts;

use CodebarAg\Odoo\Responses\OdooResponse;

class NameSearchContactResponse extends OdooResponse
{
    /**
     * Returns an array of [id, display_name] tuples as Odoo returns them.
     *
     * @return array<mixed>
     */
    public function results(): array
    {
        if ($this->failed()) {
            return [];
        }

        return $this->response->json();
    }
}
