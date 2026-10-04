<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Responses\Api\Models;

use CodebarAg\Odoo\Responses\OdooResponse;

class SearchReadResponse extends OdooResponse
{
    /** @return array<int, array<string, mixed>> */
    public function records(): array
    {
        if ($this->failed()) {
            return [];
        }

        $result = $this->response->json();

        if (! array_is_list($result)) {
            return [];
        }

        $records = [];
        foreach ($result as $item) {
            if (\is_array($item)) {
                /** @var array<string, mixed> $item */
                $records[] = $item;
            }
        }

        return $records;
    }
}
