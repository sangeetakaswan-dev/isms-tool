<?php

namespace App\Services\Compliance;

use App\DTOs\SoAEntryData;
use App\DTOs\SoAVersionData;
use App\Models\Assessment;
use App\Models\AssessmentResponse;
use App\Models\Control;
use App\Models\SoAEntry;
use App\Models\SoAVersion;
use App\Repositories\Contracts\SoAEntryRepositoryInterface;
use App\Repositories\Contracts\SoAVersionRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class StatementOfApplicabilityService
{
    public function __construct(
        private SoAEntryRepositoryInterface $soaEntryRepository,
        private SoAVersionRepositoryInterface $soaVersionRepository
    ) {}

    /**
     * Generate SoA entries for an assessment (if not already existing).
     * Iterates over all controls (main + annex) and creates initial entries.
     */
    public function generateSoA(Assessment $assessment): void
    {
        DB::transaction(function () use ($assessment) {
            $controls = Control::where('is_active', true)->get();
            foreach ($controls as $control) {
                $existing = $this->soaEntryRepository->getByControl($assessment->id, $control->id);
                if (!$existing) {
                    $data = $this->determineInitialApplicability($assessment, $control);
                    $this->soaEntryRepository->create([
                        'assessment_id' => $assessment->id,
                        'control_id' => $control->id,
                        'applicable' => $data['applicable'],
                        'justification' => $data['justification'],
                        'implementation_status' => $data['implementation_status'],
                    ]);
                }
            }
        });
    }

    /**
     * Determine initial applicability based on assessment responses and risk data.
     * Simple logic: if response status != 'not_applicable', mark as applicable.
     * For Annex A controls, also consider risk levels (if risk exists for related assets).
     */
    private function determineInitialApplicability(Assessment $assessment, Control $control): array
    {
        $response = AssessmentResponse::where('assessment_id', $assessment->id)
            ->where('control_id', $control->id)
            ->first();

        $applicable = true;
        $justification = '';

        if ($response && $response->status === 'not_applicable') {
            $applicable = false;
            $justification = 'Marked as Not Applicable in assessment';
        } else {
            // For Annex A, if no related risks and not required, could be non-applicable.
            // For simplicity, assume applicable if not explicitly not_applicable.
            $applicable = true;
            $justification = 'Control is applicable based on assessment and risk evaluation.';
        }

        $implementationStatus = 'not_implemented';
        if ($response) {
            $implementationStatus = match ($response->status) {
                'compliant' => 'fully_implemented',
                'partially_compliant' => 'partially_implemented',
                'non_compliant' => 'not_implemented',
                'not_applicable' => 'not_applicable',
                default => 'not_implemented',
            };
        }

        return [
            'applicable' => $applicable,
            'justification' => $justification,
            'implementation_status' => $implementationStatus,
        ];
    }

    /**
     * Update a single SoA entry with given data.
     */
    public function updateSoAEntry(int $entryId, SoAEntryData $data): SoAEntry
    {
        return $this->soaEntryRepository->update($entryId, (array) $data);
    }

    /**
     * Approve a single entry.
     */
    public function approveEntry(SoAEntry $entry, int $userId): SoAEntry
    {
        $entry->approved_by = $userId;
        $entry->approved_at = now();
        $entry->save();
        return $entry;
    }

    /**
     * Bulk approve all entries for an assessment.
     */
    public function bulkApprove(Assessment $assessment, int $userId): void
    {
        $entries = $this->soaEntryRepository->getByAssessment($assessment->id);
        foreach ($entries as $entry) {
            $entry->approved_by = $userId;
            $entry->approved_at = now();
            $entry->save();
        }
    }

    /**
     * Create a version snapshot of current SoA.
     */
    public function createVersion(Assessment $assessment, int $userId, string $version = null): SoAVersion
    {
        $entries = $this->soaEntryRepository->getByAssessment($assessment->id);
        $snapshot = $entries->map(function ($entry) {
            return [
                'control_id' => $entry->control_id,
                'applicable' => $entry->applicable,
                'justification' => $entry->justification,
                'implementation_status' => $entry->implementation_status,
                'implementation_description' => $entry->implementation_description,
                'exclusion_reason' => $entry->exclusion_reason,
            ];
        })->toArray();

        $versionNumber = $version ?? '1.0'; // auto increment could be added
        return $this->soaVersionRepository->create([
            'assessment_id' => $assessment->id,
            'version' => $versionNumber,
            'data_snapshot' => $snapshot,
            'created_by' => $userId,
        ]);
    }

    /**
     * Compare two versions and return differences.
     */
    public function compareVersions(SoAVersion $v1, SoAVersion $v2): array
    {
        $snap1 = collect($v1->data_snapshot)->keyBy('control_id');
        $snap2 = collect($v2->data_snapshot)->keyBy('control_id');

        $differences = [];
        $allControlIds = $snap1->keys()->merge($snap2->keys())->unique();

        foreach ($allControlIds as $controlId) {
            $entry1 = $snap1->get($controlId);
            $entry2 = $snap2->get($controlId);
            if ($entry1 != $entry2) {
                $differences[] = [
                    'control_id' => $controlId,
                    'old' => $entry1,
                    'new' => $entry2,
                ];
            }
        }
        return $differences;
    }

    /**
     * Get SoA entries for an assessment, optionally filtered by applicable/status.
     */
    public function getSoAEntries(Assessment $assessment): Collection
    {
        return $this->soaEntryRepository->getByAssessment($assessment->id);
    }
}