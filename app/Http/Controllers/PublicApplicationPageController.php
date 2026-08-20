<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\ApplicationVersion;
use App\Models\TutorialNode;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PublicApplicationPageController extends Controller
{
    public function show(
        Request $request,
        Application $application
    ): View {
        $this->ensureApplicationIsPublic(
            $application
        );

        $versions = $this->getApplicationVersions(
            $application
        );

        $preferredVersion =
            $this->getPreferredVersion(
                $versions
            );

        $selectedVersion =
            $this->getSelectedVersion(
                $request,
                $versions,
                $preferredVersion
            );

        $publicNodes = collect();
        $tutorialTree = [];
        $materialIds = [];

        if ($selectedVersion) {
            $publicNodes =
                $this->getPublicNodes(
                    $application,
                    $selectedVersion
                );

            $tutorialTree =
                $this->buildTree(
                    $publicNodes
                );

            $materialIds =
                $this->flattenMaterialIds(
                    $tutorialTree
                );
        }

        $selectedMaterial =
            $this->getSelectedMaterial(
                $request,
                $application,
                $selectedVersion,
                $materialIds
            );

        $previousMaterial = null;
        $nextMaterial = null;

        if ($selectedMaterial) {
            [
                $previousMaterial,
                $nextMaterial,
            ] = $this->getPreviousAndNextMaterial(
                $selectedMaterial,
                $materialIds,
                $publicNodes
            );
        }

        $hasPublicNodes =
            $publicNodes->isNotEmpty();

        $hasMaterials =
            count($materialIds) > 0;

        $isOlderVersion =
            $selectedVersion &&
            $preferredVersion &&
            (int) $selectedVersion->id !==
                (int) $preferredVersion->id;

        return view(
            'Public.application_show',
            [
                'application' =>
                    $application,

                'versions' =>
                    $versions,

                'preferredVersion' =>
                    $preferredVersion,

                'selectedVersion' =>
                    $selectedVersion,

                'tutorialTree' =>
                    $tutorialTree,

                'selectedMaterial' =>
                    $selectedMaterial,

                'previousMaterial' =>
                    $previousMaterial,

                'nextMaterial' =>
                    $nextMaterial,

                'hasPublicNodes' =>
                    $hasPublicNodes,

                'hasMaterials' =>
                    $hasMaterials,

                'isOlderVersion' =>
                    $isOlderVersion,
            ]
        );
    }

    private function ensureApplicationIsPublic(
        Application $application
    ): void {
        $user = Auth::user();
        $isInternal = $user && ($user->hasRole('Admin') || $user->hasRole('Pegawai'));
        
        abort_unless(
            $application->status === 'active' &&
            ($isInternal || (bool) $application->is_public),
            Response::HTTP_NOT_FOUND
        );
    }

    private function getApplicationVersions(
        Application $application
    ): Collection {
        return $application
            ->versions()
            ->orderByDesc('is_current')
            ->orderByDesc('release_date')
            ->orderByDesc('id')
            ->get();
    }

    private function getPreferredVersion(
        Collection $versions
    ): ?ApplicationVersion {
        if ($versions->isEmpty()) {
            return null;
        }

        $current =
            $versions->first(
                fn (ApplicationVersion $version): bool =>
                    (bool) $version->is_current
            );

        if ($current) {
            return $current;
        }

        return $versions
            ->sortByDesc(
                function (
                    ApplicationVersion $version
                ): string {
                    return sprintf(
                        '%s-%020d',
                        $version->release_date
                            ?->format('Y-m-d')
                            ?? '0000-00-00',
                        $version->id
                    );
                }
            )
            ->first();
    }

    private function getSelectedVersion(
        Request $request,
        Collection $versions,
        ?ApplicationVersion $preferredVersion
    ): ?ApplicationVersion {
        if ($versions->isEmpty()) {
            return null;
        }

        $requestedVersionId =
            $request->integer('version');

        if (!$requestedVersionId) {
            return $preferredVersion;
        }

        $selected =
            $versions->first(
                fn (ApplicationVersion $version): bool =>
                    (int) $version->id ===
                    (int) $requestedVersionId
            );

        return $selected ??
            $preferredVersion;
    }

    private function getPublicNodes(
        Application $application,
        ApplicationVersion $version
    ): Collection {
        $query = TutorialNode::query()
            ->where(
                'application_id',
                $application->id
            )
            ->where(
                'application_version_id',
                $version->id
            );
            
        $user = Auth::user();
        $isInternal = $user && ($user->hasRole('Admin') || $user->hasRole('Pegawai'));
        
        if ($isInternal) {
            $query->visibleToInternal();
        } else {
            $query->visibleToPublic();
        }

        return $query
            ->orderBy('sort_order')
            ->orderBy('title')
            ->orderBy('id')
            ->get([
                'id',
                'application_id',
                'application_version_id',
                'parent_id',
                'title',
                'slug',
                'description',
                'node_type',
                'sort_order',
                'status',
                'is_public',
            ]);
    }

    private function buildTree(
        Collection $nodes
    ): array {
        if ($nodes->isEmpty()) {
            return [];
        }

        $availableIds =
            $nodes
                ->pluck('id')
                ->map(
                    fn ($id): int =>
                        (int) $id
                )
                ->all();

        $availableLookup =
            array_fill_keys(
                $availableIds,
                true
            );

        $childrenByParent = [];

        foreach ($nodes as $node) {
            $parentId =
                $node->parent_id !== null
                    ? (int) $node->parent_id
                    : null;

            if (
                $parentId !== null &&
                !isset(
                    $availableLookup[
                        $parentId
                    ]
                )
            ) {
                continue;
            }

            $key =
                $parentId === null
                    ? 'root'
                    : (string) $parentId;

            $childrenByParent[$key][] =
                $node;
        }

        $build =
            function (
                string $parentKey
            ) use (
                &$build,
                $childrenByParent
            ): array {
                $result = [];

                foreach (
                    $childrenByParent[
                        $parentKey
                    ] ?? []
                    as $node
                ) {
                    $result[] = [
                        'id' =>
                            (int) $node->id,

                        'title' =>
                            $node->title,

                        'slug' =>
                            $node->slug,

                        'description' =>
                            $node->description,

                        'node_type' =>
                            $node->node_type,

                        'sort_order' =>
                            (int) $node->sort_order,

                        'children' =>
                            $build(
                                (string) $node->id
                            ),
                    ];
                }

                return $result;
            };

        return $build('root');
    }

    private function flattenMaterialIds(
        array $tree
    ): array {
        $ids = [];

        $walk =
            function (
                array $nodes
            ) use (
                &$walk,
                &$ids
            ): void {
                foreach ($nodes as $node) {
                    if (
                        $node['node_type'] ===
                        TutorialNode::TYPE_MATERI
                    ) {
                        $ids[] =
                            (int) $node['id'];
                    }

                    if (
                        !empty(
                            $node['children']
                        )
                    ) {
                        $walk(
                            $node['children']
                        );
                    }
                }
            };

        $walk($tree);

        return $ids;
    }

    private function getSelectedMaterial(
        Request $request,
        Application $application,
        ?ApplicationVersion $version,
        array $materialIds
    ): ?TutorialNode {
        if (
            !$version ||
            empty($materialIds)
        ) {
            return null;
        }

        $materialId =
            $request->integer('materi');

        if (!$materialId) {
            return null;
        }

        if (
            !in_array(
                $materialId,
                $materialIds,
                true
            )
        ) {
            abort(
                Response::HTTP_NOT_FOUND
            );
        }

        $query = TutorialNode::query()
            ->whereKey($materialId)
            ->where(
                'application_id',
                $application->id
            )
            ->where(
                'application_version_id',
                $version->id
            )
            ->where(
                'node_type',
                TutorialNode::TYPE_MATERI
            );
            
        $user = Auth::user();
        $isInternal = $user && ($user->hasRole('Admin') || $user->hasRole('Pegawai'));
        
        if ($isInternal) {
            $query->visibleToInternal();
        } else {
            $query->visibleToPublic();
        }

        return $query
            ->with([
                'application:id,name,slug',

                'applicationVersion:id,application_id,version_number',

                'parent:id,title,slug',

                'contentBlocks' =>
                    function (
                        $query
                    ): void {
                        $query
                            ->orderBy(
                                'sort_order'
                            )
                            ->orderBy('id');
                    },
            ])
            ->firstOrFail();
    }

    private function getPreviousAndNextMaterial(
        TutorialNode $selectedMaterial,
        array $materialIds,
        Collection $publicNodes
    ): array {
        $currentIndex =
            array_search(
                (int) $selectedMaterial->id,
                $materialIds,
                true
            );

        if ($currentIndex === false) {
            return [
                null,
                null,
            ];
        }

        $previousId =
            $materialIds[
                $currentIndex - 1
            ] ?? null;

        $nextId =
            $materialIds[
                $currentIndex + 1
            ] ?? null;

        $nodesById =
            $publicNodes->keyBy(
                fn (TutorialNode $node): int =>
                    (int) $node->id
            );

        return [
            $previousId
                ? $nodesById->get(
                    $previousId
                )
                : null,

            $nextId
                ? $nodesById->get(
                    $nextId
                )
                : null,
        ];
    }
}