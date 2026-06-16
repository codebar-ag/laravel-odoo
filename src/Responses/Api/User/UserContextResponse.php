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

        $user = data_get($this->response->json(), 0);

        if (! \is_array($user)) {
            return null;
        }

        return UserDto::fromArray($user);
    }
}
