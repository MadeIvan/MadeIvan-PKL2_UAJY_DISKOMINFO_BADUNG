<?php

namespace App\Services\Admin;

use App\Models\Application;
use App\Models\ApplicationVersion;
use App\Models\TutorialContentBlock;
use App\Models\TutorialNode;
use App\Repositories\ApplicationVersionRepository;
use App\Repositories\TutorialNodeRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Throwable;

class ApplicationVersionService
{
    public function __construct(
        protected ApplicationVersionRepository $applicationVersionRepository,
        protected TutorialNodeRepository $tutorialNodeRepository
    ) {
    }

    public function create(
        Application $application,
        array $data
    ): ApplicationVersion {
        $copiedFilePaths = [];

        try {
            return DB::transaction(
                function () use (
                    $application,
                    $data,
                    &$copiedFilePaths
                ): ApplicationVersion {
                    $isCurrent = (bool) (
                        $data['is_current'] ?? false
                    );

                    if ($isCurrent) {
                        $this->applicationVersionRepository->clearCurrentVersion(
                            $application->id
                        );
                    }

                    $version = $application
                        ->versions()
                        ->create([
                            'version_number' =>
                                $data['version_number'],

                            'release_date' =>
                                $data['release_date'] ?? null,

                            'release_notes' =>
                                $data['release_notes'] ?? null,

                            'status' =>
                                $data['status'],

                            'is_current' =>
                                $isCurrent,
                        ]);

                    $copySummary = [
                        'copied_nodes' => 0,
                        'copied_content_blocks' => 0,
                        'copied_files' => 0,
                    ];

                    if (
                        (bool) (
                            $data['copy_materials']
                            ?? false
                        )
                    ) {
                        $copySummary =
                            $this->duplicateSelectedMaterials(
                                $application,
                                $version,
                                (int) $data['source_version_id'],
                                array_map(
                                    'intval',
                                    $data['selected_node_ids']
                                    ?? []
                                ),
                                $copiedFilePaths
                            );
                    }

                    $freshVersion = $version->fresh([
                        'application',
                    ]);

                    $freshVersion->setAttribute(
                        'copy_summary',
                        $copySummary
                    );

                    return $freshVersion;
                }
            );
        } catch (Throwable $error) {
            foreach ($copiedFilePaths as $path) {
                Storage::disk('public')->delete(
                    $path
                );
            }

            throw $error;
        }
    }

    public function update(
        ApplicationVersion $applicationVersion,
        array $data
    ): ApplicationVersion {
        return DB::transaction(
            function () use (
                $applicationVersion,
                $data
            ): ApplicationVersion {
                $willBecomeCurrent =
                    array_key_exists(
                        'is_current',
                        $data
                    ) &&
                    (bool) $data['is_current'];

                if ($willBecomeCurrent) {
                    $this->applicationVersionRepository->clearCurrentVersion(
                        $applicationVersion->application_id,
                        $applicationVersion->id
                    );
                }

                $applicationVersion->update(
                    $data
                );

                return $applicationVersion->fresh([
                    'application',
                ]);
            }
        );
    }

    public function delete(
        ApplicationVersion $applicationVersion
    ): void {
        DB::transaction(
            function () use (
                $applicationVersion
            ): void {
                $applicationVersion->delete();
            }
        );
    }

