<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Requests\CallKw;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

abstract class CallKwRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    abstract protected function getModel(): string;

    abstract protected function getOdooMethod(): string;

    /** @return array<mixed> */
    abstract protected function getArgs(): array;

    /** @return array<string, mixed> */
    abstract protected function getKwargs(): array;

    public function resolveEndpoint(): string
    {
        return '/web/dataset/call_kw';
    }

    /** @return array<string, mixed> */
    protected function defaultBody(): array
    {
        return [
            'jsonrpc' => '2.0',
            'method' => 'call',
            'params' => [
                'model' => $this->getModel(),
                'method' => $this->getOdooMethod(),
                'args' => $this->getArgs(),
                'kwargs' => $this->getKwargs(),
            ],
        ];
    }
}
