<?php

namespace App\Admin\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TutorialNode\StoreTutorialNodeRequest;
use App\Http\Requests\Admin\TutorialNode\UpdateTutorialNodeRequest;
use App\Models\TutorialNode;
use App\Services\Admin\TutorialNodeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TutorialNodeController extends Controller
{
    private TutorialNodeService $service;

    public function __construct(
        TutorialNodeService $service
    ) {
        $this->service = $service;
    }

    public function index(Request $request): JsonResponse
    {
        $applicationId = $request->integer(
            'application_id'
        );

        $tutorialNodes = $this->service->getAll(
            $applicationId ?: null
        );

        return response()->json([
            'message' => 'Data tutorial node berhasil diambil.',
            'data' => $tutorialNodes,
        ]);
    }

    public function tree(Request $request): JsonResponse
    {
        $applicationId = $request->integer(
            'application_id'
        );

        $tutorialNodes = $this->service->getTree(
            $applicationId ?: null
        );

        return response()->json([
            'message' => 'Tree tutorial berhasil diambil.',
            'data' => $tutorialNodes,
        ]);
    }

    public function store(
        StoreTutorialNodeRequest $request
    ): JsonResponse {
        $tutorialNode = $this->service->create(
            $request->validated()
        );

        return response()->json([
            'message' => 'Tutorial node berhasil ditambahkan.',
            'data' => $tutorialNode,
        ], 201);
    }

    public function show(
        TutorialNode $tutorialNode
    ): JsonResponse {
        return response()->json([
            'message' => 'Detail tutorial node berhasil diambil.',
            'data' => $this->service->find(
                $tutorialNode
            ),
        ]);
    }

    public function update(
        UpdateTutorialNodeRequest $request,
        TutorialNode $tutorialNode
    ): JsonResponse {
        $updatedTutorialNode =
            $this->service->update(
                $tutorialNode,
                $request->validated()
            );

        return response()->json([
            'message' => 'Tutorial node berhasil diperbarui.',
            'data' => $updatedTutorialNode,
        ]);
    }

    public function destroy(
        TutorialNode $tutorialNode
    ): JsonResponse {
        $this->service->delete($tutorialNode);

        return response()->json([
            'message' => 'Tutorial node berhasil dihapus.',
        ]);
    }
}