    private function duplicateSelectedMaterials(
        Application $application,
        ApplicationVersion $targetVersion,
        int $sourceVersionId,
        array $selectedNodeIds,
        array &$copiedFilePaths
    ): array {
        $sourceVersion =
            $this->applicationVersionRepository->getByIdAndApplication(
                $sourceVersionId,
                $application->id
            );

        if (!$sourceVersion) {
            throw ValidationException::withMessages([
                'source_version_id' => [
                    'Versi sumber harus berasal dari aplikasi yang sama.',
                ],
            ]);
        }

        $selectedNodeIds = collect(
            $selectedNodeIds
        )
            ->map(
                fn ($id): int => (int) $id
            )
            ->filter(
                fn (int $id): bool => $id > 0
            )
            ->unique()
            ->values();

        if ($selectedNodeIds->isEmpty()) {
            throw ValidationException::withMessages([
                'selected_node_ids' => [
                    'Pilih minimal satu materi yang akan disalin.',
                ],
            ]);
        }

        $sourceNodes =
            $this->tutorialNodeRepository->getNodesForVersion(
                $application->id,
                $sourceVersion->id
            );

        $sourceNodesById =
            $sourceNodes->keyBy('id');

        foreach ($selectedNodeIds as $nodeId) {
            if (!$sourceNodesById->has($nodeId)) {
                throw ValidationException::withMessages([
                    'selected_node_ids' => [
                        'Salah satu materi yang dipilih tidak berasal dari versi sumber.',
                    ],
                ]);
            }
        }

        $effectiveSelectedIds =
            $this->expandSelectedDescendants(
                $sourceNodes,
                $selectedNodeIds->all()
            );

        $includedNodeIds =
            $this->includeRequiredAncestors(
                $sourceNodesById,
                $effectiveSelectedIds
            );

        $nodesToCopy =
            $sourceNodes
                ->filter(
                    fn (TutorialNode $node): bool =>
                        in_array(
                            (int) $node->id,
                            $includedNodeIds,
                            true
                        )
                )
                ->sortBy(
                    fn (TutorialNode $node): string =>
                        sprintf(
                            '%05d-%010d',
                            $this->calculateNodeDepth(
                                $node,
                                $sourceNodesById
                            ),
                            $node->id
                        )
                )
                ->values();

        $oldToNewNodeIds = [];
        $copiedContentBlockCount = 0;
        $copiedFileCount = 0;

        foreach ($nodesToCopy as $sourceNode) {
            $newParentId = null;

            if ($sourceNode->parent_id !== null) {
                $newParentId =
                    $oldToNewNodeIds[
                        (int) $sourceNode->parent_id
                    ] ?? null;

                if ($newParentId === null) {
                    throw ValidationException::withMessages([
                        'selected_node_ids' => [
                            'Struktur parent materi tidak dapat dibangun ulang.',
                        ],
                    ]);
                }
            }

            $newNode = TutorialNode::create([
                'application_id' =>
                    $application->id,

                'application_version_id' =>
                    $targetVersion->id,

                'parent_id' =>
                    $newParentId,

                'title' =>
                    $sourceNode->title,

                'slug' =>
                    $sourceNode->slug,

                'description' =>
                    $sourceNode->description,

                'node_type' =>
                    $sourceNode->node_type,

                'sort_order' =>
                    $sourceNode->sort_order,

                'status' =>
                    $sourceNode->status,

                'is_public' =>
                    $sourceNode->is_public,
            ]);

            $oldToNewNodeIds[
                (int) $sourceNode->id
            ] = (int) $newNode->id;

            if (
                !in_array(
                    (int) $sourceNode->id,
                    $effectiveSelectedIds,
                    true
                )
            ) {
                continue;
            }

            foreach (
                $sourceNode->contentBlocks
                as $sourceBlock
            ) {
                $blockData =
                    $this->makeContentBlockCopyData(
                        $sourceBlock,
                        $newNode,
                        $copiedFilePaths
                    );

                TutorialContentBlock::create(
                    $blockData
                );

                $copiedContentBlockCount++;

                if ($blockData['file_path']) {
                    $copiedFileCount++;
                }
            }
        }

        return [
            'copied_nodes' =>
                $nodesToCopy->count(),

            'copied_content_blocks' =>
                $copiedContentBlockCount,

            'copied_files' =>
                $copiedFileCount,
        ];
    }

