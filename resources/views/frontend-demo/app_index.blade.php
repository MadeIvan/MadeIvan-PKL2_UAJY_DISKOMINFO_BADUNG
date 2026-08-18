<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Daftar Aplikasi</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-50 text-slate-900">

    {{-- Navbar --}}
    @include('components.navbar')

    <main class="min-h-screen pt-28 pb-12">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6 lg:p-8">
                <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-slate-900">
                            Semua Aplikasi
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Pilih aplikasi untuk melihat daftar tutorialnya.
                        </p>
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row">
                        <div class="relative">
                            <i class="bi bi-search pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>

                            <input
                                id="applicationSearch"
                                type="search"
                                placeholder="Cari aplikasi..."
                                class="w-full min-w-64 rounded-xl border border-slate-200 bg-white py-3 pl-11 pr-4 text-sm outline-none focus:border-blue-900 focus:ring-4 focus:ring-blue-900/10"
                            >
                        </div>
                    </div>
                </div>

                <div
                    id="applicationGrid"
                    class="grid gap-6 md:grid-cols-2 xl:grid-cols-3"
                >
                    {{-- Application 1 --}}
                    <article
                        class="application-item group flex h-full flex-col overflow-hidden rounded-2xl bg-white shadow-lg ring-1 ring-slate-200 transition duration-200 hover:-translate-y-1 hover:shadow-xl"
                        data-name="sistem informasi kepegawaian"
                        data-description="aplikasi untuk mengelola data pegawai absensi cuti dan informasi administrasi kepegawaian"
                        data-category="kepegawaian"
                    >
                        <div class="aspect-video overflow-hidden bg-blue-50">
                            <img
                                src="{{ asset('images/Logo.png') }}"
                                alt="Sistem Informasi Kepegawaian"
                                class="h-full w-full object-contain p-8 transition duration-300 group-hover:scale-105"
                            >
                        </div>

                        <div class="flex flex-1 flex-col px-6 py-5">
                            <div class="mb-3 flex items-center justify-between gap-3">
                                <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-800">
                                    Kepegawaian
                                </span>

                                <span class="text-xs font-medium text-slate-500">
                                    <i class="bi bi-book mr-1"></i>
                                    8 Tutorial
                                </span>
                            </div>

                        <div class="mb-2 flex flex-wrap items-center gap-2">
                        <h3 class="text-xl font-bold text-slate-900">
                            Sistem Informasi Kepegawaian
                        </h3>

                        <span
                            class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700"
                            title="Versi aplikasi yang sedang digunakan"
                        >
                            v3.2.1
                        </span>
                    </div>

                            <p class="flex-1 text-base leading-7 text-slate-600">
                                Aplikasi untuk mengelola data pegawai, absensi, pengajuan cuti, serta informasi administrasi kepegawaian.
                            </p>

                            <a
                                href="{{ url('/content') }}"
                                class="mt-5 inline-flex items-center justify-center gap-2 rounded-xl bg-blue-900 px-4 py-3 text-sm font-semibold text-white no-underline transition hover:bg-blue-950"
                            >
                                Lihat Tutorial
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </article>

                    {{-- Application 2 --}}
                    <article
                        class="application-item group flex h-full flex-col overflow-hidden rounded-2xl bg-white shadow-lg ring-1 ring-slate-200 transition duration-200 hover:-translate-y-1 hover:shadow-xl"
                        data-name="sistem persuratan digital"
                        data-description="aplikasi pengelolaan surat masuk surat keluar disposisi dan arsip digital"
                        data-category="administrasi"
                    >
                        <div class="aspect-video overflow-hidden bg-sky-50">
                            <img
                                src="{{ asset('images/Logo.png') }}"
                                alt="Sistem Persuratan Digital"
                                class="h-full w-full object-contain p-8 transition duration-300 group-hover:scale-105"
                            >
                        </div>

                        <div class="flex flex-1 flex-col px-6 py-5">
                            <div class="mb-3 flex items-center justify-between gap-3">
                                <span class="rounded-full bg-sky-100 px-3 py-1 text-xs font-semibold text-sky-800">
                                    Administrasi
                                </span>

                                <span class="text-xs font-medium text-slate-500">
                                    <i class="bi bi-book mr-1"></i>
                                    6 Tutorial
                                </span>
                            </div>
                            <div class="mb-2 flex flex-wrap items-center gap-2">
                                <h3 class="text-xl font-bold text-slate-900">
                                    Sistem Informasi Kepegawaian
                                </h3>

                                <span
                                    class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700"
                                    title="Versi aplikasi yang sedang digunakan"
                                >
                                    v3.2.1
                                </span>
                            </div>

                            <p class="flex-1 text-base leading-7 text-slate-600">
                                Aplikasi untuk mengelola surat masuk, surat keluar, disposisi pimpinan, dan penyimpanan arsip secara digital.
                            </p>

                            <a
                                href="{{ url('/content') }}"
                                class="mt-5 inline-flex items-center justify-center gap-2 rounded-xl bg-blue-900 px-4 py-3 text-sm font-semibold text-white no-underline transition hover:bg-blue-950"
                            >
                                Lihat Tutorial
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </article>

                    {{-- Application 3 --}}
                    <article
                        class="application-item group flex h-full flex-col overflow-hidden rounded-2xl bg-white shadow-lg ring-1 ring-slate-200 transition duration-200 hover:-translate-y-1 hover:shadow-xl"
                        data-name="portal pelayanan publik"
                        data-description="portal layanan masyarakat pengajuan permohonan dokumen dan pemantauan status"
                        data-category="pelayanan"
                    >
                        <div class="aspect-video overflow-hidden bg-emerald-50">
                            <img
                                src="{{ asset('images/Logo.png') }}"
                                alt="Portal Pelayanan Publik"
                                class="h-full w-full object-contain p-8 transition duration-300 group-hover:scale-105"
                            >
                        </div>

                        <div class="flex flex-1 flex-col px-6 py-5">
                            <div class="mb-3 flex items-center justify-between gap-3">
                                <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-800">
                                    Pelayanan Publik
                                </span>

                                <span class="text-xs font-medium text-slate-500">
                                    <i class="bi bi-book mr-1"></i>
                                    10 Tutorial
                                </span>
                            </div>

                            <div class="mb-2 flex flex-wrap items-center gap-2">
                                <h3 class="text-xl font-bold text-slate-900">
                                    Sistem Informasi Kepegawaian
                                </h3>

                                <span
                                    class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700"
                                    title="Versi aplikasi yang sedang digunakan"
                                >
                                    v3.2.1
                                </span>
                            </div>

                            <p class="flex-1 text-base leading-7 text-slate-600">
                                Portal layanan masyarakat untuk mengajukan permohonan, mengunggah dokumen, dan memantau status pelayanan.
                            </p>

                            <a
                                href="{{ url('/content') }}"
                                class="mt-5 inline-flex items-center justify-center gap-2 rounded-xl bg-blue-900 px-4 py-3 text-sm font-semibold text-white no-underline transition hover:bg-blue-950"
                            >
                                Lihat Tutorial
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </article>

                    {{-- Application 4 --}}
                    <article
                        class="application-item group flex h-full flex-col overflow-hidden rounded-2xl bg-white shadow-lg ring-1 ring-slate-200 transition duration-200 hover:-translate-y-1 hover:shadow-xl"
                        data-name="aplikasi rapat digital"
                        data-description="aplikasi penjadwalan rapat undangan notulensi dan dokumentasi kegiatan"
                        data-category="komunikasi"
                    >
                        <div class="aspect-video overflow-hidden bg-purple-50">
                            <img
                                src="{{ asset('images/Logo.png') }}"
                                alt="Aplikasi Rapat Digital"
                                class="h-full w-full object-contain p-8 transition duration-300 group-hover:scale-105"
                            >
                        </div>

                        <div class="flex flex-1 flex-col px-6 py-5">
                            <div class="mb-3 flex items-center justify-between gap-3">
                                <span class="rounded-full bg-purple-100 px-3 py-1 text-xs font-semibold text-purple-800">
                                    Komunikasi
                                </span>

                                <span class="text-xs font-medium text-slate-500">
                                    <i class="bi bi-book mr-1"></i>
                                    5 Tutorial
                                </span>
                            </div>

                            <div class="mb-2 flex flex-wrap items-center gap-2">
                                <h3 class="text-xl font-bold text-slate-900">
                                    Sistem Informasi Kepegawaian
                                </h3>

                                <span
                                    class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700"
                                    title="Versi aplikasi yang sedang digunakan"
                                >
                                    v3.2.1
                                </span>
                            </div>

                            <p class="flex-1 text-base leading-7 text-slate-600">
                                Aplikasi untuk membuat jadwal rapat, mengirim undangan, mencatat notulensi, dan menyimpan dokumentasi kegiatan.
                            </p>

                            <a
                                href="{{ url('/content') }}"
                                class="mt-5 inline-flex items-center justify-center gap-2 rounded-xl bg-blue-900 px-4 py-3 text-sm font-semibold text-white no-underline transition hover:bg-blue-950"
                            >
                                Lihat Tutorial
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </article>

                    {{-- Application 5 --}}
                    <article
                        class="application-item group flex h-full flex-col overflow-hidden rounded-2xl bg-white shadow-lg ring-1 ring-slate-200 transition duration-200 hover:-translate-y-1 hover:shadow-xl"
                        data-name="sistem manajemen dokumen"
                        data-description="aplikasi penyimpanan pencarian pengelompokan dan pengelolaan dokumen digital"
                        data-category="teknologi"
                    >
                        <div class="aspect-video overflow-hidden bg-amber-50">
                            <img
                                src="{{ asset('images/Logo.png') }}"
                                alt="Sistem Manajemen Dokumen"
                                class="h-full w-full object-contain p-8 transition duration-300 group-hover:scale-105"
                            >
                        </div>

                        <div class="flex flex-1 flex-col px-6 py-5">
                            <div class="mb-3 flex items-center justify-between gap-3">
                                <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-800">
                                    Teknologi Informasi
                                </span>

                                <span class="text-xs font-medium text-slate-500">
                                    <i class="bi bi-book mr-1"></i>
                                    7 Tutorial
                                </span>
                            </div>

                            <div class="mb-2 flex flex-wrap items-center gap-2">
                                <h3 class="text-xl font-bold text-slate-900">
                                    Sistem Informasi Kepegawaian
                                </h3>

                                <span
                                    class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700"
                                    title="Versi aplikasi yang sedang digunakan"
                                >
                                    v3.2.1
                                </span>
                            </div>

                            <p class="flex-1 text-base leading-7 text-slate-600">
                                Aplikasi untuk menyimpan, mengelompokkan, mencari, dan mengelola dokumen digital secara terpusat.
                            </p>

                            <a
                                href="{{ url('/content') }}"
                                class="mt-5 inline-flex items-center justify-center gap-2 rounded-xl bg-blue-900 px-4 py-3 text-sm font-semibold text-white no-underline transition hover:bg-blue-950"
                            >
                                Lihat Tutorial
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </article>

                    {{-- Application 6 --}}
                    <article
                        class="application-item group flex h-full flex-col overflow-hidden rounded-2xl bg-white shadow-lg ring-1 ring-slate-200 transition duration-200 hover:-translate-y-1 hover:shadow-xl"
                        data-name="dashboard data terpadu"
                        data-description="dashboard visualisasi statistik laporan dan pemantauan data organisasi"
                        data-category="teknologi"
                    >
                        <div class="aspect-video overflow-hidden bg-indigo-50">
                            <img
                                src="{{ asset('images/Logo.png') }}"
                                alt="Dashboard Data Terpadu"
                                class="h-full w-full object-contain p-8 transition duration-300 group-hover:scale-105"
                            >
                        </div>

                        <div class="flex flex-1 flex-col px-6 py-5">
                            <div class="mb-3 flex items-center justify-between gap-3">
                                <span class="rounded-full bg-indigo-100 px-3 py-1 text-xs font-semibold text-indigo-800">
                                    Teknologi Informasi
                                </span>

                                <span class="text-xs font-medium text-slate-500">
                                    <i class="bi bi-book mr-1"></i>
                                    9 Tutorial
                                </span>
                            </div>

                            <div class="mb-2 flex flex-wrap items-center gap-2">
                                <h3 class="text-xl font-bold text-slate-900">
                                    Dashboard Data Terpadu
                                </h3>

                                <span
                                    class="inline-flex items-center rounded-full bg-indigo-100 px-2.5 py-1 text-xs font-semibold text-indigo-700"
                                    title="Versi aplikasi yang sedang digunakan"
                                >
                                    v3.2.1
                                </span>
                            </div>

                            <p class="flex-1 text-base leading-7 text-slate-600">
                                Dashboard untuk melihat visualisasi, statistik, laporan, dan pemantauan data organisasi secara terintegrasi.
                            </p>

                            <a
                                href="{{ url('/content') }}"
                                class="mt-5 inline-flex items-center justify-center gap-2 rounded-xl bg-blue-900 px-4 py-3 text-sm font-semibold text-white no-underline transition hover:bg-blue-950"
                            >
                                Lihat Tutorial
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </article>
                </div>

                <div
                    id="emptyResult"
                    class="hidden py-16 text-center"
                >
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                        <i class="bi bi-search text-2xl"></i>
                    </div>

                    <div class="mb-2 flex flex-wrap items-center gap-2">
                        <h3 class="text-xl font-bold text-slate-900">
                            Sistem Informasi Kepegawaian
                        </h3>

                        <span
                            class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700"
                            title="Versi aplikasi yang sedang digunakan"
                        >
                            v3.2.1
                        </span>
                    </div>

                    <p class="mt-2 text-sm text-slate-500">
                        Coba gunakan nama aplikasi atau kategori yang berbeda.
                    </p>
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
                <a href="#" class="no-underline transition hover:text-blue-900">
                    Bantuan
                </a>

                <a href="#" class="no-underline transition hover:text-blue-900">
                    Tentang Sistem
                </a>
            </div>
        </div>
    </footer>


</body>
</html>