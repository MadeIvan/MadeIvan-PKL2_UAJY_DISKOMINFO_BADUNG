<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Admin Dashboard</title>

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
                    href="{{ url('admin/input') }}"
                    class="inline-flex items-center gap-2 rounded-xl bg-blue-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-950"
                >
                    <i class="bi bi-plus-lg"></i>

                    <span class="hidden sm:inline">
                        Tambah Pengetahuan
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
                    <p class="font-bold">
                        Menu Administrator
                    </p>

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
                        class="sidebar-link flex items-center gap-3 rounded-xl bg-blue-50 px-3 py-3 text-sm font-semibold text-blue-900"
                    >
                        <i class="bi bi-grid shrink-0 text-lg"></i>
                        <span class="sidebar-label whitespace-nowrap">
                            Dashboard
                        </span>
                    </a>

                    <a
                        href="{{ url('/admin/add_app') }}"
                        class="sidebar-link flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-blue-900"
                    >
                        <i class="bi bi-journal-text shrink-0 text-lg"></i>
                        <span class="sidebar-label whitespace-nowrap">
                            Daftar Aplikasi
                        </span>
                    </a>

                    <a
                        href="{{ url('admin/input') }}"
                        class="sidebar-link flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-blue-900"
                    >
                        <i class="bi bi-plus-square shrink-0 text-lg"></i>
                        <span class="sidebar-label whitespace-nowrap">
                            Tambah Pengetahuan
                        </span>
                    </a>

                    <a
                        href="{{ url('/admin/category/index') }}"
                        class="sidebar-link flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-blue-900"
                    >
                        <i class="bi bi-folder2-open shrink-0 text-lg"></i>
                        <span class="sidebar-label whitespace-nowrap">
                            Kategori
                        </span>
                    </a>

                    <a
                        href="{{ url('/admin/add_app') }}"
                        class="sidebar-link flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-blue-900"
                    >
                        <i class="bi bi-window-stack shrink-0 text-lg"></i>
                        <span class="sidebar-label whitespace-nowrap">
                            Daftar Aplikasi
                        </span>
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
                        <span class="sidebar-label whitespace-nowrap">
                            Pengguna
                        </span>
                    </a>

                    <a
                        href="{{ url('/admin/media') }}"
                        class="sidebar-link flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-blue-900"
                    >
                        <i class="bi bi-images shrink-0 text-lg"></i>
                        <span class="sidebar-label whitespace-nowrap">
                            Media
                        </span>
                    </a>

                    <a
                        href="{{ url('/admin/log-aktivitas') }}"
                        class="sidebar-link flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-blue-900"
                    >
                        <i class="bi bi-clock-history shrink-0 text-lg"></i>
                        <span class="sidebar-label whitespace-nowrap">
                            Log Aktivitas
                        </span>
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
                        <span class="sidebar-label whitespace-nowrap">
                            Pengaturan
                        </span>
                    </a>

                    <a
                        href="#"
                        class="sidebar-link flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium text-red-600 transition hover:bg-red-50"
                    >
                        <i class="bi bi-box-arrow-right shrink-0 text-lg"></i>
                        <span class="sidebar-label whitespace-nowrap">
                            Keluar
                        </span>
                    </a>
                </nav>
            </div>
        </aside>

        {{-- Mobile sidebar overlay --}}
        <div
            id="sidebarOverlay"
            class="fixed inset-0 top-20 z-30 hidden bg-slate-950/40 lg:hidden"
        ></div>

        {{-- Dashboard Content --}}
        <main
            id="adminMain"
            class="min-w-0 flex-1 transition-all duration-300"
        >
            <div class="mx-auto max-w-[1600px] p-5 lg:p-8">

                {{-- Breadcrumb --}}
                <nav class="mb-5 flex items-center gap-2 text-sm text-slate-500">
                    <a href="/admin/admin_dashboard" class="hover:text-blue-900">
                        Admin
                    </a>

                    <i class="bi bi-chevron-right text-xs"></i>

                    <span class="font-medium text-slate-800">
                        Dashboard
                    </span>
                </nav>

                {{-- Page heading --}}
                <div class="mb-7 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <h1 class="text-2xl font-bold tracking-tight sm:text-3xl">
                            Dashboard Administrator
                        </h1>

                        <p class="mt-2 text-sm text-slate-500">
                            Ringkasan kondisi konten dan aktivitas sistem pengetahuan.
                        </p>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <a
                            href="{{ url('/admin/add_app') }}"
                            class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                        >
                            <i class="bi bi-window-plus"></i>
                            Tambah Aplikasi
                        </a>

                        <a
                            href="{{ url('admin/input') }}"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-950"
                        >
                            <i class="bi bi-plus-lg"></i>
                            Tambah Pengetahuan
                        </a>
                    </div>
                </div>

                {{-- Summary cards --}}
                <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-6">
                    @php
                        $summaryCards = [
                            ['label' => 'Total Pengetahuan', 'value' => 124, 'icon' => 'bi-journal-text', 'box' => 'bg-blue-100 text-blue-900'],
                            ['label' => 'Total Aplikasi', 'value' => 8, 'icon' => 'bi-window-stack', 'box' => 'bg-violet-100 text-violet-700'],
                            ['label' => 'Dipublikasikan', 'value' => 96, 'icon' => 'bi-check-circle', 'box' => 'bg-emerald-100 text-emerald-700'],
                            ['label' => 'Draft', 'value' => 14, 'icon' => 'bi-pencil-square', 'box' => 'bg-amber-100 text-amber-700'],
                            ['label' => 'Menunggu Tinjauan', 'value' => 6, 'icon' => 'bi-hourglass-split', 'box' => 'bg-orange-100 text-orange-700'],
                            ['label' => 'Total Pengguna', 'value' => 37, 'icon' => 'bi-people', 'box' => 'bg-cyan-100 text-cyan-700'],
                        ];
                    @endphp

                    @foreach ($summaryCards as $card)
                        <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="text-sm text-slate-500">
                                        {{ $card['label'] }}
                                    </p>

                                    <p class="mt-2 text-3xl font-bold">
                                        {{ $card['value'] }}
                                    </p>
                                </div>

                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl {{ $card['box'] }}">
                                    <i class="bi {{ $card['icon'] }} text-lg"></i>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </section>

                {{-- Main analytics --}}
                <section class="mt-7 grid gap-6 xl:grid-cols-[1.1fr_0.9fr]">

                    {{-- Content status --}}
                    <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-lg font-bold">
                                    Status Konten
                                </h2>

                                <p class="mt-1 text-sm text-slate-500">
                                    Distribusi status seluruh pengetahuan.
                                </p>
                            </div>

                            <a
                                href="{{ url('/admin/pengetahuan') }}"
                                class="text-sm font-semibold text-blue-900 hover:underline"
                            >
                                Lihat semua
                            </a>
                        </div>

                        <div class="mt-7 space-y-5">
                            @php
                                $statusItems = [
                                    ['label' => 'Dipublikasikan', 'value' => 96, 'percent' => 77, 'bar' => 'bg-emerald-500'],
                                    ['label' => 'Draft', 'value' => 14, 'percent' => 11, 'bar' => 'bg-amber-500'],
                                    ['label' => 'Menunggu Tinjauan', 'value' => 6, 'percent' => 5, 'bar' => 'bg-orange-500'],
                                    ['label' => 'Diarsipkan', 'value' => 8, 'percent' => 7, 'bar' => 'bg-slate-400'],
                                ];
                            @endphp

                            @foreach ($statusItems as $item)
                                <div>
                                    <div class="mb-2 flex items-center justify-between gap-4 text-sm">
                                        <span class="font-medium text-slate-700">
                                            {{ $item['label'] }}
                                        </span>

                                        <span class="text-slate-500">
                                            {{ $item['value'] }} konten
                                        </span>
                                    </div>

                                    <div class="h-2.5 overflow-hidden rounded-full bg-slate-100">
                                        <div
                                            class="h-full rounded-full {{ $item['bar'] }}"
                                            style="width: {{ $item['percent'] }}%"
                                        ></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </article>

                    {{-- Knowledge by application --}}
                    <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-lg font-bold">
                                    Pengetahuan per Aplikasi
                                </h2>

                                <p class="mt-1 text-sm text-slate-500">
                                    Jumlah materi berdasarkan aplikasi.
                                </p>
                            </div>

                            <i class="bi bi-bar-chart text-xl text-slate-400"></i>
                        </div>

                        <div class="mt-6 space-y-4">
                            @php
                                $applications = [
                                    ['name' => 'Sistem Cuti', 'value' => 32, 'percent' => 100],
                                    ['name' => 'Knowledge System', 'value' => 24, 'percent' => 75],
                                    ['name' => 'Buku Tamu Digital', 'value' => 18, 'percent' => 56],
                                    ['name' => 'Inventory System', 'value' => 14, 'percent' => 44],
                                    ['name' => 'Sistem Presensi', 'value' => 11, 'percent' => 34],
                                ];
                            @endphp

                            @foreach ($applications as $application)
                                <div>
                                    <div class="mb-2 flex items-center justify-between gap-4">
                                        <span class="truncate text-sm font-medium text-slate-700">
                                            {{ $application['name'] }}
                                        </span>

                                        <span class="text-sm font-semibold text-slate-900">
                                            {{ $application['value'] }}
                                        </span>
                                    </div>

                                    <div class="h-2 overflow-hidden rounded-full bg-slate-100">
                                        <div
                                            class="h-full rounded-full bg-blue-900"
                                            style="width: {{ $application['percent'] }}%"
                                        ></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </article>
                </section>

                {{-- Recent knowledge --}}
                <section class="mt-7 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex flex-col gap-3 border-b border-slate-200 p-5 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h2 class="text-lg font-bold">
                                Pengetahuan Terbaru
                            </h2>

                            <p class="mt-1 text-sm text-slate-500">
                                Konten yang baru dibuat atau diperbarui.
                            </p>
                        </div>

                        <a
                            href="{{ url('/admin/pengetahuan') }}"
                            class="text-sm font-semibold text-blue-900 hover:underline"
                        >
                            Lihat semua pengetahuan
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[850px]">
                            <thead class="bg-slate-50">
                                <tr class="text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                    <th class="px-5 py-4">Konten</th>
                                    <th class="px-5 py-4">Aplikasi</th>
                                    <th class="px-5 py-4">Status</th>
                                    <th class="px-5 py-4">Diperbarui</th>
                                    <th class="px-5 py-4 text-right">Aksi</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-100">
                                @php
                                    $recentKnowledge = [
                                        ['title' => 'Cara Menginstal Laravel Menggunakan Docker', 'app' => 'Knowledge System', 'status' => 'Dipublikasikan', 'statusClass' => 'bg-emerald-100 text-emerald-700', 'date' => 'Hari ini, 08.30'],
                                        ['title' => 'Panduan Pengajuan Cuti Karyawan', 'app' => 'Sistem Cuti', 'status' => 'Draft', 'statusClass' => 'bg-amber-100 text-amber-700', 'date' => 'Kemarin, 16.40'],
                                        ['title' => 'Konfigurasi Koneksi Database', 'app' => 'Knowledge System', 'status' => 'Menunggu Tinjauan', 'statusClass' => 'bg-orange-100 text-orange-700', 'date' => '18 Juli 2026'],
                                        ['title' => 'Panduan Check-in Buku Tamu', 'app' => 'Buku Tamu Digital', 'status' => 'Dipublikasikan', 'statusClass' => 'bg-emerald-100 text-emerald-700', 'date' => '17 Juli 2026'],
                                        ['title' => 'Manajemen Stok Barang', 'app' => 'Inventory System', 'status' => 'Dipublikasikan', 'statusClass' => 'bg-emerald-100 text-emerald-700', 'date' => '16 Juli 2026'],
                                    ];
                                @endphp

                                @foreach ($recentKnowledge as $item)
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-5 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-900">
                                                    <i class="bi bi-file-earmark-text"></i>
                                                </div>

                                                <p class="font-semibold text-slate-900">
                                                    {{ $item['title'] }}
                                                </p>
                                            </div>
                                        </td>

                                        <td class="px-5 py-4 text-sm text-slate-600">
                                            {{ $item['app'] }}
                                        </td>

                                        <td class="px-5 py-4">
                                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $item['statusClass'] }}">
                                                {{ $item['status'] }}
                                            </span>
                                        </td>

                                        <td class="px-5 py-4 text-sm text-slate-500">
                                            {{ $item['date'] }}
                                        </td>

                                        <td class="px-5 py-4">
                                            <div class="flex justify-end gap-2">
                                                <a
                                                    href="#"
                                                    class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-600 transition hover:bg-blue-50 hover:text-blue-900"
                                                    title="Lihat"
                                                >
                                                    <i class="bi bi-eye"></i>
                                                </a>

                                                <a
                                                    href="#"
                                                    class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-600 transition hover:bg-amber-50 hover:text-amber-700"
                                                    title="Edit"
                                                >
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>

                {{-- Activity and attention --}}
                <section class="mt-7 grid gap-6 xl:grid-cols-2">

                    {{-- Recent activity --}}
                    <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-lg font-bold">
                                    Aktivitas Terbaru
                                </h2>

                                <p class="mt-1 text-sm text-slate-500">
                                    Perubahan terakhir pada sistem.
                                </p>
                            </div>

                            <a
                                href="{{ url('/admin/log-aktivitas') }}"
                                class="text-sm font-semibold text-blue-900 hover:underline"
                            >
                                Lihat log
                            </a>
                        </div>

                        <div class="mt-6 space-y-5">
                            @php
                                $activities = [
                                    ['icon' => 'bi-pencil-square', 'box' => 'bg-blue-100 text-blue-900', 'text' => 'Administrator memperbarui “Instalasi Laravel”.', 'time' => '10 menit lalu'],
                                    ['icon' => 'bi-plus-lg', 'box' => 'bg-emerald-100 text-emerald-700', 'text' => 'Editor menambahkan “Panduan Login Sistem Cuti”.', 'time' => '1 jam lalu'],
                                    ['icon' => 'bi-file-earmark-arrow-up', 'box' => 'bg-violet-100 text-violet-700', 'text' => 'Administrator mengunggah “manual-user.pdf”.', 'time' => '3 jam lalu'],
                                    ['icon' => 'bi-trash3', 'box' => 'bg-red-100 text-red-600', 'text' => 'Administrator menghapus konten lama.', 'time' => 'Kemarin'],
                                ];
                            @endphp

                            @foreach ($activities as $activity)
                                <div class="flex gap-3">
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl {{ $activity['box'] }}">
                                        <i class="bi {{ $activity['icon'] }}"></i>
                                    </div>

                                    <div class="min-w-0">
                                        <p class="text-sm leading-6 text-slate-700">
                                            {{ $activity['text'] }}
                                        </p>

                                        <p class="mt-1 text-xs text-slate-400">
                                            {{ $activity['time'] }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </article>

                    {{-- Content needs attention --}}
                    <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div>
                            <h2 class="text-lg font-bold">
                                Perlu Perhatian
                            </h2>

                            <p class="mt-1 text-sm text-slate-500">
                                Konten yang memerlukan tindakan administrator.
                            </p>
                        </div>

                        <div class="mt-6 space-y-3">
                            <a
                                href="#"
                                class="flex items-center gap-4 rounded-xl border border-amber-200 bg-amber-50 p-4 transition hover:bg-amber-100/60"
                            >
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-700">
                                    <i class="bi bi-clock-history"></i>
                                </div>

                                <div class="min-w-0 flex-1">
                                    <p class="font-semibold text-slate-800">
                                        5 draft belum diperbarui selama 30 hari
                                    </p>

                                    <p class="mt-1 text-sm text-slate-500">
                                        Tinjau atau hapus draft yang tidak lagi digunakan.
                                    </p>
                                </div>

                                <i class="bi bi-chevron-right text-slate-400"></i>
                            </a>

                            <a
                                href="#"
                                class="flex items-center gap-4 rounded-xl border border-red-200 bg-red-50 p-4 transition hover:bg-red-100/60"
                            >
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-red-100 text-red-600">
                                    <i class="bi bi-link-45deg"></i>
                                </div>

                                <div class="min-w-0 flex-1">
                                    <p class="font-semibold text-slate-800">
                                        2 tautan video tidak dapat dibuka
                                    </p>

                                    <p class="mt-1 text-sm text-slate-500">
                                        Periksa kembali URL YouTube pada konten.
                                    </p>
                                </div>

                                <i class="bi bi-chevron-right text-slate-400"></i>
                            </a>

                            <a
                                href="#"
                                class="flex items-center gap-4 rounded-xl border border-blue-200 bg-blue-50 p-4 transition hover:bg-blue-100/60"
                            >
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-900">
                                    <i class="bi bi-folder-x"></i>
                                </div>

                                <div class="min-w-0 flex-1">
                                    <p class="font-semibold text-slate-800">
                                        3 pengetahuan belum memiliki kategori
                                    </p>

                                    <p class="mt-1 text-sm text-slate-500">
                                        Tentukan kategori agar konten mudah ditemukan.
                                    </p>
                                </div>

                                <i class="bi bi-chevron-right text-slate-400"></i>
                            </a>
                        </div>
                    </article>
                </section>
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

        let desktopSidebarCollapsed = false;

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

        openSidebar?.addEventListener('click', showSidebar);
        closeSidebar?.addEventListener('click', hideSidebar);
        sidebarOverlay?.addEventListener('click', hideSidebar);

        toggleDesktopSidebar?.addEventListener('click', () => {
            setDesktopSidebarCollapsed(!desktopSidebarCollapsed);
        });
    </script>
</body>
</html>
