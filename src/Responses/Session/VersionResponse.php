<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Responses\Session;

use CodebarAg\Odoo\Responses\OdooResponse;
use Saloon\Http\Response;

class VersionResponse extends OdooResponse
{
    private function __construct(Response $response)
    {
        parent::__construct($response);
    }

    public static function fromResponse(Response $response): self
    {
        return new self($response);
    }

    public function serverVersion(): ?string
    {
        $v = $this->response->json('server_version')
            ?? $this->response->json('result.server_version');

        if (\is_string($v)) {
            return $v;
        }

        $info = $this->response->json('version_info');
        if (\is_array($info) && isset($info[0]) && \is_string($info[0])) {
            return $info[0];
        }

        return null;
    }

    public function serie(): ?string
    {
        $v = $this->response->json('server_serie')
            ?? $this->response->json('result.server_serie');

        if (\is_string($v)) {
            return $v;
        }

        $info = $this->response->json('version_info');
        if (\is_array($info) && isset($info[0]) && \is_string($info[0])) {
            return $info[0];
        }

        return null;
    }
}
