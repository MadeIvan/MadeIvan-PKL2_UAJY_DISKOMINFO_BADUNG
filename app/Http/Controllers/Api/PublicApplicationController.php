<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\ApplicationVersion;
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
                        $query
                    ) use (
                        $search
                    ): void {
                        $query->where(
                            function (
                                $query
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
                )
                ->orderBy('name')
                ->paginate(9);

        $applications->setCollection(
            $applications
                ->getCollection()
                ->map(
                    function (
                        Application $application
                    ): array {
                        $displayVersion =
                            $this
                                ->getPreferredVersion(
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

                            /*
                             * Tetap menggunakan nama
                             * current_version supaya
                             * JavaScript publik yang
                             * sekarang tidak perlu diubah.
                             *
                             * Nilainya:
                             * 1. versi is_current
                             * 2. jika tidak ada, versi terbaru
                             */
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
                    $applications
                        ->currentPage(),

                'last_page' =>
                    $applications
                        ->lastPage(),

                'per_page' =>
                    $applications
                        ->perPage(),

                'total' =>
                    $applications
                        ->total(),

                'from' =>
                    $applications
                        ->firstItem(),

                'to' =>
                    $applications
                        ->lastItem(),
            ],
        ]);
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