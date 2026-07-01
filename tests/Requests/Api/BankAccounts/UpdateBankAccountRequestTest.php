<?php

use CodebarAg\Odoo\Dto\BankAccounts\UpdateBankAccountDto;
use CodebarAg\Odoo\OdooConnector;
use CodebarAg\Odoo\Requests\Api\BankAccounts\UpdateBankAccountRequest;
use CodebarAg\Odoo\Responses\Api\BankAccounts\MutateBankAccountResponse;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('sends request to correct endpoint', function () {
    $mockClient = new MockClient([
        UpdateBankAccountRequest::class => MockResponse::fixture('Api/BankAccounts/mutate-bank-account'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $dto = new UpdateBankAccountDto(id: 5, accHolderName: 'John Doe');

    $response = MutateBankAccountResponse::fromResponse(
        $connector->send(new UpdateBankAccountRequest($dto))
    );

    $mockClient->assertSent(UpdateBankAccountRequest::class);
    expect($response->successful())->toBeTrue();
});

it('sends correct ids and vals body', function () {
    $mockClient = new MockClient([
        UpdateBankAccountRequest::class => MockResponse::fixture('Api/BankAccounts/mutate-bank-account'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $dto = new UpdateBankAccountDto(id: 5, accNumber: 'CH5604835012345678009', sequence: 20);

    $connector->send(new UpdateBankAccountRequest($dto));

    $mockClient->assertSent(function (UpdateBankAccountRequest $request) {
        $body = $request->body()->all();

        return data_get($body, 'ids') === [5]
            && data_get($body, 'vals.acc_number') === 'CH5604835012345678009'
            && data_get($body, 'vals.sequence') === 20;
    });
});

it('confirms successful update', function () {
    $mockClient = new MockClient([
        UpdateBankAccountRequest::class => MockResponse::fixture('Api/BankAccounts/mutate-bank-account'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $dto = new UpdateBankAccountDto(id: 5, accHolderName: 'John Doe');

    $response = MutateBankAccountResponse::fromResponse(
        $connector->send(new UpdateBankAccountRequest($dto))
    );

    expect($response->ok())->toBeTrue();
});
