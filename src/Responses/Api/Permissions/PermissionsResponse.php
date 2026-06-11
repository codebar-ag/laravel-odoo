<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Responses\Api\Permissions;

use CodebarAg\Odoo\Responses\OdooResponse;

class PermissionsResponse extends OdooResponse
{
    public function allowed(): bool
    {
        if ($this->failed()) {
            return false;
        }

        return \json_decode($this->response->body()) === true;
    }
}
