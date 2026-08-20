<?php

namespace App\Repositories;

use App\Models\ApplicationVersion;
use Illuminate\Database\Eloquent\Collection;

class ApplicationVersionRepository
{
    public function getByIdAndApplication(int $id, int $applicationId): ?ApplicationVersion
    {
        return ApplicationVersion::query()
            ->whereKey($id)
            ->where('application_id', $applicationId)
            ->first();
    }

    public function clearCurrentVersion(int $applicationId, ?int $ignoredVersionId = null): void
    {
        ApplicationVersion::query()
            ->where('application_id', $applicationId)
            ->when(
                $ignoredVersionId !== null,
                fn ($query) => $query->where('id', '!=', $ignoredVersionId)
            )
            ->where('is_current', true)
            ->update([
                'is_current' => false,
            ]);
    }
}
