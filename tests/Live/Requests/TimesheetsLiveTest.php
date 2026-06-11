<?php

it('gets timesheet entries', function () {
    $response = $this->connector()->getTimesheetEntries(limit: 5);

    expect($response->successful())->toBeTrue()
        ->and($response->body())->toBeJson();
})->group('live');

it('exposes the approval / lock status on entries', function () {
    $entries = $this->connector()->getTimesheetEntries(limit: 5)->entries();

    if ($entries === []) {
        $this->markTestSkipped('No timesheet entries available on the live instance.');
    }

    $entry = $entries[0];

    // validated is the lock-after-approval flag; null only if the field is permission-gated.
    expect($entry->validated)->toBeIn([true, false, null]);
    expect($entry->validatedStatus)->toBeIn(['draft', 'validated', null]);
})->group('live');

it('gets timesheet entries from last 30 days', function () {
    $response = $this->connector()->getTimesheetEntriesLastDays(days: 30);

    expect($response->successful())->toBeTrue()
        ->and($response->body())->toBeJson();
})->group('live');
