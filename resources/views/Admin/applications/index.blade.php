@extends('components.admin.layouts.admin')

@section('title')
Kelola Aplikasi
@endsection

@section('page-title')
Kelola Aplikasi
@endsection

@push('scripts')
    @vite('resources/js/admin/applications/index.js')
@endpush

@section('content')
    <div id="application-page" class="space-y-6">

        {{-- Header --}}
        <section class="border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-col gap-5 p-5 sm:flex-row sm:items-center sm:justify-between sm:p-6">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-blue-900">
                        Manajemen Aplikasi
                    </p>

                    <h2 class="mt-2 text-2xl font-bold text-slate-950">
                        Daftar Aplikasi
                    </h2>

                    <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-500">
                        Kelola informasi aplikasi, logo, status publik, serta versi
                        aplikasi yang tersedia pada sistem.
                    </p>
                </div>

                <button
                    id="open-application-form"
                    type="button"
                    class="inline-flex shrink-0 items-center justify-center gap-2 bg-blue-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-900"
                >
                    <i class="bi bi-plus-lg"></i>
                    Tambah Aplikasi
                </button>
            </div>
        </section>

        {{-- Statistik --}}
        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <article class="border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-sm font-medium text-slate-500">
                            Total Aplikasi
                        </p>

                        <p
                            id="stat-total-applications"
                            class="mt-2 text-3xl font-bold text-slate-950"
                        >
                            0
                        </p>
                    </div>

                    <div class="flex h-12 w-12 items-center justify-center bg-blue-100 text-blue-900">
                        <i class="bi bi-window-stack text-xl"></i>
                    </div>
                </div>
            </article>

            <article class="border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-sm font-medium text-slate-500">
                            Aplikasi Aktif
                        </p>

                        <p
                            id="stat-active-applications"
                            class="mt-2 text-3xl font-bold text-slate-950"
                        >
                            0
                        </p>
                    </div>

                    <div class="flex h-12 w-12 items-center justify-center bg-emerald-100 text-emerald-700">
                        <i class="bi bi-check-circle text-xl"></i>
                    </div>
                </div>
            </article>

            <article class="border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-sm font-medium text-slate-500">
                            Aplikasi Publik
                        </p>

                        <p
                            id="stat-public-applications"
                            class="mt-2 text-3xl font-bold text-slate-950"
                        >
                            0
                        </p>
                    </div>

                    <div class="flex h-12 w-12 items-center justify-center bg-violet-100 text-violet-700">
                        <i class="bi bi-globe text-xl"></i>
                    </div>
                </div>
            </article>

            <article class="border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-sm font-medium text-slate-500">
                            Aplikasi Tidak Aktif
                        </p>

                        <p
                            id="stat-inactive-applications"
                            class="mt-2 text-3xl font-bold text-slate-950"
                        >
                            0
                        </p>
                    </div>

                    <div class="flex h-12 w-12 items-center justify-center bg-amber-100 text-amber-700">
                        <i class="bi bi-pause-circle text-xl"></i>
                    </div>
                </div>
            </article>
        </section>

        {{-- Data Aplikasi --}}
        <section class="border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 p-5 sm:p-6">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-slate-950">
                            Data Aplikasi
                        </h3>

                        <p
                            id="application-count"
                            class="mt-1 text-sm text-slate-500"
                        >
                            Memuat data aplikasi...
                        </p>
                    </div>

                    <div class="flex w-full flex-col gap-3 sm:flex-row md:w-auto">
                        {{-- Sorting --}}
                        <div class="relative w-full sm:w-52">
                            <i class="bi bi-sort-down pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>

                            <select
                                id="application-sort"
                                class="w-full appearance-none border border-slate-300 bg-white py-3 pl-11 pr-10 text-sm outline-none transition focus:border-blue-900 focus:ring-2 focus:ring-blue-900/10"
                                aria-label="Urutkan aplikasi"
                            >
                                <option value="latest">Terbaru</option>
                                <option value="oldest">Terlama</option>
                                <option value="name_asc">Nama A–Z</option>
                                <option value="name_desc">Nama Z–A</option>
                            </select>

                            <i class="bi bi-chevron-down pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-xs text-slate-400"></i>
                        </div>

                        {{-- Search --}}
                        <div class="relative w-full md:w-80">
                            <i class="bi bi-search pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>

                            <input
                                id="application-search"
                                type="search"
                                placeholder="Cari aplikasi..."
                                autocomplete="off"
                                class="w-full border border-slate-300 bg-white py-3 pl-11 pr-4 text-sm outline-none transition focus:border-blue-900 focus:ring-2 focus:ring-blue-900/10"
                            >
                        </div>
                    </div>
                </div>
            </div>

            {{-- Loading --}}
            <div
                id="application-loading"
                class="py-16 text-center text-sm text-slate-500"
            >
                <i class="bi bi-arrow-repeat mr-2 inline-block animate-spin text-xl"></i>
                Memuat data aplikasi...
            </div>

            {{-- Empty --}}
            <div
                id="application-empty"
                class="hidden px-5 py-16 text-center"
            >
                <div class="mx-auto flex h-14 w-14 items-center justify-center bg-slate-100 text-slate-400">
                    <i class="bi bi-window-stack text-2xl"></i>
                </div>

                <h3 class="mt-4 text-lg font-bold text-slate-950">
                    Aplikasi tidak ditemukan
                </h3>

                <p class="mt-2 text-sm text-slate-500">
                    Tambahkan aplikasi baru atau gunakan kata pencarian lain.
                </p>
            </div>

            {{-- Error --}}
            <div
                id="application-error"
                class="hidden px-5 py-16 text-center"
            >
                <div class="mx-auto flex h-14 w-14 items-center justify-center bg-red-50 text-red-500">
                    <i class="bi bi-exclamation-triangle text-2xl"></i>
                </div>

                <h3 class="mt-4 text-lg font-bold text-slate-950">
                    Gagal memuat aplikasi
                </h3>

                <p
                    id="application-error-message"
                    class="mt-2 text-sm text-slate-500"
                ></p>

                <button
                    id="application-retry-button"
                    type="button"
                    class="mt-5 inline-flex items-center gap-2 border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                >
                    <i class="bi bi-arrow-clockwise"></i>
                    Coba Lagi
                </button>
            </div>

            {{-- Table --}}
            <div
                id="application-table-wrapper"
                class="hidden overflow-x-auto"
            >
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="w-20 px-5 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                                Logo
                            </th>

                            <th class="min-w-64 px-5 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                                Aplikasi
                            </th>

                            <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                                Kategori
                            </th>

                            <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                                Status
                            </th>

                            <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                                Visibilitas
                            </th>

                            <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                                Versi
                            </th>

                            <th class="min-w-56 px-5 py-4 text-right text-xs font-bold uppercase tracking-wider text-slate-500">
                                Aksi
                            </th>
                        </tr>
                    </thead>

                    <tbody
                        id="application-table-body"
                        class="divide-y divide-slate-200 bg-white"
                    ></tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div
                id="application-pagination-wrapper"
                class="hidden border-t border-slate-200 px-5 py-5 sm:px-6"
            >
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <p
                        id="application-page-info"
                        class="text-sm text-slate-500"
                    >
                        Menampilkan data aplikasi
                    </p>

                    <div
                        id="application-pagination"
                        class="flex flex-wrap items-center justify-center gap-2"
                    ></div>
                </div>
            </div>
        </section>
    </div>

    {{-- Row Action Menu --}}
    <div
        id="application-row-menu"
        class="fixed z-[90] hidden w-44 border border-slate-200 bg-white py-1 shadow-xl"
        role="menu"
        aria-hidden="true"
    >
        <button
            id="application-view-material"
            type="button"
            class="block w-full px-4 py-3 text-left text-sm font-semibold text-slate-700 transition hover:bg-slate-50 hover:text-slate-950"
            role="menuitem"
        >
            Lihat Materi
        </button>
    </div>

    {{-- =========================================================
        APPLICATION FORM MODAL
    ========================================================== --}}
    <div
        id="application-form-modal"
        class="fixed inset-0 z-[60] hidden items-center justify-center bg-slate-950/60 p-4"
        aria-hidden="true"
    >
        <div class="max-h-[92vh] w-full max-w-3xl overflow-y-auto border border-slate-200 bg-white shadow-2xl">

            {{-- Modal Header --}}
            <div class="sticky top-0 z-10 flex items-start justify-between gap-4 border-b border-slate-200 bg-white px-5 py-5 sm:px-6">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-blue-900">
                        Form Aplikasi
                    </p>

                    <h2
                        id="application-form-title"
                        class="mt-1 text-xl font-bold text-slate-950"
                    >
                        Tambah Aplikasi
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Isi informasi aplikasi dan unggah logo aplikasi.
                    </p>
                </div>

                <button
                    id="application-form-modal-close"
                    type="button"
                    class="flex h-10 w-10 items-center justify-center text-slate-500 transition hover:bg-slate-100 hover:text-slate-950"
                    aria-label="Tutup form"
                >
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            {{-- Application Form --}}
            <form
                id="application-form"
                class="space-y-6 p-5 sm:p-6"
                enctype="multipart/form-data"
            >
                <input
                    id="application-id"
                    type="hidden"
                >

                {{-- Main Information --}}
                <div class="grid gap-5 md:grid-cols-2">
                    {{-- Name --}}
                    <div>
                        <label
                            for="application-name"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Nama Aplikasi
                            <span class="text-red-500">*</span>
                        </label>

                        <input
                            id="application-name"
                            type="text"
                            required
                            maxlength="200"
                            placeholder="Contoh: Sistem Manajemen Pengetahuan"
                            class="w-full border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-blue-900 focus:ring-2 focus:ring-blue-900/10"
                        >
                    </div>

                    {{-- Slug --}}
                    <div>
                        <label
                            for="application-slug"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Slug
                        </label>

                        <input
                            id="application-slug"
                            type="text"
                            maxlength="200"
                            placeholder="Dibuat otomatis jika kosong"
                            class="w-full border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-blue-900 focus:ring-2 focus:ring-blue-900/10"
                        >

                        <p class="mt-1 text-xs text-slate-500">
                            Digunakan sebagai alamat URL aplikasi.
                        </p>
                    </div>

                    {{-- Category --}}
                    <div>
                        <label
                            for="application-category"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Kategori
                        </label>

                        <select
                            id="application-category"
                            class="w-full border border-slate-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-blue-900 focus:ring-2 focus:ring-blue-900/10"
                        >
                            <option value="">
                                Tidak ada kategori
                            </option>
                        </select>
                    </div>

                    {{-- Status --}}
                    <div>
                        <label
                            for="application-status"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Status
                            <span class="text-red-500">*</span>
                        </label>

                        <select
                            id="application-status"
                            required
                            class="w-full border border-slate-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-blue-900 focus:ring-2 focus:ring-blue-900/10"
                        >
                            <option value="active">
                                Aktif
                            </option>

                            <option value="inactive">
                                Tidak Aktif
                            </option>

                            <option value="archived">
                                Diarsipkan
                            </option>
                        </select>
                    </div>
                </div>

                {{-- =================================================
                    STATUS INFORMATION
                ================================================== --}}
                <div class="border border-blue-200 bg-blue-50 p-4">
                    <div class="flex items-start gap-3">
                        <div
                            class="flex h-9 w-9 shrink-0 items-center justify-center bg-blue-100 text-blue-800"
                        >
                            <i class="bi bi-info-circle"></i>
                        </div>

                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-blue-950">
                                Informasi Status Aplikasi
                            </p>

                            <div class="mt-3 space-y-2.5 text-xs leading-5 text-slate-600 sm:text-sm">
                                <p>
                                    <strong class="font-bold text-slate-900">
                                        Aktif
                                    </strong>
                                    — aplikasi sedang digunakan pada Pemerintah Kabupaten Badung.
                                </p>

                                <p>
                                    <strong class="font-bold text-slate-900">
                                        Tidak Aktif
                                    </strong>
                                    — aplikasi sedang tidak digunakan untuk sementara waktu.
                                </p>

                                <p>
                                    <strong class="font-bold text-slate-900">
                                        Diarsipkan
                                    </strong>
                                    — aplikasi dan seluruh materi terkait tidak akan
                                    ditampilkan pada Learning Management System.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Description --}}
                <div>
                    <label
                        for="application-description"
                        class="mb-2 block text-sm font-semibold text-slate-700"
                    >
                        Deskripsi
                    </label>

                    <textarea
                        id="application-description"
                        rows="4"
                        placeholder="Jelaskan fungsi dan tujuan aplikasi."
                        class="w-full resize-y border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-blue-900 focus:ring-2 focus:ring-blue-900/10"
                    ></textarea>
                </div>

                {{-- =================================================
                    APPLICATION LOGO
                ================================================== --}}
                <div class="border border-slate-200 bg-slate-50 p-5">
                    <div class="mb-4">
                        <h3 class="font-bold text-slate-950">
                            Logo Aplikasi
                        </h3>

                        <p class="mt-1 text-xs leading-5 text-slate-500">
                            Gunakan JPG, PNG, atau WebP. Ukuran maksimal 2 MB.
                        </p>
                    </div>

                    <input
                        id="application-logo"
                        type="file"
                        accept="image/jpeg,image/png,image/webp"
                        class="block w-full border border-slate-300 bg-white text-sm text-slate-600 file:mr-4 file:border-0 file:bg-blue-100 file:px-4 file:py-3 file:font-semibold file:text-blue-900 hover:file:bg-blue-200"
                    >

                    <div
                        id="application-logo-preview-wrapper"
                        class="mt-5 hidden"
                    >
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
                            <div class="flex h-28 w-28 shrink-0 items-center justify-center border border-slate-200 bg-white">
                                <img
                                    id="application-logo-preview"
                                    src=""
                                    alt="Pratinjau logo aplikasi"
                                    class="h-full w-full object-contain p-3"
                                >
                            </div>

                            <label class="inline-flex cursor-pointer items-center gap-2 text-sm font-semibold text-red-600">
                                <input
                                    id="application-remove-logo"
                                    type="checkbox"
                                    class="h-4 w-4 border-slate-300 text-red-600"
                                >

                                Hapus logo saat ini
                            </label>
                        </div>
                    </div>
                </div>

                {{-- =================================================
                    PUBLIC VISIBILITY
                ================================================== --}}
                <div class="border border-slate-200 bg-white">
                    <label
                        for="application-is-public"
                        class="flex cursor-pointer items-start gap-3 p-4 sm:p-5"
                    >
                        <input
                            id="application-is-public"
                            type="checkbox"
                            class="mt-0.5 h-5 w-5 shrink-0 border-slate-300 text-blue-900 focus:ring-blue-900"
                        >

                        <span class="min-w-0">
                            <span class="block text-sm font-semibold text-slate-800">
                                Tampilkan pada halaman publik
                            </span>

                            <span class="mt-1 block text-xs leading-5 text-slate-500">
                                Aktifkan pilihan ini apabila aplikasi dan materi
                                diperbolehkan untuk dilihat tanpa harus login.
                            </span>
                        </span>
                    </label>

                    {{-- Public Information --}}
                    <div class="border-t border-amber-200 bg-amber-50 px-4 py-4 sm:px-5">
                        <div class="flex items-start gap-3">
                            <div
                                class="flex h-8 w-8 shrink-0 items-center justify-center bg-amber-100 text-amber-700"
                            >
                                <i class="bi bi-exclamation-circle"></i>
                            </div>

                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-amber-950">
                                    Informasi Visibilitas
                                </p>

                                <p class="mt-2 text-xs leading-5 text-slate-600 sm:text-sm sm:leading-6">
                                    <strong class="font-bold text-slate-900">
                                        Jika kotak ini tidak dicentang,
                                    </strong>
                                    aplikasi dan materi tidak akan ditampilkan kepada
                                    pengguna publik.
                                </p>

                                <p class="mt-2 text-xs leading-5 text-slate-600 sm:text-sm sm:leading-6">
                                    <strong class="font-bold text-slate-900">
                                        Pegawai yang sudah login
                                    </strong>
                                    tetap dapat melihat materi yang tersedia sesuai
                                    dengan hak akses yang diberikan.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="flex flex-col-reverse gap-3 border-t border-slate-200 pt-5 sm:flex-row sm:justify-end">
                    <button
                        id="application-cancel-button"
                        type="button"
                        class="inline-flex items-center justify-center gap-2 border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                    >
                        <i class="bi bi-x-lg"></i>
                        Batal
                    </button>

                    <button
                        id="application-submit-button"
                        type="submit"
                        class="inline-flex min-w-44 items-center justify-center gap-2 bg-blue-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-900 disabled:cursor-not-allowed disabled:opacity-60"
                    >
                        <i class="bi bi-plus-lg"></i>
                        <span>Simpan Aplikasi</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- =========================================================
        VERSION MODAL
    ========================================================== --}}
    <div
        id="version-modal"
        class="fixed inset-0 z-[70] hidden items-center justify-center bg-slate-950/60 p-4"
        aria-hidden="true"
    >
        <div class="max-h-[92vh] w-full max-w-5xl overflow-y-auto border border-slate-200 bg-white shadow-2xl">

            {{-- Header --}}
            <div class="sticky top-0 z-10 flex items-start justify-between gap-4 border-b border-slate-200 bg-white px-5 py-5 sm:px-6">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-violet-700">
                        Versi Aplikasi
                    </p>

                    <h2 class="mt-1 text-xl font-bold text-slate-950">
                        Kelola Versi Aplikasi
                    </h2>

                    <p
                        id="version-modal-application-name"
                        class="mt-1 text-sm text-slate-500"
                    ></p>
                </div>

                <button
                    id="version-modal-close"
                    type="button"
                    class="flex h-10 w-10 items-center justify-center text-slate-500 transition hover:bg-slate-100 hover:text-slate-950"
                    aria-label="Tutup modal"
                >
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <div class="grid gap-6 p-5 sm:p-6 lg:grid-cols-[360px_1fr]">

                {{-- Version Form --}}
                <form
                    id="version-form"
                    class="h-fit space-y-4 border border-slate-200 bg-slate-50 p-5"
                >
                    <input
                        id="version-id"
                        type="hidden"
                    >

                    <input
                        id="version-application-id"
                        type="hidden"
                    >

                    <div>
                        <h3
                            id="version-form-title"
                            class="font-bold text-slate-950"
                        >
                            Tambah Versi
                        </h3>

                        <p class="mt-1 text-xs text-slate-500">
                            Tambahkan versi atau rilis baru.
                        </p>
                    </div>

                    {{-- Version Number --}}
                    <div>
                        <label
                            for="version-number"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Nomor Versi
                            <span class="text-red-500">*</span>
                        </label>

                        <input
                            id="version-number"
                            type="text"
                            required
                            maxlength="50"
                            placeholder="Contoh: 1.0.0"
                            class="w-full border border-slate-300 px-4 py-3 text-sm outline-none focus:border-blue-900 focus:ring-2 focus:ring-blue-900/10"
                        >
                    </div>

                    {{-- Release Date --}}
                    <div>
                        <label
                            for="version-release-date"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Tanggal Rilis
                        </label>

                        <input
                            id="version-release-date"
                            type="date"
                            class="w-full border border-slate-300 px-4 py-3 text-sm outline-none focus:border-blue-900 focus:ring-2 focus:ring-blue-900/10"
                        >
                    </div>

                    {{-- Version Status --}}
                    <div>
                        <label
                            for="version-status"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Status Versi
                        </label>

                        <select
                            id="version-status"
                            class="w-full border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-blue-900 focus:ring-2 focus:ring-blue-900/10"
                        >
                            <option value="draft">Draf</option>
                            <option value="beta">Beta</option>
                            <option value="stable">Stabil</option>
                            <option value="deprecated">Tidak Digunakan</option>
                        </select>
                    </div>

                    {{-- Release Notes --}}
                    <div>
                        <label
                            for="version-release-notes"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Catatan Rilis
                        </label>

                        <textarea
                            id="version-release-notes"
                            rows="4"
                            placeholder="Jelaskan perubahan pada versi ini."
                            class="w-full resize-y border border-slate-300 px-4 py-3 text-sm outline-none focus:border-blue-900 focus:ring-2 focus:ring-blue-900/10"
                        ></textarea>
                    </div>

                    {{-- Current Version --}}
                    <label class="flex cursor-pointer items-center gap-3">
                        <input
                            id="version-is-current"
                            type="checkbox"
                            class="h-5 w-5 border-slate-300 text-blue-900 focus:ring-blue-900"
                        >

                        <span class="text-sm font-medium text-slate-700">
                            Jadikan versi saat ini
                        </span>
                    </label>

                    {{-- Copy Material --}}
                    <div
                        id="version-copy-section"
                        class="border border-slate-200 bg-white p-4"
                    >
                        <label class="flex cursor-pointer items-start gap-3">
                            <input
                                id="version-copy-materials"
                                type="checkbox"
                                class="mt-0.5 h-5 w-5 border-slate-300 text-blue-900 focus:ring-blue-900"
                            >

                            <span>
                                <span class="block text-sm font-semibold text-slate-800">
                                    Salin materi dari versi lain
                                </span>

                                <span class="mt-1 block text-xs leading-5 text-slate-500">
                                    Pilih versi sumber dan materi yang ingin dibawa ke versi baru.
                                </span>
                            </span>
                        </label>

                        <div
                            id="version-copy-options"
                            class="mt-4 hidden space-y-4 border-t border-slate-200 pt-4"
                        >
                            {{-- Source Version --}}
                            <div>
                                <label
                                    for="version-source-select"
                                    class="mb-2 block text-sm font-semibold text-slate-700"
                                >
                                    Versi Sumber
                                </label>

                                <select
                                    id="version-source-select"
                                    class="w-full border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-blue-900 focus:ring-2 focus:ring-blue-900/10"
                                >
                                    <option value="">
                                        Pilih versi sumber
                                    </option>
                                </select>
                            </div>

                            {{-- Selected Materials --}}
                            <div>
                                <div class="flex flex-wrap items-center justify-between gap-3">
                                    <div>
                                        <p class="text-sm font-semibold text-slate-700">
                                            Materi yang Disalin
                                        </p>

                                        <p
                                            id="version-copy-selected-count"
                                            class="mt-1 text-xs text-slate-500"
                                        >
                                            0 materi dipilih
                                        </p>
                                    </div>

                                    <div class="flex gap-2">
                                        <button
                                            id="version-copy-select-all"
                                            type="button"
                                            disabled
                                            class="border border-slate-300 px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-50"
                                        >
                                            Pilih Semua
                                        </button>

                                        <button
                                            id="version-copy-clear-all"
                                            type="button"
                                            disabled
                                            class="border border-slate-300 px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-50"
                                        >
                                            Hapus Pilihan
                                        </button>
                                    </div>
                                </div>

                                {{-- Loading --}}
                                <div
                                    id="version-copy-tree-loading"
                                    class="mt-3 hidden border border-slate-200 bg-slate-50 px-4 py-6 text-center text-sm text-slate-500"
                                >
                                    <i class="bi bi-arrow-repeat mr-2 inline-block animate-spin"></i>
                                    Memuat materi versi sumber...
                                </div>

                                {{-- Empty --}}
                                <div
                                    id="version-copy-tree-empty"
                                    class="mt-3 hidden border border-dashed border-slate-300 px-4 py-6 text-center text-sm text-slate-500"
                                >
                                    Versi sumber belum memiliki materi.
                                </div>

                                {{-- Error --}}
                                <div
                                    id="version-copy-tree-error"
                                    class="mt-3 hidden border border-red-200 bg-red-50 px-4 py-4 text-sm text-red-700"
                                ></div>

                                {{-- Tree --}}
                                <div
                                    id="version-copy-tree"
                                    class="mt-3 hidden max-h-80 space-y-2 overflow-y-auto border border-slate-200 bg-slate-50 p-3"
                                ></div>

                                <p class="mt-2 text-xs leading-5 text-slate-500">
                                    Memilih parent akan memilih seluruh child.
                                    Parent yang diperlukan akan tetap disalin agar
                                    struktur tidak rusak.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Version Form Actions --}}
                    <div class="flex gap-3">
                        <button
                            id="version-submit-button"
                            type="submit"
                            class="inline-flex flex-1 items-center justify-center gap-2 bg-blue-950 px-4 py-3 text-sm font-semibold text-white transition hover:bg-blue-900 disabled:opacity-60"
                        >
                            <i class="bi bi-plus-lg"></i>
                            <span>Simpan Versi</span>
                        </button>

                        <button
                            id="version-cancel-button"
                            type="button"
                            class="hidden items-center justify-center border border-slate-300 px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-white"
                        >
                            Batal
                        </button>
                    </div>
                </form>

                {{-- Version List --}}
                <div>
                    <div
                        id="version-empty"
                        class="hidden border border-dashed border-slate-300 py-12 text-center"
                    >
                        <i class="bi bi-tags text-3xl text-slate-400"></i>

                        <p class="mt-3 text-sm text-slate-500">
                            Belum ada versi yang ditambahkan.
                        </p>
                    </div>

                    <div
                        id="version-list"
                        class="space-y-3"
                    ></div>
                </div>
            </div>
        </div>
    </div>

    {{-- =========================================================
        COPY MATERIAL CONFIRMATION MODAL
    ========================================================== --}}
    <div
        id="version-copy-confirmation-modal"
        class="fixed inset-0 z-[80] hidden items-center justify-center bg-slate-950/60 p-4"
        aria-hidden="true"
    >
        <div class="w-full max-w-xl border border-slate-200 bg-white shadow-2xl">
            <div class="border-b border-slate-200 p-5 sm:p-6">
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-blue-900">
                    Konfirmasi Penyalinan Materi
                </p>

                <h2 class="mt-2 text-xl font-bold text-slate-950">
                    Salin materi ke versi baru?
                </h2>
            </div>

            <div class="space-y-4 p-5 sm:p-6">
                <p
                    id="version-copy-confirmation-text"
                    class="whitespace-pre-line text-sm leading-6 text-slate-600"
                ></p>

                <div class="border border-blue-200 bg-blue-50 p-4">
                    <p class="text-sm font-semibold text-blue-950">
                        Materi sumber tidak akan berubah.
                    </p>

                    <p class="mt-1 text-xs leading-5 text-blue-700">
                        Sistem akan membuat node, content block, serta salinan
                        fisik file gambar dan PDF sebagai data baru.
                    </p>
                </div>
            </div>

            <div class="flex flex-col-reverse gap-3 border-t border-slate-200 p-5 sm:flex-row sm:justify-end sm:p-6">
                <button
                    id="version-copy-confirmation-cancel"
                    type="button"
                    class="border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                >
                    Batal
                </button>

                <button
                    id="version-copy-confirmation-submit"
                    type="button"
                    class="inline-flex items-center justify-center gap-2 bg-blue-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-900 disabled:cursor-not-allowed disabled:opacity-60"
                >
                    <i class="bi bi-files"></i>

                    <span>
                        Ya, Salin Materi dan Buat Versi
                    </span>
                </button>
            </div>
        </div>
    </div>

@endsection