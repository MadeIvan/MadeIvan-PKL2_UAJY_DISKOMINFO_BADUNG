<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\ApplicationVersion;
use App\Models\TutorialNode;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TutorialContentPageController extends Controller
{
    /**
     * Membuka halaman editor content block.
     */
    public function edit(TutorialNode $tutorialNode): View
    {
        $this->ensureMaterialNode($tutorialNode);

        return view('Admin.materi.content', [
            'tutorialNode' => $tutorialNode->id,
        ]);
    }

    /**
     * Membuka full preview untuk admin.
     *
     * Preview admin tetap dapat membuka materi yang masih
     * berstatus draf, diarsipkan, atau belum publik.
     */
    public function preview(TutorialNode $tutorialNode): View
    {
        $this->ensureMaterialNode($tutorialNode);

        $this->loadMaterialRelations($tutorialNode);

        return view('Admin.materi.preview', [
            'tutorialNode' => $tutorialNode,
        ]);
    }

    /**
     * Membuka materi publik melalui halaman dokumentasi aplikasi.
     *
     * Dengan cara ini pengguna selalu mendapatkan:
     * - sidebar
     * - version selector
     * - struktur kategori / bagian / materi
     * - content block
     * - previous / next navigation
     */
    public function publicShow(TutorialNode $tutorialNode): RedirectResponse
    {
        $this->ensureMaterialNode($tutorialNode);

        $user = Auth::user();
        $isInternal = $user && ($user->hasRole('Admin') || $user->hasRole('Pegawai'));

        // Check if the node is visible by checking if it exists with the appropriate scope
        $isVisible = TutorialNode::query()
            ->whereKey($tutorialNode->id)
            ->when($isInternal, function (Builder $query) {
                $query->visibleToInternal();
            }, function (Builder $query) {
                $query->visibleToPublic();
            })
            ->exists();

        abort_unless(
            $isVisible,
            Response::HTTP_NOT_FOUND
        );

        $tutorialNode->load([
            'application:id,name,slug,status,is_public',
            'applicationVersion:id,application_id,version_number,status',
        ]);

        abort_unless(
            $tutorialNode->application &&
            $tutorialNode->application->status === 'active' &&
            ($isInternal || (bool) $tutorialNode->application->is_public),
            Response::HTTP_NOT_FOUND
        );

        abort_unless(
            $tutorialNode->applicationVersion &&
            ($isInternal || $tutorialNode->applicationVersion->status !== 'draft'),
            Response::HTTP_NOT_FOUND
        );

        return redirect()->route('applications.show', [
            'application' => $tutorialNode->application->slug,
            'version' => $tutorialNode->applicationVersion->id,
            'materi' => $tutorialNode->id,
        ]);
    }

    /**
     * Membuka full preview untuk keseluruhan aplikasi (Admin).
     */
    public function previewApp(Application $application, ApplicationVersion $version, ?TutorialNode $materi = null): View
    {
        $nodes = TutorialNode::query()
            ->where('application_id', $application->id)
            ->where('application_version_id', $version->id)
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

        $tutorialTree = $this->buildTree($nodes);
        $materialIds = $this->flattenMaterialIds($tutorialTree);

        if ($materi) {
            $selectedMaterial = TutorialNode::query()
                ->whereKey($materi->id)
                ->where('application_id', $application->id)
                ->where('application_version_id', $version->id)
                ->where('node_type', TutorialNode::TYPE_MATERI)
                ->with([
                    'application:id,name,slug',
                    'applicationVersion:id,application_id,version_number',
                    'parent:id,title,slug',
                    'contentBlocks' => function ($query): void {
                        $query->orderBy('sort_order')->orderBy('id');
                    },
                ])
                ->first();
        } else {
            $selectedMaterial = null;
        }

        $previousMaterial = null;
        $nextMaterial = null;

        if ($selectedMaterial) {
            $currentIndex = array_search((int) $selectedMaterial->id, $materialIds, true);
            if ($currentIndex !== false) {
                $previousId = $materialIds[$currentIndex - 1] ?? null;
                $nextId = $materialIds[$currentIndex + 1] ?? null;
                $nodesById = $nodes->keyBy(fn (TutorialNode $node): int => (int) $node->id);
                
                $previousMaterial = $previousId ? $nodesById->get($previousId) : null;
                $nextMaterial = $nextId ? $nodesById->get($nextId) : null;
            }
        }

        return view('Admin.materi.preview_app', [
            'application' => $application,
            'selectedVersion' => $version,
            'tutorialTree' => $tutorialTree,
            'selectedMaterial' => $selectedMaterial,
            'previousMaterial' => $previousMaterial,
            'nextMaterial' => $nextMaterial,
            'hasMaterials' => count($materialIds) > 0,
            'hasNodes' => $nodes->isNotEmpty(),
        ]);
    }

    private function buildTree(\Illuminate\Support\Collection $nodes): array
    {
        if ($nodes->isEmpty()) {
            return [];
        }
        $availableIds = reset($nodes) ? $nodes->pluck('id')->map(fn($id) => (int)$id)->all() : [];
        $availableLookup = array_fill_keys($availableIds, true);
        $childrenByParent = [];
        foreach ($nodes as $node) {
            $parentId = $node->parent_id !== null ? (int) $node->parent_id : null;
            if ($parentId !== null && !isset($availableLookup[$parentId])) {
                continue;
            }
            $key = $parentId === null ? 'root' : (string) $parentId;
            $childrenByParent[$key][] = $node;
        }
        $build = function (string $parentKey) use (&$build, $childrenByParent): array {
            $result = [];
            foreach ($childrenByParent[$parentKey] ?? [] as $node) {
                $result[] = [
                    'id' => (int) $node->id,
                    'title' => $node->title,
                    'slug' => $node->slug,
                    'description' => $node->description,
                    'node_type' => $node->node_type,
                    'sort_order' => (int) $node->sort_order,
                    'status' => $node->status,
                    'is_public' => $node->is_public,
                    'children' => $build((string) $node->id),
                ];
            }
            return $result;
        };
        return $build('root');
    }

    private function flattenMaterialIds(array $tree): array
    {
        $ids = [];
        $walk = function (array $nodes) use (&$walk, &$ids): void {
            foreach ($nodes as $node) {
                if ($node['node_type'] === TutorialNode::TYPE_MATERI) {
                    $ids[] = (int) $node['id'];
                }
                if (!empty($node['children'])) {
                    $walk($node['children']);
                }
            }
        };
        $walk($tree);
        return $ids;
    }

    /**
     * Memuat seluruh data yang dibutuhkan oleh preview admin.
     */
    private function loadMaterialRelations(TutorialNode $tutorialNode): void
    {
        $tutorialNode->load([
            'application:id,name,slug',
            'applicationVersion:id,application_id,version_number',
            'parent:id,title,slug',
            'contentBlocks' => function ($query): void {
                $query->orderBy('sort_order')->orderBy('id');
            },
        ]);
    }

    /**
     * Hanya node berjenis Materi yang dapat memiliki konten.
     */
    private function ensureMaterialNode(TutorialNode $tutorialNode): void
    {
        abort_unless(
            $tutorialNode->isMateri(),
            Response::HTTP_NOT_FOUND
        );
    }
}