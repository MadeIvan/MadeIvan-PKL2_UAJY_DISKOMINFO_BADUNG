@extends('components.admin.layouts.admin')

@section('title', 'Kelola Isi Materi')
@section('page-title', 'Kelola Isi Materi')

@push('scripts')
    @vite('resources/js/admin/materi/content.js')
@endpush

@section('content')
    <div
        id="content-page"
        data-node-id="{{ $tutorialNode }}"
        class="space-y-6"
    >

        {{-- Page Header --}}
        <section class="border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-col gap-5 p-5 sm:p-6 lg:flex-row lg:items-start lg:justify-between">
                <div class="min-w-0">
                    <a
                        href="{{ route('admin.materi.index') }}"
                        class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 transition hover:text-blue-950"
                    >
                        <i class="bi bi-arrow-left"></i>
                        Kembali ke Struktur Materi
                    </a>

                    <p class="mt-5 text-xs font-bold uppercase tracking-[0.18em] text-blue-900">
                        Editor Konten
                    </p>

                    <h2
                        id="node-title"
                        class="mt-2 text-2xl font-bold text-slate-950"
                    >
                        Memuat materi...
                    </h2>

                    <p
                        id="node-meta"
                        class="mt-2 text-sm leading-6 text-slate-500"
                    >
                        Mohon tunggu.
                    </p>
                </div>

                <div class="flex shrink-0 flex-col gap-3 sm:flex-row">
                    <a
                        href="{{ route(
                            'admin.materi.preview',
                            ['tutorialNode' => $tutorialNode]
                        ) }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex items-center justify-center gap-2 border border-blue-200 bg-blue-50 px-4 py-2.5 text-sm font-semibold text-blue-950 transition hover:bg-blue-100"
                    >
                        <i class="bi bi-eye"></i>
                        Preview Materi
                    </a>

                    <button
                        id="refresh-button"
                        type="button"
                        class="inline-flex items-center justify-center gap-2 border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-50"
                    >
                        <i class="bi bi-arrow-clockwise"></i>
                        Muat Ulang
                    </button>
                </div>
            </div>
        </section>

        {{-- Add Block Toolbar --}}
        <section class="border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <div>
                <h3 class="text-lg font-bold text-slate-950">
                    Tambahkan Blok Konten
                </h3>

                <p class="mt-1 text-sm leading-6 text-slate-500">
                    Susun materi menggunakan teks, gambar, video YouTube, dan dokumen PDF.
                    Blok baru akan ditempatkan pada urutan paling bawah.
                </p>
            </div>

            <div class="mt-5 grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                <button
                    type="button"
                    data-add-type="text"
                    class="add-block-button border border-slate-200 p-4 text-left transition hover:border-blue-300 hover:bg-blue-50 disabled:cursor-not-allowed disabled:opacity-50"
                >
                    <i class="bi bi-type text-xl text-blue-900"></i>

                    <span class="mt-3 block font-semibold text-slate-900">
                        Teks
                    </span>

                    <span class="mt-1 block text-xs leading-5 text-slate-500">
                        Tambahkan paragraf, heading, daftar, tabel, tautan, dan format teks.
                    </span>
                </button>

                <button
                    type="button"
                    data-add-type="image"
                    class="add-block-button border border-slate-200 p-4 text-left transition hover:border-emerald-300 hover:bg-emerald-50 disabled:cursor-not-allowed disabled:opacity-50"
                >
                    <i class="bi bi-image text-xl text-emerald-700"></i>

                    <span class="mt-3 block font-semibold text-slate-900">
                        Gambar
                    </span>

                    <span class="mt-1 block text-xs leading-5 text-slate-500">
                        Upload gambar dengan format JPG, PNG, atau WEBP.
                    </span>
                </button>

                <button
                    type="button"
                    data-add-type="youtube"
                    class="add-block-button border border-slate-200 p-4 text-left transition hover:border-red-300 hover:bg-red-50 disabled:cursor-not-allowed disabled:opacity-50"
                >
                    <i class="bi bi-youtube text-xl text-red-600"></i>

                    <span class="mt-3 block font-semibold text-slate-900">
                        YouTube
                    </span>

                    <span class="mt-1 block text-xs leading-5 text-slate-500">
                        Tambahkan judul dan tautan video YouTube.
                    </span>
                </button>

                <button
                    type="button"
                    data-add-type="pdf"
                    class="add-block-button border border-slate-200 p-4 text-left transition hover:border-amber-300 hover:bg-amber-50 disabled:cursor-not-allowed disabled:opacity-50"
                >
                    <i class="bi bi-file-earmark-pdf text-xl text-red-600"></i>

                    <span class="mt-3 block font-semibold text-slate-900">
                        PDF
                    </span>

                    <span class="mt-1 block text-xs leading-5 text-slate-500">
                        Upload dokumen panduan dalam format PDF.
                    </span>
                </button>
            </div>
        </section>

        {{-- Content Block List --}}
        <section class="border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-col gap-4 border-b border-slate-200 p-5 sm:flex-row sm:items-center sm:justify-between sm:p-6">
                <div>
                    <h3 class="text-lg font-bold text-slate-950">
                        Isi Materi
                    </h3>

                    <p class="mt-1 text-sm leading-6 text-slate-500">
                        Gunakan tombol caret untuk mengubah urutan blok. Setiap perubahan urutan akan langsung disimpan.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <span
                        id="reorder-status"
                        class="hidden items-center gap-2 border border-blue-200 bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-900"
                    >
                        <i class="bi bi-arrow-repeat animate-spin"></i>
                        Menyimpan urutan...
                    </span>

                    <span
                        id="block-count"
                        class="w-fit border border-blue-200 bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-900"
                    >
                        0 blok
                    </span>
                </div>
            </div>

            {{-- Loading State --}}
            <div
                id="loading-state"
                class="px-5 py-16 text-center text-sm text-slate-500"
            >
                <i class="bi bi-arrow-repeat mr-2 inline-block animate-spin text-xl"></i>
                Memuat konten...
            </div>

            {{-- Error State --}}
            <div
                id="error-state"
                class="hidden px-5 py-16 text-center"
            >
                <i class="bi bi-exclamation-triangle text-4xl text-red-400"></i>

                <h3 class="mt-4 text-lg font-bold text-slate-950">
                    Gagal Memuat Konten
                </h3>

                <p
                    id="error-message"
                    class="mt-2 text-sm text-slate-500"
                ></p>

                <button
                    id="retry-button"
                    type="button"
                    class="mt-5 inline-flex items-center justify-center gap-2 border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                >
                    <i class="bi bi-arrow-clockwise"></i>
                    Coba Lagi
                </button>
            </div>

            {{-- Empty State --}}
            <div
                id="empty-state"
                class="hidden px-5 py-16 text-center"
            >
                <i class="bi bi-file-earmark-plus text-4xl text-slate-300"></i>

                <h3 class="mt-4 text-lg font-bold text-slate-950">
                    Belum Ada Blok Konten
                </h3>

                <p class="mt-2 text-sm text-slate-500">
                    Tambahkan blok pertama melalui pilihan di atas.
                </p>
            </div>

            {{-- Blocks Container --}}
            <div
                id="blocks-container"
                class="hidden space-y-4 p-5 sm:p-6"
            ></div>
        </section>
    </div>

    {{-- Block Form Modal --}}
    <div
        id="block-modal"
        class="fixed inset-0 z-[70] hidden items-center justify-center bg-slate-950/60 p-4"
        aria-hidden="true"
    >
        <div class="max-h-[92vh] w-full max-w-4xl overflow-y-auto border border-slate-200 bg-white shadow-2xl">
            {{-- Modal Header --}}
            <div class="sticky top-0 z-20 flex items-start justify-between gap-4 border-b border-slate-200 bg-white p-5 sm:p-6">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-blue-900">
                        Form Blok
                    </p>

                    <h2
                        id="form-title"
                        class="mt-1 text-xl font-bold text-slate-950"
                    >
                        Tambah Blok
                    </h2>

                    <p
                        id="form-description"
                        class="mt-1 text-sm text-slate-500"
                    >
                        Isi informasi blok konten.
                    </p>
                </div>

                <button
                    id="modal-close"
                    type="button"
                    class="flex h-10 w-10 items-center justify-center text-slate-500 transition hover:bg-slate-100"
                    aria-label="Tutup modal"
                >
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            {{-- Modal Form --}}
            <form
                id="block-form"
                class="space-y-6 p-5 sm:p-6"
                enctype="multipart/form-data"
            >
                <input
                    id="block-id"
                    type="hidden"
                >

                <input
                    id="block-type"
                    type="hidden"
                >

                {{-- Text Fields --}}
                <div
                    id="text-fields"
                    class="hidden"
                >
                    <label
                        for="block-content"
                        class="mb-2 block text-sm font-semibold text-slate-700"
                    >
                        Isi Teks
                    </label>

                    <textarea
                        id="block-content"
                        rows="14"
                        placeholder="Tuliskan isi materi..."
                        class="w-full border border-slate-300 px-4 py-3 leading-7 outline-none focus:border-blue-900"
                    ></textarea>

                    <p class="mt-2 text-xs leading-5 text-slate-500">
                        Gunakan toolbar untuk heading, teks tebal, daftar, tautan, tabel, kode, dan perataan.
                    </p>
                </div>

                {{-- YouTube Fields --}}
                <div
                    id="youtube-fields"
                    class="hidden space-y-5"
                >
                    <div>
                        <label
                            for="block-title"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Judul Video
                        </label>

                        <input
                            id="block-title"
                            type="text"
                            maxlength="255"
                            placeholder="Contoh: Cara Menginstal Laravel"
                            class="w-full border border-slate-300 px-4 py-3 outline-none focus:border-blue-900"
                        >
                    </div>

                    <div>
                        <label
                            for="block-url"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Tautan YouTube
                        </label>

                        <input
                            id="block-url"
                            type="url"
                            placeholder="https://www.youtube.com/watch?v=..."
                            class="w-full border border-slate-300 px-4 py-3 outline-none focus:border-blue-900"
                        >

                        <p class="mt-2 text-xs leading-5 text-slate-500">
                            Mendukung youtube.com/watch, youtu.be, embed, Shorts, dan YouTube Live.
                        </p>
                    </div>

                    <button
                        id="youtube-preview-button"
                        type="button"
                        class="inline-flex items-center justify-center gap-2 border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                    >
                        <i class="bi bi-play-btn"></i>
                        Tampilkan Preview
                    </button>

                    <div
                        id="youtube-preview"
                        class="hidden aspect-video overflow-hidden bg-slate-950"
                    >
                        <iframe
                            id="youtube-iframe"
                            class="h-full w-full"
                            src=""
                            title="Preview YouTube"
                            loading="lazy"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen
                        ></iframe>
                    </div>
                </div>

                {{-- File Fields --}}
                <div
                    id="file-fields"
                    class="hidden space-y-4"
                >
                    <div>
                        <label
                            for="block-file"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            File
                        </label>

                        <input
                            id="block-file"
                            type="file"
                            class="block w-full border border-slate-300 bg-white p-3 text-sm"
                        >

                        <p
                            id="file-help"
                            class="mt-2 text-xs text-slate-500"
                        ></p>

                        <p
                            id="existing-file"
                            class="mt-2 hidden text-xs font-semibold text-blue-800"
                        ></p>
                    </div>

                    <div
                        id="image-preview"
                        class="hidden overflow-hidden border border-slate-200 bg-slate-50 p-3"
                    >
                        <img
                            id="image-preview-element"
                            src=""
                            alt="Preview gambar"
                            class="max-h-80 w-full object-contain"
                        >
                    </div>
                </div>

                {{-- Caption Fields --}}
                <div
                    id="caption-fields"
                    class="hidden grid gap-5 md:grid-cols-2"
                >
                    <div>
                        <label
                            for="block-caption"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Keterangan
                        </label>

                        <input
                            id="block-caption"
                            type="text"
                            maxlength="255"
                            placeholder="Keterangan file"
                            class="w-full border border-slate-300 px-4 py-3 outline-none focus:border-blue-900"
                        >
                    </div>

                    <div id="alt-wrapper">
                        <label
                            for="block-alt"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Teks Alternatif
                        </label>

                        <input
                            id="block-alt"
                            type="text"
                            maxlength="255"
                            placeholder="Deskripsi gambar untuk aksesibilitas"
                            class="w-full border border-slate-300 px-4 py-3 outline-none focus:border-blue-900"
                        >
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="flex flex-col-reverse gap-3 border-t border-slate-200 pt-5 sm:flex-row sm:justify-end">
                    <button
                        id="cancel-button"
                        type="button"
                        class="border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-50"
                    >
                        Batal
                    </button>

                    <button
                        id="submit-button"
                        type="submit"
                        class="inline-flex items-center justify-center gap-2 bg-blue-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-900 disabled:cursor-not-allowed disabled:opacity-60"
                    >
                        <i class="bi bi-floppy"></i>
                        <span>Simpan Blok</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection