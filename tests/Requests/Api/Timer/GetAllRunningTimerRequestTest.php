<?php

declare(strict_types=1);

use CodebarAg\Odoo\Requests\Api\Timer\GetAllRunningTimerRequest;
use CodebarAg\Odoo\Responses\Api\Timer\RunningTimersResponse;

it('sends request to correct endpoint', function () {
    [$connector, $mockClient] = odooMockClient([
        GetAllRunningTimerRequest::class => 'Api/Timer/running-timers',
    ]);

    $response = RunningTimersResponse::fromResponse(
        $connector->send(new GetAllRunningTimerRequest)
    );

    $mockClient->assertSent(GetAllRunningTimerRequest::class);
    expect($response->successful())->toBeTrue();
});

it('sends correct body', function () {
    [$connector, $mockClient] = odooMockClient([
        GetAllRunningTimerRequest::class => 'Api/Timer/running-timers',
    ]);

    $connector->send(new GetAllRunningTimerRequest);

    $mockClient->assertSent(function (GetAllRunningTimerRequest $request) {
        $body = $request->body()->all();

        return data_get($body, 'domain') === []
            && in_array('id', data_get($body, 'fields', []))
            && in_array('is_timer_running', data_get($body, 'fields', []))
            && in_array('user_id', data_get($body, 'fields', []))
            && data_get($body, 'limit') === 500;
    });
});

it('parses running timers from response', function () {
    [$connector] = odooMockClient([
        GetAllRunningTimerRequest::class => 'Api/Timer/running-timers',
    ]);

    $response = RunningTimersResponse::fromResponse(
        $connector->send(new GetAllRunningTimerRequest)
    );

    $timers = $response->timers();

    expect($timers)->toHaveCount(1);

    $timer = $timers[0];
    expect($timer->id)->toBe(7)
        ->and($timer->displayName)->toBe('timer.timer,7')
        ->and($timer->timerStart)->toBe('2026-06-18 12:16:34')
        ->and($timer->timerPause)->toBeFalse()
        ->and($timer->isTimerRunning)->toBeTrue()
        ->and($timer->resModel)->toBe('account.analytic.line')
        ->and($timer->resId)->toBe(54)
        ->and($timer->userId)->toBe(6)
        ->and($timer->userName)->toBe('Max Mustermann')
        ->and($timer->parentResModel)->toBeNull()
        ->and($timer->parentResId)->toBe(0)
        ->and($timer->createUid)->toBe(6)
        ->and($timer->createUidName)->toBe('Max Mustermann')
        ->and($timer->createDate)->toBe('2026-06-18 12:16:34')
        ->and($timer->writeUid)->toBe(6)
        ->and($timer->writeUidName)->toBe('Max Mustermann')
        ->and($timer->writeDate)->toBe('2026-06-18 12:16:34');
});
