    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1.0"
        >

        <title>Buat Tutorial</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="min-h-screen bg-slate-100 text-slate-900">

        {{-- Admin Navbar --}}
        <header
            class="sticky top-0 z-50 h-20 border-b border-slate-200 bg-white"
        >
            <div class="flex h-full items-center justify-between px-4 lg:px-6">
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

                    <a href="/" class="flex items-center gap-3">
                        <img
                            src="{{ asset('images/Logo.png') }}"
                            alt="Logo Pusat Pengetahuan"
                            class="h-11 w-11 object-contain"
                        >

                        <div class="hidden sm:block">
                            <p class="font-bold leading-tight text-slate-900">Pusat Pengetahuan</p>
                            <p class="text-xs text-slate-500">Panel Administrator</p>
                        </div>
                    </a>
                </div>

                <div class="flex items-center gap-3">
                    <a
                        href="/"
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
                        <span class="hidden sm:inline">Simpan Pengetahuan</span>
                    </button>

                    <button
                        type="button"
                        class="relative flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 text-slate-600 transition hover:bg-slate-100"
                        aria-label="Notifikasi"
                    >
                        <i class="bi bi-bell"></i>
                        <span class="absolute right-2 top-2 h-2 w-2 rounded-full bg-red-500"></span>
                    </button>

                    <button type="button" class="sidebar-link flex items-center gap-3 rounded-xl px-2 py-1.5 transition hover:bg-slate-100">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 font-semibold text-blue-900">AD</div>

                        <div class="hidden text-left md:block">
                            <p class="text-sm font-semibold">Administrator</p>
                            <p class="text-xs text-slate-500">admin@example.com</p>
                        </div>

                        <i class="bi bi-chevron-down hidden text-xs text-slate-400 md:block"></i>
                    </button>
                </div>
            </div>
        </header>

        {{-- Admin Sidebar --}}
        <aside
            id="sidebar"
            class="fixed bottom-0 left-0 top-20 z-40 w-72 -translate-x-full overflow-x-hidden overflow-y-auto border-r border-slate-200 bg-white transition-all duration-300 lg:translate-x-0"
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

                <p class="sidebar-section-label mb-3 px-3 text-xs font-semibold uppercase tracking-wider text-slate-400">Menu Utama</p>

                <nav class="space-y-1">
                    <a href="admin_dashboard" class="sidebar-link flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-blue-900">
                        <i class="bi bi-grid shrink-0 text-lg"></i>
                        <span class="sidebar-label whitespace-nowrap">Dashboard</span>
                    </a>

                    <a href="admin/content-index" class="sidebar-link flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-blue-900">
                        <i class="bi bi-journal-text shrink-0 text-lg"></i>
                        <span class="sidebar-label whitespace-nowrap">Daftar Pengetahuan</span>
                    </a>

                    <a href="admin/input" class="sidebar-link flex items-center gap-3 rounded-xl bg-blue-50 px-3 py-3 text-sm font-semibold text-blue-900">
                        <i class="bi bi-plus-square shrink-0 text-lg"></i>
                        <span class="sidebar-label whitespace-nowrap">Tambah Pengetahuan</span>
                    </a>

                    <a href="{{ url('/admin/kategori') }}" class="sidebar-link flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-blue-900">
                        <i class="bi bi-folder2-open shrink-0 text-lg"></i>
                        <span class="sidebar-label whitespace-nowrap">Kategori</span>
                    </a>

                    <a href="{{ url('/admin/aplikasi') }}" class="sidebar-link flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-blue-900">
                        <i class="bi bi-window-stack shrink-0 text-lg"></i>
                        <span class="sidebar-label whitespace-nowrap">Daftar Aplikasi</span>
                    </a>
                </nav>

                <p class="mb-3 mt-8 px-3 text-xs font-semibold uppercase tracking-wider text-slate-400">Pengelolaan</p>

                <nav class="space-y-1">
                    <a href="{{ url('/admin/pengguna') }}" class="sidebar-link flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-blue-900">
                        <i class="bi bi-people shrink-0 text-lg"></i>
                        <span class="sidebar-label whitespace-nowrap">Pengguna</span>
                    </a>

                    <a href="{{ url('/admin/media') }}" class="sidebar-link flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-blue-900">
                        <i class="bi bi-images shrink-0 text-lg"></i>
                        <span class="sidebar-label whitespace-nowrap">Media</span>
                    </a>

                    <a href="{{ url('/admin/log-aktivitas') }}" class="sidebar-link flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-blue-900">
                        <i class="bi bi-clock-history shrink-0 text-lg"></i>
                        <span class="sidebar-label whitespace-nowrap">Log Aktivitas</span>
                    </a>
                </nav>

                <p class="mb-3 mt-8 px-3 text-xs font-semibold uppercase tracking-wider text-slate-400">Sistem</p>

                <nav class="space-y-1">
                    <a href="{{ url('/admin/pengaturan') }}" class="sidebar-link flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-blue-900">
                        <i class="bi bi-gear shrink-0 text-lg"></i>
                        <span class="sidebar-label whitespace-nowrap">Pengaturan</span>
                    </a>

                    <a href="#" class="sidebar-link flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium text-red-600 transition hover:bg-red-50">
                        <i class="bi bi-box-arrow-right shrink-0 text-lg"></i>
                        <span class="sidebar-label whitespace-nowrap">Keluar</span>
                    </a>
                </nav>
            </div>
        </aside>

        <div id="sidebarOverlay" class="fixed inset-0 top-20 z-30 hidden bg-slate-950/40 lg:hidden"></div>

        <main id="adminMain" class="transition-[padding] duration-300 lg:pl-72">
            <div class="mx-auto max-w-7xl px-5 py-8 lg:px-8">
                <nav class="mb-5 flex items-center gap-2 text-sm text-slate-500">
                    <a href="/" class="hover:text-blue-900">Admin</a>
                    <i class="bi bi-chevron-right text-xs"></i>
                    <a href="/content-index" class="hover:text-blue-900">Daftar Pengetahuan</a>
                    <i class="bi bi-chevron-right text-xs"></i>
                    <span class="font-medium text-slate-800">Tambah Pengetahuan</span>
                </nav>


            <div class="grid gap-8 lg:grid-cols-[1fr_320px]">

                {{-- Editor --}}
                <div class="space-y-6">

                    {{-- Informasi Tutorial --}}
                    <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h1 class="text-2xl font-bold">
                            Buat Pengetahuan Baru
                        </h1>

                        <p class="mt-2 text-sm text-slate-500">
                            Isi informasi dasar pengetahuan sebelum menambahkan konten.
                        </p>

                        <div class="mt-6 grid gap-5">
                            <div>
                                <label class="mb-2 block text-sm font-semibold">
                                    Judul pengetahuan
                                </label>

                                <input
                                    type="text"
                                    placeholder="Contoh: Cara Menggunakan Docker"
                                    class="w-full rounded-xl border border-slate-200 px-4 py-3 outline-none focus:border-blue-900 focus:ring-2 focus:ring-blue-900/10"
                                >
                            </div>

                            <div class="grid gap-5 md:grid-cols-2">
                                <div>
                                    <label class="mb-2 block text-sm font-semibold">
                                        Aplikasi
                                    </label>

                                    <select
                                        class="w-full rounded-xl border border-slate-200 px-4 py-3 outline-none focus:border-blue-900"
                                    >
                                        <option>Pilih aplikasi</option>
                                        <option>Pengembangan Sistem</option>
                                        <option>Infrastruktur</option>
                                        <option>Basis Data</option>
                                        <option>Dokumentasi</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-semibold">
                                        Parent
                                    </label>

                                    <select
                                        class="w-full rounded-xl border border-slate-200 px-4 py-3 outline-none focus:border-blue-900"
                                    >
                                        <option>Tidak ada parent</option>
                                        <option>Laravel</option>
                                        <option>Laravel → Instalasi</option>
                                        <option>Docker</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-semibold">
                                    Deskripsi singkat
                                </label>

                                <textarea
                                    rows="3"
                                    placeholder="Tuliskan ringkasan tutorial..."
                                    class="w-full resize-none rounded-xl border border-slate-200 px-4 py-3 outline-none focus:border-blue-900 focus:ring-2 focus:ring-blue-900/10"
                                ></textarea>
                            </div>
                        </div>
                    </section>

                    {{-- Toolbar --}}
                    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <p class="text-sm font-semibold text-slate-700">
                            Tambahkan blok konten
                        </p>

                        <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-4">
                            <button
                                type="button"
                                data-add-block="text"
                                class="add-block-button rounded-xl border border-slate-200 p-4 text-left transition hover:border-blue-300 hover:bg-blue-50"
                            >
                                
                                <span class=" block text-sm font-semibold">
                                    <i class="bi bi-type text-xl"></i>
                                    Teks
                                </span>
                            </button>

                            <button
                                type="button"
                                data-add-block="image"
                                class="add-block-button rounded-xl border border-slate-200 p-4 text-left transition hover:border-blue-300 hover:bg-blue-50"
                            >
                                
                                <span class="block text-sm font-semibold">
                                    <i class="bi bi-image"></i>
                                    Gambar
                                </span>
                            </button>

                            <button
                                type="button"
                                data-add-block="youtube"
                                class="add-block-button rounded-xl border border-slate-200 p-4 text-left transition hover:border-blue-300 hover:bg-blue-50"
                            >
                                
                                <span class="block text-sm font-semibold">
                                    <i class="bi bi-youtube"></i>
                                    YouTube
                                </span>
                            </button>

                            <button
                                type="button"
                                data-add-block="pdf"
                                class="add-block-button rounded-xl border border-slate-200 p-4 text-left transition hover:border-blue-300 hover:bg-blue-50"
                            >
                                
                                <span class="block text-sm font-semibold">
                                    <i class="bi bi-file-pdf"></i>
                                    Dokumen
                                </span>
                            </button>
                        </div>
                    </section>

                    {{-- Daftar Block --}}
                    <section>
                        <div class="mb-4 flex items-center justify-between">
                            <div>
                                <h2 class="text-xl font-bold">
                                    Isi tutorial
                                </h2>

                                <p class="mt-1 text-sm text-slate-500">
                                    Blok akan tampil sesuai urutan di bawah. Gunakan tombol panah untuk mengubah urutan.
                                </p>
                            </div>

                            <span
                                id="blockCount"
                                class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-900"
                            >
                                0 blok
                            </span>
                        </div>

                        <div
                            id="contentBlocks"
                            class="space-y-4"
                        >
                            <div
                                id="emptyState"
                                class="rounded-2xl border-2 border-dashed border-slate-300 bg-white px-6 py-14 text-center"
                            >
                                <p class="font-semibold text-slate-700">
                                    Belum ada konten
                                </p>

                                <p class="mt-2 text-sm text-slate-500">
                                    Pilih jenis blok di atas untuk mulai membuat tutorial.
                                </p>
                            </div>
                        </div>
                    </section>
                </div>

                {{-- Sidebar Preview --}}
                <aside class="space-y-6">

                    <section class="sticky top-28 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <h2 class="font-bold">
                            Pengaturan publikasi
                        </h2>

                        <div class="mt-5 space-y-5">
                            <div>
                                <label class="mb-2 block text-sm font-semibold">
                                    Status
                                </label>

                                <select
                                    class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm"
                                >
                                    <option>Draft</option>
                                    <option>Menunggu tinjauan</option>
                                    <option>Dipublikasikan</option>
                                </select>
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-semibold">
                                    Ikon Aplikasi
                                </label>

                                <label class="flex cursor-pointer flex-col items-center rounded-xl border-2 border-dashed border-slate-300 p-6 text-center hover:border-blue-400 hover:bg-blue-50">
                                    <i class="bi bi-image"></i>

                                    <span class="mt-2 text-sm font-medium">
                                        Pilih gambar
                                    </span>

                                    <span class="mt-1 text-xs text-slate-500">
                                        JPG, PNG, WEBP
                                    </span>

                                    <input
                                        type="file"
                                        accept="image/*"
                                        class="hidden"
                                    >
                                </label>
                            </div>

                            

                            <button
                                id="publicationSaveButton"
                                type="button"
                                class="w-full rounded-xl bg-blue-900 px-4 py-3 font-semibold text-white hover:bg-blue-950"
                            >
                                Simpan Pengetahuan
                            </button>

                            <button
                                type="button"
                                class="w-full rounded-xl border border-slate-200 px-4 py-3 font-semibold text-slate-700 hover:bg-slate-50"
                            >
                                Lihat Preview
                            </button>
                        </div>
                    </section>
                </aside>
            </div>
            </div>
        </main>

        {{-- Templates --}}
        <template id="textBlockTemplate">
            <div class="content-block rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="drag-handle cursor-move text-slate-400">⋮⋮</span>

                        <div>
                            <p class="font-semibold">
                                Blok Teks
                            </p>

                            <p class="text-xs text-slate-500">
                                Paragraf atau teks panjang
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <button
                            type="button"
                            class="move-block-up flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-600 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-900 disabled:cursor-not-allowed disabled:opacity-40 disabled:hover:border-slate-200 disabled:hover:bg-transparent disabled:hover:text-slate-600"
                            title="Pindahkan blok ke atas"
                            aria-label="Pindahkan blok ke atas"
                        >
                            <i class="bi bi-arrow-up"></i>
                        </button>

                        <button
                            type="button"
                            class="move-block-down flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-600 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-900 disabled:cursor-not-allowed disabled:opacity-40 disabled:hover:border-slate-200 disabled:hover:bg-transparent disabled:hover:text-slate-600"
                            title="Pindahkan blok ke bawah"
                            aria-label="Pindahkan blok ke bawah"
                        >
                            <i class="bi bi-arrow-down"></i>
                        </button>

                        <button
                            type="button"
                            class="remove-block rounded-lg px-3 py-2 text-sm font-medium text-red-600 hover:bg-red-50"
                        >
                            Hapus
                        </button>
                    </div>
                </div>

                <div class="mt-5">
                    <div class="mb-2 flex flex-wrap gap-2 border-b border-slate-200 pb-3">
                        <button type="button" class="rounded-lg border px-3 py-1.5 text-sm font-bold">
                            B
                        </button>

                        <button type="button" class="rounded-lg border px-3 py-1.5 text-sm italic">
                            I
                        </button>

                        <button type="button" class="rounded-lg border px-3 py-1.5 text-sm underline">
                            U
                        </button>

                        <button type="button" class="rounded-lg border px-3 py-1.5 text-sm">
                            H2
                        </button>

                        <button type="button" class="rounded-lg border px-3 py-1.5 text-sm">
                            Daftar
                        </button>
                    </div>

                    <textarea
                        rows="7"
                        placeholder="Tuliskan isi tutorial di sini..."
                        class="w-full resize-y rounded-xl border border-slate-200 px-4 py-3 leading-7 outline-none focus:border-blue-900"
                    ></textarea>
                </div>
            </div>
        </template>

        <template id="imageBlockTemplate">
            <div class="content-block rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="drag-handle cursor-move text-slate-400">⋮⋮</span>

                        <div>
                            <p class="font-semibold">
                                Blok Gambar
                            </p>

                            <p class="text-xs text-slate-500">
                                Upload gambar pendukung
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <button
                            type="button"
                            class="move-block-up flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-600 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-900 disabled:cursor-not-allowed disabled:opacity-40 disabled:hover:border-slate-200 disabled:hover:bg-transparent disabled:hover:text-slate-600"
                            title="Pindahkan blok ke atas"
                            aria-label="Pindahkan blok ke atas"
                        >
                            <i class="bi bi-arrow-up"></i>
                        </button>

                        <button
                            type="button"
                            class="move-block-down flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-600 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-900 disabled:cursor-not-allowed disabled:opacity-40 disabled:hover:border-slate-200 disabled:hover:bg-transparent disabled:hover:text-slate-600"
                            title="Pindahkan blok ke bawah"
                            aria-label="Pindahkan blok ke bawah"
                        >
                            <i class="bi bi-arrow-down"></i>
                        </button>

                        <button
                            type="button"
                            class="remove-block rounded-lg px-3 py-2 text-sm font-medium text-red-600 hover:bg-red-50"
                        >
                            Hapus
                        </button>
                    </div>
                </div>

                <div class="mt-5">
                    <label class="flex cursor-pointer flex-col items-center rounded-xl border-2 border-dashed border-slate-300 p-8 text-center hover:border-blue-400 hover:bg-blue-50">
                        <span class="text-3xl">🖼</span>

                        <span class="mt-3 font-semibold">
                            Pilih gambar
                        </span>

                        <span class="mt-1 text-sm text-slate-500">
                            PNG, JPG, atau WEBP
                        </span>

                        <input
                            type="file"
                            accept="image/*"
                            class="image-input hidden"
                        >
                    </label>

                    <div class="image-preview-container mt-4 hidden">
                        <div class="aspect-video overflow-hidden rounded-xl bg-slate-100">
                            <img
                                src=""
                                alt="Preview gambar"
                                class="image-preview h-full w-full object-contain"
                            >
                        </div>
                    </div>

                    <div class="mt-4 grid gap-4 md:grid-cols-2">
                        <input
                            type="text"
                            placeholder="Teks alternatif gambar"
                            class="rounded-xl border border-slate-200 px-4 py-3"
                        >

                        <input
                            type="text"
                            placeholder="Keterangan gambar"
                            class="rounded-xl border border-slate-200 px-4 py-3"
                        >
                    </div>
                </div>
            </div>
        </template>

        <template id="youtubeBlockTemplate">
            <div class="content-block rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="drag-handle cursor-move text-slate-400">⋮⋮</span>

                        <div>
                            <p class="font-semibold">
                                Blok YouTube
                            </p>

                            <p class="text-xs text-slate-500">
                                Masukkan tautan video YouTube
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <button
                            type="button"
                            class="move-block-up flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-600 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-900 disabled:cursor-not-allowed disabled:opacity-40 disabled:hover:border-slate-200 disabled:hover:bg-transparent disabled:hover:text-slate-600"
                            title="Pindahkan blok ke atas"
                            aria-label="Pindahkan blok ke atas"
                        >
                            <i class="bi bi-arrow-up"></i>
                        </button>

                        <button
                            type="button"
                            class="move-block-down flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-600 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-900 disabled:cursor-not-allowed disabled:opacity-40 disabled:hover:border-slate-200 disabled:hover:bg-transparent disabled:hover:text-slate-600"
                            title="Pindahkan blok ke bawah"
                            aria-label="Pindahkan blok ke bawah"
                        >
                            <i class="bi bi-arrow-down"></i>
                        </button>

                        <button
                            type="button"
                            class="remove-block rounded-lg px-3 py-2 text-sm font-medium text-red-600 hover:bg-red-50"
                        >
                            Hapus
                        </button>
                    </div>
                </div>

                <div class="mt-5">
                    <div class="flex flex-col gap-3 sm:flex-row">
                        <input
                            type="url"
                            placeholder="https://www.youtube.com/watch?v=..."
                            class="youtube-url flex-1 rounded-xl border border-slate-200 px-4 py-3"
                        >

                        <button
                            type="button"
                            class="youtube-preview-button rounded-xl bg-blue-900 px-5 py-3 font-semibold text-white hover:bg-blue-950"
                        >
                            Tampilkan
                        </button>
                    </div>

                    <p class="mt-2 text-xs text-slate-500">
                        Mendukung format youtube.com/watch dan youtu.be.
                    </p>

                    <div class="youtube-preview-container mt-4 hidden aspect-video overflow-hidden rounded-xl bg-slate-900">
                        <iframe
                            class="youtube-preview h-full w-full"
                            src=""
                            title="Preview video YouTube"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen
                        ></iframe>
                    </div>
                </div>
            </div>
        </template>

        <template id="pdfBlockTemplate">
            <div class="content-block rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="drag-handle cursor-move text-slate-400">⋮⋮</span>

                        <div>
                            <p class="font-semibold">
                                Blok PDF
                            </p>

                            <p class="text-xs text-slate-500">
                                Upload dokumen PDF
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <button
                            type="button"
                            class="move-block-up flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-600 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-900 disabled:cursor-not-allowed disabled:opacity-40 disabled:hover:border-slate-200 disabled:hover:bg-transparent disabled:hover:text-slate-600"
                            title="Pindahkan blok ke atas"
                            aria-label="Pindahkan blok ke atas"
                        >
                            <i class="bi bi-arrow-up"></i>
                        </button>

                        <button
                            type="button"
                            class="move-block-down flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-600 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-900 disabled:cursor-not-allowed disabled:opacity-40 disabled:hover:border-slate-200 disabled:hover:bg-transparent disabled:hover:text-slate-600"
                            title="Pindahkan blok ke bawah"
                            aria-label="Pindahkan blok ke bawah"
                        >
                            <i class="bi bi-arrow-down"></i>
                        </button>

                        <button
                            type="button"
                            class="remove-block rounded-lg px-3 py-2 text-sm font-medium text-red-600 hover:bg-red-50"
                        >
                            Hapus
                        </button>
                    </div>
                </div>

                <div class="mt-5">
                    <label class="flex cursor-pointer items-center gap-4 rounded-xl border-2 border-dashed border-slate-300 p-6 hover:border-blue-400 hover:bg-blue-50">
                        <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-red-100 font-bold text-red-700">
                            PDF
                        </div>

                        <div>
                            <p class="font-semibold">
                                Pilih dokumen PDF
                            </p>

                            <p class="mt-1 text-sm text-slate-500">
                                Maksimal ukuran dapat ditentukan nanti.
                            </p>
                        </div>

                        <input
                            type="file"
                            accept="application/pdf"
                            class="pdf-input hidden"
                        >
                    </label>

                    <div class="pdf-info mt-4 hidden rounded-xl border border-slate-200 bg-slate-50 p-4">
                        <p class="pdf-name font-semibold"></p>
                        <p class="pdf-size mt-1 text-sm text-slate-500"></p>
                    </div>
                </div>
            </div>
        </template>

        <script>
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            const openSidebar = document.getElementById('openSidebar');
            const closeSidebar = document.getElementById('closeSidebar');
            const navbarSaveButton = document.getElementById('navbarSaveButton');
            const publicationSaveButton = document.getElementById('publicationSaveButton');
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

            navbarSaveButton?.addEventListener('click', () => {
                publicationSaveButton?.click();
            });

            const contentBlocks = document.getElementById('contentBlocks');
            const emptyState = document.getElementById('emptyState');
            const blockCount = document.getElementById('blockCount');

            const templates = {
                text: document.getElementById('textBlockTemplate'),
                image: document.getElementById('imageBlockTemplate'),
                youtube: document.getElementById('youtubeBlockTemplate'),
                pdf: document.getElementById('pdfBlockTemplate'),
            };

            document.querySelectorAll('.add-block-button').forEach((button) => {
                button.addEventListener('click', () => {
                    const type = button.dataset.addBlock;
                    const template = templates[type];

                    if (!template) {
                        return;
                    }

                    emptyState?.classList.add('hidden');

                    const clone = template.content.cloneNode(true);
                    contentBlocks.appendChild(clone);

                    initializeBlocks();
                    updateBlockCount();
                    updateBlockOrderControls();
                });
            });

            function initializeBlocks() {
                document.querySelectorAll('.move-block-up').forEach((button) => {
                    if (button.dataset.ready) {
                        return;
                    }

                    button.dataset.ready = 'true';

                    button.addEventListener('click', () => {
                        const block = button.closest('.content-block');
                        const previousBlock = block?.previousElementSibling;

                        if (
                            block &&
                            previousBlock?.classList.contains('content-block')
                        ) {
                            contentBlocks.insertBefore(block, previousBlock);
                            updateBlockOrderControls();

                            block.scrollIntoView({
                                behavior: 'smooth',
                                block: 'nearest',
                            });
                        }
                    });
                });

                document.querySelectorAll('.move-block-down').forEach((button) => {
                    if (button.dataset.ready) {
                        return;
                    }

                    button.dataset.ready = 'true';

                    button.addEventListener('click', () => {
                        const block = button.closest('.content-block');
                        const nextBlock = block?.nextElementSibling;

                        if (
                            block &&
                            nextBlock?.classList.contains('content-block')
                        ) {
                            contentBlocks.insertBefore(nextBlock, block);
                            updateBlockOrderControls();

                            block.scrollIntoView({
                                behavior: 'smooth',
                                block: 'nearest',
                            });
                        }
                    });
                });

                document.querySelectorAll('.remove-block').forEach((button) => {
                    if (button.dataset.ready) {
                        return;
                    }

                    button.dataset.ready = 'true';

                    button.addEventListener('click', () => {
                        button.closest('.content-block')?.remove();
                        updateBlockCount();
                        updateBlockOrderControls();
                    });
                });

                document.querySelectorAll('.image-input').forEach((input) => {
                    if (input.dataset.ready) {
                        return;
                    }

                    input.dataset.ready = 'true';

                    input.addEventListener('change', () => {
                        const file = input.files?.[0];

                        if (!file) {
                            return;
                        }

                        const block = input.closest('.content-block');
                        const preview = block?.querySelector('.image-preview');
                        const container = block?.querySelector('.image-preview-container');

                        if (preview) {
                            preview.src = URL.createObjectURL(file);
                        }

                        container?.classList.remove('hidden');
                    });
                });

                document.querySelectorAll('.youtube-preview-button').forEach((button) => {
                    if (button.dataset.ready) {
                        return;
                    }

                    button.dataset.ready = 'true';

                    button.addEventListener('click', () => {
                        const block = button.closest('.content-block');
                        const input = block?.querySelector('.youtube-url');
                        const iframe = block?.querySelector('.youtube-preview');
                        const container = block?.querySelector('.youtube-preview-container');

                        const videoId = extractYoutubeId(input?.value ?? '');

                        if (!videoId) {
                            alert('Tautan YouTube tidak valid.');
                            return;
                        }

                        iframe.src = `https://www.youtube.com/embed/${videoId}`;
                        container?.classList.remove('hidden');
                    });
                });

                document.querySelectorAll('.pdf-input').forEach((input) => {
                    if (input.dataset.ready) {
                        return;
                    }

                    input.dataset.ready = 'true';

                    input.addEventListener('change', () => {
                        const file = input.files?.[0];

                        if (!file) {
                            return;
                        }

                        const block = input.closest('.content-block');
                        const info = block?.querySelector('.pdf-info');
                        const name = block?.querySelector('.pdf-name');
                        const size = block?.querySelector('.pdf-size');

                        if (name) {
                            name.textContent = file.name;
                        }

                        if (size) {
                            size.textContent = formatFileSize(file.size);
                        }

                        info?.classList.remove('hidden');
                    });
                });
            }

            function extractYoutubeId(url) {
                const patterns = [
                    /youtube\.com\/watch\?v=([^&]+)/,
                    /youtu\.be\/([^?&]+)/,
                    /youtube\.com\/embed\/([^?&]+)/,
                    /youtube\.com\/shorts\/([^?&]+)/,
                ];

                for (const pattern of patterns) {
                    const match = url.match(pattern);

                    if (match?.[1]) {
                        return match[1];
                    }
                }

                return null;
            }

            function formatFileSize(bytes) {
                if (bytes < 1024) {
                    return `${bytes} B`;
                }

                if (bytes < 1024 * 1024) {
                    return `${(bytes / 1024).toFixed(1)} KB`;
                }

                return `${(bytes / (1024 * 1024)).toFixed(1)} MB`;
            }

            function updateBlockOrderControls() {
                const blocks = Array.from(
                    contentBlocks.querySelectorAll('.content-block')
                );

                blocks.forEach((block, index) => {
                    const moveUpButton = block.querySelector('.move-block-up');
                    const moveDownButton = block.querySelector('.move-block-down');

                    if (moveUpButton) {
                        moveUpButton.disabled = index === 0;
                    }

                    if (moveDownButton) {
                        moveDownButton.disabled = index === blocks.length - 1;
                    }
                });
            }

            function updateBlockCount() {
                const total = document.querySelectorAll('.content-block').length;

                blockCount.textContent = `${total} blok`;

                if (total === 0) {
                    emptyState?.classList.remove('hidden');
                }
            }
        </script>
    </body>
    </html>