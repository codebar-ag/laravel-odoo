<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Dto\BankAccounts;

use Spatie\LaravelData\Data;

class UpdateBankAccountDto extends Data
{
    /**
     * @param  array<string, mixed>  $extraValues  Additional Odoo field values (e.g. custom studio fields)
     */
    public function __construct(
        public readonly int $id,
        public readonly ?string $accNumber = null,
        public readonly ?int $partnerId = null,
        public readonly ?string $accHolderName = null,
        public readonly ?string $bankName = null,
        public readonly ?string $bankBic = null,
        public readonly ?int $bankId = null,
        public readonly ?int $currencyId = null,
        public readonly ?bool $allowOutPayment = null,
        public readonly ?int $sequence = null,
        public readonly array $extraValues = [],
    ) {}

    /**
     * Serialise to the Odoo `write` value map. Only explicitly provided fields are written
     * and `extraValues` (studio fields) are merged at the top level.
     *
     * The `$modern` flag selects the Odoo 19.3+ field names; on 19.3 the `bank_id`
     * and `currency_id` relations no longer exist and are therefore skipped.
     *
     * @return array<string, mixed>
     */
    public function toArray(bool $modern = false): array
    {
        $data = [];

        if ($this->accNumber !== null) {
            data_set($data, $modern ? 'account_number' : 'acc_number', $this->accNumber);
        }

        if ($this->partnerId !== null) {
            data_set($data, 'partner_id', $this->partnerId);
        }

        if ($this->accHolderName !== null) {
            data_set($data, $modern ? 'holder_name' : 'acc_holder_name', $this->accHolderName);
        }

        if ($this->bankName !== null) {
            data_set($data, 'bank_name', $this->bankName);
        }

        if ($this->bankBic !== null) {
            data_set($data, 'bank_bic', $this->bankBic);
        }

        if (! $modern && $this->bankId !== null) {
            data_set($data, 'bank_id', $this->bankId);
        }

        if (! $modern && $this->currencyId !== null) {
            data_set($data, 'currency_id', $this->currencyId);
        }

        if ($this->allowOutPayment !== null) {
            data_set($data, 'allow_out_payment', $this->allowOutPayment);
        }

        if ($this->sequence !== null) {
            data_set($data, 'sequence', $this->sequence);
        }

        /** @var array<string, mixed> $data */
        return [...$data, ...$this->extraValues];
    }
}
