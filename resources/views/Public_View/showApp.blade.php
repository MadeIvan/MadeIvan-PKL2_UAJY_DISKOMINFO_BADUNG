<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Daftar Aplikasi</title>

    @vite([
        'resources/css/app.css',
        'resources/js/applications/index.js'
    ])
</head>

<body class="min-h-screen bg-slate-50 text-slate-900">
    @include('components.navbar')

    <main class="min-h-screen pb-12 pt-28">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6 lg:p-8">

                {{-- Header --}}
                <div class="mb-6 flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-slate-900">
                            Semua Aplikasi
                        </h1>

                        <p class="mt-1 text-sm text-slate-500">
                            Pilih aplikasi untuk melihat daftar tutorialnya.
                        </p>

                        <p
                            id="applicationCount"
                            class="mt-2 text-xs font-semibold text-blue-900"
                        >
                            Memuat aplikasi...
                        </p>
                    </div>

                    {{-- Search + Sort --}}
                    <div class="flex w-full flex-col gap-3 sm:flex-row lg:w-auto">

                        {{-- Sort --}}
                        <div class="relative w-full sm:w-52">
                            <i class="bi bi-sort-down pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>

                            <select
                                id="applicationSort"
                                class="w-full appearance-none rounded-xl border border-slate-300 bg-white py-3 pl-11 pr-10 text-sm text-slate-700 outline-none transition focus:border-blue-900 focus:ring-4 focus:ring-blue-900/10"
                                aria-label="Urutkan aplikasi"
                            >
                                <option value="latest">
                                    Terbaru
                                </option>

                                <option value="oldest">
                                    Terlama
                                </option>

                                <option value="name_asc">
                                    Nama A–Z
                                </option>

                                <option value="name_desc">
                                    Nama Z–A
                                </option>
                            </select>

                            <i class="bi bi-chevron-down pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-xs text-slate-400"></i>
                        </div>

                        {{-- Search --}}
                        <div class="relative w-full sm:w-80">
                            <i class="bi bi-search pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>

                            <input
                                id="applicationSearch"
                                type="search"
                                placeholder="Cari aplikasi..."
                                autocomplete="off"
                                class="w-full rounded-xl border border-slate-300 bg-white py-3 pl-11 pr-4 text-sm outline-none transition focus:border-blue-900 focus:ring-4 focus:ring-blue-900/10"
                            >
                        </div>
                    </div>
                </div>

                {{-- Loading --}}
                <div
                    id="applicationLoading"
                    class="py-16 text-center text-slate-500"
                >
                    <i class="bi bi-arrow-repeat mr-2 inline-block animate-spin text-xl"></i>

                    Memuat daftar aplikasi...
                </div>

                {{-- Application grid --}}
                <div
                    id="applicationGrid"
                    class="grid gap-6 md:grid-cols-2 xl:grid-cols-3"
                ></div>

                {{-- Empty state --}}
                <div
                    id="emptyResult"
                    class="hidden py-16 text-center"
                >
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                        <i class="bi bi-search text-2xl"></i>
                    </div>

                    <h2 class="mt-4 text-lg font-semibold text-slate-900">
                        Aplikasi tidak ditemukan
                    </h2>

                    <p class="mt-2 text-sm text-slate-500">
                        Coba gunakan nama aplikasi atau kategori yang berbeda.
                    </p>
                </div>

                {{-- Error state --}}
                <div
                    id="applicationError"
                    class="hidden py-16 text-center"
                >
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-red-50 text-red-500">
                        <i class="bi bi-exclamation-triangle text-2xl"></i>
                    </div>

                    <h2 class="mt-4 text-lg font-semibold text-slate-900">
                        Gagal memuat aplikasi
                    </h2>

                    <p
                        data-error-message
                        class="mt-2 text-sm text-slate-500"
                    >
                        Terjadi kesalahan saat mengambil data aplikasi.
                    </p>

                    <button
                        id="applicationRetry"
                        type="button"
                        class="mt-5 inline-flex items-center gap-2 rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                    >
                        <i class="bi bi-arrow-clockwise"></i>

                        Coba Lagi
                    </button>
                </div>

                {{-- Pagination --}}
                <div
                    id="applicationPaginationWrapper"
                    class="mt-8 hidden border-t border-slate-200 pt-6"
                >
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <p
                            id="applicationPageInfo"
                            class="text-sm text-slate-500"
                        >
                            Menampilkan data aplikasi
                        </p>

                        <div
                            id="applicationPagination"
                            class="flex flex-wrap items-center justify-center gap-2"
                        ></div>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <footer class="border-t border-slate-200 bg-white py-6">
        <div class="mx-auto flex max-w-7xl flex-col gap-3 px-4 text-sm text-slate-500 sm:px-6 md:flex-row md:items-center md:justify-between lg:px-8">
            <p>
                © {{ date('Y') }} Pusat Pengetahuan. Seluruh hak cipta dilindungi.
            </p>

            <div class="flex gap-5">
                <a
                    href="#"
                    class="no-underline transition hover:text-blue-900"
                >
                    Bantuan
                </a>

                <a
                    href="#"
                    class="no-underline transition hover:text-blue-900"
                >
                    Tentang Sistem
                </a>
            </div>
        </div>
    </footer>
</body>
</html>