<?php

namespace App\Admin\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Application\StoreApplicationRequest;
use App\Http\Requests\Admin\Application\UpdateApplicationRequest;
use App\Models\Application;
use App\Services\Admin\ApplicationService;
use Illuminate\Http\JsonResponse;

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


    public function getAllWithVersions(): JsonResponse
{
    $applications = Application::query()
        ->with([
            'versions' => function ($query) {
                $query
                    ->latest('release_date')
                    ->latest('id');
            },
            'currentVersion',
        ])
        ->latest('id')
        ->get();

    return response()->json([
        'message' => 'Applications and versions retrieved successfully.',
        'data' => $applications,
    ]);
}
}