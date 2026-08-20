<?php

namespace App\Services\Admin;

use App\Models\TutorialContentBlock;
use App\Models\TutorialNode;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

use App\Repositories\TutorialContentBlockRepository;

class TutorialContentBlockService
{
    public function __construct(
        protected TutorialContentBlockRepository $tutorialContentBlockRepository
    ) {
    }

    public function create(
        TutorialNode $tutorialNode,
        array $data,
        ?UploadedFile $file = null
    ): TutorialContentBlock {
        $this->ensureNodeCanContainContent(
            $tutorialNode
        );

        return DB::transaction(
            function () use (
                $tutorialNode,
                $data,
                $file
            ): TutorialContentBlock {
                $data['tutorial_node_id'] =
                    $tutorialNode->id;

                $data['sort_order'] =
                    $this->getNextSortOrder(
                        $tutorialNode
                    );

                $blockType =
                    (string) $data['block_type'];

                if ($file) {
                    $data = array_merge(
                        $data,
                        $this->storeFile(
                            $tutorialNode,
                            $blockType,
                            $file
                        )
                    );
                }

                $this->clearUnusedFields(
                    $blockType,
                    $data
                );

                return $this->tutorialContentBlockRepository->create(
                    $data
                )->refresh();
            }
        );
    }

    public function update(
        TutorialContentBlock $block,
        array $data,
        ?UploadedFile $file = null
    ): TutorialContentBlock {
        $block->loadMissing(
            'tutorialNode'
        );

        $this->ensureNodeCanContainContent(
            $block->tutorialNode
        );

        return DB::transaction(
            function () use (
                $block,
                $data,
                $file
            ): TutorialContentBlock {
                $oldBlockType =
                    $block->block_type;

                $newBlockType =
                    (string) (
                        $data['block_type']
                        ?? $oldBlockType
                    );

                $changedAwayFromFileType =
                    in_array(
                        $oldBlockType,
                        [
                            TutorialContentBlock::TYPE_IMAGE,
                            TutorialContentBlock::TYPE_PDF,
                        ],
                        true
                    ) &&
                    !in_array(
                        $newBlockType,
                        [
                            TutorialContentBlock::TYPE_IMAGE,
                            TutorialContentBlock::TYPE_PDF,
                        ],
                        true
                    );

                $changedFileType =
                    $oldBlockType !==
                        $newBlockType &&
                    in_array(
                        $oldBlockType,
                        [
                            TutorialContentBlock::TYPE_IMAGE,
                            TutorialContentBlock::TYPE_PDF,
                        ],
                        true
                    );

                if (
                    $changedAwayFromFileType ||
                    $changedFileType ||
                    $file
                ) {
                    $this->deleteStoredFile(
                        $block
                    );
                }

                if ($file) {
                    $data = array_merge(
                        $data,
                        $this->storeFile(
                            $block->tutorialNode,
                            $newBlockType,
                            $file
                        )
                    );
                }

                $this->clearUnusedFields(
                    $newBlockType,
                    $data
                );

                unset(
                    $data['sort_order']
                );

                $block->update(
                    $data
                );

                return $block->refresh();
            }
        );
    }

    public function delete(
        TutorialContentBlock $block
    ): void {
        DB::transaction(
            function () use ($block): void {
                $tutorialNodeId =
                    (int) $block->tutorial_node_id;

                $this->deleteStoredFile(
                    $block
                );

                $block->delete();

                $this->normalizeSortOrder(
                    $tutorialNodeId
                );
            }
        );
    }

