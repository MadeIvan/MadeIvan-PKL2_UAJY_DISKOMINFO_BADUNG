<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ApplicationVersion;
use App\Models\TutorialNode;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PublicMateriController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $search = trim((string) $request->query('search', ''));
        $sort = $request->query('sort', 'latest');
        
        $user = auth('sanctum')->user();
        $isAdmin = $user && $user->isAdmin();
        $canSeeNonPublic = $user && $user->isInternal();

        $query = TutorialNode::search($search)->query(function (Builder $builder) use ($canSeeNonPublic, $isAdmin) {
            $builder->where('node_type', TutorialNode::TYPE_MATERI)
                ->whereHas('application', function (Builder $q) use ($canSeeNonPublic) {
                    $q->where('status', 'active');
                    if (!$canSeeNonPublic) {
                        $q->where('is_public', true);
                    }
                })
                ->whereHas('applicationVersion', function (Builder $q) use ($canSeeNonPublic, $isAdmin) {
                    // Ensure the version exists and is not a draft for public users
                    $q->whereNotNull('id');

                    if ($isAdmin) {
                        // Admin dapat melihat seluruh versi, termasuk draf.
                    } elseif ($canSeeNonPublic) {
                        $q->whereIn('status', ApplicationVersion::INTERNAL_STATUSES);
                    } else {
                        $q->whereIn('status', ApplicationVersion::PUBLIC_STATUSES);
                    }
                });

            if ($canSeeNonPublic) {
                $builder->visibleToInternal();
            } else {
                $builder->visibleToPublic();
            }

            $builder->with([
                'application:id,name,slug,logo_path',
                'applicationVersion:id,version_number',
                'parent:id,title'
            ]);
        });

        match ($sort) {
            'name_asc' => $query->orderBy('title', 'asc'),
            'name_desc' => $query->orderBy('title', 'desc'),
            'oldest' => $query->orderBy('created_at', 'asc'),
            default => $query->orderBy('created_at', 'desc'),
        };

        $perPage = min((int) $request->query('per_page', 9), 20);
        $materi = $query->paginate($perPage);

        return response()->json([
            'message' => 'Data materi berhasil diambil.',
            'data' => $materi->items(),
            'meta' => [
                'current_page' => $materi->currentPage(),
                'last_page' => $materi->lastPage(),
                'per_page' => $materi->perPage(),
                'total' => $materi->total(),
                'from' => $materi->firstItem(),
                'to' => $materi->lastItem(),
            ],
            'filters' => [
                'search' => $search,
                'sort' => $sort,
            ],
        ]);
    }
}
