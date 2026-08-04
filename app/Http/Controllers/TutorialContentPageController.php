<?php

namespace App\Http\Controllers;

use App\Models\TutorialNode;
use Illuminate\Contracts\View\View;
use Symfony\Component\HttpFoundation\Response;

class TutorialContentPageController extends Controller
{
    /**
     * Membuka halaman editor content block.
     */
    public function edit(
        TutorialNode $tutorialNode
    ): View {
        $this->ensureMaterialNode(
            $tutorialNode
        );

        return view(
            'Admin.materi-demo.content',
            [
                'tutorialNode' =>
                    $tutorialNode->id,
            ]
        );
    }

    /**
     * Membuka full preview untuk admin.
     *
     * Preview admin tetap dapat membuka materi yang masih
     * berstatus draf, diarsipkan, atau belum publik.
     */
    public function preview(
        TutorialNode $tutorialNode
    ): View {
        $this->ensureMaterialNode(
            $tutorialNode
        );

        $this->loadMaterialRelations(
            $tutorialNode
        );

        return view(
            'Admin.materi-demo.preview',
            [
                'tutorialNode' =>
                    $tutorialNode,
            ]
        );
    }

    /**
     * Membuka halaman materi untuk pengguna publik.
     *
     * Halaman hanya dapat dibuka ketika materi:
     * - berstatus published
     * - is_public bernilai true
     */
    public function publicShow(
        TutorialNode $tutorialNode
    ): View {
        $this->ensureMaterialNode(
            $tutorialNode
        );

        abort_unless(
            $tutorialNode->status ===
                'published' &&
            (bool) $tutorialNode->is_public,
            Response::HTTP_NOT_FOUND
        );

        $this->loadMaterialRelations(
            $tutorialNode
        );

        return view(
            'Public.materi.show',
            [
                'tutorialNode' =>
                    $tutorialNode,
            ]
        );
    }

    /**
     * Memuat seluruh data yang dibutuhkan oleh preview
     * dan halaman publik.
     */
    private function loadMaterialRelations(
        TutorialNode $tutorialNode
    ): void {
        $tutorialNode->load([
            'application:id,name,slug',

            'applicationVersion:id,application_id,version_number',

            'parent:id,title,slug',

            'contentBlocks' => function ($query): void {
                $query
                    ->orderBy('sort_order')
                    ->orderBy('id');
            },
        ]);
    }

    /**
     * Hanya node berjenis Materi yang dapat memiliki konten.
     */
    private function ensureMaterialNode(
        TutorialNode $tutorialNode
    ): void {
        abort_unless(
            $tutorialNode->isMateri(),
            Response::HTTP_NOT_FOUND
        );
    }
}