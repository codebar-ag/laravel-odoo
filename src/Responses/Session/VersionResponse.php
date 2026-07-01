<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Responses\Session;

use CodebarAg\Odoo\Responses\OdooResponse;

class VersionResponse extends OdooResponse
{
    public function serverVersion(): ?string
    {
        $first = data_get($this->response->json('version_info'), 0);

        // On-premise Odoo returns the major version as an int (e.g. `19`), while
        // SaaS returns a string (e.g. `"saas~19"`); normalise both to a string.
        return \is_scalar($first) ? (string) $first : null;
    }

    public function serie(): ?string
    {
        $version = $this->response->json('version');

        return \is_string($version) ? $version : null;
    }

    /**
     * The [major, minor] server version parsed from `version_info`.
     *
     * Handles both the numeric on-premise shape (`[19, 0, ...]`) and the SaaS
     * shape whose first element is a string (`["saas~19", 3, ...]`).
     *
     * @return array{0: int, 1: int}|null
     */
    public function majorMinor(): ?array
    {
        $info = $this->response->json('version_info');

        $major = data_get($info, 0);
        $minor = data_get($info, 1);

        if ($major === null || $minor === null) {
            return null;
        }

        $major = \is_int($major) ? $major : (int) preg_replace('/\D/', '', (string) $major);

        return [$major, (int) $minor];
    }
}
