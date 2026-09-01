<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
        $canSeeNonPublic = $user && ($user->hasRole('Admin') || $user->hasRole('Pegawai'));

        $query = TutorialNode::search($search)->query(function (Builder $builder) use ($canSeeNonPublic) {
            $builder->where('node_type', TutorialNode::TYPE_MATERI)
                ->whereHas('application', function (Builder $q) use ($canSeeNonPublic) {
                    $q->where('status', 'active');
                    if (!$canSeeNonPublic) {
                        $q->where('is_public', true);
                    }
                })
                ->whereHas('applicationVersion', function (Builder $q) use ($canSeeNonPublic) {
                    // Ensure the version exists and is not a draft for public users
                    $q->whereNotNull('id');

                    if (!$canSeeNonPublic) {
                        $q->whereIn('status', ['beta', 'stable', 'deprecated']);
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
