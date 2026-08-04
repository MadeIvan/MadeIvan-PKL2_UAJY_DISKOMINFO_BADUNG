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
    public function __construct(
        private readonly TutorialNodeService $service
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'application_id' => [
                'nullable',
                'required_with:application_version_id',
                'integer',
                'exists:applications,id',
            ],

            'application_version_id' => [
                'nullable',
                'required_with:application_id',
                'integer',
                'exists:application_versions,id',
            ],
        ]);

        $applicationId = isset(
            $validated['application_id']
        )
            ? (int) $validated['application_id']
            : null;

        $applicationVersionId = isset(
            $validated['application_version_id']
        )
            ? (int) $validated['application_version_id']
            : null;

        $tutorialNodes = $this->service->getAll(
            $applicationId,
            $applicationVersionId
        );

        return response()->json([
            'message' => 'Data tutorial node berhasil diambil.',
            'data' => $tutorialNodes,
        ]);
    }

    public function tree(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'application_id' => [
                'required',
                'integer',
                'exists:applications,id',
            ],

            'application_version_id' => [
                'required',
                'integer',
                'exists:application_versions,id',
            ],
        ]);

        $tutorialNodes = $this->service->getTree(
            (int) $validated['application_id'],
            (int) $validated['application_version_id']
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
        $this->service->delete(
            $tutorialNode
        );

        return response()->json([
            'message' => 'Tutorial node berhasil dihapus.',
        ]);
    }
}