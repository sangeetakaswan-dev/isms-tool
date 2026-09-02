<?php

namespace App\DTOs;

class SoAEntryData
{
    public function __construct(
        public readonly int $assessmentId,
        public readonly int $controlId,
        public readonly bool $applicable,
        public readonly ?string $justification = null,
        public readonly string $implementationStatus = 'not_implemented',
        public readonly ?string $implementationDescription = null,
        public readonly ?string $exclusionReason = null,
        public readonly ?int $approvedBy = null,
    ) {}
}