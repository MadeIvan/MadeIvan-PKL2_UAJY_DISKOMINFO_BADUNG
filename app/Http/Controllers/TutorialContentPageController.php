<?php

namespace App\Http\Controllers;

use App\Models\TutorialNode;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
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

        abort_unless(
            $tutorialNode->status === 'published' &&
            (bool) $tutorialNode->is_public,
            Response::HTTP_NOT_FOUND
        );

        $tutorialNode->load([
            'application:id,name,slug,status,is_public',
            'applicationVersion:id,application_id,version_number',
        ]);

        abort_unless(
            $tutorialNode->application &&
            $tutorialNode->application->status === 'active' &&
            (bool) $tutorialNode->application->is_public,
            Response::HTTP_NOT_FOUND
        );

        abort_unless(
            $tutorialNode->applicationVersion,
            Response::HTTP_NOT_FOUND
        );

        return redirect()->route('applications.show', [
            'application' => $tutorialNode->application->slug,
            'version' => $tutorialNode->applicationVersion->id,
            'materi' => $tutorialNode->id,
        ]);
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