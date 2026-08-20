<?php

namespace App\Services\Admin;

use App\Models\ApplicationVersion;
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
        ?string $newTitle = null
    ): TutorialNode {
        $sourceNode = TutorialNode::query()
            ->with(['contentBlocks', 'childrenRecursive'])
            ->findOrFail($sourceNodeId);

        $destinationVersion = ApplicationVersion::query()
            ->findOrFail($destinationVersionId);

        return DB::transaction(
            function () use (
                $sourceNode,
                $destinationVersion,
                $destinationParentId,
                $newTitle
            ): TutorialNode {
                return $this->copyNodeRecursively(
                    $sourceNode,
                    $destinationVersion,
                    $destinationParentId,
                    $newTitle
                );
            }
        );
    }

    private function copyNodeRecursively(
        TutorialNode $sourceNode,
        ApplicationVersion $destinationVersion,
        ?int $parentId,
        ?string $newTitle = null
    ): TutorialNode {
        $data = $sourceNode->toArray();
        $data['application_id'] = $destinationVersion->application_id;
        $data['application_version_id'] = $destinationVersion->id;
        $data['parent_id'] = $parentId;
        if ($newTitle !== null) {
            $data['title'] = $newTitle;
        }
        $data['slug'] = $this->makeSlug($data['title'], null);

        $newNode = TutorialNode::create($data);

        foreach ($sourceNode->contentBlocks as $block) {
            $blockData = $block->toArray();
            unset(
                $blockData['id'],
                $blockData['tutorial_node_id'],
                $blockData['created_at'],
                $blockData['updated_at'],
                $blockData['deleted_at']
            );
            $newNode->contentBlocks()->create($blockData);
        }

        foreach ($sourceNode->children as $child) {
            $this->copyNodeRecursively(
                $child,
                $destinationVersion,
                $newNode->id
            );
        }

        return $newNode;
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
}