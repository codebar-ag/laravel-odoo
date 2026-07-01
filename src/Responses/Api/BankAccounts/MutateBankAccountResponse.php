<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Responses\Api\BankAccounts;

use CodebarAg\Odoo\Responses\OdooResponse;

class MutateBankAccountResponse extends OdooResponse
{
    public function ok(): bool
    {
        return $this->response->successful();
    }
}
