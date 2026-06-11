<?php

use CodebarAg\Odoo\Dto\Employees\EmployeeDto;
use CodebarAg\Odoo\Dto\Fields\FieldDto;
use CodebarAg\Odoo\Dto\Users\UserDto;

it('builds an employee dto', function () {
    $dto = EmployeeDto::fromArray(['id' => 3, 'name' => 'Alice']);

    expect($dto->id)->toBe(3)
        ->and($dto->name)->toBe('Alice');
});

it('builds a field dto and defaults the label to the field name', function () {
    $required = FieldDto::fromArray('date', ['type' => 'date', 'string' => 'Date', 'required' => true]);
    $defaulted = FieldDto::fromArray('name', []);

    expect($required->type)->toBe('date')
        ->and($required->label)->toBe('Date')
        ->and($required->required)->toBeTrue()
        ->and($defaulted->type)->toBe('char')
        ->and($defaulted->label)->toBe('name')
        ->and($defaulted->required)->toBeFalse();
});

it('builds a user dto with nullable lang', function () {
    $withLang = UserDto::fromArray(['id' => 2, 'name' => 'Administrator', 'lang' => 'en_US']);
    $withoutLang = UserDto::fromArray(['id' => 2, 'name' => 'Administrator', 'lang' => false]);

    expect($withLang->lang)->toBe('en_US')
        ->and($withoutLang->lang)->toBeNull();
});
