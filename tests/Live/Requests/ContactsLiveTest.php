<?php

use CodebarAg\Odoo\Dto\Contacts\ContactDto;
use CodebarAg\Odoo\Dto\Contacts\CreateContactDto;
use CodebarAg\Odoo\Dto\Contacts\UpdateContactDto;
use CodebarAg\Odoo\Requests\Api\Contacts\ReadAllContactRequest;
use CodebarAg\Odoo\Requests\Api\Contacts\ReadContactRequest;
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


    expect($updateResponse)->toBeInstanceOf(MutateContactResponse::class);
    expect($updateResponse->ok())->toBeTrue();
})->group('live');

it('reads all contacts and maps them to ContactDto', function () {
    $response = $this->connector()->send(new ReadAllContactRequest());

    expect($response->successful())->toBeTrue();

    $contacts = collect($response->json())
        ->map(fn (array $record) => ContactDto::fromArray($record))
        ->all();

    expect($contacts)->toBeArray();

    if ($contacts !== []) {
        expect($contacts[0])->toBeInstanceOf(ContactDto::class);
        expect($contacts[0]->id)->toBeInt();
        expect($contacts[0]->name)->toBeString();
    }
})->group('live');

it('creates and reads a contact by id', function () {
    $createResponse = $this->connector()->createContact(new CreateContactDto(
        name: 'Test Contact Read',
        isCompany: false,
        email: 'read-test@example.com',
        phone: '+41 44 000 00 00',
    ));

    expect($createResponse->id())->toBeInt();

    $response = $this->connector()->send(new ReadContactRequest($createResponse->id()));

    expect($response->successful())->toBeTrue();

    $contact = ContactDto::fromArray($response->json()[0]);

    expect($contact)->toBeInstanceOf(ContactDto::class);
    expect($contact->id)->toBe($createResponse->id());
    expect($contact->name)->toBe('Test Contact Read');
})->group('live');

it('creates and deletes a contact', function () {
    $createResponse = $this->connector()->createContact(new CreateContactDto(
        name: 'Test Contact Delete',
        isCompany: false,
        email: 'delete-test@example.com',
    ));

    expect($createResponse->id())->toBeInt();

    $deleteResponse = $this->connector()->deleteContact($createResponse->id());

    ray($deleteResponse->ok());

    expect($deleteResponse)->toBeInstanceOf(MutateContactResponse::class);
    expect($deleteResponse->ok())->toBeTrue();
})->group('live');
