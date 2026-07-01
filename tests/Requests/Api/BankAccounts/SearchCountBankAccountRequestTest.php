<?php

use CodebarAg\Odoo\OdooConnector;
use CodebarAg\Odoo\Requests\Api\BankAccounts\SearchCountBankAccountRequest;
use CodebarAg\Odoo\Responses\Api\BankAccounts\SearchCountBankAccountResponse;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('sends request to correct endpoint', function () {
    $mockClient = new MockClient([
        SearchCountBankAccountRequest::class => MockResponse::fixture('Api/BankAccounts/search-count-bank-account'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $response = SearchCountBankAccountResponse::fromResponse(
        $connector->send(new SearchCountBankAccountRequest)
    );

    $mockClient->assertSent(SearchCountBankAccountRequest::class);
    expect($response->successful())->toBeTrue();
});

it('sends correct domain body', function () {
    $mockClient = new MockClient([
        SearchCountBankAccountRequest::class => MockResponse::fixture('Api/BankAccounts/search-count-bank-account'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $connector->send(new SearchCountBankAccountRequest([['partner_id', '=', 7]]));

    $mockClient->assertSent(function (SearchCountBankAccountRequest $request) {
        $body = $request->body()->all();

        return data_get($body, 'domain') === [['partner_id', '=', 7]];
    });
});

it('returns the count', function () {
    $mockClient = new MockClient([
        SearchCountBankAccountRequest::class => MockResponse::fixture('Api/BankAccounts/search-count-bank-account'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $response = SearchCountBankAccountResponse::fromResponse(
        $connector->send(new SearchCountBankAccountRequest)
    );

    expect($response->count())->toBe(7);
});
