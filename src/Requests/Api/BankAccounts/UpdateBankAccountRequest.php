<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Requests\Api\BankAccounts;

use CodebarAg\Odoo\Dto\BankAccounts\UpdateBankAccountDto;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class UpdateBankAccountRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        private readonly UpdateBankAccountDto $dto,
        private readonly bool $modern = false,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/json/2/res.partner.bank/write';
    }

    /** @return array<string, mixed> */
    protected function defaultBody(): array
    {
        return [
            'ids' => [$this->dto->id],
            'vals' => $this->dto->toArray($this->modern),
        ];
    }
}
