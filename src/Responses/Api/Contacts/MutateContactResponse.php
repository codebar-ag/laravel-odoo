<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Responses\Api\Contacts;

use CodebarAg\Odoo\Responses\OdooResponse;

class MutateContactResponse extends OdooResponse
{
    public function ok(): bool
    {
        return $this->response->successful();
    }
}
