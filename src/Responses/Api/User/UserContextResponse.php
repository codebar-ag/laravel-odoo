<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Responses\Api\User;

use CodebarAg\Odoo\Dto\Users\UserDto;
use CodebarAg\Odoo\Responses\OdooResponse;

class UserContextResponse extends OdooResponse
{
    public function dto(): ?UserDto
    {
        if ($this->failed()) {
            return null;
        }

        $data = $this->response->json();

        if (! \is_array($data) || empty($data)) {
            return null;
        }

        return UserDto::fromArray([
            'id' => data_get($data, 'uid'),
            'lang' => data_get($data, 'lang'),
            'tz' => data_get($data, 'tz'),
        ]);
    }
}
