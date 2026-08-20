<?php

namespace App\Services\Admin;

use App\Models\Application;
use App\Repositories\ApplicationRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ApplicationService
{
    public function __construct(
        protected ApplicationRepository $applicationRepository
    ) {
    }

    public function create(array $data): Application
    {
        return DB::transaction(function () use ($data): Application {
            $logo = $data['logo'] ?? null;
            $cover = $data['cover'] ?? null;

            unset(
                $data['logo'],
                $data['cover'],
                $data['remove_logo'],
                $data['remove_cover']
            );

            $data['slug'] = $this->generateUniqueSlug(
                $data['slug'] ?? $data['name']
            );

            $data['is_public'] = (bool) (
                $data['is_public'] ?? false
            );

            if ($logo instanceof UploadedFile) {
                $data['logo_path'] = $logo->store(
                    'applications/logos',
                    'public'
                );
            }

            if ($cover instanceof UploadedFile) {
                $data['cover_path'] = $cover->store(
                    'applications/covers',
                    'public'
                );
            }

            return $this->applicationRepository->create($data);
        });
    }

    public function update(
        Application $application,
        array $data
    ): Application {
        return DB::transaction(
            function () use ($application, $data): Application {
                $logo = $data['logo'] ?? null;
                $cover = $data['cover'] ?? null;

                $removeLogo = (bool) (
                    $data['remove_logo'] ?? false
                );

                $removeCover = (bool) (
                    $data['remove_cover'] ?? false
                );

                unset(
                    $data['logo'],
                    $data['cover'],
                    $data['remove_logo'],
                    $data['remove_cover']
                );

                if (array_key_exists('slug', $data)) {
                    $slugSource = $data['slug']
                        ?: ($data['name'] ?? $application->name);

                    $data['slug'] = $this->generateUniqueSlug(
                        $slugSource,
                        $application->id
                    );
                } elseif (array_key_exists('name', $data)) {
                    $data['slug'] = $this->generateUniqueSlug(
                        $data['name'],
                        $application->id
                    );
                }

                if ($removeLogo) {
                    $this->deleteFile($application->logo_path);
                    $data['logo_path'] = null;
                }

                if ($removeCover) {
                    $this->deleteFile($application->cover_path);
                    $data['cover_path'] = null;
                }

                if ($logo instanceof UploadedFile) {
                    $this->deleteFile($application->logo_path);

                    $data['logo_path'] = $logo->store(
                        'applications/logos',
                        'public'
                    );
                }

                if ($cover instanceof UploadedFile) {
                    $this->deleteFile($application->cover_path);

                    $data['cover_path'] = $cover->store(
                        'applications/covers',
                        'public'
                    );
                }

                $this->applicationRepository->update($application, $data);

                return $application->fresh([
                    'versions',
                    'currentVersion',
                ]);
            }
        );
    }

    public function delete(Application $application): void
    {
        DB::transaction(function () use ($application): void {
            $this->applicationRepository->delete($application);
        });
    }

    private function deleteFile(?string $path): void
    {
        if (
            $path !== null &&
            Storage::disk('public')->exists($path)
        ) {
            Storage::disk('public')->delete($path);
        }
    }

    private function generateUniqueSlug(
        string $value,
        ?int $ignoredApplicationId = null
    ): string {
        $baseSlug = Str::slug($value);

        if ($baseSlug === '') {
            $baseSlug = 'application';
        }

        $slug = $baseSlug;
        $counter = 2;

        while (
            $this->applicationRepository->existsWithSlug($slug, $ignoredApplicationId)
        ) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}