<?php

namespace App\Repositories;

use App\Models\SoAEntry;
use App\Repositories\Contracts\SoAEntryRepositoryInterface;

class SoAEntryRepository implements SoAEntryRepositoryInterface
{
    public function create(array $data): SoAEntry
    {
        return SoAEntry::create($data);
    }

    public function update(int $id, array $data): SoAEntry
    {
        $entry = SoAEntry::findOrFail($id);
        $entry->update($data);
        return $entry;
    }

    public function find(int $id): ?SoAEntry
    {
        return SoAEntry::find($id);
    }

    public function delete(int $id): void
    {
        SoAEntry::destroy($id);
    }

    public function getByAssessment(int $assessmentId)
    {
        return SoAEntry::where('assessment_id', $assessmentId)
            ->with('control.domain')
            ->get();
    }

    public function getByControl(int $assessmentId, int $controlId): ?SoAEntry
    {
        return SoAEntry::where('assessment_id', $assessmentId)
            ->where('control_id', $controlId)
            ->first();
    }
}
