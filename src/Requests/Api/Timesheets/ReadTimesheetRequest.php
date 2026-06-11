<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Requests\Api\Timesheets;

use CodebarAg\Odoo\Requests\Api\Timesheets\Concerns\HasTimesheetFields;
use CodebarAg\Odoo\Requests\Concerns\HasOdooCaching;
use Saloon\CachePlugin\Contracts\Cacheable;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class ReadTimesheetRequest extends Request implements Cacheable, HasBody
{
    use HasJsonBody;
    use HasOdooCaching;
    use HasTimesheetFields;

    protected Method $method = Method::POST;

    /** @param array<string> $fields */
    public function __construct(
        private readonly int $id,
        private readonly array $fields = [],
    ) {}

    public function resolveEndpoint(): string
    {
        return '/json/2/account.analytic.line/search_read';
    }

    /** @return array<string, mixed> */
    protected function defaultBody(): array
    {
        return [
            'domain' => [['id', '=', $this->id]],
            'fields' => $this->fields ?: self::DEFAULT_FIELDS,
        ];
    }
}
