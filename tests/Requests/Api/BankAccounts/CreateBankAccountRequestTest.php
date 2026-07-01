<?php

use CodebarAg\Odoo\Dto\BankAccounts\CreateBankAccountDto;
use CodebarAg\Odoo\OdooConnector;
use CodebarAg\Odoo\Requests\Api\BankAccounts\CreateBankAccountRequest;
use CodebarAg\Odoo\Responses\Api\BankAccounts\CreateBankAccountResponse;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('sends request to correct endpoint', function () {
    $mockClient = new MockClient([
        CreateBankAccountRequest::class => MockResponse::fixture('Api/BankAccounts/create-bank-account'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $dto = new CreateBankAccountDto(accNumber: 'CH9300762011623852957', partnerId: 7);

    $response = CreateBankAccountResponse::fromResponse(
        $connector->send(new CreateBankAccountRequest($dto))
    );

    $mockClient->assertSent(CreateBankAccountRequest::class);
    expect($response->successful())->toBeTrue();
});

it('sends correct vals_list body', function () {
    $mockClient = new MockClient([
        CreateBankAccountRequest::class => MockResponse::fixture('Api/BankAccounts/create-bank-account'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $dto = new CreateBankAccountDto(
        accNumber: 'CH9300762011623852957',
        partnerId: 7,
        accHolderName: 'Jane Doe',
        bankId: 3,
        allowOutPayment: true,
    );

    $connector->send(new CreateBankAccountRequest($dto));

    $mockClient->assertSent(function (CreateBankAccountRequest $request) {
        $body = $request->body()->all();

        return data_get($body, 'vals_list.acc_number') === 'CH9300762011623852957'
            && data_get($body, 'vals_list.partner_id') === 7
            && data_get($body, 'vals_list.acc_holder_name') === 'Jane Doe'
            && data_get($body, 'vals_list.bank_id') === 3
            && data_get($body, 'vals_list.allow_out_payment') === true;
    });
});

it('returns the created bank account id', function () {
    $mockClient = new MockClient([
        CreateBankAccountRequest::class => MockResponse::fixture('Api/BankAccounts/create-bank-account'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $dto = new CreateBankAccountDto(accNumber: 'CH9300762011623852957', partnerId: 7);

    $response = CreateBankAccountResponse::fromResponse(
        $connector->send(new CreateBankAccountRequest($dto))
    );

    expect($response->id())->toBe(42);
});
