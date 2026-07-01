<?php

use CodebarAg\Odoo\OdooConnector;
use CodebarAg\Odoo\Requests\Api\BankAccounts\GetBankAccountsRequest;
use CodebarAg\Odoo\Responses\Api\BankAccounts\BankAccountsResponse;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('sends request to correct endpoint', function () {
    $mockClient = new MockClient([
        GetBankAccountsRequest::class => MockResponse::fixture('Api/BankAccounts/bank-accounts'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $response = BankAccountsResponse::fromResponse(
        $connector->send(new GetBankAccountsRequest)
    );

    $mockClient->assertSent(GetBankAccountsRequest::class);
    expect($response->successful())->toBeTrue();
});

it('sends correct body with domain, fields and limit', function () {
    $mockClient = new MockClient([
        GetBankAccountsRequest::class => MockResponse::fixture('Api/BankAccounts/bank-accounts'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $connector->send(new GetBankAccountsRequest);

    $mockClient->assertSent(function (GetBankAccountsRequest $request) {
        $body = $request->body()->all();

        return data_get($body, 'domain') === []
            && data_get($body, 'limit') === 100
            && in_array('acc_number', data_get($body, 'fields', []));
    });
});

it('parses bank accounts from response', function () {
    $mockClient = new MockClient([
        GetBankAccountsRequest::class => MockResponse::fixture('Api/BankAccounts/bank-accounts'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $response = BankAccountsResponse::fromResponse(
        $connector->send(new GetBankAccountsRequest)
    );

    $bankAccounts = $response->bankAccounts();

    expect($bankAccounts)->toHaveCount(1);
    expect(data_get($bankAccounts, '0.id'))->toBe(5);
    expect(data_get($bankAccounts, '0.accNumber'))->toBe('CH9300762011623852957');
    expect(data_get($bankAccounts, '0.accType'))->toBe('iban');
    expect(data_get($bankAccounts, '0.partnerId'))->toBe(7);
    expect(data_get($bankAccounts, '0.partnerName'))->toBe('Jane Doe');
    expect(data_get($bankAccounts, '0.bankId'))->toBe(3);
    expect(data_get($bankAccounts, '0.currencyName'))->toBe('CHF');
    expect(data_get($bankAccounts, '0.companyName'))->toBe('MyCompany');
    expect(data_get($bankAccounts, '0.allowOutPayment'))->toBeTrue();
    expect(data_get($bankAccounts, '0.sequence'))->toBe(10);
});
