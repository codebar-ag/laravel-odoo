<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Responses\CallKw\Permissions;

use CodebarAg\Odoo\Responses\OdooResponse;
use Saloon\Http\Response;

class PermissionsResponse extends OdooResponse
{
    private function __construct(Response $response)
    {
        parent::__construct($response);
    }

    public static function fromResponse(Response $response): self
    {
        return new self($response);
    }

    public function allowed(): bool
    {
        if ($this->failed()) {
            return false;
        }

        try {
            return $this->response->json('result') === true;
        } catch (\JsonException) {
            return false;
        }
    }
}
