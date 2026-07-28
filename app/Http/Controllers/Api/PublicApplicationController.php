<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PublicApplicationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $search = trim((string) $request->query('search', ''));

        $applications = Application::query()
            ->where('status', 'active')
            ->where('is_public', true)
            ->with('currentVersion')
            ->when(
                $search !== '',
                function ($query) use ($search): void {
                    $query->where(function ($query) use ($search): void {
                        $query
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('description', 'like', "%{$search}%")
                            ->orWhere('category_name', 'like', "%{$search}%");
                    });
                }
            )
            ->orderBy('name')
            ->paginate(9);

        $applications->setCollection(
            $applications->getCollection()->map(
                function (Application $application): array {
                    return [
                        'id' => $application->id,
                        'name' => $application->name,
                        'slug' => $application->slug,
                        'description' => $application->description,
                        'category_name' => $application->category_name,

                        'logo_url' => $application->logo_path
                            ? asset(
                                'storage/' . $application->logo_path
                            )
                            : asset('images/Logo.png'),

                        'current_version' => $application->currentVersion
                            ? [
                                'id' =>
                                    $application->currentVersion->id,

                                'version_number' =>
                                    $application->currentVersion
                                        ->version_number,

                                'status' =>
                                    $application->currentVersion->status,

                                'release_date' =>
                                    $application->currentVersion
                                        ->release_date
                                        ?->format('Y-m-d'),
                            ]
                            : null,
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
}