<?php

it('gets timesheet entries', function () {
    $response = $this->connector()->getTimesheetEntries(limit: 5);

    expect($response->successful())->toBeTrue()
        ->and($response->body())->toBeJson();
})->group('live');

it('gets timesheet entries from last 30 days', function () {
    $response = $this->connector()->getTimesheetEntriesLastDays(days: 30);

    expect($response->successful())->toBeTrue()
        ->and($response->body())->toBeJson();
})->group('live');
