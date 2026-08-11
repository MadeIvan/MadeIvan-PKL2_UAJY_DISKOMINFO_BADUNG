<?php

namespace App\Admin\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TutorialContentBlock\ReorderTutorialContentBlockRequest;
use App\Http\Requests\Admin\TutorialContentBlock\StoreTutorialContentBlockRequest;
use App\Http\Requests\Admin\TutorialContentBlock\UpdateTutorialContentBlockRequest;
use App\Models\TutorialContentBlock;
use App\Models\TutorialNode;
use App\Services\Admin\TutorialContentBlockService;
use Illuminate\Http\JsonResponse;
use Throwable;

class TutorialContentBlockController extends Controller
{
    private TutorialContentBlockService $service;

    public function __construct(TutorialContentBlockService $service)
    {
        $this->service = $service;
    }

    public function index(TutorialNode $tutorialNode): JsonResponse
    {
        $tutorialNode->load([
            'application:id,name',
            'applicationVersion:id,application_id,version_number',
            'parent:id,title',
            'contentBlocks',
        ]);

        return response()->json([
            'message' => 'Konten materi berhasil diambil.',
            'data' => [
                'tutorial_node' => $tutorialNode,
                'blocks' => $tutorialNode->contentBlocks,
            ],
        ]);
    }

    public function store(
        StoreTutorialContentBlockRequest $request,
        TutorialNode $tutorialNode
    ): JsonResponse {
        try {
            $block = $this->service->create(
                $tutorialNode,
                $request->safe()->except('file'),
                $request->file('file')
            );

            return response()->json([
                'message' => 'Blok konten berhasil ditambahkan.',
                'data' => $block,
            ], 201);
        } catch (Throwable $error) {
            report($error);

            throw $error;
        }
    }

    public function update(
        UpdateTutorialContentBlockRequest $request,
        TutorialContentBlock $tutorialContentBlock
    ): JsonResponse {
        try {
            $block = $this->service->update(
                $tutorialContentBlock,
                $request->safe()->except('file'),
                $request->file('file')
            );

            return response()->json([
                'message' => 'Blok konten berhasil diperbarui.',
                'data' => $block,
            ]);
        } catch (Throwable $error) {
            report($error);

            throw $error;
        }
    }

    public function destroy(
        TutorialContentBlock $tutorialContentBlock
    ): JsonResponse {
        try {
            $this->service->delete(
                $tutorialContentBlock
            );

            return response()->json([
                'message' => 'Blok konten berhasil dihapus.',
            ]);
        } catch (Throwable $error) {
            report($error);

            throw $error;
        }
    }

    public function reorder(
        ReorderTutorialContentBlockRequest $request,
        TutorialNode $tutorialNode
    ): JsonResponse {
        $this->service->reorder(
            $tutorialNode,
            $request->validated('blocks')
        );

        return response()->json([
            'message' => 'Urutan blok berhasil diperbarui.',
        ]);
    }
}