    public function reorder(
        TutorialNode $tutorialNode,
        array $blocks
    ): void {
        $this->ensureNodeCanContainContent(
            $tutorialNode
        );

        DB::transaction(
            function () use (
                $tutorialNode,
                $blocks
            ): void {
                $orderedBlocks =
                    collect($blocks)
                        ->sortBy('sort_order')
                        ->values();

                $requestedIds =
                    $orderedBlocks
                        ->pluck('id')
                        ->map(
                            fn ($id): int =>
                                (int) $id
                        )
                        ->values();

                $ownedIds =
                    $tutorialNode
                        ->contentBlocks()
                        ->pluck('id')
                        ->map(
                            fn ($id): int =>
                                (int) $id
                        )
                        ->values();

                if (
                    $requestedIds
                        ->sort()
                        ->values()
                        ->all() !==
                    $ownedIds
                        ->sort()
                        ->values()
                        ->all()
                ) {
                    throw ValidationException::withMessages([
                        'blocks' => [
                            'Seluruh blok milik materi harus dikirim saat memperbarui urutan.',
                        ],
                    ]);
                }

                foreach (
                    $orderedBlocks
                    as $index => $item
                ) {
                    $this->tutorialContentBlockRepository->updateSortOrder(
                        (int) $item['id'],
                        $tutorialNode->id,
                        $index
                    );
                }
            }
        );
    }

    private function ensureNodeCanContainContent(
        TutorialNode $tutorialNode
    ): void {
        if (
            $tutorialNode->node_type !==
            TutorialNode::TYPE_MATERI
        ) {
            throw ValidationException::withMessages([
                'tutorial_node' => [
                    'Konten hanya dapat ditambahkan pada node berjenis Materi.',
                ],
            ]);
        }
    }

    private function getNextSortOrder(
        TutorialNode $tutorialNode
    ): int {
        $maximumOrder =
            $this->tutorialContentBlockRepository->getMaxSortOrder($tutorialNode->id);

        if ($maximumOrder === null) {
            return 0;
        }

        return (int) $maximumOrder + 1;
    }

    private function normalizeSortOrder(
        int $tutorialNodeId
    ): void {
        $blocks = $this->tutorialContentBlockRepository->getBlocksForNode($tutorialNodeId);

        foreach (
            $blocks
            as $index => $block
        ) {
            if (
                (int) $block->sort_order ===
                $index
            ) {
                continue;
            }

            $block->update([
                'sort_order' => $index,
            ]);
        }
    }

    private function storeFile(
        TutorialNode $tutorialNode,
        string $blockType,
        UploadedFile $file
    ): array {
        if (
            !in_array(
                $blockType,
                [
                    TutorialContentBlock::TYPE_IMAGE,
                    TutorialContentBlock::TYPE_PDF,
                ],
                true
            )
        ) {
            throw ValidationException::withMessages([
                'file' => [
                    'Jenis blok ini tidak menerima file.',
                ],
            ]);
        }

        $folder =
            $blockType ===
                TutorialContentBlock::TYPE_IMAGE
                ? 'tutorials/images'
                : 'tutorials/pdfs';

        $path =
            $file->store(
                "{$folder}/{$tutorialNode->id}",
                'public'
            );

        return [
            'file_path' =>
                $path,

            'original_file_name' =>
                $file->getClientOriginalName(),

            'file_size' =>
                $file->getSize(),

            'mime_type' =>
                $file->getMimeType(),
        ];
    }

    private function deleteStoredFile(
        TutorialContentBlock $block
    ): void {
        if (!$block->file_path) {
            return;
        }

        if (
            Storage::disk('public')
                ->exists(
                    $block->file_path
                )
        ) {
            Storage::disk('public')
                ->delete(
                    $block->file_path
                );
        }
    }

    private function clearUnusedFields(
        string $blockType,
        array &$data
    ): void {
        if (
            $blockType !==
            TutorialContentBlock::TYPE_YOUTUBE
        ) {
            $data['title'] = null;
            $data['external_url'] = null;
        }

        if (
            $blockType !==
            TutorialContentBlock::TYPE_TEXT
        ) {
            $data['content'] = null;
        }

        if (
            !in_array(
                $blockType,
                [
                    TutorialContentBlock::TYPE_IMAGE,
                    TutorialContentBlock::TYPE_PDF,
                ],
                true
            )
        ) {
            $data['file_path'] = null;
            $data['original_file_name'] = null;
            $data['file_size'] = null;
            $data['mime_type'] = null;
            $data['caption'] = null;
            $data['alt_text'] = null;
        }

        if (
            $blockType ===
            TutorialContentBlock::TYPE_PDF
        ) {
            $data['alt_text'] = null;
        }
    }
}