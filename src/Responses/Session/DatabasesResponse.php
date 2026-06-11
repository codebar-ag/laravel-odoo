<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Responses\Session;

use CodebarAg\Odoo\Responses\OdooResponse;

class DatabasesResponse extends OdooResponse
{
    /** @return array<string> */
    public function databases(): array
    {
        if ($this->failed()) {
            return [];
        }

        $result = $this->response->json('result');

        return collect(\is_array($result) ? $result : [])
            ->filter(fn ($v) => \is_string($v))
            ->values()
            ->all();
    }
}
