<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Responses\Api\Models;

use CodebarAg\Odoo\Responses\OdooResponse;
use Illuminate\Support\Arr;

class CreateResponse extends OdooResponse
{
    public function id(): ?int
    {
        if ($this->failed()) {
            return null;
        }

        $result = json_decode($this->response->body(), true);

        $id = \is_array($result) ? Arr::first($result) : $result;

        return \is_int($id) ? $id : null;
    }
}
