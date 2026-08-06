<?php

namespace App\Admin\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Application\StoreApplicationRequest;
use App\Http\Requests\Admin\Application\UpdateApplicationRequest;
use App\Models\Application;
use App\Services\Admin\ApplicationService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    private ApplicationService $service;

    public function __construct(
        ApplicationService $service
    ) {
        $this->service = $service;
    }

    public function index(Request $request): JsonResponse
    {
        $sort = $this->normalizeSort(
            (string) $request->query('sort', 'latest')
        );

        $query = Application::query()
            ->with('currentVersion');

        $this->applySorting($query, $sort);

        $applications = $query->paginate(15);

        return response()->json([
            'message' =>
                'Applications retrieved successfully.',

            'data' => $applications,
        ]);
    }

    public function store(
        StoreApplicationRequest $request
    ): JsonResponse {
        $application = $this->service->create(
            $request->validated()
        );

        return response()->json([
            'message' =>
                'Application created successfully.',

            'data' => $application,
        ], 201);
    }

    public function show(
        Application $application
    ): JsonResponse {
        $application->load([
            'versions',
            'currentVersion',
        ]);

        return response()->json([
            'message' =>
                'Application retrieved successfully.',

            'data' => $application,
        ]);
    }

    public function update(
        UpdateApplicationRequest $request,
        Application $application
    ): JsonResponse {
        $application = $this->service->update(
            $application,
            $request->validated()
        );

        return response()->json([
            'message' =>
                'Application updated successfully.',

            'data' => $application,
        ]);
    }

    public function destroy(
        Application $application
    ): JsonResponse {
        $this->service->delete($application);

        return response()->json([
            'message' =>
                'Application deleted successfully.',
        ]);
    }

    public function getAllWithVersions(
        Request $request
    ): JsonResponse {
        $search = trim(
            (string) $request->query('search', '')
        );

        $sort = $this->normalizeSort(
            (string) $request->query('sort', 'latest')
        );

        $query = Application::query()
            ->with([
                'versions' => function ($query): void {
                    $query
                        ->latest('release_date')
                        ->latest('id');
                },

                'currentVersion',
            ])
            ->when(
                $search !== '',
                function (Builder $query) use ($search): void {
                    $query->where(
                        function (Builder $query) use ($search): void {
                            $query
                                ->where(
                                    'name',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'slug',
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
                                )
                                ->orWhere(
                                    'status',
                                    'like',
                                    "%{$search}%"
                                );
                        }
                    );
                }
            );

        $this->applySorting($query, $sort);

        $applications = $query
            ->paginate(9)
            ->withQueryString();

        $applications->setCollection(
            $applications
                ->getCollection()
                ->map(
                    function (Application $application): array {
                        return [
                            ...$application->toArray(),

                            'logo_url' =>
                                $application->logo_path
                                    ? asset(
                                        'storage/' .
                                        $application->logo_path
                                    )
                                    : asset(
                                        'images/Logo.png'
                                    ),
                        ];
                    }
                )
        );

        /*
         * Statistics are calculated from all application records,
         * not only the currently opened pagination page.
         */
        $summary = [
            'total_applications' =>
                Application::query()->count(),

            'active_applications' =>
                Application::query()
                    ->where('status', 'active')
                    ->count(),

            'public_applications' =>
                Application::query()
                    ->where('is_public', true)
                    ->count(),

            'inactive_applications' =>
                Application::query()
                    ->where('status', 'inactive')
                    ->count(),
        ];

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

            'summary' => $summary,

            'filters' => [
                'search' => $search,
                'sort' => $sort,
            ],
        ]);
    }

    public function options(): JsonResponse
    {
        $applications = Application::query()
            ->select([
                'id',
                'name',
                'description',
            ])
            ->with([
                'versions' => function ($query): void {
                    $query
                        ->select([
                            'id',
                            'application_id',
                            'version_number',
                        ])
                        ->orderByDesc('id');
                },
            ])
            ->orderBy('name')
            ->get();

        return response()->json([
            'message' =>
                'Pilihan aplikasi berhasil diambil.',

            'data' => $applications,
        ]);
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

    private function applySorting(
        Builder $query,
        string $sort
    ): void {
        match ($sort) {
            'oldest' => $query
                ->orderBy('created_at', 'asc')
                ->orderBy('id', 'asc'),

            'name_asc' => $query
                ->orderBy('name', 'asc')
                ->orderBy('id', 'asc'),

            'name_desc' => $query
                ->orderBy('name', 'desc')
                ->orderBy('id', 'desc'),

            default => $query
                ->orderBy('created_at', 'desc')
                ->orderBy('id', 'desc'),
        };
    }
}