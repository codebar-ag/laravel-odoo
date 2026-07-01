<?php

use CodebarAg\Odoo\OdooConnector;
use CodebarAg\Odoo\Requests\Api\BankAccounts\SearchBankAccountRequest;
use CodebarAg\Odoo\Responses\Api\BankAccounts\SearchBankAccountResponse;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('sends request to correct endpoint', function () {
    $mockClient = new MockClient([
        SearchBankAccountRequest::class => MockResponse::fixture('Api/BankAccounts/search-bank-account'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $response = SearchBankAccountResponse::fromResponse(
        $connector->send(new SearchBankAccountRequest([['partner_id', '=', 7]]))
    );

    $mockClient->assertSent(SearchBankAccountRequest::class);
    expect($response->successful())->toBeTrue();
});

it('sends correct domain body', function () {
    $mockClient = new MockClient([
        SearchBankAccountRequest::class => MockResponse::fixture('Api/BankAccounts/search-bank-account'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $connector->send(new SearchBankAccountRequest([['partner_id', '=', 7]]));

    $mockClient->assertSent(function (SearchBankAccountRequest $request) {
        $body = $request->body()->all();

        return data_get($body, 'domain') === [['partner_id', '=', 7]];
    });
});

it('returns matching ids', function () {
    $mockClient = new MockClient([
        SearchBankAccountRequest::class => MockResponse::fixture('Api/BankAccounts/search-bank-account'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $response = SearchBankAccountResponse::fromResponse(
        $connector->send(new SearchBankAccountRequest([]))
    );

    expect($response->ids())->toBe([5, 6]);
});
