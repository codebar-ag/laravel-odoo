<?php

it('gets user context', function () {
    $response = $this->connector()->getUserContext();

    expect($response->successful())->toBeTrue()
        ->and($response->body())->toBeJson();
})->group('live');

it('gets user context data', function () {
    $response = $this->connector()->getUserContext();

    $dto = $response->dto();

    expect($dto)->not->toBeNull()
        ->and($dto->id)->toBeInt()->toBeGreaterThan(0)
        ->and($dto->lang)->toBeString()->not->toBeEmpty()
        ->and($dto->tz)->toBeString()->not->toBeEmpty();
})->group('live');
