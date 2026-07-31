<?php

namespace App\Services\Admin;

use App\Models\TutorialContentBlock;
use App\Models\TutorialNode;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class TutorialContentBlockService
{
    public function create(
        TutorialNode $tutorialNode,
        array $data,
        ?UploadedFile $file = null
    ): TutorialContentBlock {
        $this->ensureNodeCanContainContent($tutorialNode);

        return DB::transaction(function () use (
            $tutorialNode,
            $data,
            $file
        ): TutorialContentBlock {
            $data['tutorial_node_id'] = $tutorialNode->id;

            if (!isset($data['sort_order'])) {
                $data['sort_order'] = (
                    (int) $tutorialNode
                        ->contentBlocks()
                        ->max('sort_order')
                ) + 1;
            }

            if ($file) {
                $data = array_merge(
                    $data,
                    $this->storeFile(
                        $tutorialNode,
                        $data['block_type'],
                        $file
                    )
                );
            }

            return TutorialContentBlock::create($data);
        });
    }

    public function update(
        TutorialContentBlock $block,
        array $data,
        ?UploadedFile $file = null
    ): TutorialContentBlock {
        return DB::transaction(function () use (
            $block,
            $data,
            $file
        ): TutorialContentBlock {
            $blockType = $data['block_type'] ?? $block->block_type;

            if ($file) {
                $this->deleteStoredFile($block);

                $data = array_merge(
                    $data,
                    $this->storeFile(
                        $block->tutorialNode,
                        $blockType,
                        $file
                    )
                );
            }

            $this->clearUnusedFields(
                $blockType,
                $data
            );

            $block->update($data);

            return $block->refresh();
        });
    }

    public function delete(
        TutorialContentBlock $block
    ): void {
        DB::transaction(function () use ($block): void {
            $this->deleteStoredFile($block);
            $block->delete();
        });
    }

    public function reorder(
        TutorialNode $tutorialNode,
        array $blocks
    ): void {
        DB::transaction(function () use (
            $tutorialNode,
            $blocks
        ): void {
            $requestedIds = collect($blocks)
                ->pluck('id')
                ->map(fn ($id) => (int) $id)
                ->values();

            $ownedIds = $tutorialNode
                ->contentBlocks()
                ->whereIn('id', $requestedIds)
                ->pluck('id')
                ->map(fn ($id) => (int) $id)
                ->values();

            if (
                $requestedIds->sort()->values()->all() !==
                $ownedIds->sort()->values()->all()
            ) {
                throw ValidationException::withMessages([
                    'blocks' => [
                        'Terdapat blok yang bukan milik materi ini.',
                    ],
                ]);
            }

            foreach ($blocks as $item) {
                TutorialContentBlock::query()
                    ->where('id', $item['id'])
                    ->where(
                        'tutorial_node_id',
                        $tutorialNode->id
                    )
                    ->update([
                        'sort_order' => $item['sort_order'],
                    ]);
            }
        });
    }

    private function ensureNodeCanContainContent(
        TutorialNode $tutorialNode
    ): void {
        if (
            !in_array(
                $tutorialNode->node_type,
                ['tutorial', 'step'],
                true
            )
        ) {
            throw ValidationException::withMessages([
                'tutorial_node' => [
                    'Konten hanya dapat ditambahkan pada node berjenis tutorial atau langkah.',
                ],
            ]);
        }
    }

    private function storeFile(
        TutorialNode $tutorialNode,
        string $blockType,
        UploadedFile $file
    ): array {
        $folder = $blockType === 'image'
            ? 'tutorials/images'
            : 'tutorials/pdfs';

        $path = $file->store(
            "{$folder}/{$tutorialNode->id}",
            'public'
        );

        return [
            'file_path' => $path,
            'original_file_name' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
        ];
    }

    private function deleteStoredFile(
        TutorialContentBlock $block
    ): void {
        if (
            $block->file_path &&
            Storage::disk('public')->exists($block->file_path)
        ) {
            Storage::disk('public')->delete($block->file_path);
        }
    }

    private function clearUnusedFields(
        string $blockType,
        array &$data
    ): void {
        if ($blockType === 'text') {
            $data['external_url'] = null;
        }

        if ($blockType === 'youtube') {
            $data['content'] = null;
        }

        if (!in_array($blockType, ['image', 'pdf'], true)) {
            $data['file_path'] = null;
            $data['original_file_name'] = null;
            $data['file_size'] = null;
            $data['mime_type'] = null;
        }
    }
}