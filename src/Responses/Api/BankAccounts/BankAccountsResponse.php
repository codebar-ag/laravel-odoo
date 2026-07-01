<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Responses\Api\BankAccounts;

use CodebarAg\Odoo\Dto\BankAccounts\BankAccountDto;
use CodebarAg\Odoo\Responses\OdooResponse;

class BankAccountsResponse extends OdooResponse
{
    /** @return array<BankAccountDto> */
    public function bankAccounts(): array
    {
        if ($this->failed()) {
            return [];
        }

        $result = $this->response->json();

        $bankAccounts = [];
        foreach ($result as $item) {
            if (\is_array($item)) {
                $bankAccounts[] = BankAccountDto::fromArray($item);
            }
        }

        return $bankAccounts;
    }
}
