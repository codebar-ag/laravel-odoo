<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Responses\Api\Timer;

use CodebarAg\Odoo\Dto\Timer\TimerDto;
use CodebarAg\Odoo\Responses\OdooResponse;

class RunningTimersResponse extends OdooResponse
{
    /** @return array<TimerDto> */
    public function timers(): array
    {
        if ($this->failed()) {
            return [];
        }

        $result = $this->response->json();

        $timers = [];
        foreach ($result as $item) {
            if (\is_array($item)) {
                $timers[] = TimerDto::fromArray($item);
            }
        }

        return $timers;
    }
}
