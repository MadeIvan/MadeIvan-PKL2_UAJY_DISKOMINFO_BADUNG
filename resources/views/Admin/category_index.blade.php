<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Daftar Kategori</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-100 text-slate-900">

<header class="sticky top-0 z-50 h-20 border-b border-slate-200 bg-white">
    <div class="flex h-full items-center justify-between px-4 lg:px-6">
        <div class="flex items-center gap-3">
            <button id="openSidebar" type="button"
                class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 lg:hidden"
                aria-label="Buka sidebar">
                <i class="bi bi-list text-xl"></i>
            </button>

            <button id="toggleDesktopSidebar" type="button"
                class="hidden h-10 w-10 items-center justify-center rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 lg:flex"
                aria-label="Ciutkan sidebar" aria-expanded="true">
                <i id="desktopSidebarIcon" class="bi bi-layout-sidebar-inset text-lg"></i>
            </button>

            <a href="/" class="flex items-center gap-3">
                <img src="{{ asset('images/Logo.png') }}" alt="Logo Pusat Pengetahuan"
                    class="h-11 w-11 object-contain">
                <div class="hidden sm:block">
                    <p class="font-bold leading-tight">Pusat Pengetahuan</p>
                    <p class="text-xs text-slate-500">Panel Administrator</p>
                </div>
            </a>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ url('/admin/kategori/tambah') }}"
                class="inline-flex items-center gap-2 rounded-xl bg-blue-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-950">
                <i class="bi bi-plus-lg"></i>
                <span class="hidden sm:inline">Tambah Kategori</span>
            </a>

            <button type="button"
                class="relative flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100"
                aria-label="Notifikasi">
                <i class="bi bi-bell"></i>
                <span class="absolute right-2 top-2 h-2 w-2 rounded-full bg-red-500"></span>
            </button>

            <button type="button" class="flex items-center gap-3 rounded-xl px-2 py-1.5 hover:bg-slate-100">
                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 font-semibold text-blue-900">AD</div>
                <div class="hidden text-left md:block">
                    <p class="text-sm font-semibold">Administrator</p>
                    <p class="text-xs text-slate-500">admin@example.com</p>
                </div>
            </button>
        </div>
    </div>
</header>

