<?php

namespace App\DTOs;

class TenantData
{
    public function __construct(
        public readonly string $name,
        public readonly string $contactEmail,
        public readonly ?string $slug = null,
        public readonly ?string $industry = null,
        public readonly ?string $size = null,
        public readonly ?string $contactPhone = null,
        public readonly ?string $address = null,
        public readonly ?string $city = null,
        public readonly ?string $state = null,
        public readonly ?string $country = 'India',
        public readonly ?string $pincode = null,
        public readonly ?array $settings = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            contactEmail: $data['contact_email'],
            slug: $data['slug'] ?? null,
            industry: $data['industry'] ?? null,
            size: $data['size'] ?? null,
            contactPhone: $data['contact_phone'] ?? null,
            address: $data['address'] ?? null,
            city: $data['city'] ?? null,
            state: $data['state'] ?? null,
            country: $data['country'] ?? 'India',
            pincode: $data['pincode'] ?? null,
            settings: $data['settings'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'industry' => $this->industry,
            'size' => $this->size,
            'contact_email' => $this->contactEmail,
            'contact_phone' => $this->contactPhone,
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'country' => $this->country,
            'pincode' => $this->pincode,
            'settings' => $this->settings,
        ];
    }
}