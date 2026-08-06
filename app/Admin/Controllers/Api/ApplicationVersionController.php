<?php

namespace App\Admin\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ApplicationVersion\StoreApplicationVersionRequest;
use App\Http\Requests\Admin\ApplicationVersion\UpdateApplicationVersionRequest;
use App\Models\Application;
use App\Models\ApplicationVersion;
use App\Services\Admin\ApplicationVersionService;
use Illuminate\Http\JsonResponse;

class ApplicationVersionController extends Controller
{
    public function __construct(
        private readonly ApplicationVersionService $service
    ) {
    }

    public function index(
        Application $application
    ): JsonResponse {
        $versions = $application
            ->versions()
            ->latest('release_date')
            ->latest('id')
            ->paginate(15);

        return response()->json([
            'message' =>
                'Application versions retrieved successfully.',

            'data' =>
                $versions,
        ]);
    }

    public function store(
        StoreApplicationVersionRequest $request,
        Application $application
    ): JsonResponse {
        $version = $this->service->create(
            $application,
            $request->validated()
        );

        $copySummary =
            $version->getAttribute(
                'copy_summary'
            );

        $message =
            $request->boolean('copy_materials')
                ? 'Versi aplikasi dan materi terpilih berhasil dibuat.'
                : 'Versi aplikasi berhasil dibuat.';

        return response()->json([
            'message' =>
                $message,

            'data' =>
                $version,

            'copy_summary' =>
                $copySummary,
        ], 201);
    }

    public function show(
        ApplicationVersion $applicationVersion
    ): JsonResponse {
        $applicationVersion->load(
            'application'
        );

        return response()->json([
            'message' =>
                'Application version retrieved successfully.',

            'data' =>
                $applicationVersion,
        ]);
    }

    public function update(
        UpdateApplicationVersionRequest $request,
        ApplicationVersion $applicationVersion
    ): JsonResponse {
        $version = $this->service->update(
            $applicationVersion,
            $request->validated()
        );

        return response()->json([
            'message' =>
                'Application version updated successfully.',

            'data' =>
                $version,
        ]);
    }

    public function destroy(
        ApplicationVersion $applicationVersion
    ): JsonResponse {
        $this->service->delete(
            $applicationVersion
        );

        return response()->json([
            'message' =>
                'Application version deleted successfully.',
        ]);
    }
}
