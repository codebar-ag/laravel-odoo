<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Responses\Api\Projects;

use CodebarAg\Odoo\Responses\OdooResponse;

class MutateProjectResponse extends OdooResponse
{
    public function ok(): bool
    {
        return $this->response->successful();
    }
}
