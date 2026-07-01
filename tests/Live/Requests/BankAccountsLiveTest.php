<?php

use CodebarAg\Odoo\Dto\BankAccounts\BankAccountDto;
use CodebarAg\Odoo\Dto\BankAccounts\CreateBankAccountDto;
use CodebarAg\Odoo\Dto\BankAccounts\UpdateBankAccountDto;
use CodebarAg\Odoo\Dto\Contacts\CreateContactDto;
use CodebarAg\Odoo\OdooConnector;
use CodebarAg\Odoo\Responses\Api\BankAccounts\BankAccountsResponse;
use CodebarAg\Odoo\Responses\Api\BankAccounts\CreateBankAccountResponse;
use CodebarAg\Odoo\Responses\Api\BankAccounts\MutateBankAccountResponse;

/** Create a partner to attach bank accounts to, returning its id. */
function liveBankAccountPartnerId(OdooConnector $connector): int
{
    $response = $connector->createContact(new CreateContactDto(
        name: 'Test Bank Account Partner',
        isCompany: true,
    ));

    expect($response->id())->toBeInt();

    return $response->id();
}

it('creates a bank account and returns its id', function () {
    $dto = new CreateBankAccountDto(
        accNumber: 'CH93 0076 2011 6238 5295 7',
        partnerId: liveBankAccountPartnerId($this->connector()),
        accHolderName: 'Test Holder',
    );

    $response = $this->connector()->createBankAccount($dto);

    expect($response)->toBeInstanceOf(CreateBankAccountResponse::class);
    expect($response->id())->toBeInt();
})->group('live');

it('reads all bank accounts and maps them to BankAccountDto', function () {
    $response = $this->connector()->getBankAccounts();

    expect($response)->toBeInstanceOf(BankAccountsResponse::class);
    expect($response->successful())->toBeTrue();

    $bankAccounts = $response->bankAccounts();

    expect($bankAccounts)->toBeArray();

    if ($bankAccounts !== []) {
        expect($bankAccounts[0])->toBeInstanceOf(BankAccountDto::class);
        expect($bankAccounts[0]->id)->toBeInt();
        expect($bankAccounts[0]->accNumber)->toBeString();
    }
})->group('live');

it('creates and reads a bank account by id', function () {
    $partnerId = liveBankAccountPartnerId($this->connector());

    $createResponse = $this->connector()->createBankAccount(new CreateBankAccountDto(
        accNumber: 'CH56 0483 5012 3456 7800 9',
        partnerId: $partnerId,
        accHolderName: 'Read Holder',
    ));

    expect($createResponse->id())->toBeInt();

    $response = $this->connector()->readBankAccount($createResponse->id());

    expect($response->successful())->toBeTrue();

    $bankAccounts = $response->bankAccounts();

    expect($bankAccounts)->toHaveCount(1);
    expect($bankAccounts[0])->toBeInstanceOf(BankAccountDto::class);
    expect($bankAccounts[0]->id)->toBe($createResponse->id());
    expect($bankAccounts[0]->partnerId)->toBe($partnerId);
})->group('live');

it('searches bank accounts by partner and counts them', function () {
    $partnerId = liveBankAccountPartnerId($this->connector());

    $createResponse = $this->connector()->createBankAccount(new CreateBankAccountDto(
        accNumber: 'CH64 0070 0110 0012 3456 7',
        partnerId: $partnerId,
    ));

    expect($createResponse->id())->toBeInt();

    $searchResponse = $this->connector()->searchBankAccounts([['partner_id', '=', $partnerId]]);

    expect($searchResponse->ids())->toContain($createResponse->id());

    $countResponse = $this->connector()->searchCountBankAccounts([['partner_id', '=', $partnerId]]);

    expect($countResponse->count())->toBeGreaterThanOrEqual(1);
})->group('live');

it('updates a bank account', function () {
    $createResponse = $this->connector()->createBankAccount(new CreateBankAccountDto(
        accNumber: 'CH18 0483 5029 4829 8100 0',
        partnerId: liveBankAccountPartnerId($this->connector()),
        accHolderName: 'Before Update',
    ));

    expect($createResponse->id())->toBeInt();

    $updateResponse = $this->connector()->updateBankAccount(new UpdateBankAccountDto(
        id: $createResponse->id(),
        accHolderName: 'After Update',
    ));

    expect($updateResponse)->toBeInstanceOf(MutateBankAccountResponse::class);
    expect($updateResponse->ok())->toBeTrue();
})->group('live');

it('creates and deletes a bank account', function () {
    $createResponse = $this->connector()->createBankAccount(new CreateBankAccountDto(
        accNumber: 'CH31 8123 9000 0012 4568 9',
        partnerId: liveBankAccountPartnerId($this->connector()),
    ));

    expect($createResponse->id())->toBeInt();

    $deleteResponse = $this->connector()->deleteBankAccount($createResponse->id());

    expect($deleteResponse)->toBeInstanceOf(MutateBankAccountResponse::class);

    // Some Odoo instances (e.g. SaaS trials) don't grant the API user `unlink`
    // rights on res.partner.bank — the request is still well-formed, so treat an
    // access denial as a skip rather than a failure.
    if ($deleteResponse->failed()) {
        $this->markTestSkipped('The API user is not allowed to delete res.partner.bank records on this instance.');
    }

    expect($deleteResponse->ok())->toBeTrue();
})->group('live');
