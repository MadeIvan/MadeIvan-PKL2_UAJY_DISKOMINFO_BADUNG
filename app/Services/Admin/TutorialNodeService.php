<?php

namespace App\Services\Admin;

use App\Models\ApplicationVersion;
use App\Models\TutorialContentBlock;
use App\Models\TutorialNode;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

use App\Repositories\TutorialNodeRepository;

class TutorialNodeService
{
    public function __construct(
        protected TutorialNodeRepository $tutorialNodeRepository
    ) {
    }

    public function getAll(
        ?int $applicationId = null,
        ?int $applicationVersionId = null
    ): Collection {
        if (
            $applicationId !== null &&
            $applicationVersionId !== null
        ) {
            $this->validateApplicationVersion(
                $applicationId,
                $applicationVersionId
            );
        }

        return $this->tutorialNodeRepository->getAll($applicationId, $applicationVersionId);
    }

    public function getTree(
        int $applicationId,
        int $applicationVersionId
    ): Collection {
        $this->validateApplicationVersion(
            $applicationId,
            $applicationVersionId
        );

        return $this->tutorialNodeRepository->getTree($applicationId, $applicationVersionId);
    }

    public function find(
        TutorialNode $tutorialNode
    ): TutorialNode {
        return $tutorialNode->load([
            'application:id,name,slug',
            'applicationVersion:id,application_id,version_number',
            'parent:id,title,application_id,application_version_id',
            'children',
        ]);
    }

    public function create(
        array $data
    ): TutorialNode {
        $this->validateRelations(
            $data
        );

        return DB::transaction(
            function () use ($data): TutorialNode {
                $data['slug'] =
                    $this->makeSlug(
                        $data['title'],
                        $data['slug'] ?? null
                    );

                $data['sort_order'] =
                    $data['sort_order'] ?? 0;

                $tutorialNode =
                    $this->tutorialNodeRepository->create(
                        $data
                    );

                return $this->find(
                    $tutorialNode
                );
            }
        );
    }

    public function update(
        TutorialNode $tutorialNode,
        array $data
    ): TutorialNode {
        $mergedData = [
            'application_id' =>
                $data['application_id']
                ?? $tutorialNode->application_id,

            'application_version_id' =>
                $data['application_version_id']
                ?? $tutorialNode->application_version_id,

            'parent_id' =>
                array_key_exists(
                    'parent_id',
                    $data
                )
                    ? $data['parent_id']
                    : $tutorialNode->parent_id,

            'title' =>
                $data['title']
                ?? $tutorialNode->title,

            'slug' =>
                array_key_exists(
                    'slug',
                    $data
                )
                    ? $data['slug']
                    : $tutorialNode->slug,

            'description' =>
                array_key_exists(
                    'description',
                    $data
                )
                    ? $data['description']
                    : $tutorialNode->description,

            'node_type' =>
                $data['node_type']
                ?? $tutorialNode->node_type,

            'sort_order' =>
                $data['sort_order']
                ?? $tutorialNode->sort_order,

            'status' =>
                $data['status']
                ?? $tutorialNode->status,

            'is_public' =>
                $data['is_public']
                ?? $tutorialNode->is_public,
        ];

        $this->validateRelations(
            $mergedData,
            $tutorialNode
        );

        $this->validateChildrenRemainCompatible(
            $tutorialNode,
            (int) $mergedData['application_id'],
            (int) $mergedData['application_version_id']
        );

        $this->validateNodeTypeChange(
            $tutorialNode,
            (string) $mergedData['node_type']
        );

        return DB::transaction(
            function () use (
                $tutorialNode,
                $data
            ): TutorialNode {
                if (
                    array_key_exists(
                        'title',
                        $data
                    ) ||
                    array_key_exists(
                        'slug',
                        $data
                    )
                ) {
                    $title =
                        $data['title']
                        ?? $tutorialNode->title;

                    $slug =
                        array_key_exists(
                            'slug',
                            $data
                        )
                            ? $data['slug']
                            : $tutorialNode->slug;

                    $data['slug'] =
                        $this->makeSlug(
                            $title,
                            $slug
                        );
                }

                $originalStatus = $tutorialNode->status;
                $originalIsPublic = $tutorialNode->is_public;

                $tutorialNode->update(
                    $data
                );

                $statusChanged = array_key_exists('status', $data) && $data['status'] !== $originalStatus;
                $isPublicChanged = array_key_exists('is_public', $data) && $data['is_public'] !== $originalIsPublic;

                if ($statusChanged || $isPublicChanged) {
                    $this->updateDescendantsVisibility(
                        $tutorialNode,
                        $tutorialNode->status,
                        (bool) $tutorialNode->is_public
                    );
                }

                return $this->find(
                    $tutorialNode->refresh()
                );
            }
        );
    }

