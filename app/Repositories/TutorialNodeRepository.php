<?php

namespace App\Repositories;

use App\Models\TutorialNode;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;

class TutorialNodeRepository
{
    public function getNodesForVersion(int $applicationId, int $versionId): Collection
    {
        return TutorialNode::query()
            ->where('application_id', $applicationId)
            ->where('application_version_id', $versionId)
            ->with('contentBlocks')
            ->orderBy('parent_id')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }

    public function getAll(?int $applicationId = null, ?int $applicationVersionId = null): Collection
    {
        return TutorialNode::query()
            ->with([
                'application:id,name,slug',
                'applicationVersion:id,application_id,version_number',
                'parent:id,title',
            ])
            ->when(
                $applicationId !== null,
                fn ($query) => $query->where('application_id', $applicationId)
            )
            ->when(
                $applicationVersionId !== null,
                fn ($query) => $query->where('application_version_id', $applicationVersionId)
            )
            ->orderBy('application_id')
            ->orderBy('application_version_id')
            ->orderBy('parent_id')
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get();
    }

    public function getTree(int $applicationId, int $applicationVersionId): Collection
    {
        return TutorialNode::query()
            ->roots()
            ->where('application_id', $applicationId)
            ->where('application_version_id', $applicationVersionId)
            ->with([
                'application:id,name,slug',
                'applicationVersion:id,application_id,version_number',
                'childrenRecursive',
            ])
            ->ordered()
            ->get();
    }

    public function findWithRelations(int $id): TutorialNode
    {
        return TutorialNode::query()->findOrFail($id)->load([
            'application:id,name,slug',
            'applicationVersion:id,application_id,version_number',
            'parent:id,title,application_id,application_version_id',
            'children',
        ]);
    }

    public function create(array $data): TutorialNode
    {
        return TutorialNode::create($data);
    }
    
    public function find(int $id): ?TutorialNode
    {
        return TutorialNode::query()->find($id);
    }
}
