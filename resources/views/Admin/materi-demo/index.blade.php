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
            classA="hidden border px-4 py-3 text-sm"
            role="alert"
        ></div>

        {{-- Page Header --}}
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
                        Kelola struktur materi berdasarkan aplikasi dan versi aplikasi.
                        Setiap versi memiliki struktur Kategori, Bagian, dan Materi
                        yang berbeda.
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

        {{-- Application and Version Filter --}}
        <section class="border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <div class="grid gap-5 xl:grid-cols-[minmax(0,1fr)_minmax(260px,0.65fr)_auto] xl:items-start">
                {{-- Application Search --}}
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

                    <p class="mt-2 text-xs text-slate-500">
                        Pilih aplikasi terlebih dahulu untuk menampilkan daftar versinya.
                    </p>

                    <p
                        id="application-search-error"
                        class="mt-2 hidden text-xs font-semibold text-red-600"
                    >
                        Pilih aplikasi yang tersedia pada daftar.
                    </p>
                </div>

                {{-- Application Version --}}
                <div>
                    <label
                        for="application-version-select"
                        class="mb-2 block text-sm font-semibold text-slate-700"
                    >
                        Pilih Versi Aplikasi
                    </label>

                    <select
                        id="application-version-select"
                        disabled
                        class="w-full border border-slate-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-blue-900 disabled:cursor-not-allowed disabled:bg-slate-100 disabled:text-slate-400"
                    >
                        <option value="">
                            Pilih aplikasi terlebih dahulu
                        </option>
                    </select>

                    <p
                        id="application-version-help"
                        class="mt-2 text-xs text-slate-500"
                    >
                        Struktur materi akan dibedakan berdasarkan versi.
                    </p>

                    <p
                        id="application-version-error"
                        class="mt-2 hidden text-xs font-semibold text-red-600"
                    >
                        Pilih versi aplikasi terlebih dahulu.
                    </p>
                </div>

                {{-- Refresh Button --}}
                <div>
                    <span
                        aria-hidden="true"
                        class="mb-2 block select-none text-sm font-semibold text-transparent"
                    >
                        Aksi
                    </span>

                    <button
                        id="refresh-tree-button"
                        type="button"
                        disabled
                        class="inline-flex w-full items-center justify-center gap-2 whitespace-nowrap border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-50 xl:w-auto"
                    >
                        <i class="bi bi-arrow-clockwise"></i>
                    </button>
                </div>
            </div>

            {{-- Selected Application Context --}}
            <div
                id="selected-context"
                class="mt-5 hidden border border-blue-200 bg-blue-50 p-4"
            >
                <p class="text-xs font-bold uppercase tracking-wide text-blue-700">
                    Struktur yang sedang dikelola
                </p>

                <p
                    id="selected-context-text"
                    class="mt-1 text-sm font-semibold text-blue-950"
                ></p>
            </div>
        </section>

        {{-- Statistics --}}
        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <article class="border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">
                    Total Node
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
                    Node Publik
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
                    Materi
                </p>

                <p
                    id="stat-material-nodes"
                    class="mt-2 text-3xl font-bold text-slate-950"
                >
                    0
                </p>
            </article>
        </section>

        {{-- Material Tree --}}
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
                        Pilih aplikasi dan versi untuk menampilkan materi.
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

            {{-- Initial State --}}
            <div
                id="tree-initial"
                class="px-5 py-16 text-center"
            >
                <i class="bi bi-diagram-3 text-4xl text-slate-300"></i>

                <h3 class="mt-4 text-lg font-bold text-slate-950">
                    Belum ada versi dipilih
                </h3>

                <p class="mt-2 text-sm text-slate-500">
                    Pilih aplikasi dan versi aplikasi terlebih dahulu.
                </p>
            </div>

            {{-- Loading State --}}
            <div
                id="tree-loading"
                class="hidden px-5 py-16 text-center text-sm text-slate-500"
            >
                <i class="bi bi-arrow-repeat mr-2 inline-block animate-spin text-xl"></i>
                Memuat struktur materi...
            </div>

            {{-- Empty State --}}
            <div
                id="tree-empty"
                class="hidden px-5 py-16 text-center"
            >
                <i class="bi bi-folder2-open text-4xl text-slate-300"></i>

                <h3 class="mt-4 text-lg font-bold text-slate-950">
                    Belum ada materi
                </h3>

                <p class="mt-2 text-sm text-slate-500">
                    Belum ada struktur materi untuk versi aplikasi ini.
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

            {{-- Error State --}}
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

            {{-- Tree Container --}}
            <div
                id="tutorial-tree"
                class="hidden space-y-3 p-5 sm:p-6"
            ></div>
        </section>
    </div>

    {{-- Node Form Modal --}}
    <div
        id="node-form-modal"
        class="fixed inset-0 z-[70] hidden items-center justify-center bg-slate-950/60 p-4"
        aria-hidden="true"
    >
        <div class="max-h-[92vh] w-full max-w-3xl overflow-y-auto border border-slate-200 bg-white shadow-2xl">
            {{-- Modal Header --}}
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

            {{-- Node Form --}}
            <form
                id="node-form"
                class="space-y-6 p-5 sm:p-6"
            >
                <input
                    id="node-id"
                    type="hidden"
                >

                <input
                    id="node-parent-id"
                    type="hidden"
                >

                {{-- Application Information --}}
                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="border border-slate-200 bg-slate-50 p-4">
                        <p class="text-xs font-bold uppercase text-slate-500">
                            Aplikasi
                        </p>

                        <p
                            id="form-application-name"
                            class="mt-1 text-sm font-semibold text-slate-900"
                        ></p>
                    </div>

                    <div class="border border-slate-200 bg-slate-50 p-4">
                        <p class="text-xs font-bold uppercase text-slate-500">
                            Versi Aplikasi
                        </p>

                        <p
                            id="form-version-name"
                            class="mt-1 text-sm font-semibold text-slate-900"
                        ></p>
                    </div>
                </div>

                {{-- Parent Information --}}
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

                {{-- Main Fields --}}
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
                            class="w-full border border-slate-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-blue-900 disabled:cursor-not-allowed disabled:border-slate-200 disabled:bg-slate-100 disabled:text-slate-500"
                        >
                            <option value="kategori">
                                Kategori
                            </option>

                            <option value="bagian">
                                Bagian
                            </option>

                            <option value="materi">
                                Materi
                            </option>
                        </select>

                        <p
                            id="node-type-help"
                            class="mt-2 text-xs text-slate-500"
                        >
                            Pilih jenis materi sesuai posisi dalam struktur.
                        </p>

                        <div
                            id="node-type-notice"
                            class="mt-3 hidden border border-slate-200 bg-slate-100 p-4"
                        >
                            <div class="flex items-start gap-3">
                                <i class="bi bi-info-circle mt-0.5 text-slate-500"></i>

                                <div>
                                    <p class="text-sm font-semibold text-slate-700">
                                        Jenis materi dibatasi
                                    </p>

                                    <p
                                        id="node-type-notice-text"
                                        class="mt-1 text-xs leading-5 text-slate-500"
                                    ></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="border border-slate-200 bg-slate-50 p-4">
                        <p class="text-sm font-semibold text-slate-700">
                            Pengaturan Urutan
                        </p>

                        <p class="mt-2 text-xs leading-5 text-slate-500">
                            Urutan materi diatur melalui tombol caret atas dan bawah
                            pada daftar struktur materi.
                        </p>
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
                            <option value="draft">
                                Draf
                            </option>

                            <option value="published">
                                Dipublikasikan
                            </option>

                            <option value="archived">
                                Diarsipkan
                            </option>
                        </select>
                    </div>
                </div>

                {{-- Description --}}
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

                {{-- Public Setting --}}
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

                {{-- Modal Footer --}}
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