    public function delete(
        TutorialNode $tutorialNode
    ): void {
        DB::transaction(
            function () use (
                $tutorialNode
            ): void {
                $this->deleteNodeRecursively(
                    $tutorialNode
                );
            }
        );
    }

    public function copy(
        int $sourceNodeId,
        int $destinationVersionId,
        ?int $destinationParentId = null,
        ?string $newTitle = null,
        bool $includeChildren = false
    ): TutorialNode {
        try {
            return DB::transaction(
                function () use (
                    $sourceNodeId,
                    $destinationVersionId,
                    $destinationParentId,
                    $newTitle,
                    $includeChildren
                ): TutorialNode {
                    $destinationVersion = ApplicationVersion::query()
                        ->findOrFail($destinationVersionId);

                    $sourceNode = TutorialNode::with([
                        'childrenRecursive',
                        'contentBlocks',
                    ])->findOrFail($sourceNodeId);

                    $parent = null;

                    if ($destinationParentId !== null) {
                        $parent = TutorialNode::findOrFail(
                            $destinationParentId
                        );
                    }

                    $newNode = TutorialNode::create([
                        'application_id'           => $destinationVersion->application_id,
                        'application_version_id'   => $destinationVersionId,
                        'parent_id'                => $parent?->id,
                        'title'                    => $newTitle ?? $sourceNode->title,
                        'slug'                     => Str::slug($newTitle ?? $sourceNode->title),
                        'description'              => $sourceNode->description,
                        'node_type'                => $sourceNode->node_type,
                        'sort_order'               => $parent
                            ? $this->getNextSortOrderForParent(
                                $parent->id,
                                $destinationVersionId
                            )
                            : $this->getNextRootSortOrder(
                                $destinationVersion->application_id,
                                $destinationVersionId
                            ),
                        'status'                   => 'draft',
                        'is_public'                => false,
                    ]);

                    $this->copyContentBlocks(
                        $sourceNode,
                        $newNode
                    );

                    if ($includeChildren && $sourceNode->childrenRecursive->isNotEmpty()) {
                        $childCount = $sourceNode->childrenRecursive->count();
                        \Log::info("Copying {$childCount} children for node {$sourceNodeId}", [
                            'parent_id' => $newNode->id,
                            'child_ids' => $sourceNode->childrenRecursive->pluck('id')->toArray(),
                        ]);
                        $this->copyChildrenRecursive(
                            $sourceNode->childrenRecursive,
                            $newNode->id,
                            $destinationVersionId,
                            $destinationVersion->application_id
                        );

                        // Reload children to include in response
                        $newNode->load('childrenRecursive');
                    }

                    return $this->find($newNode->load([
                        'application:id,name,slug',
                        'applicationVersion:id,application_id,version_number',
                        'parent:id,title',
                        'childrenRecursive',
                    ]));
                }
            );
        } catch (\Exception $e) {
            \Log::error('TutorialNode copy failed', [
                'source_node_id' => $sourceNodeId,
                'destination_version_id' => $destinationVersionId,
                'destination_parent_id' => $destinationParentId,
                'new_title' => $newTitle,
                'include_children' => $includeChildren,
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }

    /**
     * Recursively copy children of a node to the new parent.
     */
    private function copyChildrenRecursive(
        \Illuminate\Database\Eloquent\Collection $children,
        int $newParentId,
        int $destinationVersionId,
        int $destinationApplicationId
    ): void {
        $currentSortOrder = $this->getNextSortOrderForParent(
            $newParentId,
            $destinationVersionId
        );

        foreach ($children as $child) {
            $newChild = TutorialNode::create([
                'application_id'           => $destinationApplicationId,
                'application_version_id'   => $destinationVersionId,
                'parent_id'                => $newParentId,
                'title'                    => $child->title,
                'slug'                     => $child->slug,
                'description'              => $child->description,
                'node_type'                => $child->node_type,
                'sort_order'               => $currentSortOrder++,
                'status'                   => 'draft',
                'is_public'                => false,
            ]);

            $this->copyContentBlocks(
                $child,
                $newChild
            );

            // Recursively copy this child's children
            if ($child->childrenRecursive->isNotEmpty()) {
                $this->copyChildrenRecursive(
                    $child->childrenRecursive,
                    $newChild->id,
                    $destinationVersionId,
                    $destinationApplicationId
                );
            }
        }
    }

    /**
     * Copy content blocks from a source node to a newly created node.
     */
    private function copyContentBlocks(
        TutorialNode $sourceNode,
        TutorialNode $newNode
    ): void {
        if ($sourceNode->node_type !== TutorialNode::TYPE_MATERI) {
            return;
        }

        $sourceNode->loadMissing('contentBlocks');

        foreach ($sourceNode->contentBlocks as $block) {
            TutorialContentBlock::create([
                'tutorial_node_id'    => $newNode->id,
                'block_type'          => $block->block_type,
                'title'               => $block->title,
                'content'             => $block->content,
                'file_path'           => $block->file_path,
                'original_file_name'  => $block->original_file_name,
                'file_size'           => $block->file_size,
                'mime_type'           => $block->mime_type,
                'external_url'        => $block->external_url,
                'caption'             => $block->caption,
                'alt_text'            => $block->alt_text,
                'sort_order'          => $block->sort_order,
                'metadata'            => $block->metadata,
            ]);
        }

        \Log::info('Copied content blocks for node', [
            'source_node_id' => $sourceNode->id,
            'new_node_id'    => $newNode->id,
            'block_count'    => $sourceNode->contentBlocks->count(),
        ]);
    }

    private function deleteNodeRecursively(
        TutorialNode $tutorialNode
    ): void {
        $children = $tutorialNode
            ->children()
            ->get();

        foreach ($children as $child) {
            $this->deleteNodeRecursively(
                $child
            );
        }

        $tutorialNode->delete();
    }

    private function validateRelations(
        array $data,
        ?TutorialNode $currentNode = null
    ): void {
        $applicationId =
            (int) $data['application_id'];

        $applicationVersionId =
            (int) $data['application_version_id'];

        $parentId =
            $data['parent_id'] ?? null;

        $nodeType =
            (string) $data['node_type'];

        $this->validateApplicationVersion(
            $applicationId,
            $applicationVersionId
        );

        $this->validateNodeTypeHierarchy(
            $parentId,
            $nodeType
        );

        if ($parentId === null) {
            return;
        }

        $parent = $this->tutorialNodeRepository->find($parentId);

        if (!$parent) {
            throw ValidationException::withMessages([
                'parent_id' => [
                    'Parent materi tidak ditemukan.',
                ],
            ]);
        }

        if (
            $currentNode &&
            (int) $parent->id ===
                (int) $currentNode->id
        ) {
            throw ValidationException::withMessages([
                'parent_id' => [
                    'Node tidak dapat menjadi parent untuk dirinya sendiri.',
                ],
            ]);
        }

        if (
            (int) $parent->application_id !==
            $applicationId
        ) {
            throw ValidationException::withMessages([
                'parent_id' => [
                    'Parent materi harus berasal dari aplikasi yang sama.',
                ],
            ]);
        }

        if (
            (int) $parent->application_version_id !==
            $applicationVersionId
        ) {
            throw ValidationException::withMessages([
                'parent_id' => [
                    'Parent materi harus berasal dari versi aplikasi yang sama.',
                ],
            ]);
        }

        if (
            $currentNode &&
            $this->isDescendant(
                $parent,
                (int) $currentNode->id
            )
        ) {
            throw ValidationException::withMessages([
                'parent_id' => [
                    'Node tidak dapat dipindahkan ke salah satu turunannya.',
                ],
            ]);
        }
    }

    private function validateNodeTypeHierarchy(
        ?int $parentId,
        string $nodeType
    ): void {
        if (
            $parentId === null &&
            $nodeType !==
                TutorialNode::TYPE_KATEGORI
        ) {
            throw ValidationException::withMessages([
                'node_type' => [
                    'Materi utama wajib menggunakan jenis Kategori.',
                ],
            ]);
        }

        if (
            $parentId !== null &&
            $nodeType ===
                TutorialNode::TYPE_KATEGORI
        ) {
            throw ValidationException::withMessages([
                'node_type' => [
                    'Kategori hanya dapat digunakan sebagai materi utama dan tidak dapat ditambahkan sebagai child.',
                ],
            ]);
        }

        if (
            $parentId !== null &&
            !in_array(
                $nodeType,
                [
                    TutorialNode::TYPE_BAGIAN,
                    TutorialNode::TYPE_MATERI,
                ],
                true
            )
        ) {
            throw ValidationException::withMessages([
                'node_type' => [
                    'Child hanya boleh menggunakan jenis Bagian atau Materi.',
                ],
            ]);
        }
    }

    private function validateApplicationVersion(
        int $applicationId,
        int $applicationVersionId
    ): ApplicationVersion {
        $applicationVersion =
            ApplicationVersion::query()
                ->whereKey(
                    $applicationVersionId
                )
                ->where(
                    'application_id',
                    $applicationId
                )
                ->first();

        if (!$applicationVersion) {
            throw ValidationException::withMessages([
                'application_version_id' => [
                    'Versi aplikasi tidak berasal dari aplikasi yang dipilih.',
                ],
            ]);
        }

        return $applicationVersion;
    }

    private function validateChildrenRemainCompatible(
        TutorialNode $tutorialNode,
        int $applicationId,
        int $applicationVersionId
    ): void {
        $applicationChanged =
            (int) $tutorialNode->application_id !==
            $applicationId;

        $versionChanged =
            (int) $tutorialNode->application_version_id !==
            $applicationVersionId;

        if (
            !$applicationChanged &&
            !$versionChanged
        ) {
            return;
        }

        if (
            $tutorialNode
                ->children()
                ->exists()
        ) {
            throw ValidationException::withMessages([
                'application_version_id' => [
                    'Aplikasi atau versi node tidak dapat diubah karena node masih memiliki child.',
                ],
            ]);
        }
    }

    private function validateNodeTypeChange(
        TutorialNode $tutorialNode,
        string $newNodeType
    ): void {
        $nodeTypeChanged =
            $tutorialNode->node_type !==
            $newNodeType;

        if (!$nodeTypeChanged) {
            return;
        }

        if (
            $tutorialNode
                ->children()
                ->exists()
        ) {
            throw ValidationException::withMessages([
                'node_type' => [
                    'Jenis materi tidak dapat diubah karena node masih memiliki child.',
                ],
            ]);
        }
    }

    private function isDescendant(
        TutorialNode $candidateParent,
        int $currentNodeId
    ): bool {
        $node = $candidateParent;

        while ($node->parent_id !== null) {
            if (
                (int) $node->parent_id ===
                $currentNodeId
            ) {
                return true;
            }

            $node = $this->tutorialNodeRepository->find(
                $node->parent_id
            );

            if (!$node) {
                break;
            }
        }

        return false;
    }

    private function makeSlug(
        string $title,
        ?string $slug
    ): string {
        if ($slug) {
            return Str::slug($slug);
        }
        return Str::slug($title);
    }

    private function updateDescendantsVisibility(
        TutorialNode $node,
        string $status,
        bool $isPublic
    ): void {
        $node->load('children');
        
        foreach ($node->children as $child) {
            $child->update([
                'status' => $status,
                'is_public' => $isPublic,
            ]);
            
            $this->updateDescendantsVisibility(
                $child,
                $status,
                $isPublic
            );
        }
    }

    private function getNextSortOrderForParent(
        int $parentId,
        int $applicationVersionId
    ): int {
        return (int) TutorialNode::query()
            ->where('parent_id', $parentId)
            ->where('application_version_id', $applicationVersionId)
            ->max('sort_order') + 1;
    }

    private function getNextRootSortOrder(
        int $applicationId,
        int $applicationVersionId
    ): int {
        return (int) TutorialNode::query()
            ->roots()
            ->where('application_id', $applicationId)
            ->where('application_version_id', $applicationVersionId)
            ->max('sort_order') + 1;
    }
}