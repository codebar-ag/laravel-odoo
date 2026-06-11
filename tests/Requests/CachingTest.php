<?php

use CodebarAg\Odoo\Dto\Timesheets\CreateTimesheetDto;
use CodebarAg\Odoo\OdooConnector;
use CodebarAg\Odoo\Requests\Api\Tasks\GetAllTasksRequest;
use CodebarAg\Odoo\Requests\Api\Timesheets\CreateTimesheetRequest;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

function cachingConnector(): OdooConnector
{
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient(new MockClient([
        GetAllTasksRequest::class => MockResponse::make([['id' => 1, 'name' => 'Task']], 200),
        CreateTimesheetRequest::class => MockResponse::make([1], 200),
    ]));

    return $connector;
}

it('serves a repeated read request from cache', function () {
    $connector = cachingConnector();

    $first = $connector->send(new GetAllTasksRequest(limit: 100));
    $second = $connector->send(new GetAllTasksRequest(limit: 100));

    expect($first->isCached())->toBeFalse()
        ->and($second->isCached())->toBeTrue();
});

it('keys the cache on the request body so different queries do not collide', function () {
    $connector = cachingConnector();

    $connector->send(new GetAllTasksRequest(limit: 100));

    // Same endpoint + class, different body (limit) -> must be a cache miss.
    $other = $connector->send(new GetAllTasksRequest(limit: 50));

    expect($other->isCached())->toBeFalse();
});

it('does not cache write requests', function () {
    $connector = cachingConnector();

    $dto = new CreateTimesheetDto(
        name: 'Entry',
        projectId: 1,
        taskId: 2,
        date: '2026-06-10',
        unitAmount: 1.5,
    );

    $first = $connector->send(new CreateTimesheetRequest($dto));
    $second = $connector->send(new CreateTimesheetRequest($dto));

    expect($first->isCached())->toBeFalse()
        ->and($second->isCached())->toBeFalse();
});
