<?php

use CodebarAg\Odoo\Dto\BankAccounts\BankAccountDto;
use CodebarAg\Odoo\Dto\BankAccounts\CreateBankAccountDto;
use CodebarAg\Odoo\Dto\BankAccounts\UpdateBankAccountDto;

it('maps the classic 19.0 schema (acc_* names and relations)', function () {
    $dto = BankAccountDto::fromArray([
        'id' => 5,
        'acc_number' => 'CH9300762011623852957',
        'acc_type' => 'iban',
        'sanitized_acc_number' => 'CH9300762011623852957',
        'acc_holder_name' => 'Jane Doe',
        'partner_id' => [7, 'Jane Doe'],
        'bank_id' => [3, 'UBS'],
        'bank_name' => 'UBS Switzerland AG',
        'currency_id' => [1, 'CHF'],
        'company_id' => [1, 'MyCompany'],
        'allow_out_payment' => true,
        'sequence' => 10,
    ]);

    expect($dto->id)->toBe(5)
        ->and($dto->accNumber)->toBe('CH9300762011623852957')
        ->and($dto->accType)->toBe('iban')
        ->and($dto->sanitizedAccNumber)->toBe('CH9300762011623852957')
        ->and($dto->accHolderName)->toBe('Jane Doe')
        ->and($dto->partnerId)->toBe(7)
        ->and($dto->partnerName)->toBe('Jane Doe')
        ->and($dto->bankId)->toBe(3)
        ->and($dto->bankName)->toBe('UBS Switzerland AG')
        ->and($dto->currencyId)->toBe(1)
        ->and($dto->currencyName)->toBe('CHF')
        ->and($dto->companyId)->toBe(1)
        ->and($dto->allowOutPayment)->toBeTrue()
        ->and($dto->sequence)->toBe(10);
});

it('normalises the renamed 19.3 schema to the same properties', function () {
    $dto = BankAccountDto::fromArray([
        'id' => 5,
        'account_number' => 'CH9300762011623852957',
        'account_type' => 'iban',
        'sanitized_account_number' => 'CH9300762011623852957',
        'holder_name' => 'Jane Doe',
        'partner_id' => [7, 'Jane Doe'],
        'bank_name' => 'UBS Switzerland AG',
        'bank_bic' => 'UBSWCHZH80A',
        'currency_symbol' => 'CHF',
        'company_id' => [1, 'MyCompany'],
        'allow_out_payment' => true,
        'sequence' => 10,
    ]);

    expect($dto->accNumber)->toBe('CH9300762011623852957')
        ->and($dto->accType)->toBe('iban')
        ->and($dto->sanitizedAccNumber)->toBe('CH9300762011623852957')
        ->and($dto->accHolderName)->toBe('Jane Doe')
        ->and($dto->bankBic)->toBe('UBSWCHZH80A')
        ->and($dto->currencySymbol)->toBe('CHF')
        ->and($dto->bankId)->toBeNull()
        ->and($dto->currencyId)->toBeNull();
});

it('serialises the create DTO with classic field names by default', function () {
    $body = (new CreateBankAccountDto(
        accNumber: 'CH93',
        partnerId: 7,
        accHolderName: 'Jane',
        bankId: 3,
        currencyId: 1,
    ))->toArray();

    expect($body)->toBe([
        'acc_number' => 'CH93',
        'partner_id' => 7,
        'acc_holder_name' => 'Jane',
        'bank_id' => 3,
        'currency_id' => 1,
    ]);
});

it('serialises the create DTO with 19.3 names and drops missing relations', function () {
    $body = (new CreateBankAccountDto(
        accNumber: 'CH93',
        partnerId: 7,
        accHolderName: 'Jane',
        bankBic: 'UBSWCHZH80A',
        bankId: 3,       // unsupported on 19.3 → skipped
        currencyId: 1,   // unsupported on 19.3 → skipped
    ))->toArray(modern: true);

    expect($body)->toBe([
        'account_number' => 'CH93',
        'partner_id' => 7,
        'holder_name' => 'Jane',
        'bank_bic' => 'UBSWCHZH80A',
    ]);
});

it('serialises the update DTO respecting the schema dialect', function () {
    $dto = new UpdateBankAccountDto(id: 5, accHolderName: 'New', accNumber: 'CH99');

    expect($dto->toArray())->toBe([
        'acc_number' => 'CH99',
        'acc_holder_name' => 'New',
    ]);

    expect($dto->toArray(modern: true))->toBe([
        'account_number' => 'CH99',
        'holder_name' => 'New',
    ]);
});
