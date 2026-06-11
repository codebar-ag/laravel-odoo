<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Requests\Api\Timesheets;

use CodebarAg\Odoo\Requests\Api\Timesheets\Concerns\HasTimesheetFields;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class GetTimesheetEntriesLastDaysRequest extends Request implements HasBody
{
    use HasJsonBody;
    use HasTimesheetFields;

    protected Method $method = Method::POST;

    /** @param array<string> $fields */
    public function __construct(
        private readonly int $days,
        private readonly array $fields = [],
    ) {}

    public function resolveEndpoint(): string
    {
        return '/json/2/account.analytic.line/search_read';
    }

    /** @return array<string, mixed> */
    protected function defaultBody(): array
    {
        $timestamp = \strtotime("-{$this->days} days");
        $since = \date('Y-m-d', $timestamp !== false ? $timestamp : 0);

        return [
            'domain' => [['date', '>=', $since]],
            'fields' => $this->fields ?: self::DEFAULT_FIELDS,
        ];
    }
}
