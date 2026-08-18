<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Admin - Kelola Pengetahuan</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Sesuaikan lokasi Bootstrap Icons --}}

</head>

<body class="min-h-screen bg-slate-100 text-slate-900">

    {{-- Navbar --}}
    <header class="sticky top-0 z-50 h-20 border-b border-slate-200 bg-white">
        <div class="flex h-full items-center justify-between px-4 lg:px-6">

            {{-- Left navbar --}}
            <div class="flex items-center gap-3">
                <button
                    id="openSidebar"
                    type="button"
                    class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 lg:hidden"
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
                    <i id="desktopSidebarIcon" class="bi bi-layout-sidebar-inset text-lg"></i>
                </button>

                <a
                    href="/"
                    class="flex items-center gap-3"
                >
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

                {{-- Tombol Add terhubung ke menu tambah --}}
                <a
                    href="{{ url('/admin/pengetahuan/tambah') }}"
                    class="inline-flex items-center gap-2 rounded-xl bg-blue-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-950"
                >
                    <i class="bi bi-plus-lg"></i>

                    <span class="hidden sm:inline">
                        Tambah Konten
                    </span>
                </a>

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
                    class="sidebar-link flex items-center gap-3 rounded-xl px-2 py-1.5 transition hover:bg-slate-100"
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

    {{-- Sidebar --}}
    <aside
        id="sidebar"
        class="fixed bottom-0 left-0 top-20 z-40 w-72 -translate-x-full overflow-x-hidden overflow-y-auto border-r border-slate-200 bg-white transition-all duration-300 lg:translate-x-0"
    >
        <div class="p-5">

            <div class="mb-4 flex items-center justify-between lg:hidden">
                <p class="font-bold">
                    Menu Administrator
                </p>

                <button
                    id="closeSidebar"
                    type="button"
                    class="flex h-9 w-9 items-center justify-center rounded-lg text-slate-500 hover:bg-slate-100"
                >
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <p class="sidebar-section-label mb-3 px-3 text-xs font-semibold uppercase tracking-wider text-slate-400">
                Menu Utama
            </p>

            <nav class="space-y-1">

                <a
                    href="/admin_dashboard"
                    class="sidebar-link flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-blue-900"
                >
                    <i class="bi bi-grid shrink-0 text-lg"></i>
                    <span class="sidebar-label whitespace-nowrap">Dashboard</span>
                </a>

                <a
                    href="{{ url('admin/content-index') }}"
                    class="sidebar-link flex items-center gap-3 rounded-xl bg-blue-50 px-3 py-3 text-sm font-semibold text-blue-900"
                >
                    <i class="bi bi-journal-text shrink-0 text-lg"></i>
                    <span class="sidebar-label whitespace-nowrap">Daftar Pengetahuan</span>

                    <span class="sidebar-label ml-auto rounded-full bg-blue-100 px-2 py-0.5 text-xs">
                        24
                    </span>
                </a>

                <a
                    href="{{ url('admin/input') }}"
                    class="sidebar-link flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-blue-900"
                >
                    <i class="bi bi-plus-square shrink-0 text-lg"></i>
                    <span class="sidebar-label whitespace-nowrap">Tambah Pengetahuan</span>
                </a>

                <a
                    href="{{ url('admin/category/index') }}"
                    class="sidebar-link flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-blue-900"
                >
                    <i class="bi bi-folder2-open shrink-0 text-lg"></i>
                    <span class="sidebar-label whitespace-nowrap">Kategori</span>
                </a>

                <a
                    href="{{ url('admin/aplikasi') }}"
                    class="sidebar-link flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-blue-900"
                >
                    <i class="bi bi-window-stack shrink-0 text-lg"></i>
                    <span class="sidebar-label whitespace-nowrap">Daftar Aplikasi</span>
                </a>
            </nav>

            <p class="mb-3 mt-8 px-3 text-xs font-semibold uppercase tracking-wider text-slate-400">
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
                    href="{{ url('/admin/komentar') }}"
                    class="sidebar-link flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-blue-900"
                >
                    <i class="bi bi-chat-left-text shrink-0 text-lg"></i>
                    <span class="sidebar-label whitespace-nowrap">Komentar</span>
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

            <p class="mb-3 mt-8 px-3 text-xs font-semibold uppercase tracking-wider text-slate-400">
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
    <main id="adminMain" class="transition-[padding] duration-300 lg:pl-72">
        <div class="p-5 lg:p-8">

            {{-- Breadcrumb --}}
            <nav class="mb-5 flex items-center gap-2 text-sm text-slate-500">
                <a href="#" class="hover:text-blue-900">
                    Admin
                </a>

                <i class="bi bi-chevron-right text-xs"></i>

                <span class="font-medium text-slate-800">
                    Daftar Pengetahuan
                </span>
            </nav>

            {{-- Page header --}}
            <div class="mb-7 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight sm:text-3xl">
                        Daftar Pengetahuan
                    </h1>

                    <p class="mt-2 text-sm text-slate-500">
                        Kelola seluruh tutorial dan aplikasi yang tersedia pada sistem.
                    </p>
                </div>

                <a
                    href="{{ url('/input') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-950"
                >
                    <i class="bi bi-plus-lg"></i>
                    Tambah Pengetahuan
                </a>
            </div>

            {{-- Summary cards --}}
            <div class="mb-7 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">

                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-500">
                                Total aplikasi
                            </p>

                            <p class="mt-2 text-3xl font-bold">
                                24
                            </p>
                        </div>

                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100 text-blue-900">
                            <i class="bi bi-journal-richtext text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-500">
                                Pengetahuan
                            </p>

                            <p class="mt-2 text-3xl font-bold">
                                18
                            </p>
                        </div>

                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">
                            <i class="bi bi-book text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-500">
                                Admin
                            </p>

                            <p class="mt-2 text-3xl font-bold">
                                6
                            </p>
                        </div>

                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-violet-100 text-violet-700">
                            <i class="bi bi-window text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-500">
                                Draft
                            </p>

                            <p class="mt-2 text-3xl font-bold">
                                4
                            </p>
                        </div>

                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-100 text-amber-700">
                            <i class="bi bi-pencil-square text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- List card --}}
            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                {{-- Search and filter --}}
                <div class="border-b border-slate-200 p-5">
                    <div class="grid gap-4 lg:grid-cols-[1fr_180px_180px_auto]">

                        <div class="relative">
                            <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>

                            <input
                                id="knowledgeSearch"
                                type="search"
                                placeholder="Cari judul tutorial atau aplikasi..."
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm outline-none focus:border-blue-900 focus:bg-white focus:ring-2 focus:ring-blue-900/10"
                            >
                        </div>

                        <select
                            id="typeFilter"
                            class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-blue-900"
                        >
                            <option value="all">Semua Jenis</option>
                            <option value="tutorial">Tutorial</option>
                            <option value="application">Aplikasi</option>
                        </select>

                        <select
                            id="statusFilter"
                            class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-blue-900"
                        >
                            <option value="all">Semua Status</option>
                            <option value="published">Dipublikasikan</option>
                            <option value="draft">Draft</option>
                            <option value="review">Menunggu Tinjauan</option>
                        </select>

                        <button
                            id="resetFilter"
                            type="button"
                            class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 px-4 py-3 text-sm font-medium text-slate-600 hover:bg-slate-50"
                        >
                            <i class="bi bi-arrow-counterclockwise"></i>
                            Reset
                        </button>
                    </div>
                </div>

                {{-- Desktop table --}}
                <div class="hidden overflow-x-auto md:block">
                    <table class="w-full min-w-[1000px]">
                        <thead class="bg-slate-50">
                            <tr class="text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                <th class="px-5 py-4">Konten</th>
                                <th class="px-5 py-4">Jenis</th>
                                <th class="px-5 py-4">Kategori</th>
                                <th class="px-5 py-4">Status</th>
                                <th class="px-5 py-4">Diperbarui</th>
                                <th class="px-5 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>

                        <tbody
                            id="knowledgeTable"
                            class="divide-y divide-slate-100"
                        >

                            {{-- Tutorial 1 --}}
                            <tr
                                class="knowledge-row hover:bg-slate-50"
                                data-title="Cara Menginstal Laravel Menggunakan Docker"
                                data-type="tutorial"
                                data-status="published"
                            >
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-4">
                                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-900">
                                            <i class="bi bi-play-btn text-xl"></i>
                                        </div>

                                        <div>
                                            <p class="font-semibold text-slate-900">
                                                Cara Menginstal Laravel Menggunakan Docker
                                            </p>

                                            <p class="mt-1 max-w-md truncate text-sm text-slate-500">
                                                Tutorial instalasi Laravel menggunakan Docker Compose.
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-4">
                                    <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                        Tutorial
                                    </span>
                                </td>

                                <td class="px-5 py-4 text-sm text-slate-600">
                                    Infrastruktur
                                </td>

                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center gap-1.5 text-sm font-medium text-emerald-700">
                                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                        Dipublikasikan
                                    </span>
                                </td>

                                <td class="px-5 py-4 text-sm text-slate-500">
                                    15 Juli 2026
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex justify-end gap-2">

                                        <a
                                            href="#"
                                            class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-600 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-900"
                                            title="Lihat"
                                        >
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <a
                                            href="{{ url('/admin/pengetahuan/1/edit') }}"
                                            class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-600 transition hover:border-amber-200 hover:bg-amber-50 hover:text-amber-700"
                                            title="Edit"
                                        >
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        <button
                                            type="button"
                                            class="delete-button flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-600 transition hover:border-red-200 hover:bg-red-50 hover:text-red-600"
                                            title="Hapus"
                                            data-title="Cara Menginstal Laravel Menggunakan Docker"
                                        >
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            {{-- Application --}}
                            <tr
                                class="knowledge-row hover:bg-slate-50"
                                data-title="Sistem Informasi Pengajuan Cuti"
                                data-type="application"
                                data-status="published"
                            >
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-4">
                                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-violet-100 text-violet-700">
                                            <i class="bi bi-window-stack text-xl"></i>
                                        </div>

                                        <div>
                                            <p class="font-semibold text-slate-900">
                                                Sistem Informasi Pengajuan Cuti
                                            </p>

                                            <p class="mt-1 max-w-md truncate text-sm text-slate-500">
                                                Aplikasi internal untuk pengajuan dan persetujuan cuti.
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-4">
                                    <span class="inline-flex rounded-full bg-violet-100 px-3 py-1 text-xs font-semibold text-violet-700">
                                        Aplikasi
                                    </span>
                                </td>

                                <td class="px-5 py-4 text-sm text-slate-600">
                                    Aplikasi Internal
                                </td>

                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center gap-1.5 text-sm font-medium text-emerald-700">
                                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                        Dipublikasikan
                                    </span>
                                </td>

                                <td class="px-5 py-4 text-sm text-slate-500">
                                    14 Juli 2026
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex justify-end gap-2">
                                        <a
                                            href="#"
                                            class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-600 hover:bg-blue-50 hover:text-blue-900"
                                            title="Lihat"
                                        >
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <a
                                            href="#"
                                            class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-600 hover:bg-amber-50 hover:text-amber-700"
                                            title="Edit"
                                        >
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        <button
                                            type="button"
                                            class="delete-button flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-600 hover:bg-red-50 hover:text-red-600"
                                            title="Hapus"
                                            data-title="Sistem Informasi Pengajuan Cuti"
                                        >
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            {{-- Tutorial draft --}}
                            <tr
                                class="knowledge-row hover:bg-slate-50"
                                data-title="Memahami Relasi Database Laravel"
                                data-type="tutorial"
                                data-status="draft"
                            >
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-4">
                                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-900">
                                            <i class="bi bi-file-text text-xl"></i>
                                        </div>

                                        <div>
                                            <p class="font-semibold text-slate-900">
                                                Memahami Relasi Database Laravel
                                            </p>

                                            <p class="mt-1 max-w-md truncate text-sm text-slate-500">
                                                Penjelasan relasi one-to-one, one-to-many, dan many-to-many.
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-4">
                                    <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                        Tutorial
                                    </span>
                                </td>

                                <td class="px-5 py-4 text-sm text-slate-600">
                                    Basis Data
                                </td>

                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center gap-1.5 text-sm font-medium text-amber-700">
                                        <span class="h-2 w-2 rounded-full bg-amber-500"></span>
                                        Draft
                                    </span>
                                </td>

                                <td class="px-5 py-4 text-sm text-slate-500">
                                    13 Juli 2026
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex justify-end gap-2">
                                        <a
                                            href="#"
                                            class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-600 hover:bg-blue-50 hover:text-blue-900"
                                            title="Lihat"
                                        >
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <a
                                            href="#"
                                            class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-600 hover:bg-amber-50 hover:text-amber-700"
                                            title="Edit"
                                        >
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        <button
                                            type="button"
                                            class="delete-button flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-600 hover:bg-red-50 hover:text-red-600"
                                            title="Hapus"
                                            data-title="Memahami Relasi Database Laravel"
                                        >
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Mobile list --}}
                <div class="divide-y divide-slate-100 md:hidden">
                    <article class="p-5">
                        <div class="flex items-start gap-3">
                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-900">
                                <i class="bi bi-play-btn"></i>
                            </div>

                            <div class="min-w-0 flex-1">
                                <p class="font-semibold">
                                    Cara Menginstal Laravel Menggunakan Docker
                                </p>

                                <div class="mt-2 flex flex-wrap gap-2">
                                    <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                                        Tutorial
                                    </span>

                                    <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs text-slate-600">
                                        Infrastruktur
                                    </span>
                                </div>

                                <div class="mt-4 flex gap-2">
                                    <a
                                        href="#"
                                        class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200"
                                    >
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    <a
                                        href="#"
                                        class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-amber-700"
                                    >
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    <button
                                        type="button"
                                        class="delete-button flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-red-600"
                                        data-title="Cara Menginstal Laravel Menggunakan Docker"
                                    >
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </article>
                </div>

                {{-- Empty state --}}
                <div
                    id="emptyResult"
                    class="hidden px-6 py-16 text-center"
                >
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                        <i class="bi bi-search text-2xl"></i>
                    </div>

                    <p class="mt-4 font-semibold">
                        Konten tidak ditemukan
                    </p>

                    <p class="mt-2 text-sm text-slate-500">
                        Coba gunakan kata kunci atau filter lain.
                    </p>
                </div>

                {{-- Pagination --}}
                <div class="flex flex-col gap-4 border-t border-slate-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-sm text-slate-500">
                        Menampilkan 1–3 dari 24 data
                    </p>

                    <div class="flex items-center gap-1">
                        <button
                            type="button"
                            class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-500 hover:bg-slate-50"
                        >
                            <i class="bi bi-chevron-left"></i>
                        </button>

                        <button
                            type="button"
                            class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-900 text-sm font-semibold text-white"
                        >
                            1
                        </button>

                        <button
                            type="button"
                            class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-sm hover:bg-slate-50"
                        >
                            2
                        </button>

                        <button
                            type="button"
                            class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-sm hover:bg-slate-50"
                        >
                            3
                        </button>

                        <button
                            type="button"
                            class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-500 hover:bg-slate-50"
                        >
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </section>
        </div>
    </main>

    {{-- Delete modal --}}
    <div
        id="deleteModal"
        class="fixed inset-0 z-[60] hidden items-center justify-center bg-slate-950/50 p-4"
    >
        <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl">
            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-red-100 text-red-600">
                <i class="bi bi-trash3 text-xl"></i>
            </div>

            <h2 class="mt-5 text-xl font-bold">
                Hapus konten?
            </h2>

            <p class="mt-2 text-sm leading-6 text-slate-500">
                Konten
                <strong id="deleteItemName" class="text-slate-700"></strong>
                akan dihapus. Tindakan ini belum benar-benar menghapus data karena halaman masih frontend-only.
            </p>

            <div class="mt-6 flex justify-end gap-3">
                <button
                    id="cancelDelete"
                    type="button"
                    class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50"
                >
                    Batal
                </button>

                <button
                    id="confirmDelete"
                    type="button"
                    class="rounded-xl bg-red-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-red-700"
                >
                    Hapus
                </button>
            </div>
        </div>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const openSidebar = document.getElementById('openSidebar');
        const closeSidebar = document.getElementById('closeSidebar');
        const toggleDesktopSidebar = document.getElementById('toggleDesktopSidebar');
        const desktopSidebarIcon = document.getElementById('desktopSidebarIcon');
        const adminMain = document.getElementById('adminMain');

        let desktopSidebarCollapsed = false;

        function setDesktopSidebarCollapsed(collapsed) {
            desktopSidebarCollapsed = collapsed;

            sidebar?.classList.toggle('lg:w-20', collapsed);
            sidebar?.classList.toggle('lg:w-72', !collapsed);

            adminMain?.classList.toggle('lg:pl-20', collapsed);
            adminMain?.classList.toggle('lg:pl-72', !collapsed);

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

        toggleDesktopSidebar?.addEventListener('click', () => {
            setDesktopSidebarCollapsed(!desktopSidebarCollapsed);
        });

        function showSidebar() {
            sidebar?.classList.remove('-translate-x-full');
            sidebarOverlay?.classList.remove('hidden');
        }

        function hideSidebar() {
            sidebar?.classList.add('-translate-x-full');
            sidebarOverlay?.classList.add('hidden');
        }

        openSidebar?.addEventListener('click', showSidebar);
        closeSidebar?.addEventListener('click', hideSidebar);
        sidebarOverlay?.addEventListener('click', hideSidebar);

        const searchInput = document.getElementById('knowledgeSearch');
        const typeFilter = document.getElementById('typeFilter');
        const statusFilter = document.getElementById('statusFilter');
        const resetFilter = document.getElementById('resetFilter');
        const rows = document.querySelectorAll('.knowledge-row');
        const emptyResult = document.getElementById('emptyResult');

        function filterKnowledge() {
            const keyword = searchInput.value.trim().toLowerCase();
            const selectedType = typeFilter.value;
            const selectedStatus = statusFilter.value;

            let visibleTotal = 0;

            rows.forEach((row) => {
                const title = row.dataset.title.toLowerCase();
                const type = row.dataset.type;
                const status = row.dataset.status;

                const matchesKeyword = title.includes(keyword);
                const matchesType =
                    selectedType === 'all' || type === selectedType;
                const matchesStatus =
                    selectedStatus === 'all' || status === selectedStatus;

                const visible =
                    matchesKeyword &&
                    matchesType &&
                    matchesStatus;

                row.classList.toggle('hidden', !visible);

                if (visible) {
                    visibleTotal++;
                }
            });

            emptyResult?.classList.toggle('hidden', visibleTotal !== 0);
        }

        searchInput?.addEventListener('input', filterKnowledge);
        typeFilter?.addEventListener('change', filterKnowledge);
        statusFilter?.addEventListener('change', filterKnowledge);

        resetFilter?.addEventListener('click', () => {
            searchInput.value = '';
            typeFilter.value = 'all';
            statusFilter.value = 'all';

            filterKnowledge();
        });

        const deleteModal = document.getElementById('deleteModal');
        const deleteItemName = document.getElementById('deleteItemName');
        const cancelDelete = document.getElementById('cancelDelete');
        const confirmDelete = document.getElementById('confirmDelete');

        let selectedDeleteButton = null;

        document.querySelectorAll('.delete-button').forEach((button) => {
            button.addEventListener('click', () => {
                selectedDeleteButton = button;

                deleteItemName.textContent =
                    `"${button.dataset.title}"`;

                deleteModal.classList.remove('hidden');
                deleteModal.classList.add('flex');
            });
        });

        function closeDeleteModal() {
            deleteModal.classList.add('hidden');
            deleteModal.classList.remove('flex');
            selectedDeleteButton = null;
        }

        cancelDelete?.addEventListener('click', closeDeleteModal);

        deleteModal?.addEventListener('click', (event) => {
            if (event.target === deleteModal) {
                closeDeleteModal();
            }
        });

        confirmDelete?.addEventListener('click', () => {
            selectedDeleteButton
                ?.closest('.knowledge-row, article')
                ?.remove();

            closeDeleteModal();
        });
    </script>
</body>
</html>