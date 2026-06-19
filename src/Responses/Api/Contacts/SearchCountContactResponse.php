<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Responses\Api\Contacts;

use CodebarAg\Odoo\Responses\OdooResponse;

class SearchCountContactResponse extends OdooResponse
{
    public function count(): ?int
    {
        if ($this->failed()) {
            return null;
        }

        $body = trim($this->response->body());

        return is_numeric($body) ? (int) $body : null;
    }
}
