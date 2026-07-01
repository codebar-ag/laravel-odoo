<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Dto\Contacts;

use Spatie\LaravelData\Data;

class CreateContactDto extends Data
{
    /**
     * @param  array<string, mixed>  $extraValues  Additional Odoo field values (e.g. custom studio fields)
     */
    public function __construct(
        public readonly string $name,
        public readonly ?bool $isCompany = null,
        public readonly ?int $parentId = null,
        public readonly ?string $type = null,
        public readonly ?string $street = null,
        public readonly ?string $street2 = null,
        public readonly ?string $city = null,
        public readonly ?string $zip = null,
        public readonly ?int $stateId = null,
        public readonly ?int $countryId = null,
        public readonly ?string $phone = null,
        public readonly ?string $mobile = null,
        public readonly ?string $email = null,
        public readonly ?string $website = null,
        public readonly ?string $comment = null,
        public readonly ?string $function = null,
        public readonly ?string $lang = null,
        public readonly ?int $titleId = null,
        public readonly ?int $userId = null,
        public readonly ?int $categoryId = null,
        public readonly ?string $vat = null,
        public readonly ?string $ref = null,
        public readonly ?bool $active = null,
        public readonly array $extraValues = [],
    ) {}

    /**
     * Serialise to the Odoo `create` value map. Optional fields are omitted when null
     * and `extraValues` (studio fields) are merged at the top level.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $data = [
            'name' => $this->name,
        ];

        if ($this->isCompany !== null) {
            data_set($data, 'is_company', $this->isCompany);
        }

        if ($this->parentId !== null) {
            data_set($data, 'parent_id', $this->parentId);
        }

        if ($this->type !== null) {
            data_set($data, 'type', $this->type);
        }

        if ($this->street !== null) {
            data_set($data, 'street', $this->street);
        }

        if ($this->street2 !== null) {
            data_set($data, 'street2', $this->street2);
        }

        if ($this->city !== null) {
            data_set($data, 'city', $this->city);
        }

        if ($this->zip !== null) {
            data_set($data, 'zip', $this->zip);
        }

        if ($this->stateId !== null) {
            data_set($data, 'state_id', $this->stateId);
        }

        if ($this->countryId !== null) {
            data_set($data, 'country_id', $this->countryId);
        }

        if ($this->phone !== null) {
            data_set($data, 'phone', $this->phone);
        }

        if ($this->mobile !== null) {
            data_set($data, 'mobile', $this->mobile);
        }

        if ($this->email !== null) {
            data_set($data, 'email', $this->email);
        }

        if ($this->website !== null) {
            data_set($data, 'website', $this->website);
        }

        if ($this->comment !== null) {
            data_set($data, 'comment', $this->comment);
        }

        if ($this->function !== null) {
            data_set($data, 'function', $this->function);
        }

        if ($this->lang !== null) {
            data_set($data, 'lang', $this->lang);
        }

        if ($this->titleId !== null) {
            data_set($data, 'title', $this->titleId);
        }

        if ($this->userId !== null) {
            data_set($data, 'user_id', $this->userId);
        }

        if ($this->categoryId !== null) {
            data_set($data, 'category_id', $this->categoryId);
        }

        if ($this->vat !== null) {
            data_set($data, 'vat', $this->vat);
        }

        if ($this->ref !== null) {
            data_set($data, 'ref', $this->ref);
        }

        if ($this->active !== null) {
            data_set($data, 'active', $this->active);
        }

        /** @var array<string, mixed> $data */
        return [...$data, ...$this->extraValues];
    }
}