<div class="flex">
    <aside id="sidebar"
        class="fixed bottom-0 left-0 top-20 z-40 w-72 -translate-x-full overflow-x-hidden overflow-y-auto border-r border-slate-200 bg-white transition-all duration-300 lg:sticky lg:top-20 lg:h-[calc(100vh-5rem)] lg:translate-x-0">
        <div class="p-5">
            <div class="mb-4 flex items-center justify-between lg:hidden">
                <p class="font-bold">Menu Administrator</p>
                <button id="closeSidebar" type="button"
                    class="flex h-9 w-9 items-center justify-center rounded-lg text-slate-500 hover:bg-slate-100">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <p class="sidebar-section-label mb-3 px-3 text-xs font-semibold uppercase tracking-wider text-slate-400">Menu Utama</p>
            <nav class="space-y-1">
                <a href="{{ url('admin/admin_dashboard') }}" class="sidebar-link flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium text-slate-600 hover:bg-slate-100 hover:text-blue-900">
                    <i class="bi bi-grid shrink-0 text-lg"></i><span class="sidebar-label whitespace-nowrap">Dashboard</span>
                </a>
                <a href="{{ url('admin/content-index') }}" class="sidebar-link flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium text-slate-600 hover:bg-slate-100 hover:text-blue-900">
                    <i class="bi bi-journal-text shrink-0 text-lg"></i><span class="sidebar-label whitespace-nowrap">Daftar Pengetahuan</span>
                </a>
                <a href="{{ url('admin/input') }}" class="sidebar-link flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium text-slate-600 hover:bg-slate-100 hover:text-blue-900">
                    <i class="bi bi-plus-square shrink-0 text-lg"></i><span class="sidebar-label whitespace-nowrap">Tambah Pengetahuan</span>
                </a>
                <a href="{{ url('admin/category/index') }}" class="sidebar-link flex items-center gap-3 rounded-xl bg-blue-50 px-3 py-3 text-sm font-semibold text-blue-900">
                    <i class="bi bi-folder2-open shrink-0 text-lg"></i><span class="sidebar-label whitespace-nowrap">Kategori</span>
                </a>
                <a href="{{ url('admin/add_app') }}" class="sidebar-link flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium text-slate-600 hover:bg-slate-100 hover:text-blue-900">
                    <i class="bi bi-window-stack shrink-0 text-lg"></i><span class="sidebar-label whitespace-nowrap">Daftar Aplikasi</span>
                </a>
            </nav>

            <p class="sidebar-section-label mb-3 mt-8 px-3 text-xs font-semibold uppercase tracking-wider text-slate-400">Pengelolaan</p>
            <nav class="space-y-1">
                <a href="{{ url('/admin/pengguna') }}" class="sidebar-link flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium text-slate-600 hover:bg-slate-100 hover:text-blue-900">
                    <i class="bi bi-people shrink-0 text-lg"></i><span class="sidebar-label whitespace-nowrap">Pengguna</span>
                </a>
                <a href="{{ url('/admin/media') }}" class="sidebar-link flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium text-slate-600 hover:bg-slate-100 hover:text-blue-900">
                    <i class="bi bi-images shrink-0 text-lg"></i><span class="sidebar-label whitespace-nowrap">Media</span>
                </a>
                <a href="{{ url('/admin/log-aktivitas') }}" class="sidebar-link flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium text-slate-600 hover:bg-slate-100 hover:text-blue-900">
                    <i class="bi bi-clock-history shrink-0 text-lg"></i><span class="sidebar-label whitespace-nowrap">Log Aktivitas</span>
                </a>
            </nav>
        </div>
    </aside>

    <div id="sidebarOverlay" class="fixed inset-0 top-20 z-30 hidden bg-slate-950/40 lg:hidden"></div>

    <main class="min-w-0 flex-1">
        <div class="mx-auto max-w-7xl px-5 py-8 lg:px-8">
            <nav class="mb-5 flex items-center gap-2 text-sm text-slate-500">
                <a href="{{ url('/admin/dashboard') }}" class="hover:text-blue-900">Admin</a>
                <i class="bi bi-chevron-right text-xs"></i>
                <span class="font-medium text-slate-800">Daftar Kategori</span>
            </nav>

            <div class="mb-7 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight sm:text-3xl">Daftar Kategori</h1>
                    <p class="mt-2 text-sm text-slate-500">Kelola kategori berdasarkan aplikasi dan versi aplikasi.</p>
                </div>
                <a href="{{ url('/admin/kategori/tambah') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-900 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-950">
                    <i class="bi bi-plus-lg"></i>Tambah Kategori
                </a>
            </div>

            <section class="mb-7 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm text-slate-500">Total Kategori</p><p class="mt-2 text-3xl font-bold">18</p>
                </article>
                <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm text-slate-500">Kategori Aktif</p><p class="mt-2 text-3xl font-bold">15</p>
                </article>
                <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm text-slate-500">Tidak Aktif</p><p class="mt-2 text-3xl font-bold">3</p>
                </article>
                <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm text-slate-500">Total Pengetahuan</p><p class="mt-2 text-3xl font-bold">124</p>
                </article>
            </section>

            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 p-5">
                    <div class="grid gap-4 lg:grid-cols-[1fr_220px_220px_auto]">
                        <div class="relative">
                            <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input id="categorySearch" type="search" placeholder="Cari kategori..."
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm outline-none focus:border-blue-900 focus:bg-white">
                        </div>

                        <select id="applicationFilter" class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm">
                            <option value="all">Semua Aplikasi</option>
                            <option value="kepegawaian">Sistem Kepegawaian</option>
                            <option value="persuratan">Sistem Persuratan</option>
                            <option value="pelayanan">Portal Pelayanan</option>
                        </select>

                        <select id="statusFilter" class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm">
                            <option value="all">Semua Status</option>
                            <option value="active">Aktif</option>
                            <option value="inactive">Tidak Aktif</option>
                        </select>

                        <button id="resetFilter" type="button"
                            class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-600 hover:bg-slate-50">
                            <i class="bi bi-arrow-counterclockwise"></i>Reset
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[1000px]">
                        <thead class="bg-slate-50">
                            <tr class="text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                <th class="px-5 py-4">Kategori</th>
                                <th class="px-5 py-4">Aplikasi</th>
                                <th class="px-5 py-4">Versi</th>
                                <th class="px-5 py-4">Pengetahuan</th>
                                <th class="px-5 py-4">Status</th>
                                <th class="px-5 py-4">Urutan</th>
                                <th class="px-5 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @php
                                $categories = [
                                    ['name'=>'Autentikasi dan Akun','description'=>'Login, password, dan pengelolaan akun.','app'=>'Sistem Informasi Kepegawaian','appKey'=>'kepegawaian','version'=>'v3.2.1','knowledge'=>12,'status'=>'active','order'=>1,'icon'=>'bi-person-lock','iconClass'=>'bg-blue-100 text-blue-900'],
                                    ['name'=>'Pengajuan Cuti','description'=>'Pengajuan, persetujuan, dan pembatalan cuti.','app'=>'Sistem Informasi Kepegawaian','appKey'=>'kepegawaian','version'=>'v3.2.1','knowledge'=>9,'status'=>'active','order'=>2,'icon'=>'bi-calendar-check','iconClass'=>'bg-emerald-100 text-emerald-700'],
                                    ['name'=>'Surat Masuk','description'=>'Pencatatan, disposisi, dan arsip surat masuk.','app'=>'Sistem Persuratan Digital','appKey'=>'persuratan','version'=>'v2.4.0','knowledge'=>6,'status'=>'inactive','order'=>1,'icon'=>'bi-envelope-paper','iconClass'=>'bg-violet-100 text-violet-700'],
                                    ['name'=>'Permohonan Layanan','description'=>'Pengajuan layanan dan pemantauan status.','app'=>'Portal Pelayanan Publik','appKey'=>'pelayanan','version'=>'v1.8.2','knowledge'=>10,'status'=>'active','order'=>1,'icon'=>'bi-ui-checks-grid','iconClass'=>'bg-amber-100 text-amber-700'],
                                ];
                            @endphp

                            @foreach ($categories as $index => $category)
                                <tr class="category-row hover:bg-slate-50"
                                    data-name="{{ strtolower($category['name']) }}"
                                    data-application="{{ $category['appKey'] }}"
                                    data-status="{{ $category['status'] }}">
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl {{ $category['iconClass'] }}">
                                                <i class="bi {{ $category['icon'] }}"></i>
                                            </div>
                                            <div>
                                                <p class="font-semibold">{{ $category['name'] }}</p>
                                                <p class="mt-1 text-sm text-slate-500">{{ $category['description'] }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 text-sm text-slate-600">{{ $category['app'] }}</td>
                                    <td class="px-5 py-4">
                                        <span class="rounded-full bg-blue-100 px-2.5 py-1 text-xs font-semibold text-blue-900">{{ $category['version'] }}</span>
                                    </td>
                                    <td class="px-5 py-4 text-sm font-semibold">{{ $category['knowledge'] }}</td>
                                    <td class="px-5 py-4">
                                        @if ($category['status'] === 'active')
                                            <span class="inline-flex items-center gap-1.5 text-sm font-medium text-emerald-700">
                                                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 text-sm font-medium text-amber-700">
                                                <span class="h-2 w-2 rounded-full bg-amber-500"></span>Tidak Aktif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 text-sm text-slate-500">{{ $category['order'] }}</td>
                                    <td class="px-5 py-4">
                                        <div class="flex justify-end gap-2">
                                            <a href="#" class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-600 hover:bg-blue-50 hover:text-blue-900" title="Lihat">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ url('/admin/kategori/'.($index + 1).'/edit') }}" class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-600 hover:bg-amber-50 hover:text-amber-700" title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <button type="button" class="delete-button flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-600 hover:bg-red-50 hover:text-red-600" data-title="{{ $category['name'] }}" title="Hapus">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div id="emptyResult" class="hidden px-6 py-16 text-center">
                    <i class="bi bi-search text-2xl text-slate-400"></i>
                    <p class="mt-4 font-semibold">Kategori tidak ditemukan</p>
                    <p class="mt-2 text-sm text-slate-500">Coba gunakan kata kunci atau filter lain.</p>
                </div>

                <div class="flex items-center justify-between border-t border-slate-200 px-5 py-4">
                    <p class="text-sm text-slate-500">Menampilkan 1–4 dari 18 kategori</p>
                    <div class="flex gap-1">
                        <button class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200"><i class="bi bi-chevron-left"></i></button>
                        <button class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-900 text-white">1</button>
                        <button class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200">2</button>
                        <button class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200"><i class="bi bi-chevron-right"></i></button>
                    </div>
                </div>
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

    const searchInput = document.getElementById('categorySearch');
    const applicationFilter = document.getElementById('applicationFilter');
    const statusFilter = document.getElementById('statusFilter');
    const resetFilter = document.getElementById('resetFilter');
    const rows = document.querySelectorAll('.category-row');
    const emptyResult = document.getElementById('emptyResult');

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

        document.querySelectorAll('.sidebar-label, .sidebar-section-label').forEach((element) => {
            element.classList.toggle('lg:hidden', collapsed);
        });

        document.querySelectorAll('.sidebar-link').forEach((link) => {
            link.classList.toggle('lg:justify-center', collapsed);
            link.classList.toggle('lg:px-0', collapsed);
        });

        desktopSidebarIcon?.classList.toggle('bi-layout-sidebar-inset', !collapsed);
        desktopSidebarIcon?.classList.toggle('bi-layout-sidebar-inset-reverse', collapsed);

        toggleDesktopSidebar?.setAttribute('aria-expanded', String(!collapsed));
        toggleDesktopSidebar?.setAttribute('aria-label', collapsed ? 'Perluas sidebar' : 'Ciutkan sidebar');
    }

    function filterCategories() {
        const keyword = searchInput.value.trim().toLowerCase();
        const app = applicationFilter.value;
        const status = statusFilter.value;
        let visible = 0;

        rows.forEach((row) => {
            const matches =
                row.dataset.name.includes(keyword) &&
                (app === 'all' || row.dataset.application === app) &&
                (status === 'all' || row.dataset.status === status);

            row.classList.toggle('hidden', !matches);
            if (matches) visible++;
        });

        emptyResult.classList.toggle('hidden', visible !== 0);
    }

    openSidebar?.addEventListener('click', showSidebar);
    closeSidebar?.addEventListener('click', hideSidebar);
    sidebarOverlay?.addEventListener('click', hideSidebar);

    toggleDesktopSidebar?.addEventListener('click', () => {
        setDesktopSidebarCollapsed(!desktopSidebarCollapsed);
    });

    searchInput?.addEventListener('input', filterCategories);
    applicationFilter?.addEventListener('change', filterCategories);
    statusFilter?.addEventListener('change', filterCategories);

    resetFilter?.addEventListener('click', () => {
        searchInput.value = '';
        applicationFilter.value = 'all';
        statusFilter.value = 'all';
        filterCategories();
    });

    document.querySelectorAll('.delete-button').forEach((button) => {
        button.addEventListener('click', () => {
            const confirmed = confirm(`Hapus kategori "${button.dataset.title}"?`);
            if (confirmed) {
                button.closest('.category-row')?.remove();
            }
        });
    });
</script>
</body>
</html>
