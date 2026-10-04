<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Responses\Api\Models;

use CodebarAg\Odoo\Responses\OdooResponse;

class CallMethodResponse extends OdooResponse
{
    /**
     * The decoded JSON result of the call.
     *
     * Odoo answers with any JSON value (`true`, an id, a string, a list, an object),
     * so the body is decoded directly instead of through Saloon's array-only `json()`.
     * Returns null when the call failed or the body is not valid JSON.
     */
    public function result(): mixed
    {
        if ($this->failed()) {
            return null;
        }

        try {
            return json_decode($this->response->body(), true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException) {
            return null;
        }
    }
}
