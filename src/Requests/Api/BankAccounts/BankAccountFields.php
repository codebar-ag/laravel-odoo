<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Requests\Api\BankAccounts;

/**
 * Default `res.partner.bank` field sets, keyed by Odoo schema dialect.
 *
 * Odoo renamed the bank-account fields between 19.0 and 19.3: the classic
 * `acc_*` names plus the `bank_id` / `currency_id` relations were replaced by
 * `account_*` / `holder_name` and the relations were dropped in favour of the
 * plain `bank_name` / `bank_bic` / `currency_symbol` columns. The connector
 * detects the server version and picks the matching dialect via {@see for()}.
 */
final class BankAccountFields
{
    /** Odoo <= 19.0 (classic schema). @var array<string> */
    public const LEGACY = [
        'id', 'acc_number', 'acc_type', 'sanitized_acc_number', 'acc_holder_name',
        'partner_id', 'bank_id', 'bank_name', 'currency_id', 'company_id',
        'allow_out_payment', 'sequence',
    ];

    /** Odoo >= 19.3 (renamed schema). @var array<string> */
    public const MODERN = [
        'id', 'account_number', 'account_type', 'sanitized_account_number', 'holder_name',
        'partner_id', 'bank_name', 'bank_bic', 'currency_symbol', 'company_id',
        'allow_out_payment', 'sequence',
    ];

    /** @return array<string> */
    public static function for(bool $modern): array
    {
        return $modern ? self::MODERN : self::LEGACY;
    }
}
