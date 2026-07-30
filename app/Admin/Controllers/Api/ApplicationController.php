<?php

namespace App\Admin\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Application\StoreApplicationRequest;
use App\Http\Requests\Admin\Application\UpdateApplicationRequest;
use App\Models\Application;
use App\Services\Admin\ApplicationService;
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

    public function index(): JsonResponse
    {
        $applications = Application::query()
            ->with('currentVersion')
            ->latest()
            ->paginate(15);

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


public function getAllWithVersions(Request $request): JsonResponse
{
    $search = trim((string) $request->query('search', ''));

    $applications = Application::query()
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
            function ($query) use ($search): void {
                $query->where(
                    function ($query) use ($search): void {
                        $query
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('slug', 'like', "%{$search}%")
                            ->orWhere('description', 'like', "%{$search}%")
                            ->orWhere('category_name', 'like', "%{$search}%")
                            ->orWhere('status', 'like', "%{$search}%");
                    }
                );
            }
        )
        ->latest('id')
        ->paginate(9);

    $applications->setCollection(
        $applications->getCollection()->map(
            function (Application $application): array {
                return [
                    ...$application->toArray(),

                    'logo_url' => $application->logo_path
                        ? asset(
                            'storage/' . $application->logo_path
                        )
                        : asset('images/Logo.png'),
                ];
            }
        )
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
        'message' => 'Pilihan aplikasi berhasil diambil.',
        'data' => $applications,
    ]);
}

}