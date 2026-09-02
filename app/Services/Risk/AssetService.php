<?php

namespace App\Services\Risk;

use App\Models\Asset;
use App\Repositories\AssetRepository;
use Illuminate\Support\Collection;

class AssetService
{
    public function __construct(
        private AssetRepository $assetRepository
    ) {
    }

    public function createAsset(array $data): Asset
    {
        return $this->assetRepository->create($data);
    }

    public function updateAsset(int $id, array $data): Asset
    {
        $asset = $this->assetRepository->find($id);
        $asset->update($data);
        return $asset;
    }

    public function deleteAsset(int $id): void
    {
        $this->assetRepository->delete($id);
    }

    /**
     * Calculate asset value based on CIA ratings (average).
     */
    public function calculateAssetValue(Asset $asset): int
    {
        return (int) round(
            ($asset->confidentiality_rating + $asset->integrity_rating + $asset->availability_rating) / 3
        );
    }

    /**
     * Identify critical assets where any CIA rating >= 4.
     */
    public function identifyCriticalAssets(): Collection
    {
        return Asset::where('confidentiality_rating', '>=', 4)
            ->orWhere('integrity_rating', '>=', 4)
            ->orWhere('availability_rating', '>=', 4)
            ->get();
    }

    public function getAssetsByType(string $type): Collection
    {
        return Asset::where('asset_type', $type)->get();
    }
}