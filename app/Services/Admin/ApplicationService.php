<?php

namespace App\Services\Admin;

use App\Models\Application;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ApplicationService
{
    public function create(array $data): Application
    {
        return DB::transaction(
            function () use ($data): Application {
                $data['slug'] = $this->generateUniqueSlug(
                    $data['slug'] ?? $data['name']
                );

                $data['is_public'] = (bool) (
                    $data['is_public'] ?? false
                );

                return Application::create($data);
            }
        );
    }

    public function update(
        Application $application,
        array $data
    ): Application {
        return DB::transaction(
            function () use (
                $application,
                $data
            ): Application {
                if (array_key_exists('slug', $data)) {
                    $data['slug'] = $this->generateUniqueSlug(
                        $data['slug'],
                        $application->id
                    );
                } elseif (array_key_exists('name', $data)) {
                    $data['slug'] = $this->generateUniqueSlug(
                        $data['name'],
                        $application->id
                    );
                }

                $application->update($data);

                return $application->fresh([
                    'versions',
                    'currentVersion',
                ]);
            }
        );
    }

    public function delete(Application $application): void
    {
        DB::transaction(
            function () use ($application): void {
                $application->delete();
            }
        );
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
            Application::query()
                ->when(
                    $ignoredApplicationId !== null,
                    fn ($query) => $query->where(
                        'id',
                        '!=',
                        $ignoredApplicationId
                    )
                )
                ->where('slug', $slug)
                ->exists()
        ) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}