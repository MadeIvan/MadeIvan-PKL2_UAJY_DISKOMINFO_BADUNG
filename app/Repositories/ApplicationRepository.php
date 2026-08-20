<?php

namespace App\Repositories;

use App\Models\Application;

class ApplicationRepository
{
    public function create(array $data): Application
    {
        return Application::create($data);
    }

    public function update(Application $application, array $data): bool
    {
        return $application->update($data);
    }

    public function delete(Application $application): bool|null
    {
        return $application->delete();
    }

    public function existsWithSlug(string $slug, ?int $ignoredApplicationId = null): bool
    {
        return Application::query()
            ->when(
                $ignoredApplicationId !== null,
                fn ($query) => $query->where('id', '!=', $ignoredApplicationId)
            )
            ->where('slug', $slug)
            ->exists();
    }
}
