<?php

namespace App\Repositories;

use App\Models\TutorialContentBlock;
use Illuminate\Database\Eloquent\Collection;

class TutorialContentBlockRepository
{
    public function getBlocksForNode(int $nodeId): Collection
    {
        return TutorialContentBlock::query()
            ->where('tutorial_node_id', $nodeId)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }

    public function create(array $data): TutorialContentBlock
    {
        return TutorialContentBlock::create($data);
    }
    
    public function update(TutorialContentBlock $block, array $data): bool
    {
        return $block->update($data);
    }
    
    public function delete(TutorialContentBlock $block): bool|null
    {
        return $block->delete();
    }

    public function getMaxSortOrder(int $nodeId): ?int
    {
        return TutorialContentBlock::query()
            ->where('tutorial_node_id', $nodeId)
            ->max('sort_order');
    }

    public function updateSortOrder(int $blockId, int $nodeId, int $sortOrder): int
    {
        return TutorialContentBlock::query()
            ->whereKey($blockId)
            ->where('tutorial_node_id', $nodeId)
            ->update([
                'sort_order' => $sortOrder,
            ]);
    }
}
