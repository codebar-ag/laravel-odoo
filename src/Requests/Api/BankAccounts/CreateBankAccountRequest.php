<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Requests\Api\BankAccounts;

use CodebarAg\Odoo\Dto\BankAccounts\CreateBankAccountDto;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class CreateBankAccountRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        private readonly CreateBankAccountDto $dto,
        private readonly bool $modern = false,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/json/2/res.partner.bank/create';
    }

    /** @return array<string, mixed> */
    protected function defaultBody(): array
    {
        return ['vals_list' => $this->dto->toArray($this->modern)];
    }
}
