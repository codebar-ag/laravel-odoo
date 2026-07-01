<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Responses\Api\BankAccounts;

use CodebarAg\Odoo\Responses\OdooResponse;
use Illuminate\Support\Arr;

class CreateBankAccountResponse extends OdooResponse
{
    public function id(): ?int
    {
        if ($this->failed()) {
            return null;
        }

        $first = Arr::first($this->response->json());

        if (\is_int($first)) {
            return $first;
        }

        $id = $this->response->json('id');

        return \is_int($id) ? $id : null;
    }
}
