<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Tambah Aplikasi</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-100 text-slate-900">

    {{-- Admin Navbar --}}
    <header class="sticky top-0 z-50 h-20 border-b border-slate-200 bg-white">
        <div class="flex h-full items-center justify-between px-4 lg:px-6">

            {{-- Left navbar --}}
            <div class="flex items-center gap-3">
                <button
                    id="openSidebar"
                    type="button"
                    class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 text-slate-600 transition hover:bg-slate-100 lg:hidden"
                    aria-label="Buka sidebar"
                >
                    <i class="bi bi-list text-xl"></i>
                </button>

                <button
                    id="toggleDesktopSidebar"
                    type="button"
                    class="hidden h-10 w-10 items-center justify-center rounded-xl border border-slate-200 text-slate-600 transition hover:bg-slate-100 lg:flex"
                    aria-label="Ciutkan sidebar"
                    aria-expanded="true"
                >
                    <i
                        id="desktopSidebarIcon"
                        class="bi bi-layout-sidebar-inset text-lg"
                    ></i>
                </button>

                <a href="/" class="flex items-center gap-3">
                    <img
                        src="{{ asset('images/Logo.png') }}"
                        alt="Logo Pusat Pengetahuan"
                        class="h-11 w-11 object-contain"
                    >

                    <div class="hidden sm:block">
                        <p class="font-bold leading-tight text-slate-900">
                            Pusat Pengetahuan
                        </p>

                        <p class="text-xs text-slate-500">
                            Panel Administrator
                        </p>
                    </div>
                </a>
            </div>

            {{-- Right navbar --}}
            <div class="flex items-center gap-3">
                <a
                    href="{{ url('/admin/aplikasi') }}"
                    class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                >
                    <i class="bi bi-arrow-left"></i>
                    <span class="hidden sm:inline">Kembali</span>
                </a>

                <button
                    id="navbarSaveButton"
                    type="button"
                    class="inline-flex items-center gap-2 rounded-xl bg-blue-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-950"
                >
                    <i class="bi bi-floppy"></i>
                    <span class="hidden sm:inline">Simpan Aplikasi</span>
                </button>

                <button
                    type="button"
                    class="relative flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 text-slate-600 transition hover:bg-slate-100"
                    aria-label="Notifikasi"
                >
                    <i class="bi bi-bell"></i>
                    <span class="absolute right-2 top-2 h-2 w-2 rounded-full bg-red-500"></span>
                </button>

                <button
                    type="button"
                    class="flex items-center gap-3 rounded-xl px-2 py-1.5 transition hover:bg-slate-100"
                >
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 font-semibold text-blue-900">
                        AD
                    </div>

                    <div class="hidden text-left md:block">
                        <p class="text-sm font-semibold">
                            Administrator
                        </p>

                        <p class="text-xs text-slate-500">
                            admin@example.com
                        </p>
                    </div>

                    <i class="bi bi-chevron-down hidden text-xs text-slate-400 md:block"></i>
                </button>
            </div>
        </div>
    </header>

    <div class="flex">

        {{-- Admin Sidebar --}}
        <aside
            id="sidebar"
            class="fixed bottom-0 left-0 top-20 z-40 w-72 -translate-x-full overflow-x-hidden overflow-y-auto border-r border-slate-200 bg-white transition-all duration-300 lg:sticky lg:top-20 lg:h-[calc(100vh-5rem)] lg:translate-x-0"
        >
            <div class="p-5">
                <div class="mb-4 flex items-center justify-between lg:hidden">
                    <p class="font-bold">Menu Administrator</p>

                    <button
                        id="closeSidebar"
                        type="button"
                        class="flex h-9 w-9 items-center justify-center rounded-lg text-slate-500 hover:bg-slate-100"
                        aria-label="Tutup sidebar"
                    >
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <p class="sidebar-section-label mb-3 px-3 text-xs font-semibold uppercase tracking-wider text-slate-400">
                    Menu Utama
                </p>

                <nav class="space-y-1">
                    <a
                        href="{{ url('/admin/admin_dashboard') }}"
                        class="sidebar-link flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-blue-900"
                    >
                        <i class="bi bi-grid shrink-0 text-lg"></i>
                        <span class="sidebar-label whitespace-nowrap">Dashboard</span>
                    </a>

                    <a
                        href="{{ url('/admin/pengetahuan') }}"
                        class="sidebar-link flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-blue-900"
                    >
                        <i class="bi bi-journal-text shrink-0 text-lg"></i>
                        <span class="sidebar-label whitespace-nowrap">Daftar Pengetahuan</span>
                    </a>

                    <a
                        href="{{ url('admin/input') }}"
                        class="sidebar-link flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-blue-900"
                    >
                        <i class="bi bi-plus-square shrink-0 text-lg"></i>
                        <span class="sidebar-label whitespace-nowrap">Tambah Pengetahuan</span>
                    </a>

                    <a
                        href="{{ url('admin/category/index  ') }}"
                        class="sidebar-link flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-blue-900"
                    >
                        <i class="bi bi-folder2-open shrink-0 text-lg"></i>
                        <span class="sidebar-label whitespace-nowrap">Kategori</span>
                    </a>

                    <a
                        href="{{ url('/admin/aplikasi') }}"
                        class="sidebar-link flex items-center gap-3 rounded-xl bg-blue-50 px-3 py-3 text-sm font-semibold text-blue-900"
                    >
                        <i class="bi bi-window-stack shrink-0 text-lg"></i>
                        <span class="sidebar-label whitespace-nowrap">Daftar Aplikasi</span>
                    </a>
                </nav>

                <p class="sidebar-section-label mb-3 mt-8 px-3 text-xs font-semibold uppercase tracking-wider text-slate-400">
                    Pengelolaan
                </p>

                <nav class="space-y-1">
                    <a
                        href="{{ url('/admin/pengguna') }}"
                        class="sidebar-link flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-blue-900"
                    >
                        <i class="bi bi-people shrink-0 text-lg"></i>
                        <span class="sidebar-label whitespace-nowrap">Pengguna</span>
                    </a>

                    <a
                        href="{{ url('/admin/media') }}"
                        class="sidebar-link flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-blue-900"
                    >
                        <i class="bi bi-images shrink-0 text-lg"></i>
                        <span class="sidebar-label whitespace-nowrap">Media</span>
                    </a>

                    <a
                        href="{{ url('/admin/log-aktivitas') }}"
                        class="sidebar-link flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-blue-900"
                    >
                        <i class="bi bi-clock-history shrink-0 text-lg"></i>
                        <span class="sidebar-label whitespace-nowrap">Log Aktivitas</span>
                    </a>
                </nav>

                <p class="sidebar-section-label mb-3 mt-8 px-3 text-xs font-semibold uppercase tracking-wider text-slate-400">
                    Sistem
                </p>

                <nav class="space-y-1">
                    <a
                        href="{{ url('/admin/pengaturan') }}"
                        class="sidebar-link flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-blue-900"
                    >
                        <i class="bi bi-gear shrink-0 text-lg"></i>
                        <span class="sidebar-label whitespace-nowrap">Pengaturan</span>
                    </a>

                    <a
                        href="#"
                        class="sidebar-link flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium text-red-600 transition hover:bg-red-50"
                    >
                        <i class="bi bi-box-arrow-right shrink-0 text-lg"></i>
                        <span class="sidebar-label whitespace-nowrap">Keluar</span>
                    </a>
                </nav>
            </div>
        </aside>

        {{-- Mobile overlay --}}
        <div
            id="sidebarOverlay"
            class="fixed inset-0 top-20 z-30 hidden bg-slate-950/40 lg:hidden"
        ></div>

        {{-- Main content --}}
        <main id="adminMain" class="min-w-0 flex-1 transition-all duration-300">
            <div class="mx-auto max-w-7xl px-5 py-8 lg:px-8">

                {{-- Breadcrumb --}}
                <nav class="mb-5 flex flex-wrap items-center gap-2 text-sm text-slate-500">
                    <a href="{{ url('/admin/dashboard') }}" class="hover:text-blue-900">
                        Admin
                    </a>

                    <i class="bi bi-chevron-right text-xs"></i>

                    <a href="{{ url('/admin/aplikasi') }}" class="hover:text-blue-900">
                        Daftar Aplikasi
                    </a>

                    <i class="bi bi-chevron-right text-xs"></i>

                    <span class="font-medium text-slate-800">
                        Tambah Aplikasi
                    </span>
                </nav>

                <div class="mb-7">
                    <h1 class="text-2xl font-bold tracking-tight sm:text-3xl">
                        Tambah Aplikasi Baru
                    </h1>

                    <p class="mt-2 text-sm text-slate-500">
                        Lengkapi informasi aplikasi dan versi awal yang akan digunakan pada sistem pengetahuan.
                    </p>
                </div>

                <form
                    id="applicationForm"
                    action="{{ url('/admin/aplikasi') }}"
                    method="POST"
                    enctype="multipart/form-data"
                    class="grid gap-8 xl:grid-cols-[1fr_340px]"
                >
                    @csrf

                    {{-- Main editor --}}
                    <div class="space-y-6">

                        {{-- Basic information --}}
                        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                            <div class="flex items-start gap-3">
                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-900">
                                    <i class="bi bi-window-stack text-lg"></i>
                                </div>

                                <div>
                                    <h2 class="text-lg font-bold">
                                        Informasi Dasar
                                    </h2>

                                    <p class="mt-1 text-sm text-slate-500">
                                        Informasi utama yang tampil pada daftar aplikasi.
                                    </p>
                                </div>
                            </div>

                            <div class="mt-6 grid gap-5">
                                <div>
                                    <label for="applicationName" class="mb-2 block text-sm font-semibold">
                                        Nama aplikasi
                                        <span class="text-red-500">*</span>
                                    </label>

                                    <input
                                        id="applicationName"
                                        name="name"
                                        type="text"
                                        placeholder="Contoh: Sistem Informasi Kepegawaian"
                                        required
                                        class="w-full rounded-xl border border-slate-200 px-4 py-3 outline-none transition focus:border-blue-900 focus:ring-2 focus:ring-blue-900/10"
                                    >
                                </div>

                                <div class="grid gap-5 md:grid-cols-2">
                                    <div>
                                        <label for="applicationSlug" class="mb-2 block text-sm font-semibold">
                                            Slug
                                        </label>

                                        <input
                                            id="applicationSlug"
                                            name="slug"
                                            type="text"
                                            placeholder="sistem-informasi-kepegawaian"
                                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none transition focus:border-blue-900 focus:bg-white"
                                        >

                                        <p class="mt-2 text-xs text-slate-500">
                                            Dibuat otomatis dari nama aplikasi dan dapat disunting.
                                        </p>
                                    </div>

                                    <div>
                                        <label for="applicationCategory" class="mb-2 block text-sm font-semibold">
                                            Kategori aplikasi
                                            <span class="text-red-500">*</span>
                                        </label>

                                        <select
                                            id="applicationCategory"
                                            name="category_name"
                                            required
                                            class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 outline-none transition focus:border-blue-900"
                                        >
                                            <option value="">Pilih kategori</option>
                                            <option value="Kepegawaian">Kepegawaian</option>
                                            <option value="Administrasi">Administrasi</option>
                                            <option value="Pelayanan Publik">Pelayanan Publik</option>
                                            <option value="Teknologi Informasi">Teknologi Informasi</option>
                                            <option value="Komunikasi">Komunikasi</option>
                                        </select>
                                    </div>
                                </div>

                                <div>
                                    <label for="applicationDescription" class="mb-2 block text-sm font-semibold">
                                        Deskripsi aplikasi
                                        <span class="text-red-500">*</span>
                                    </label>

                                    <textarea
                                        id="applicationDescription"
                                        name="description"
                                        rows="5"
                                        placeholder="Jelaskan fungsi dan tujuan utama aplikasi..."
                                        required
                                        class="w-full resize-y rounded-xl border border-slate-200 px-4 py-3 leading-7 outline-none transition focus:border-blue-900 focus:ring-2 focus:ring-blue-900/10"
                                    ></textarea>

                                    <div class="mt-2 flex items-center justify-between text-xs text-slate-500">
                                        <span>Gunakan deskripsi singkat dan mudah dipahami.</span>
                                        <span id="descriptionCount">0/500</span>
                                    </div>
                                </div>
                            </div>
                        </section>

                        {{-- Initial version --}}
                        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                            <div class="flex items-start gap-3">
                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-violet-100 text-violet-700">
                                    <i class="bi bi-git text-lg"></i>
                                </div>

                                <div>
                                    <h2 class="text-lg font-bold">
                                        Versi Awal Aplikasi
                                    </h2>

                                    <p class="mt-1 text-sm text-slate-500">
                                        Versi ini akan menjadi versi aktif pertama aplikasi.
                                    </p>
                                </div>
                            </div>

                            <div class="mt-6 grid gap-5">
                                <div class="grid gap-5 md:grid-cols-2">
                                    <div>
                                        <label for="versionNumber" class="mb-2 block text-sm font-semibold">
                                            Nomor versi
                                            <span class="text-red-500">*</span>
                                        </label>

                                        <div class="relative">
                                            <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-sm font-semibold text-slate-400">
                                                v
                                            </span>

                                            <input
                                                id="versionNumber"
                                                name="version_number"
                                                type="text"
                                                placeholder="1.0.0"
                                                required
                                                class="w-full rounded-xl border border-slate-200 py-3 pl-8 pr-4 outline-none transition focus:border-blue-900 focus:ring-2 focus:ring-blue-900/10"
                                            >
                                        </div>
                                    </div>

                                    <div>
                                        <label for="releaseDate" class="mb-2 block text-sm font-semibold">
                                            Tanggal rilis
                                        </label>

                                        <input
                                            id="releaseDate"
                                            name="release_date"
                                            type="date"
                                            class="w-full rounded-xl border border-slate-200 px-4 py-3 outline-none transition focus:border-blue-900"
                                        >
                                    </div>
                                </div>

                                <div class="grid gap-5 md:grid-cols-2">
                                    <div>
                                        <label for="versionStatus" class="mb-2 block text-sm font-semibold">
                                            Status versi
                                            <span class="text-red-500">*</span>
                                        </label>

                                        <select
                                            id="versionStatus"
                                            name="version_status"
                                            required
                                            class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 outline-none transition focus:border-blue-900"
                                        >
                                            <option value="stable">Stable</option>
                                            <option value="beta">Beta</option>
                                            <option value="draft">Draft</option>
                                            <option value="deprecated">Deprecated</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label for="applicationStatus" class="mb-2 block text-sm font-semibold">
                                            Status aplikasi
                                            <span class="text-red-500">*</span>
                                        </label>

                                        <select
                                            id="applicationStatus"
                                            name="status"
                                            required
                                            class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 outline-none transition focus:border-blue-900"
                                        >
                                            <option value="active">Aktif</option>
                                            <option value="inactive">Tidak Aktif</option>
                                            <option value="archived">Diarsipkan</option>
                                        </select>
                                    </div>
                                </div>

                                <div>
                                    <label for="releaseNotes" class="mb-2 block text-sm font-semibold">
                                        Catatan rilis
                                    </label>

                                    <textarea
                                        id="releaseNotes"
                                        name="release_notes"
                                        rows="4"
                                        placeholder="Tuliskan fitur utama atau perubahan pada versi ini..."
                                        class="w-full resize-y rounded-xl border border-slate-200 px-4 py-3 leading-7 outline-none transition focus:border-blue-900 focus:ring-2 focus:ring-blue-900/10"
                                    ></textarea>
                                </div>

                                <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-blue-200 bg-blue-50 p-4">
                                    <input
                                        id="isCurrentVersion"
                                        name="is_current"
                                        type="checkbox"
                                        value="1"
                                        checked
                                        class="mt-1 h-4 w-4 rounded border-slate-300 text-blue-900 focus:ring-blue-900"
                                    >

                                    <span>
                                        <span class="block text-sm font-semibold text-blue-950">
                                            Jadikan sebagai versi saat ini
                                        </span>

                                        <span class="mt-1 block text-sm leading-6 text-blue-900/70">
                                            Versi ini akan tampil sebagai versi utama pada halaman aplikasi.
                                        </span>
                                    </span>
                                </label>
                            </div>
                        </section>

                        {{-- Optional settings --}}
                        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                            <div class="flex items-start gap-3">
                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">
                                    <i class="bi bi-sliders text-lg"></i>
                                </div>

                                <div>
                                    <h2 class="text-lg font-bold">
                                        Pengaturan Tambahan
                                    </h2>

                                    <p class="mt-1 text-sm text-slate-500">
                                        Pengaturan akses dan tampilan aplikasi.
                                    </p>
                                </div>
                            </div>

                            <div class="mt-6 space-y-4">
                                <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-slate-200 p-4 transition hover:bg-slate-50">
                                    <input
                                        name="is_public"
                                        type="checkbox"
                                        value="1"
                                        checked
                                        class="mt-1 h-4 w-4 rounded border-slate-300 text-blue-900 focus:ring-blue-900"
                                    >

                                    <span>
                                        <span class="block text-sm font-semibold">
                                            Tampilkan pada halaman publik
                                        </span>

                                        <span class="mt-1 block text-sm text-slate-500">
                                            Pengguna umum dapat melihat aplikasi dan pengetahuan yang telah dipublikasikan.
                                        </span>
                                    </span>
                                </label>

                                <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-slate-200 p-4 transition hover:bg-slate-50">
                                    <input
                                        name="allow_feedback"
                                        type="checkbox"
                                        value="1"
                                        checked
                                        class="mt-1 h-4 w-4 rounded border-slate-300 text-blue-900 focus:ring-blue-900"
                                    >

                                    <span>
                                        <span class="block text-sm font-semibold">
                                            Izinkan feedback pengguna
                                        </span>

                                        <span class="mt-1 block text-sm text-slate-500">
                                            Pengguna dapat memberikan penilaian terhadap artikel aplikasi.
                                        </span>
                                    </span>
                                </label>
                            </div>
                        </section>
                    </div>

                    {{-- Right settings --}}
                    <aside class="space-y-6">

                        {{-- Publication --}}
                        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                            <h2 class="font-bold">
                                Publikasi
                            </h2>

                            <p class="mt-1 text-sm text-slate-500">
                                Periksa informasi sebelum menyimpan aplikasi.
                            </p>

                            <div class="mt-5 space-y-4">
                                <div class="rounded-xl bg-slate-50 p-4">
                                    <div class="flex items-center justify-between gap-3">
                                        <span class="text-sm text-slate-500">
                                            Status aplikasi
                                        </span>

                                        <span
                                            id="statusPreview"
                                            class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700"
                                        >
                                            Aktif
                                        </span>
                                    </div>

                                    <div class="mt-3 flex items-center justify-between gap-3">
                                        <span class="text-sm text-slate-500">
                                            Versi saat ini
                                        </span>

                                        <span
                                            id="versionPreview"
                                            class="rounded-full bg-blue-100 px-2.5 py-1 text-xs font-semibold text-blue-900"
                                        >
                                            v1.0.0
                                        </span>
                                    </div>
                                </div>

                                <button
                                    id="publicationSaveButton"
                                    type="submit"
                                    class="w-full rounded-xl bg-blue-900 px-4 py-3 font-semibold text-white transition hover:bg-blue-950"
                                >
                                    <i class="bi bi-floppy mr-2"></i>
                                    Simpan Aplikasi
                                </button>

                                <button
                                    type="button"
                                    class="w-full rounded-xl border border-slate-200 px-4 py-3 font-semibold text-slate-700 transition hover:bg-slate-50"
                                >
                                    Simpan sebagai Draft
                                </button>
                            </div>
                        </section>

                        {{-- Logo --}}
                        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                            <h2 class="font-bold">
                                Logo Aplikasi
                            </h2>

                            <p class="mt-1 text-sm text-slate-500">
                                Disarankan menggunakan gambar persegi.
                            </p>

                            <label class="mt-4 flex cursor-pointer flex-col items-center rounded-xl border-2 border-dashed border-slate-300 p-6 text-center transition hover:border-blue-400 hover:bg-blue-50">
                                <div
                                    id="logoPlaceholder"
                                    class="flex h-20 w-20 items-center justify-center rounded-2xl bg-slate-100 text-slate-400"
                                >
                                    <i class="bi bi-image text-2xl"></i>
                                </div>

                                <img
                                    id="logoPreview"
                                    src=""
                                    alt="Preview logo aplikasi"
                                    class="hidden h-20 w-20 rounded-2xl object-contain"
                                >

                                <span class="mt-3 text-sm font-semibold">
                                    Pilih logo
                                </span>

                                <span class="mt-1 text-xs text-slate-500">
                                    PNG, JPG, WEBP · Maks. 2 MB
                                </span>

                                <input
                                    id="logoInput"
                                    name="logo"
                                    type="file"
                                    accept="image/png,image/jpeg,image/webp"
                                    class="hidden"
                                >
                            </label>
                        </section>

                        {{-- Cover --}}
                        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                            <h2 class="font-bold">
                                Cover Aplikasi
                            </h2>

                            <p class="mt-1 text-sm text-slate-500">
                                Digunakan pada kartu dan halaman detail aplikasi.
                            </p>

                            <label class="mt-4 block cursor-pointer overflow-hidden rounded-xl border-2 border-dashed border-slate-300 transition hover:border-blue-400">
                                <div
                                    id="coverPlaceholder"
                                    class="flex aspect-video items-center justify-center bg-slate-50 text-slate-400"
                                >
                                    <div class="text-center">
                                        <i class="bi bi-card-image text-2xl"></i>
                                        <p class="mt-2 text-sm font-medium">Pilih cover</p>
                                    </div>
                                </div>

                                <img
                                    id="coverPreview"
                                    src=""
                                    alt="Preview cover aplikasi"
                                    class="hidden aspect-video w-full object-cover"
                                >

                                <input
                                    id="coverInput"
                                    name="cover"
                                    type="file"
                                    accept="image/png,image/jpeg,image/webp"
                                    class="hidden"
                                >
                            </label>
                        </section>
                    </aside>
                </form>
            </div>
        </main>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const openSidebar = document.getElementById('openSidebar');
        const closeSidebar = document.getElementById('closeSidebar');
        const toggleDesktopSidebar = document.getElementById('toggleDesktopSidebar');
        const desktopSidebarIcon = document.getElementById('desktopSidebarIcon');

        const navbarSaveButton = document.getElementById('navbarSaveButton');
        const publicationSaveButton = document.getElementById('publicationSaveButton');

        const applicationName = document.getElementById('applicationName');
        const applicationSlug = document.getElementById('applicationSlug');
        const applicationDescription = document.getElementById('applicationDescription');
        const descriptionCount = document.getElementById('descriptionCount');
        const versionNumber = document.getElementById('versionNumber');
        const versionPreview = document.getElementById('versionPreview');
        const applicationStatus = document.getElementById('applicationStatus');
        const statusPreview = document.getElementById('statusPreview');

        const logoInput = document.getElementById('logoInput');
        const logoPreview = document.getElementById('logoPreview');
        const logoPlaceholder = document.getElementById('logoPlaceholder');

        const coverInput = document.getElementById('coverInput');
        const coverPreview = document.getElementById('coverPreview');
        const coverPlaceholder = document.getElementById('coverPlaceholder');

        let desktopSidebarCollapsed = false;
        let slugWasEdited = false;

        function showSidebar() {
            sidebar?.classList.remove('-translate-x-full');
            sidebarOverlay?.classList.remove('hidden');
        }

        function hideSidebar() {
            sidebar?.classList.add('-translate-x-full');
            sidebarOverlay?.classList.add('hidden');
        }

        function setDesktopSidebarCollapsed(collapsed) {
            desktopSidebarCollapsed = collapsed;

            sidebar?.classList.toggle('lg:w-20', collapsed);
            sidebar?.classList.toggle('lg:w-72', !collapsed);

            document
                .querySelectorAll('.sidebar-label, .sidebar-section-label')
                .forEach((element) => {
                    element.classList.toggle('lg:hidden', collapsed);
                });

            document.querySelectorAll('.sidebar-link').forEach((link) => {
                link.classList.toggle('lg:justify-center', collapsed);
                link.classList.toggle('lg:px-0', collapsed);
            });

            desktopSidebarIcon?.classList.toggle(
                'bi-layout-sidebar-inset',
                !collapsed
            );

            desktopSidebarIcon?.classList.toggle(
                'bi-layout-sidebar-inset-reverse',
                collapsed
            );

            toggleDesktopSidebar?.setAttribute(
                'aria-expanded',
                String(!collapsed)
            );

            toggleDesktopSidebar?.setAttribute(
                'aria-label',
                collapsed ? 'Perluas sidebar' : 'Ciutkan sidebar'
            );
        }

        function createSlug(value) {
            return value
                .toLowerCase()
                .trim()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');
        }

        function updateVersionPreview() {
            const value = versionNumber?.value.trim() || '1.0.0';
            versionPreview.textContent = `v${value}`;
        }

        function updateStatusPreview() {
            const statusLabels = {
                active: 'Aktif',
                inactive: 'Tidak Aktif',
                archived: 'Diarsipkan',
            };

            const statusClasses = {
                active: ['bg-emerald-100', 'text-emerald-700'],
                inactive: ['bg-amber-100', 'text-amber-700'],
                archived: ['bg-slate-200', 'text-slate-700'],
            };

            const currentStatus = applicationStatus?.value || 'active';

            statusPreview.textContent = statusLabels[currentStatus];

            statusPreview.className =
                'rounded-full px-2.5 py-1 text-xs font-semibold';

            statusPreview.classList.add(...statusClasses[currentStatus]);
        }

        function previewImage(input, preview, placeholder) {
            const file = input?.files?.[0];

            if (!file) {
                preview?.classList.add('hidden');
                placeholder?.classList.remove('hidden');
                return;
            }

            preview.src = URL.createObjectURL(file);
            preview.classList.remove('hidden');
            placeholder?.classList.add('hidden');
        }

        openSidebar?.addEventListener('click', showSidebar);
        closeSidebar?.addEventListener('click', hideSidebar);
        sidebarOverlay?.addEventListener('click', hideSidebar);

        toggleDesktopSidebar?.addEventListener('click', () => {
            setDesktopSidebarCollapsed(!desktopSidebarCollapsed);
        });

        navbarSaveButton?.addEventListener('click', () => {
            publicationSaveButton?.click();
        });

        applicationName?.addEventListener('input', () => {
            if (!slugWasEdited) {
                applicationSlug.value = createSlug(applicationName.value);
            }
        });

        applicationSlug?.addEventListener('input', () => {
            slugWasEdited = applicationSlug.value.trim() !== '';
        });

        applicationDescription?.addEventListener('input', () => {
            if (applicationDescription.value.length > 500) {
                applicationDescription.value =
                    applicationDescription.value.slice(0, 500);
            }

            descriptionCount.textContent =
                `${applicationDescription.value.length}/500`;
        });

        versionNumber?.addEventListener('input', updateVersionPreview);
        applicationStatus?.addEventListener('change', updateStatusPreview);

        logoInput?.addEventListener('change', () => {
            previewImage(logoInput, logoPreview, logoPlaceholder);
        });

        coverInput?.addEventListener('change', () => {
            previewImage(coverInput, coverPreview, coverPlaceholder);
        });

        updateVersionPreview();
        updateStatusPreview();
    </script>
</body>
</html>
