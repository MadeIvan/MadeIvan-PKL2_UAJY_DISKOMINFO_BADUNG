<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\ApplicationVersion;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PublicApplicationController extends Controller
{
    public function index(
        Request $request
    ): JsonResponse {
        $search =
            trim(
                (string) $request->query(
                    'search',
                    ''
                )
            );

        $sort =
            $this->normalizeSort(
                (string) $request->query(
                    'sort',
                    'latest'
                )
            );

        $applications =
            Application::query()
                ->where(
                    'status',
                    'active'
                )
                ->where(
                    'is_public',
                    true
                )
                ->with([
                    'versions' =>
                        function (
                            $query
                        ): void {
                            $query
                                ->orderByDesc(
                                    'is_current'
                                )
                                ->orderByDesc(
                                    'release_date'
                                )
                                ->orderByDesc(
                                    'id'
                                );
                        },
                ])
                ->when(
                    $search !== '',
                    function (
                        Builder $query
                    ) use (
                        $search
                    ): void {
                        $query->where(
                            function (
                                Builder $query
                            ) use (
                                $search
                            ): void {
                                $query
                                    ->where(
                                        'name',
                                        'like',
                                        "%{$search}%"
                                    )
                                    ->orWhere(
                                        'description',
                                        'like',
                                        "%{$search}%"
                                    )
                                    ->orWhere(
                                        'category_name',
                                        'like',
                                        "%{$search}%"
                                    );
                            }
                        );
                    }
                );

        $this->applySort(
            $applications,
            $sort
        );

        $applications =
            $applications->paginate(
                9
            );

        $applications->setCollection(
            $applications
                ->getCollection()
                ->map(
                    function (
                        Application $application
                    ): array {
                        $displayVersion =
                            $this->getPreferredVersion(
                                $application->versions
                            );

                        return [
                            'id' =>
                                $application->id,

                            'name' =>
                                $application->name,

                            'slug' =>
                                $application->slug,

                            'description' =>
                                $application->description,

                            'category_name' =>
                                $application->category_name,

                            'logo_url' =>
                                $application->logo_path
                                    ? asset(
                                        'storage/' .
                                        $application->logo_path
                                    )
                                    : asset(
                                        'images/Logo.png'
                                    ),

                            'current_version' =>
                                $displayVersion
                                    ? [
                                        'id' =>
                                            $displayVersion->id,

                                        'version_number' =>
                                            $displayVersion
                                                ->version_number,

                                        'status' =>
                                            $displayVersion
                                                ->status,

                                        'release_date' =>
                                            $displayVersion
                                                ->release_date
                                                ?->format(
                                                    'Y-m-d'
                                                ),

                                        'is_current' =>
                                            (bool)
                                            $displayVersion
                                                ->is_current,
                                    ]
                                    : null,
                        ];
                    }
                )
        );

        return response()->json([
            'message' =>
                'Data aplikasi berhasil diambil.',

            'data' =>
                $applications->items(),

            'meta' => [
                'current_page' =>
                    $applications->currentPage(),

                'last_page' =>
                    $applications->lastPage(),

                'per_page' =>
                    $applications->perPage(),

                'total' =>
                    $applications->total(),

                'from' =>
                    $applications->firstItem(),

                'to' =>
                    $applications->lastItem(),
            ],

            'filters' => [
                'search' =>
                    $search,

                'sort' =>
                    $sort,
            ],
        ]);
    }

    private function applySort(
        Builder $query,
        string $sort
    ): void {
        match ($sort) {
            'oldest' =>
                $query
                    ->orderBy(
                        'created_at',
                        'asc'
                    )
                    ->orderBy(
                        'id',
                        'asc'
                    ),

            'name_asc' =>
                $query
                    ->orderBy(
                        'name',
                        'asc'
                    )
                    ->orderBy(
                        'id',
                        'asc'
                    ),

            'name_desc' =>
                $query
                    ->orderBy(
                        'name',
                        'desc'
                    )
                    ->orderBy(
                        'id',
                        'desc'
                    ),

            default =>
                $query
                    ->orderBy(
                        'created_at',
                        'desc'
                    )
                    ->orderBy(
                        'id',
                        'desc'
                    ),
        };
    }

    private function normalizeSort(
        string $sort
    ): string {
        $allowedSorts = [
            'latest',
            'oldest',
            'name_asc',
            'name_desc',
        ];

        return in_array(
            $sort,
            $allowedSorts,
            true
        )
            ? $sort
            : 'latest';
    }

    private function getPreferredVersion(
        $versions
    ): ?ApplicationVersion {
        if ($versions->isEmpty()) {
            return null;
        }

        $current =
            $versions->first(
                fn (
                    ApplicationVersion $version
                ): bool =>
                    (bool)
                    $version->is_current
            );

        if ($current) {
            return $current;
        }

        return $versions
            ->sortByDesc(
                function (
                    ApplicationVersion $version
                ): string {
                    return sprintf(
                        '%s-%020d',
                        $version->release_date
                            ?->format(
                                'Y-m-d'
                            )
                            ?? '0000-00-00',
                        $version->id
                    );
                }
            )
            ->first();
    }
}