<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Kelola Aplikasi</title>

    @vite([
        'resources/css/app.css',
        'resources/js/admin/applications/index.js'
    ])
</head>

<body class="min-h-screen bg-slate-100 text-slate-900">
    @include('components.navbar')

    <main class="min-h-screen pb-12 pt-28">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            {{-- Notifikasi --}}
            <div
                id="notification"
                class="mb-6 hidden rounded-xl border px-4 py-3 text-sm"
                role="alert"
            ></div>

            {{-- Form aplikasi --}}
            <section class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-5 py-5 sm:px-6">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h1
                                id="application-form-title"
                                class="text-xl font-bold text-slate-900"
                            >
                                Tambah Aplikasi
                            </h1>

                            <p class="mt-1 text-sm text-slate-500">
                                Daftarkan aplikasi beserta informasi dan logonya.
                            </p>
                        </div>

                        <button
                            id="application-cancel-button"
                            type="button"
                            class="hidden items-center justify-center gap-2 rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                        >
                            <i class="bi bi-x-lg"></i>
                            Batal Mengubah
                        </button>
                    </div>
                </div>

                <form
                    id="application-form"
                    class="space-y-6 p-5 sm:p-6"
                    enctype="multipart/form-data"
                >
                    <input
                        id="application-id"
                        type="hidden"
                    >

                    <div class="grid gap-5 md:grid-cols-2">
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
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-blue-900 focus:ring-4 focus:ring-blue-900/10"
                            >
                        </div>

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
                                placeholder="Dibuat otomatis jika dikosongkan"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-blue-900 focus:ring-4 focus:ring-blue-900/10"
                            >

                            <p class="mt-1 text-xs text-slate-500">
                                Digunakan sebagai alamat URL aplikasi.
                            </p>
                        </div>

                        <div>
                            <label
                                for="application-category"
                                class="mb-2 block text-sm font-semibold text-slate-700"
                            >
                                Kategori
                            </label>

                            <input
                                id="application-category"
                                type="text"
                                maxlength="150"
                                placeholder="Contoh: Sistem Internal"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-blue-900 focus:ring-4 focus:ring-blue-900/10"
                            >
                        </div>

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
                                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-blue-900 focus:ring-4 focus:ring-blue-900/10"
                            >
                                <option value="active">Aktif</option>
                                <option value="inactive">Tidak Aktif</option>
                                <option value="archived">Diarsipkan</option>
                            </select>
                        </div>
                    </div>

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
                            class="w-full resize-y rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-blue-900 focus:ring-4 focus:ring-blue-900/10"
                        ></textarea>
                    </div>

                    {{-- Logo aplikasi --}}
                    <div class="max-w-2xl rounded-2xl border border-slate-200 p-5">
                        <div class="mb-4">
                            <h2 class="font-semibold text-slate-900">
                                Logo Aplikasi
                            </h2>

                            <p class="mt-1 text-xs leading-5 text-slate-500">
                                Disarankan menggunakan gambar persegi. Format JPG,
                                PNG, atau WebP dengan ukuran maksimal 2 MB.
                            </p>
                        </div>

                        <input
                            id="application-logo"
                            type="file"
                            accept="image/jpeg,image/png,image/webp"
                            class="block w-full rounded-xl border border-slate-300 bg-white text-sm text-slate-600 file:mr-4 file:border-0 file:bg-blue-50 file:px-4 file:py-3 file:font-semibold file:text-blue-900 hover:file:bg-blue-100"
                        >

                        <div
                            id="application-logo-preview-wrapper"
                            class="mt-5 hidden"
                        >
                            <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
                                <div class="flex h-36 w-36 shrink-0 items-center justify-center overflow-hidden rounded-2xl border border-slate-200 bg-slate-50">
                                    <img
                                        id="application-logo-preview"
                                        src=""
                                        alt="Pratinjau logo aplikasi"
                                        class="h-full w-full object-contain p-3"
                                    >
                                </div>

                                <div>
                                    <p class="text-sm font-medium text-slate-700">
                                        Pratinjau logo
                                    </p>

                                    <label class="mt-3 inline-flex cursor-pointer items-center gap-2 text-sm font-medium text-red-600">
                                        <input
                                            id="application-remove-logo"
                                            type="checkbox"
                                            class="h-4 w-4 rounded border-slate-300 text-red-600"
                                        >

                                        Hapus logo saat ini
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <input
                            id="application-is-public"
                            type="checkbox"
                            class="h-5 w-5 rounded border-slate-300 text-blue-900 focus:ring-blue-900"
                        >

                        <label
                            for="application-is-public"
                            class="text-sm font-medium text-slate-700"
                        >
                            Tampilkan aplikasi ini pada halaman publik
                        </label>
                    </div>

                    <div class="flex justify-end">
                        <button
                            id="application-submit-button"
                            type="submit"
                            class="inline-flex min-w-44 items-center justify-center gap-2 rounded-xl bg-blue-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-950 disabled:cursor-not-allowed disabled:opacity-60"
                        >
                            <i class="bi bi-plus-lg"></i>
                            <span>Simpan Aplikasi</span>
                        </button>
                    </div>
                </form>
            </section>

            {{-- Daftar aplikasi --}}
            <section class="mt-7 rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-5 py-5 sm:px-6">
                    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                        <div>
                            <h2 class="text-xl font-bold text-slate-900">
                                Daftar Aplikasi
                            </h2>

                            <p
                                id="application-count"
                                class="mt-1 text-sm text-slate-500"
                            >
                                Memuat data aplikasi...
                            </p>
                        </div>

                        <div class="relative w-full md:w-80">
                            <i class="bi bi-search pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>

                            <input
                                id="application-search"
                                type="search"
                                placeholder="Cari aplikasi..."
                                autocomplete="off"
                                class="w-full rounded-xl border border-slate-300 py-3 pl-11 pr-4 text-sm outline-none transition focus:border-blue-900 focus:ring-4 focus:ring-blue-900/10"
                            >
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
                    class="hidden py-16 text-center"
                >
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                        <i class="bi bi-window-stack text-2xl"></i>
                    </div>

                    <h3 class="mt-4 text-lg font-semibold text-slate-900">
                        Aplikasi tidak ditemukan
                    </h3>

                    <p class="mt-1 text-sm text-slate-500">
                        Tambahkan aplikasi baru atau gunakan kata pencarian lain.
                    </p>
                </div>

                {{-- Error --}}
                <div
                    id="application-error"
                    class="hidden py-16 text-center"
                >
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-red-50 text-red-500">
                        <i class="bi bi-exclamation-triangle text-2xl"></i>
                    </div>

                    <h3 class="mt-4 text-lg font-semibold text-slate-900">
                        Gagal memuat aplikasi
                    </h3>

                    <p
                        id="application-error-message"
                        class="mt-1 text-sm text-slate-500"
                    ></p>

                    <button
                        id="application-retry-button"
                        type="button"
                        class="mt-5 inline-flex items-center gap-2 rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50"
                    >
                        <i class="bi bi-arrow-clockwise"></i>
                        Coba Lagi
                    </button>
                </div>

                {{-- Grid aplikasi --}}
                <div
                    id="application-grid"
                    class="hidden grid gap-5 p-5 sm:p-6 md:grid-cols-2 xl:grid-cols-3"
                ></div>
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
    </main>

    {{-- Modal versi --}}
    <div
        id="version-modal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/60 p-4"
        aria-hidden="true"
    >
        <div class="max-h-[92vh] w-full max-w-5xl overflow-y-auto rounded-2xl bg-white shadow-2xl">
            <div class="sticky top-0 z-10 flex items-start justify-between gap-4 border-b border-slate-200 bg-white px-5 py-5 sm:px-6">
                <div>
                    <h2 class="text-xl font-bold text-slate-900">
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
                    class="flex h-10 w-10 items-center justify-center rounded-xl text-slate-500 transition hover:bg-slate-100 hover:text-slate-900"
                    aria-label="Tutup modal"
                >
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <div class="grid gap-6 p-5 sm:p-6 lg:grid-cols-[360px_1fr]">
                {{-- Form versi --}}
                <form
                    id="version-form"
                    class="h-fit space-y-4 rounded-2xl border border-slate-200 p-4"
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
                            class="font-bold text-slate-900"
                        >
                            Tambah Versi
                        </h3>

                        <p class="mt-1 text-xs text-slate-500">
                            Tambahkan versi atau rilis baru dari aplikasi.
                        </p>
                    </div>

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
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-blue-900 focus:ring-4 focus:ring-blue-900/10"
                        >
                    </div>

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
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-blue-900 focus:ring-4 focus:ring-blue-900/10"
                        >
                    </div>

                    <div>
                        <label
                            for="version-status"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Status Versi
                        </label>

                        <select
                            id="version-status"
                            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-blue-900 focus:ring-4 focus:ring-blue-900/10"
                        >
                            <option value="draft">Draf</option>
                            <option value="beta">Beta</option>
                            <option value="stable">Stabil</option>
                            <option value="deprecated">Tidak Digunakan</option>
                        </select>
                    </div>

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
                            class="w-full resize-y rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-blue-900 focus:ring-4 focus:ring-blue-900/10"
                        ></textarea>
                    </div>

                    <label class="flex cursor-pointer items-center gap-3">
                        <input
                            id="version-is-current"
                            type="checkbox"
                            class="h-5 w-5 rounded border-slate-300 text-blue-900 focus:ring-blue-900"
                        >

                        <span class="text-sm font-medium text-slate-700">
                            Jadikan sebagai versi saat ini
                        </span>
                    </label>

                    <div class="flex gap-3">
                        <button
                            id="version-submit-button"
                            type="submit"
                            class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl bg-blue-900 px-4 py-3 text-sm font-semibold text-white hover:bg-blue-950 disabled:opacity-60"
                        >
                            <i class="bi bi-plus-lg"></i>
                            <span>Simpan Versi</span>
                        </button>

                        <button
                            id="version-cancel-button"
                            type="button"
                            class="hidden items-center justify-center rounded-xl border border-slate-300 px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50"
                        >
                            Batal
                        </button>
                    </div>
                </form>

                {{-- Daftar versi --}}
                <div>
                    <div
                        id="version-empty"
                        class="hidden rounded-2xl border border-dashed border-slate-300 py-12 text-center"
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
</body>
</html>