<?php

namespace App\Services\Admin;

use App\Models\ApplicationVersion;
use App\Models\TutorialNode;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class TutorialNodeService
{
    public function getAll(?int $applicationId = null): Collection
    {
        return TutorialNode::query()
            ->with([
                'application:id,name,slug',
                'applicationVersion:id,application_id,version_number',
                'parent:id,title',
            ])
            ->when(
                $applicationId,
                fn ($query) => $query->where(
                    'application_id',
                    $applicationId
                )
            )
            ->orderBy('application_id')
            ->orderBy('parent_id')
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get();
    }

    public function getTree(?int $applicationId = null): Collection
    {
        return TutorialNode::query()
            ->roots()
            ->when(
                $applicationId,
                fn ($query) => $query->where(
                    'application_id',
                    $applicationId
                )
            )
            ->with([
                'application:id,name,slug',
                'applicationVersion:id,application_id,version_number',
                'childrenRecursive',
            ])
            ->ordered()
            ->get();
    }

    public function find(TutorialNode $tutorialNode): TutorialNode
    {
        return $tutorialNode->load([
            'application:id,name,slug',
            'applicationVersion:id,application_id,version_number',
            'parent:id,title',
            'children',
        ]);
    }

    public function create(array $data): TutorialNode
    {
        $this->validateRelations($data);

        return DB::transaction(function () use ($data): TutorialNode {
            $data['slug'] = $this->makeSlug(
                $data['title'],
                $data['slug'] ?? null
            );

            $data['sort_order'] = $data['sort_order'] ?? 0;

            $tutorialNode = TutorialNode::create($data);

            return $this->find($tutorialNode);
        });
    }

    public function update(
        TutorialNode $tutorialNode,
        array $data
    ): TutorialNode {
        $mergedData = array_merge(
            $tutorialNode->toArray(),
            $data
        );

        $this->validateRelations(
            $mergedData,
            $tutorialNode
        );

        return DB::transaction(
            function () use (
                $tutorialNode,
                $data
            ): TutorialNode {
                if (
                    array_key_exists('title', $data) ||
                    array_key_exists('slug', $data)
                ) {
                    $title = $data['title']
                        ?? $tutorialNode->title;

                    $slug = $data['slug']
                        ?? $tutorialNode->slug;

                    $data['slug'] = $this->makeSlug(
                        $title,
                        $slug
                    );
                }

                $tutorialNode->update($data);

                return $this->find(
                    $tutorialNode->refresh()
                );
            }
        );
    }

    public function delete(
        TutorialNode $tutorialNode
    ): void {
        if ($tutorialNode->children()->exists()) {
            throw ValidationException::withMessages([
                'tutorial_node' => [
                    'Node tidak dapat dihapus karena masih memiliki child node.',
                ],
            ]);
        }

        $tutorialNode->delete();
    }

    private function validateRelations(
        array $data,
        ?TutorialNode $currentNode = null
    ): void {
        $applicationId = (int) $data['application_id'];

        $parentId = $data['parent_id'] ?? null;

        $versionId =
            $data['application_version_id'] ?? null;

        if ($parentId !== null) {
            $parent = TutorialNode::query()
                ->findOrFail($parentId);

            if (
                $currentNode &&
                $parent->id === $currentNode->id
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
                        'Parent node harus berasal dari aplikasi yang sama.',
                    ],
                ]);
            }

            if (
                $currentNode &&
                $this->isDescendant(
                    $parent,
                    $currentNode->id
                )
            ) {
                throw ValidationException::withMessages([
                    'parent_id' => [
                        'Node tidak dapat dipindahkan ke salah satu turunannya.',
                    ],
                ]);
            }
        }

        if ($versionId !== null) {
            $applicationVersion =
                ApplicationVersion::query()
                    ->findOrFail($versionId);

            if (
                (int) $applicationVersion->application_id !==
                $applicationId
            ) {
                throw ValidationException::withMessages([
                    'application_version_id' => [
                        'Versi aplikasi harus berasal dari aplikasi yang sama.',
                    ],
                ]);
            }
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

            $node = TutorialNode::query()
                ->find($node->parent_id);

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
        return Str::slug(
            $slug ?: $title
        );
    }
}