<?php

use CodebarAg\Odoo\OdooConnector;
use CodebarAg\Odoo\Requests\Api\BankAccounts\DeleteBankAccountRequest;
use CodebarAg\Odoo\Responses\Api\BankAccounts\MutateBankAccountResponse;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('sends request to correct endpoint', function () {
    $mockClient = new MockClient([
        DeleteBankAccountRequest::class => MockResponse::fixture('Api/BankAccounts/delete-bank-account'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $response = MutateBankAccountResponse::fromResponse(
        $connector->send(new DeleteBankAccountRequest(5))
    );

    $mockClient->assertSent(DeleteBankAccountRequest::class);
    expect($response->successful())->toBeTrue();
});

it('sends correct ids body', function () {
    $mockClient = new MockClient([
        DeleteBankAccountRequest::class => MockResponse::fixture('Api/BankAccounts/delete-bank-account'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $connector->send(new DeleteBankAccountRequest(5));

    $mockClient->assertSent(function (DeleteBankAccountRequest $request) {
        $body = $request->body()->all();

        return data_get($body, 'ids') === [5];
    });
});

it('confirms successful deletion', function () {
    $mockClient = new MockClient([
        DeleteBankAccountRequest::class => MockResponse::fixture('Api/BankAccounts/delete-bank-account'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $response = MutateBankAccountResponse::fromResponse(
        $connector->send(new DeleteBankAccountRequest(5))
    );

    expect($response->ok())->toBeTrue();
});
