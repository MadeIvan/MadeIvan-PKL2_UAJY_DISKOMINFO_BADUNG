<?php

namespace App\Admin\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TutorialNode\CopyTutorialNodeRequest;
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

    public function copy(
        CopyTutorialNodeRequest $request
    ): JsonResponse {
        $validated = $request->validated();

        \Log::info('Copy tutorial node request', [
            'source_node_id' => $validated['source_node_id'],
            'destination_version_id' => $validated['destination_version_id'],
            'destination_parent_id' => $validated['destination_parent_id'] ?? null,
            'new_title' => $validated['new_title'] ?? null,
            'include_children' => (bool) ($validated['include_children'] ?? false),
        ]);

        $tutorialNode = $this->service->copy(
            (int) $validated['source_node_id'],
            (int) $validated['destination_version_id'],
            isset($validated['destination_parent_id']) ? (int) $validated['destination_parent_id'] : null,
            $validated['new_title'] ?? null,
            (bool) ($validated['include_children'] ?? false)
        );

        \Log::info('Copy tutorial node success', [
            'new_node_id' => $tutorialNode->id,
            'parent_id' => $tutorialNode->parent_id,
            'application_version_id' => $tutorialNode->application_version_id,
        ]);

        return response()->json([
            'message' => 'Tutorial node berhasil disalin.',
            'data' => $tutorialNode,
        ], 201);
    }
}