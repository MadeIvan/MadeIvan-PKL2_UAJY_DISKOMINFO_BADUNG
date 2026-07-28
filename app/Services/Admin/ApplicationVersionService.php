<?php

namespace App\Services\Admin;

use App\Models\Application;
use App\Models\ApplicationVersion;
use Illuminate\Support\Facades\DB;

class ApplicationVersionService
{
    public function create(
        Application $application,
        array $data
    ): ApplicationVersion {
        return DB::transaction(
            function () use (
                $application,
                $data
            ): ApplicationVersion {
                $isCurrent = (bool) (
                    $data['is_current'] ?? false
                );

                if ($isCurrent) {
                    $this->clearCurrentVersion(
                        $application->id
                    );
                }

                return $application
                    ->versions()
                    ->create([
                        'version_number' =>
                            $data['version_number'],

                        'release_date' =>
                            $data['release_date'] ?? null,

                        'release_notes' =>
                            $data['release_notes'] ?? null,

                        'status' =>
                            $data['status'],

                        'is_current' =>
                            $isCurrent,
                    ]);
            }
        );
    }

    public function update(
        ApplicationVersion $applicationVersion,
        array $data
    ): ApplicationVersion {
        return DB::transaction(
            function () use (
                $applicationVersion,
                $data
            ): ApplicationVersion {
                $willBecomeCurrent =
                    array_key_exists('is_current', $data)
                    && (bool) $data['is_current'];

                if ($willBecomeCurrent) {
                    $this->clearCurrentVersion(
                        $applicationVersion->application_id,
                        $applicationVersion->id
                    );
                }

                $applicationVersion->update($data);

                return $applicationVersion->fresh([
                    'application',
                ]);
            }
        );
    }

    public function delete(
        ApplicationVersion $applicationVersion
    ): void {
        DB::transaction(
            function () use (
                $applicationVersion
            ): void {
                $applicationVersion->delete();
            }
        );
    }

    private function clearCurrentVersion(
        int $applicationId,
        ?int $ignoredVersionId = null
    ): void {
        ApplicationVersion::query()
            ->where('application_id', $applicationId)
            ->when(
                $ignoredVersionId !== null,
                fn ($query) => $query->where(
                    'id',
                    '!=',
                    $ignoredVersionId
                )
            )
            ->where('is_current', true)
            ->update([
                'is_current' => false,
            ]);
    }
}