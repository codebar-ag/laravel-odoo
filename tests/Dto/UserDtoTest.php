<?php

use CodebarAg\Odoo\Dto\Users\UserDto;

it('maps user scalars and relation tuples', function () {
    $dto = UserDto::fromArray([
        'id' => 2,
        'name' => 'Administrator',
        'lang' => 'en_US',
        'login' => 'admin',
        'email' => 'admin@example.com',
        'tz' => 'Europe/Zurich',
        'job_title' => false,
        'active' => true,
        'share' => false,
        'partner_id' => [3, 'Mitchell Admin'],
        'company_id' => [1, 'MyCompany'],
        'employee_id' => [1, 'John Doe'],
    ]);

    expect($dto->id)->toBe(2)
        ->and($dto->name)->toBe('Administrator')
        ->and($dto->lang)->toBe('en_US')
        ->and($dto->login)->toBe('admin')
        ->and($dto->email)->toBe('admin@example.com')
        ->and($dto->tz)->toBe('Europe/Zurich')
        ->and($dto->jobTitle)->toBeNull()
        ->and($dto->active)->toBeTrue()
        ->and($dto->share)->toBeFalse()
        ->and($dto->partnerId)->toBe(3)
        ->and($dto->partnerName)->toBe('Mitchell Admin')
        ->and($dto->companyId)->toBe(1)
        ->and($dto->companyName)->toBe('MyCompany')
        ->and($dto->employeeId)->toBe(1)
        ->and($dto->employeeName)->toBe('John Doe');
});

it('falls back to null when fields are absent or false', function () {
    $dto = UserDto::fromArray([
        'id' => 2,
        'name' => 'Administrator',
        'partner_id' => false,
        'employee_id' => false,
    ]);

    expect($dto->lang)->toBeNull()
        ->and($dto->login)->toBeNull()
        ->and($dto->email)->toBeNull()
        ->and($dto->tz)->toBeNull()
        ->and($dto->active)->toBeNull()
        ->and($dto->share)->toBeNull()
        ->and($dto->partnerId)->toBeNull()
        ->and($dto->employeeId)->toBeNull();
});
