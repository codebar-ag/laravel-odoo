<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Dto\BankAccounts;

use CodebarAg\Odoo\Data\Casts\IntCast;
use CodebarAg\Odoo\Data\Casts\OdooRelationCast;
use CodebarAg\Odoo\Data\Enums\RelationPart;
use CodebarAg\Odoo\Data\OdooData;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithCast;

class BankAccountDto extends OdooData
{
    /**
     * Odoo 19.3+ renamed several bank-account fields. Reads are normalised back to
     * the classic (`acc_*`) property names so a single DTO serves both schemas;
     * `bank_bic` / `currency_symbol` (present on 19.3, and 19.0) map directly.
     *
     * @var array<string, string>
     */
    private const MODERN_ALIASES = [
        'account_number' => 'acc_number',
        'account_type' => 'acc_type',
        'sanitized_account_number' => 'sanitized_acc_number',
        'holder_name' => 'acc_holder_name',
    ];

    public function __construct(
        #[WithCast(IntCast::class)]
        public int $id = 0,
        public string $accNumber = '',
        public ?string $accType = null,
        public ?string $sanitizedAccNumber = null,
        public ?string $accHolderName = null,
        #[WithCast(OdooRelationCast::class, RelationPart::Id)]
        public ?int $partnerId = null,
        #[MapInputName('partner_id'), WithCast(OdooRelationCast::class, RelationPart::Name)]
        public ?string $partnerName = null,
        #[WithCast(OdooRelationCast::class, RelationPart::Id)]
        public ?int $bankId = null,
        #[MapInputName('bank_id'), WithCast(OdooRelationCast::class, RelationPart::Name)]
        public ?string $bankRelationName = null,
        public ?string $bankName = null,
        public ?string $bankBic = null,
        #[WithCast(OdooRelationCast::class, RelationPart::Id)]
        public ?int $currencyId = null,
        #[MapInputName('currency_id'), WithCast(OdooRelationCast::class, RelationPart::Name)]
        public ?string $currencyName = null,
        public ?string $currencySymbol = null,
        #[WithCast(OdooRelationCast::class, RelationPart::Id)]
        public ?int $companyId = null,
        #[MapInputName('company_id'), WithCast(OdooRelationCast::class, RelationPart::Name)]
        public ?string $companyName = null,
        public bool $allowOutPayment = false,
        public ?int $sequence = null,
    ) {}

    /** @param array<array-key, mixed> $data */
    public static function fromArray(array $data): self
    {
        foreach (self::MODERN_ALIASES as $modern => $canonical) {
            if (data_get($data, $modern) !== null && data_get($data, $canonical) === null) {
                data_set($data, $canonical, data_get($data, $modern));

                /** @var array<array-key, mixed> $data */
                Arr::forget($data, $modern);
            }
        }

        return self::hydrate($data);
    }
}
