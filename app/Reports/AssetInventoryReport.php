<?php

namespace App\Reports;

class AssetInventoryReport extends AbstractReport
{
    public function key(): string { return 'asset_inventory'; }
    public function title(): string { return 'Asset Inventory'; }
    public function description(): string { return 'Registered information assets.'; }
    public function icon(): string { return '💾'; }

    public function columns(): array
    {
        return ['Name', 'Type', 'Owner', 'CIA Rating', 'Location'];
    }

    public function rows(int $tenantId): array
    {
        return $this->repo->assets($tenantId)->map(fn ($a) => [
            'name' => $a->name,
            'type' => $a->type ?? '-',
            'owner' => $a->owner?->name ?? '-',
            'cia' => $a->cia_rating ?? '-',
            'location' => $a->location ?? '-',
        ])->values()->toArray();
    }
}