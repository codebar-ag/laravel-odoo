<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Requests\Api\Timer;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class GetAllRunningTimerRequest extends Request implements HasBody
{
    use HasJsonBody;

    public const array DEFAULT_FIELDS = [
        'id',
        'display_name',
        'timer_start',
        'timer_pause',
        'is_timer_running',
        'res_model',
        'res_id',
        'user_id',
        'parent_res_model',
        'parent_res_id',
        'create_uid',
        'create_date',
        'write_uid',
        'write_date',
    ];

    protected Method $method = Method::POST;

    /**
     * @param array<string> $fields
     * @param array<mixed>  $domain
     */
    public function __construct(
        private readonly array $fields = self::DEFAULT_FIELDS,
        private readonly array $domain = [],
        private readonly int $limit = 500,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/json/2/timer.timer/search_read';
    }

    /** @return array<string, mixed> */
    protected function defaultBody(): array
    {
        return [
            'domain' => $this->domain,
            'fields' => $this->fields,
            'limit' => $this->limit
        ];
    }
}
