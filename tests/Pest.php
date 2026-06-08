<?php

use CodebarAg\Odoo\Tests\Live\TestCase as LiveTestCase;
use CodebarAg\Odoo\Tests\TestCase;
use Saloon\Laravel\Saloon;

uses(TestCase::class)
    ->afterEach(function () {
        Saloon::fake([]);
    })
    ->in('Core', 'Facades', 'Requests', 'Responses');

uses(LiveTestCase::class)->in('Live');

/**
 * Helper function to check if fixtures should be reset/regenerated.
 * Set RESET_FIXTURES=true in phpunit.xml to regenerate fixtures from live API.
 * Defaults to false (use existing fixtures).
 */
function shouldResetFixtures(): bool
{
    return filter_var(getenv('RESET_FIXTURES') ?: false, FILTER_VALIDATE_BOOLEAN);
}
