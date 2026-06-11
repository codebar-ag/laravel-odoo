<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Responses\Session;

use CodebarAg\Odoo\Responses\OdooResponse;

class VersionResponse extends OdooResponse
{
    public function serverVersion(): ?string
    {
        $first = data_get($this->response->json('version_info'), 0);

        return \is_string($first) ? $first : null;
    }

    public function serie(): ?string
    {
        $version = $this->response->json('version');

        return \is_string($version) ? $version : null;
    }
}
