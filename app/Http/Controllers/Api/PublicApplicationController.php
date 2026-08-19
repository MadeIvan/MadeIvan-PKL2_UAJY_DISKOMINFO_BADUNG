<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\ApplicationVersion;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PublicApplicationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $search = trim((string) $request->query('search', ''));
        $sort = $this->normalizeSort((string) $request->query('sort', 'latest'));
        $categoryId = $request->query('category_id');

        $user = auth('sanctum')->user();

        $query = Application::search($search)->query(function (Builder $builder) use ($categoryId, $user) {
            $builder->where('status', 'active');
            
            if ($categoryId) {
                $builder->where('category_id', $categoryId);
            }

            if (!$user || !($user->hasRole('Admin') || $user->hasRole('Pegawai'))) {
                $builder->where('is_public', true);
            }

            $builder->with([
                'category',
                'versions' => function ($versionQuery): void {
                    $versionQuery->orderByDesc('is_current')
                        ->orderByDesc('release_date')
                        ->orderByDesc('id');
                },
            ]);
        });

        match ($sort) {
            'oldest' => $query->orderBy('created_at', 'asc'),
            'name_asc' => $query->orderBy('name', 'asc'),
            'name_desc' => $query->orderBy('name', 'desc'),
            default => $query->orderBy('created_at', 'desc'),
        };

        $perPage = min((int) $request->query('per_page', 9), 20);

        $applications = $query->paginate($perPage);

        $applications->setCollection(
            $applications->getCollection()->map(function (Application $application): array {
                $displayVersion = $this->getPreferredVersion($application->versions);

                return [
                    'id' => $application->id,
                    'name' => $application->name,
                    'slug' => $application->slug,
                    'description' => $application->description,
                    'category_name' => $application->category?->name,
                    'logo_url' => $application->logo_path
                        ? asset('storage/' . $application->logo_path)
                        : asset('images/Logo.png'),
                    'current_version' => $displayVersion
                        ? [
                            'id' => $displayVersion->id,
                            'version_number' => $displayVersion->version_number,
                            'status' => $displayVersion->status,
                            'release_date' => $displayVersion->release_date?->format('Y-m-d'),
                            'is_current' => (bool) $displayVersion->is_current,
                        ]
                        : null,
                ];
            })
        );

        return response()->json([
            'message' => 'Data aplikasi berhasil diambil.',
            'data' => $applications->items(),
            'meta' => [
                'current_page' => $applications->currentPage(),
                'last_page' => $applications->lastPage(),
                'per_page' => $applications->perPage(),
                'total' => $applications->total(),
                'from' => $applications->firstItem(),
                'to' => $applications->lastItem(),
            ],
            'filters' => [
                'search' => $search,
                'sort' => $sort,
            ],
        ]);
    }

    private function normalizeSort(string $sort): string
    {
        $allowedSorts = [
            'latest',
            'oldest',
            'name_asc',
            'name_desc',
        ];

        return in_array($sort, $allowedSorts, true) ? $sort : 'latest';
    }

    private function getPreferredVersion($versions): ?ApplicationVersion
    {
        if ($versions->isEmpty()) {
            return null;
        }

        $current = $versions->first(fn (ApplicationVersion $version): bool => (bool) $version->is_current);

        if ($current) {
            return $current;
        }

        return $versions->sortByDesc(function (ApplicationVersion $version): string {
            return sprintf(
                '%s-%020d',
                $version->release_date?->format('Y-m-d') ?? '0000-00-00',
                $version->id
            );
        })->first();
    }
}