    private function expandSelectedDescendants(
        Collection $sourceNodes,
        array $selectedNodeIds
    ): array {
        $selected = collect(
            $selectedNodeIds
        )
            ->map(
                fn ($id): int => (int) $id
            )
            ->unique()
            ->values();

        $childrenByParent =
            $sourceNodes
                ->groupBy(
                    fn (TutorialNode $node): int =>
                        (int) (
                            $node->parent_id ?? 0
                        )
                );

        $queue = $selected->all();

        while ($queue !== []) {
            $currentId = array_shift($queue);

            $children =
                $childrenByParent->get(
                    (int) $currentId,
                    collect()
                );

            foreach ($children as $child) {
                $childId = (int) $child->id;

                if ($selected->contains($childId)) {
                    continue;
                }

                $selected->push($childId);
                $queue[] = $childId;
            }
        }

        return $selected
            ->unique()
            ->values()
            ->all();
    }

    private function includeRequiredAncestors(
        Collection $sourceNodesById,
        array $selectedNodeIds
    ): array {
        $included = collect(
            $selectedNodeIds
        )
            ->map(
                fn ($id): int => (int) $id
            )
            ->unique()
            ->values();

        foreach ($selectedNodeIds as $nodeId) {
            $node = $sourceNodesById->get(
                (int) $nodeId
            );

            while (
                $node &&
                $node->parent_id !== null
            ) {
                $parentId =
                    (int) $node->parent_id;

                if (!$included->contains($parentId)) {
                    $included->push($parentId);
                }

                $node =
                    $sourceNodesById->get(
                        $parentId
                    );
            }
        }

        return $included
            ->unique()
            ->values()
            ->all();
    }

    private function calculateNodeDepth(
        TutorialNode $node,
        Collection $sourceNodesById
    ): int {
        $depth = 0;
        $parentId = $node->parent_id;

        while ($parentId !== null) {
            $depth++;

            $parent =
                $sourceNodesById->get(
                    (int) $parentId
                );

            if (!$parent) {
                break;
            }

            $parentId = $parent->parent_id;
        }

        return $depth;
    }

    private function makeContentBlockCopyData(
        TutorialContentBlock $sourceBlock,
        TutorialNode $newNode,
        array &$copiedFilePaths
    ): array {
        $newFilePath = null;

        if ($sourceBlock->file_path) {
            $newFilePath =
                $this->copyStoredFile(
                    $sourceBlock,
                    $newNode
                );

            $copiedFilePaths[] =
                $newFilePath;
        }

        return [
            'tutorial_node_id' =>
                $newNode->id,

            'block_type' =>
                $sourceBlock->block_type,

            'title' =>
                $sourceBlock->title,

            'content' =>
                $sourceBlock->content,

            'file_path' =>
                $newFilePath,

            'original_file_name' =>
                $sourceBlock->original_file_name,

            'file_size' =>
                $sourceBlock->file_size,

            'mime_type' =>
                $sourceBlock->mime_type,

            'external_url' =>
                $sourceBlock->external_url,

            'caption' =>
                $sourceBlock->caption,

            'alt_text' =>
                $sourceBlock->alt_text,

            'sort_order' =>
                $sourceBlock->sort_order,

            'metadata' =>
                $sourceBlock->metadata,
        ];
    }

    private function copyStoredFile(
        TutorialContentBlock $sourceBlock,
        TutorialNode $newNode
    ): string {
        $disk = Storage::disk('public');
        $sourcePath =
            (string) $sourceBlock->file_path;

        if (!$disk->exists($sourcePath)) {
            throw ValidationException::withMessages([
                'selected_node_ids' => [
                    "File sumber tidak ditemukan: {$sourcePath}",
                ],
            ]);
        }

        $folder =
            $sourceBlock->block_type ===
                TutorialContentBlock::TYPE_IMAGE
                ? 'tutorials/images'
                : 'tutorials/pdfs';

        $extension = pathinfo(
            $sourcePath,
            PATHINFO_EXTENSION
        );

        $fileName = (string) Str::uuid();

        if ($extension !== '') {
            $fileName .= ".{$extension}";
        }

        $targetPath =
            "{$folder}/{$newNode->id}/{$fileName}";

        if (
            !$disk->copy(
                $sourcePath,
                $targetPath
            )
        ) {
            throw ValidationException::withMessages([
                'selected_node_ids' => [
                    'Salah satu file materi gagal disalin.',
                ],
            ]);
        }

        return $targetPath;
    }
}
