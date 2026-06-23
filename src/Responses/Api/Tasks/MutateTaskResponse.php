<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Responses\Api\Tasks;

use CodebarAg\Odoo\Responses\OdooResponse;

class MutateTaskResponse extends OdooResponse
{
    public function ok(): bool
    {
        return $this->response->successful();
    }
}
