<?php

namespace App\DTOs;

use App\Models\Risk;

readonly class RiskData
{
    public function __construct(
        public int $tenantId,
        public string $title,
        public ?string $description,
        public string $category,
        public int $likelihood,
        public int $impact,
        public int $riskScore,
        public string $riskLevel,
        public string $status,
        public ?int $assetId = null,
        public ?int $ownerId = null,
        public ?string $identifiedAt = null,
    ) {}

    public static function fromRequest(array $data, int $tenantId): self
    {
        $likelihood = (int) $data['likelihood'];
        $impact     = (int) $data['impact'];
        $score      = $likelihood * $impact;

        return new self(
            tenantId:     $tenantId,
            title:        $data['title'],
            description:  $data['description'] ?? null,
            category:     $data['category'] ?? 'organizational',
            likelihood:   $likelihood,
            impact:       $impact,
            riskScore:    $score,
            riskLevel:    self::levelFor($score),
            status:       $data['status'] ?? 'identified',
            assetId:      $data['asset_id'] ?? null,
            ownerId:      $data['owner_id'] ?? null,
            identifiedAt: $data['identified_at'] ?? now()->toDateString(),
        );
    }

    public static function levelFor(int $score): string
    {
        return match (true) {
            $score >= 20 => 'critical',
            $score >= 12 => 'high',
            $score >= 6  => 'medium',
            default      => 'low',
        };
    }

    public function toArray(): array
    {
        return [
            'tenant_id'     => $this->tenantId,
            'asset_id'      => $this->assetId,
            'title'         => $this->title,
            'description'   => $this->description,
            'category'      => $this->category,
            'likelihood'    => $this->likelihood,
            'impact'        => $this->impact,
            'risk_score'    => $this->riskScore,
            'risk_level'    => $this->riskLevel,
            'status'        => $this->status,
            'owner_id'      => $this->ownerId,
            'identified_at' => $this->identifiedAt,
        ];
    }
}