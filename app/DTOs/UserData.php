<?php

namespace App\DTOs;

class UserData
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly ?string $password = null,
        public readonly ?string $phone = null,
        public readonly ?string $jobTitle = null,
        public readonly ?string $department = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            email: $data['email'],
            password: $data['password'] ?? null,
            phone: $data['phone'] ?? null,
            jobTitle: $data['job_title'] ?? null,
            department: $data['department'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'job_title' => $this->jobTitle,
            'department' => $this->department,
        ];
    }
}