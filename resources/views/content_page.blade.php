<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Jelajahi Pengetahuan</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-50 text-slate-900">

    {{-- Navbar --}}
    <header class="fixed inset-x-0 top-0 z-50 border-b border-slate-200 bg-white">
        <nav class="mx-auto flex h-20 max-w-7xl items-center justify-between px-6 lg:px-8">

            <a href="/" class="flex items-center gap-3">
                <img
                    src="{{ asset('images/Logo.png') }}"
                    alt="Logo Pusat Pengetahuan"
                    class="h-20 w-20 object-contain"
                >

                <div>
                    <p class="font-bold text-slate-900">
                        Pusat Pengetahuan
                    </p>

                    <p class="text-xs text-slate-500">
                        Belajar, berbagi, dan berkembang
                    </p>
                </div>
            </a>

            <div class="hidden items-center gap-7 md:flex">
                <a href="#" class="font-medium text-slate-600 hover:text-blue-900">
                    Beranda
                </a>

                <a href="#" class="font-medium text-blue-900">
                    Pengetahuan
                </a>

                <a href="#" class="font-medium text-slate-600 hover:text-blue-900">
                    Tentang
                </a>
            </div>

            <a
                href="{{ url('/input') }}"
                class="inline-flex items-center rounded-xl bg-blue-900 px-5 py-2.5 font-semibold text-white transition hover:bg-blue-950"
            >
            Masuk
        </a>
        </nav>
    </header>

    <div class="mx-auto flex max-w-7xl pt-20">

        {{-- Sidebar --}}
        <aside
            id="sidebar"
            class="fixed bottom-0 left-0 top-20 z-40 w-72 -translate-x-full overflow-y-auto border-r border-slate-200 bg-white transition-transform duration-300 lg:sticky lg:top-20 lg:h-[calc(100vh-5rem)] lg:translate-x-0"
        >
            <div class="p-5">

                <div class="mb-5 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-blue-900">
                            Navigasi
                        </p>

                        <h2 class="mt-1 text-lg font-bold">
                            Daftar Pengetahuan
                        </h2>
                    </div>

                    <button
                        id="closeSidebar"
                        type="button"
                        class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 lg:hidden"
                        aria-label="Tutup sidebar"
                    >
                        ✕
                    </button>
                </div>

                {{-- Search --}}
                <div class="relative mb-6">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="pointer-events-none absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="m21 21-4.35-4.35m2.35-5.65a8 8 0 1 1-16 0 8 8 0 0 1 16 0Z"
                        />
                    </svg>

                    <input
                        type="search"
                        placeholder="Cari materi..."
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 py-3 pl-10 pr-3 text-sm outline-none focus:border-blue-900 focus:ring-2 focus:ring-blue-900/10"
                    >
                </div>

                {{-- Tree Navigation --}}
                <nav class="space-y-1">

                    {{-- Category 1 --}}
                    <div class="tree-item">
                        <button
                            type="button"
                            class="tree-toggle flex w-full items-center gap-2 rounded-lg px-3 py-2.5 text-left font-semibold text-slate-800 transition hover:bg-slate-100"
                            aria-expanded="true"
                        >
                            <svg
                                class="tree-arrow h-4 w-4 rotate-90 transition-transform"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M7.21 14.77a.75.75 0 0 1 0-1.06L10.94 10 7.21 6.29a.75.75 0 0 1 1.06-1.06l4.25 4.24a.75.75 0 0 1 0 1.06l-4.25 4.24a.75.75 0 0 1-1.06 0Z"
                                    clip-rule="evenodd"
                                />
                            </svg>

                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-100 text-sm text-blue-900">
                                01
                            </span>

                            <span>Kategori 1</span>
                        </button>

                        <div class="tree-children ml-5 border-l border-slate-200 pl-3">

                            {{-- Function 1 --}}
                            <div class="tree-item mt-1">
                                <button
                                    type="button"
                                    class="tree-toggle flex w-full items-center gap-2 rounded-lg px-3 py-2 text-left text-sm font-medium text-slate-700 transition hover:bg-slate-100"
                                    aria-expanded="true"
                                >
                                    <svg
                                        class="tree-arrow h-4 w-4 rotate-90 transition-transform"
                                        viewBox="0 0 20 20"
                                        fill="currentColor"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M7.21 14.77a.75.75 0 0 1 0-1.06L10.94 10 7.21 6.29a.75.75 0 0 1 1.06-1.06l4.25 4.24a.75.75 0 0 1 0 1.06l-4.25 4.24a.75.75 0 0 1-1.06 0Z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>

                                    <span>Fungsi 1</span>
                                </button>

                                <div class="tree-children ml-5 border-l border-slate-200 pl-3">
                                    <a
                                        href="#fungsi-a"
                                        class="mt-1 block rounded-lg px-3 py-2 text-sm text-slate-600 transition hover:bg-blue-50 hover:text-blue-900"
                                    >
                                        Fungsi A
                                    </a>

                                    <a
                                        href="#fungsi-b"
                                        class="block rounded-lg px-3 py-2 text-sm text-slate-600 transition hover:bg-blue-50 hover:text-blue-900"
                                    >
                                        Fungsi B
                                    </a>

                                    {{-- Infinite-depth example --}}
                                    <div class="tree-item">
                                        <button
                                            type="button"
                                            class="tree-toggle flex w-full items-center gap-2 rounded-lg px-3 py-2 text-left text-sm text-slate-600 transition hover:bg-slate-100"
                                            aria-expanded="false"
                                        >
                                            <svg
                                                class="tree-arrow h-4 w-4 transition-transform"
                                                viewBox="0 0 20 20"
                                                fill="currentColor"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M7.21 14.77a.75.75 0 0 1 0-1.06L10.94 10 7.21 6.29a.75.75 0 0 1 1.06-1.06l4.25 4.24a.75.75 0 0 1 0 1.06l-4.25 4.24a.75.75 0 0 1-1.06 0Z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>

                                            <span>Fungsi C</span>
                                        </button>

                                        <div class="tree-children hidden ml-5 border-l border-slate-200 pl-3">
                                            <a
                                                href="#sub-fungsi-c1"
                                                class="mt-1 block rounded-lg px-3 py-2 text-sm text-slate-600 hover:bg-blue-50 hover:text-blue-900"
                                            >
                                                Subfungsi C.1
                                            </a>

                                            <a
                                                href="#sub-fungsi-c2"
                                                class="block rounded-lg px-3 py-2 text-sm text-slate-600 hover:bg-blue-50 hover:text-blue-900"
                                            >
                                                Subfungsi C.2
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Function 2 --}}
                            <div class="tree-item mt-1">
                                <button
                                    type="button"
                                    class="tree-toggle flex w-full items-center gap-2 rounded-lg px-3 py-2 text-left text-sm font-medium text-slate-700 transition hover:bg-slate-100"
                                    aria-expanded="false"
                                >
                                    <svg
                                        class="tree-arrow h-4 w-4 transition-transform"
                                        viewBox="0 0 20 20"
                                        fill="currentColor"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M7.21 14.77a.75.75 0 0 1 0-1.06L10.94 10 7.21 6.29a.75.75 0 0 1 1.06-1.06l4.25 4.24a.75.75 0 0 1 0 1.06l-4.25 4.24a.75.75 0 0 1-1.06 0Z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>

                                    <span>Fungsi 2</span>
                                </button>

                                <div class="tree-children hidden ml-5 border-l border-slate-200 pl-3">
                                    <a
                                        href="#fungsi-d"
                                        class="mt-1 block rounded-lg px-3 py-2 text-sm text-slate-600 hover:bg-blue-50 hover:text-blue-900"
                                    >
                                        Fungsi D
                                    </a>

                                    <a
                                        href="#fungsi-e"
                                        class="block rounded-lg px-3 py-2 text-sm text-slate-600 hover:bg-blue-50 hover:text-blue-900"
                                    >
                                        Fungsi E
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Category 2 --}}
                    <div class="tree-item">
                        <button
                            type="button"
                            class="tree-toggle flex w-full items-center gap-2 rounded-lg px-3 py-2.5 text-left font-semibold text-slate-800 transition hover:bg-slate-100"
                            aria-expanded="false"
                        >
                            <svg
                                class="tree-arrow h-4 w-4 transition-transform"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M7.21 14.77a.75.75 0 0 1 0-1.06L10.94 10 7.21 6.29a.75.75 0 0 1 1.06-1.06l4.25 4.24a.75.75 0 0 1 0 1.06l-4.25 4.24a.75.75 0 0 1-1.06 0Z"
                                    clip-rule="evenodd"
                                />
                            </svg>

                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-100 text-sm text-emerald-800">
                                02
                            </span>

                            <span>Kategori 2</span>
                        </button>

                        <div class="tree-children hidden ml-5 border-l border-slate-200 pl-3">
                            <a
                                href="#materi-1"
                                class="mt-1 block rounded-lg px-3 py-2 text-sm text-slate-600 hover:bg-blue-50 hover:text-blue-900"
                            >
                                Materi 1
                            </a>

                            <a
                                href="#materi-2"
                                class="block rounded-lg px-3 py-2 text-sm text-slate-600 hover:bg-blue-50 hover:text-blue-900"
                            >
                                Materi 2
                            </a>
                        </div>
                    </div>

                    {{-- Category 3 --}}
                    <div class="tree-item">
                        <button
                            type="button"
                            class="tree-toggle flex w-full items-center gap-2 rounded-lg px-3 py-2.5 text-left font-semibold text-slate-800 transition hover:bg-slate-100"
                            aria-expanded="false"
                        >
                            <svg
                                class="tree-arrow h-4 w-4 transition-transform"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M7.21 14.77a.75.75 0 0 1 0-1.06L10.94 10 7.21 6.29a.75.75 0 0 1 1.06-1.06l4.25 4.24a.75.75 0 0 1 0 1.06l-4.25 4.24a.75.75 0 0 1-1.06 0Z"
                                    clip-rule="evenodd"
                                />
                            </svg>

                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-100 text-sm text-amber-800">
                                03
                            </span>

                            <span>Kategori 3</span>
                        </button>

                        <div class="tree-children hidden ml-5 border-l border-slate-200 pl-3">
                            <a
                                href="#panduan-1"
                                class="mt-1 block rounded-lg px-3 py-2 text-sm text-slate-600 hover:bg-blue-50 hover:text-blue-900"
                            >
                                Panduan 1
                            </a>
                        </div>
                    </div>
                </nav>
            </div>
        </aside>

        {{-- Overlay Mobile --}}
        <div
            id="sidebarOverlay"
            class="fixed inset-0 top-20 z-30 hidden bg-slate-950/40 lg:hidden"
        ></div>

        {{-- Main Content --}}
        <main class="min-w-0 flex-1 px-6 py-8 lg:px-10">

            {{-- Mobile Sidebar Button --}}
            <button
                id="openSidebar"
                type="button"
                class="mb-6 inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 font-medium text-slate-700 shadow-sm lg:hidden"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16"
                    />
                </svg>

                Daftar Materi
            </button>

            {{-- Breadcrumb --}}
            <nav class="mb-5 flex flex-wrap items-center gap-2 text-sm text-slate-500">
                <a href="#" class="hover:text-blue-900">
                    Pengetahuan
                </a>

                <span>/</span>

                <a href="#" class="hover:text-blue-900">
                    Kategori 1
                </a>

                <span>/</span>

                <a href="#" class="hover:text-blue-900">
                    Fungsi 1
                </a>

                <span>/</span>

                <span class="font-medium text-slate-800">
                    Fungsi A
                </span>
            </nav>

            {{-- Article Header --}}
            <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">

                <div class="flex flex-col gap-6 border-b border-slate-200 pb-7 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-900">
                            Kategori 1
                        </span>

                        <h1 class="mt-4 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">
                            Fungsi A
                        </h1>

                        <p class="mt-4 max-w-3xl leading-7 text-slate-600">
                            Halaman ini digunakan untuk menampilkan isi tutorial atau
                            pengetahuan yang dipilih dari struktur kategori pada sidebar.
                        </p>
                    </div>

                    
                </div>

                {{-- Content Example --}}
                <div class="mt-8 max-w-4xl space-y-8">

                    <section>
                        <h2 class="text-2xl font-bold">
                            Pengantar
                        </h2>
                    <div class="mt-4 aspect-video overflow-hidden rounded-2xl bg-slate-100">
                    <img
                        src="{{ asset('images/pemkab-badung_169.png') }}"
                        alt="{{ $imageAlt ?? 'Gambar tutorial' }}"
                        class="h-full w-full object-cover"
                    >
                </div>
                        <p class="mt-3 leading-8 text-slate-600">
                            Setiap kategori dapat mempunyai beberapa materi. Setiap materi
                            juga dapat mempunyai submateri dengan kedalaman yang tidak
                            dibatasi oleh tampilan ini.
                        </p>
                    </section>

                    <section>
                        <h2 class="text-2xl font-bold">
                            Contoh isi materi
                        </h2>

                        <div class="mt-4 rounded-2xl border border-blue-100 bg-blue-50 p-5">
                            <p class="font-semibold text-blue-950">
                                Informasi
                            </p>

                            <p class="mt-2 leading-7 text-blue-900/80">
                                Isi halaman nantinya dapat terdiri dari teks, gambar,
                                video YouTube, dokumen PDF, dan blok konten lainnya.
                            </p>
                        </div>
                    </section>

                    <section>
                        <h2 class="text-2xl font-bold">
                            Video tutorial
                        </h2>

                        <div class="mt-4 flex aspect-video items-center justify-center rounded-2xl bg-slate-900 text-slate-300">
                            <iframe
                            class="h-full w-full"
                            src="https://www.youtube.com/embed/kxh6epwP3SI"
                            title="Video tutorial"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            referrerpolicy="strict-origin-when-cross-origin"
                            allowfullscreen
                        ></iframe>
                        </div>
                    </section>

                    <section>
                        <h2 class="text-2xl font-bold">
                            Dokumen pendukung
                        </h2>

                        <div class="mt-4 flex items-center justify-between rounded-2xl border border-slate-200 p-5">
                            <div class="flex items-center gap-4">
                                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-red-100 font-bold text-red-700">
                                    PDF
                                </div>

                                <div>
                                    <p class="font-semibold">
                                        Dokumen Fungsi A.pdf
                                    </p>

                                    <p class="text-sm text-slate-500">
                                        Dokumen contoh · 2,4 MB
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 overflow-hidden rounded-2xl border border-slate-200 bg-white">
                            <iframe
                                src="{{ asset('documents/Standar Penulisan Kode.pdf') }}"
                                class="h-[700px] w-full"
                                title="Dokumen tutorial PDF"
                            ></iframe>
                            </div>
                    </section>
                </div>

                {{-- Previous and Next --}}
                <div class="mt-12 grid gap-4 border-t border-slate-200 pt-7 sm:grid-cols-2">
                    <a
                        href="#"
                        class="rounded-xl border border-slate-200 p-4 transition hover:border-blue-200 hover:bg-blue-50"
                    >
                        <p class="text-xs uppercase tracking-wider text-slate-500">
                            Sebelumnya
                        </p>

                        <p class="mt-1 font-semibold text-slate-800">
                            Fungsi sebelumnya
                        </p>
                    </a>

                    <a
                        href="#"
                        class="rounded-xl border border-slate-200 p-4 text-right transition hover:border-blue-200 hover:bg-blue-50"
                    >
                        <p class="text-xs uppercase tracking-wider text-slate-500">
                            Berikutnya
                        </p>

                        <p class="mt-1 font-semibold text-slate-800">
                            Fungsi B
                        </p>
                    </a>
                </div>
            </article>
        </main>
    </div>

    <script>
        document.querySelectorAll('.tree-toggle').forEach((button) => {
            button.addEventListener('click', () => {
                const item = button.closest('.tree-item');
                const children = item?.querySelector(':scope > .tree-children');
                const arrow = button.querySelector('.tree-arrow');

                if (!children) {
                    return;
                }

                const willOpen = children.classList.contains('hidden');

                children.classList.toggle('hidden');
                arrow?.classList.toggle('rotate-90', willOpen);

                button.setAttribute(
                    'aria-expanded',
                    willOpen ? 'true' : 'false'
                );
            });
        });

        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const openSidebar = document.getElementById('openSidebar');
        const closeSidebar = document.getElementById('closeSidebar');

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
    </script>
</body>
</html>