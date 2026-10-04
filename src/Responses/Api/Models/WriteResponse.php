<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Responses\Api\Models;

use CodebarAg\Odoo\Responses\OdooResponse;

class WriteResponse extends OdooResponse
{
    /** Whether Odoo confirmed the write (`true`). */
    public function ok(): bool
    {
        return $this->successful()
            && json_decode($this->response->body(), true) === true;
    }
}
