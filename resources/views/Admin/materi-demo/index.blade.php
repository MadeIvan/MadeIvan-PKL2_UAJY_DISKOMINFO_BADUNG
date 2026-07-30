@extends('components.admin.layouts.admin')

@section('title', 'Kelola Materi')
@section('page-title', 'Kelola Materi')

@push('scripts')
    @vite('resources/js/admin/materi-demo/index.js')
@endpush

@section('content')
    <div class="space-y-6">

        {{-- Notification --}}
        <div
            id="notification"
            class="hidden border px-4 py-3 text-sm"
            role="alert"
        ></div>

        {{-- Header --}}
        <section class="border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-col gap-5 p-5 sm:flex-row sm:items-center sm:justify-between sm:p-6">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-blue-900">
                        Knowledge Management System
                    </p>

                    <h2 class="mt-2 text-2xl font-bold text-slate-950">
                        Struktur Materi
                    </h2>

                    <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-500">
                        Kelola kategori, bagian, tutorial, dan langkah materi
                        berdasarkan aplikasi.
                    </p>
                </div>

                <button
                    id="open-root-node-form"
                    type="button"
                    disabled
                    class="inline-flex items-center justify-center gap-2 bg-blue-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-900 disabled:cursor-not-allowed disabled:opacity-50"
                >
                    <i class="bi bi-plus-lg"></i>
                    Tambah Materi Utama
                </button>
            </div>
        </section>

        {{-- Search application --}}
        <section class="border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <div class="grid gap-5 lg:grid-cols-[1fr_auto] lg:items-end">

                <div>
                    <label
                        for="application-search"
                        class="mb-2 block text-sm font-semibold text-slate-700"
                    >
                        Pilih Aplikasi
                    </label>

                    <div
                        id="application-search-wrapper"
                        class="relative"
                    >
                        <div class="relative">
                            <i class="bi bi-search pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>

                            <input
                                id="application-search"
                                type="text"
                                autocomplete="off"
                                placeholder="Ketik nama aplikasi..."
                                class="w-full border border-slate-300 bg-white py-3 pl-11 pr-11 text-sm outline-none transition focus:border-blue-900 focus:ring-2 focus:ring-blue-900/10"
                            >

                            <button
                                id="application-dropdown-button"
                                type="button"
                                class="absolute right-0 top-0 flex h-full w-11 items-center justify-center text-slate-500 transition hover:bg-slate-50 hover:text-slate-800"
                                aria-label="Buka daftar aplikasi"
                            >
                                <i class="bi bi-chevron-down"></i>
                            </button>
                        </div>

                        <input
                            id="application-id"
                            type="hidden"
                        >

                        <div
                            id="application-dropdown"
                            class="absolute left-0 right-0 z-40 mt-2 hidden max-h-64 overflow-y-auto border border-slate-200 bg-white shadow-xl"
                        >
                            <div
                                id="application-options"
                                class="py-1"
                            ></div>

                            <div
                                id="application-empty"
                                class="hidden px-4 py-5 text-center text-sm text-slate-500"
                            >
                                Aplikasi tidak ditemukan.
                            </div>
                        </div>
                    </div>

                    <p
                        id="application-search-help"
                        class="mt-2 text-xs text-slate-500"
                    >
                        Ketik nama aplikasi lalu pilih dari daftar.
                    </p>

                    <p
                        id="application-search-error"
                        class="mt-2 hidden text-xs font-semibold text-red-600"
                    >
                        Pilih aplikasi yang tersedia pada daftar.
                    </p>
                </div>

                <button
                    id="refresh-tree-button"
                    type="button"
                    disabled
                    class="inline-flex items-center justify-center gap-2 border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-50"
                >
                    <i class="bi bi-arrow-clockwise"></i>
                    Muat Ulang
                </button>
            </div>
        </section>

        {{-- Statistics --}}
        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <article class="border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">
                    Total Materi
                </p>

                <p
                    id="stat-total-nodes"
                    class="mt-2 text-3xl font-bold text-slate-950"
                >
                    0
                </p>
            </article>

            <article class="border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">
                    Materi Publik
                </p>

                <p
                    id="stat-public-nodes"
                    class="mt-2 text-3xl font-bold text-slate-950"
                >
                    0
                </p>
            </article>

            <article class="border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">
                    Dipublikasikan
                </p>

                <p
                    id="stat-published-nodes"
                    class="mt-2 text-3xl font-bold text-slate-950"
                >
                    0
                </p>
            </article>

            <article class="border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">
                    Tutorial
                </p>

                <p
                    id="stat-tutorial-nodes"
                    class="mt-2 text-3xl font-bold text-slate-950"
                >
                    0
                </p>
            </article>
        </section>

        {{-- Tree --}}
        <section class="border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-col gap-4 border-b border-slate-200 p-5 sm:flex-row sm:items-center sm:justify-between sm:p-6">
                <div>
                    <h3 class="text-lg font-bold text-slate-950">
                        Struktur Materi
                    </h3>

                    <p
                        id="tree-description"
                        class="mt-1 text-sm text-slate-500"
                    >
                        Pilih aplikasi untuk menampilkan materi.
                    </p>
                </div>

                <div class="flex flex-wrap gap-2">
                    <button
                        id="expand-all-button"
                        type="button"
                        disabled
                        class="inline-flex items-center justify-center gap-2 border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-50"
                    >
                        <i class="bi bi-arrows-expand"></i>
                        Buka Semua
                    </button>

                    <button
                        id="collapse-all-button"
                        type="button"
                        disabled
                        class="inline-flex items-center justify-center gap-2 border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-50"
                    >
                        <i class="bi bi-arrows-collapse"></i>
                        Tutup Semua
                    </button>
                </div>
            </div>

            <div
                id="tree-initial"
                class="px-5 py-16 text-center"
            >
                <i class="bi bi-diagram-3 text-4xl text-slate-300"></i>

                <h3 class="mt-4 text-lg font-bold text-slate-950">
                    Belum ada aplikasi dipilih
                </h3>

                <p class="mt-2 text-sm text-slate-500">
                    Cari dan pilih aplikasi terlebih dahulu.
                </p>
            </div>

            <div
                id="tree-loading"
                class="hidden px-5 py-16 text-center text-sm text-slate-500"
            >
                <i class="bi bi-arrow-repeat mr-2 inline-block animate-spin text-xl"></i>
                Memuat struktur materi...
            </div>

            <div
                id="tree-empty"
                class="hidden px-5 py-16 text-center"
            >
                <i class="bi bi-folder2-open text-4xl text-slate-300"></i>

                <h3 class="mt-4 text-lg font-bold text-slate-950">
                    Belum ada materi
                </h3>

                <p class="mt-2 text-sm text-slate-500">
                    Tambahkan materi utama untuk aplikasi ini.
                </p>

                <button
                    id="empty-add-root-button"
                    type="button"
                    class="mt-5 inline-flex items-center justify-center gap-2 bg-blue-950 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-900"
                >
                    <i class="bi bi-plus-lg"></i>
                    Tambah Materi Utama
                </button>
            </div>

            <div
                id="tree-error"
                class="hidden px-5 py-16 text-center"
            >
                <i class="bi bi-exclamation-triangle text-4xl text-red-400"></i>

                <h3 class="mt-4 text-lg font-bold text-slate-950">
                    Gagal memuat materi
                </h3>

                <p
                    id="tree-error-message"
                    class="mt-2 text-sm text-slate-500"
                ></p>

                <button
                    id="tree-retry-button"
                    type="button"
                    class="mt-5 inline-flex items-center justify-center gap-2 border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50"
                >
                    <i class="bi bi-arrow-clockwise"></i>
                    Coba Lagi
                </button>
            </div>

            <div
                id="tutorial-tree"
                class="hidden space-y-3 p-5 sm:p-6"
            ></div>
        </section>
    </div>

    {{-- Form modal --}}
    <div
        id="node-form-modal"
        class="fixed inset-0 z-[70] hidden items-center justify-center bg-slate-950/60 p-4"
        aria-hidden="true"
    >
        <div class="max-h-[92vh] w-full max-w-3xl overflow-y-auto border border-slate-200 bg-white shadow-2xl">
            <div class="sticky top-0 z-10 flex items-start justify-between gap-4 border-b border-slate-200 bg-white p-5 sm:p-6">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-blue-900">
                        Form Materi
                    </p>

                    <h2
                        id="node-form-title"
                        class="mt-1 text-xl font-bold text-slate-950"
                    >
                        Tambah Materi
                    </h2>

                    <p
                        id="node-form-description"
                        class="mt-1 text-sm text-slate-500"
                    >
                        Isi informasi materi.
                    </p>
                </div>

                <button
                    id="node-form-modal-close"
                    type="button"
                    class="flex h-10 w-10 items-center justify-center text-slate-500 hover:bg-slate-100"
                    aria-label="Tutup"
                >
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <form
                id="node-form"
                class="space-y-6 p-5 sm:p-6"
            >
                <input id="node-id" type="hidden">
                <input id="node-parent-id" type="hidden">

                <div
                    id="parent-information"
                    class="hidden border border-blue-200 bg-blue-50 p-4"
                >
                    <p class="text-xs font-bold uppercase text-blue-700">
                        Parent
                    </p>

                    <p
                        id="parent-information-title"
                        class="mt-1 text-sm font-semibold text-blue-950"
                    ></p>
                </div>

                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label
                            for="node-title"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Judul Materi
                        </label>

                        <input
                            id="node-title"
                            type="text"
                            required
                            maxlength="200"
                            class="w-full border border-slate-300 px-4 py-3 text-sm outline-none focus:border-blue-900"
                        >
                    </div>

                    <div>
                        <label
                            for="node-slug"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Slug
                        </label>

                        <input
                            id="node-slug"
                            type="text"
                            maxlength="200"
                            placeholder="Otomatis jika dikosongkan"
                            class="w-full border border-slate-300 px-4 py-3 text-sm outline-none focus:border-blue-900"
                        >
                    </div>

                    <div>
                        <label
                            for="node-type"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Jenis Materi
                        </label>

                        <select
                            id="node-type"
                            required
                            class="w-full border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-blue-900"
                        >
                            <option value="category">Kategori</option>
                            <option value="section">Bagian</option>
                            <option value="tutorial">Tutorial</option>
                            <option value="step">Langkah</option>
                        </select>
                    </div>

                    <div>
                        <label
                            for="node-sort-order"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Urutan
                        </label>

                        <input
                            id="node-sort-order"
                            type="number"
                            min="0"
                            value="0"
                            class="w-full border border-slate-300 px-4 py-3 text-sm outline-none focus:border-blue-900"
                        >
                    </div>

                    <div>
                        <label
                            for="node-status"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Status
                        </label>

                        <select
                            id="node-status"
                            class="w-full border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-blue-900"
                        >
                            <option value="draft">Draf</option>
                            <option value="published">Dipublikasikan</option>
                            <option value="archived">Diarsipkan</option>
                        </select>
                    </div>

                    <div>
                        <label
                            for="node-application-version"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Versi Aplikasi
                        </label>

                        <select
                            id="node-application-version"
                            class="w-full border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-blue-900"
                        >
                            <option value="">
                                Semua versi
                            </option>
                        </select>
                    </div>
                </div>

                <div>
                    <label
                        for="node-description"
                        class="mb-2 block text-sm font-semibold text-slate-700"
                    >
                        Deskripsi
                    </label>

                    <textarea
                        id="node-description"
                        rows="4"
                        class="w-full resize-y border border-slate-300 px-4 py-3 text-sm outline-none focus:border-blue-900"
                    ></textarea>
                </div>

                <label class="flex items-center gap-3 border border-slate-200 p-4">
                    <input
                        id="node-is-public"
                        type="checkbox"
                        class="h-5 w-5"
                    >

                    <span>
                        <span class="block text-sm font-semibold text-slate-800">
                            Tampilkan secara publik
                        </span>

                        <span class="mt-1 block text-xs text-slate-500">
                            Materi juga harus berstatus dipublikasikan.
                        </span>
                    </span>
                </label>

                <div class="flex flex-col-reverse gap-3 border-t border-slate-200 pt-5 sm:flex-row sm:justify-end">
                    <button
                        id="node-cancel-button"
                        type="button"
                        class="border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50"
                    >
                        Batal
                    </button>

                    <button
                        id="node-submit-button"
                        type="submit"
                        class="inline-flex items-center justify-center gap-2 bg-blue-950 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-900 disabled:opacity-60"
                    >
                        <i class="bi bi-plus-lg"></i>
                        <span>Simpan Materi</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection