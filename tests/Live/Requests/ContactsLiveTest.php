<?php

use CodebarAg\Odoo\Dto\Contacts\CreateContactDto;
use CodebarAg\Odoo\Responses\Api\Contacts\CreateContactResponse;

it('creates a contact and returns its id', function () {
    $dto = new CreateContactDto(
        name: 'Test Contact',
        isCompany: false,
        email: 'test@example.com',
        phone: '+41 44 000 00 00',
        extraValues: [],
    );

    $response = $this->connector()->createContact($dto);

    ray($response->id());

    expect($response)->toBeInstanceOf(CreateContactResponse::class);
    expect($response->id())->toBeInt();
})->group('live');
