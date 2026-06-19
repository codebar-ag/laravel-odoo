<?php

use CodebarAg\Odoo\Dto\Contacts\CreateContactDto;
use CodebarAg\Odoo\Dto\Contacts\UpdateContactDto;
use CodebarAg\Odoo\Responses\Api\Contacts\CreateContactResponse;
use CodebarAg\Odoo\Responses\Api\Contacts\MutateContactResponse;

it('creates a contact and returns its id', function () {
    $dto = new CreateContactDto(
        name: 'Test Contact',
        isCompany: false,
        email: 'test@example.com',
        phone: '+41 44 000 00 00',
        extraValues: [],
    );

    $response = $this->connector()->createContact($dto);

    expect($response)->toBeInstanceOf(CreateContactResponse::class);
    expect($response->id())->toBeInt();
})->group('live');

it('updates a contact', function () {
    $createDto = new CreateContactDto(
        name: 'Test Contact',
        isCompany: false,
        email: 'test@example.com',
        phone: '+41 44 000 00 00',
    );

    $createResponse = $this->connector()->createContact($createDto);

    expect($createResponse->id())->toBeInt();

    $updateDto = new UpdateContactDto(
        id: $createResponse->id(),
        name: 'Test Contact Updated',
        phone: '+41 44 111 11 11',
    );

    $updateResponse = $this->connector()->updateContact($updateDto);

    ray($updateResponse->body());

    expect($updateResponse)->toBeInstanceOf(MutateContactResponse::class);
    expect($updateResponse->ok())->toBeTrue();
})->group('live');
