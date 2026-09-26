<?php

namespace App\DTOs;

class DocumentData
{
    public function __construct(
        public readonly int $tenantId,
        public readonly string $title,
        public readonly string $documentType,
        public readonly ?string $category,
        public readonly int $ownerId,
        public readonly ?string $description = null,
        public readonly ?array $tags = null,
        public readonly ?string $reviewDate = null,
    ) {}
}
