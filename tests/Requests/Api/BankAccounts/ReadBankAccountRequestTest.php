<?php

use CodebarAg\Odoo\OdooConnector;
use CodebarAg\Odoo\Requests\Api\BankAccounts\ReadBankAccountRequest;
use CodebarAg\Odoo\Responses\Api\BankAccounts\BankAccountsResponse;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('sends request to correct endpoint', function () {
    $mockClient = new MockClient([
        ReadBankAccountRequest::class => MockResponse::fixture('Api/BankAccounts/bank-account'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $response = BankAccountsResponse::fromResponse(
        $connector->send(new ReadBankAccountRequest(5))
    );

    $mockClient->assertSent(ReadBankAccountRequest::class);
    expect($response->successful())->toBeTrue();
});

it('sends correct ids and fields body', function () {
    $mockClient = new MockClient([
        ReadBankAccountRequest::class => MockResponse::fixture('Api/BankAccounts/bank-account'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $connector->send(new ReadBankAccountRequest(5));

    $mockClient->assertSent(function (ReadBankAccountRequest $request) {
        $body = $request->body()->all();

        return data_get($body, 'ids') === [5]
            && in_array('acc_number', data_get($body, 'fields', []));
    });
});

it('parses the bank account from response', function () {
    $mockClient = new MockClient([
        ReadBankAccountRequest::class => MockResponse::fixture('Api/BankAccounts/bank-account'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $response = BankAccountsResponse::fromResponse(
        $connector->send(new ReadBankAccountRequest(5))
    );

    $bankAccounts = $response->bankAccounts();

    expect($bankAccounts)->toHaveCount(1);
    expect(data_get($bankAccounts, '0.id'))->toBe(5);
    expect(data_get($bankAccounts, '0.accNumber'))->toBe('CH9300762011623852957');
});
