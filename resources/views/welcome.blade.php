<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Sistem Manajemen Pengetahuan</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-50 pt-20 text-slate-900">    

    {{-- Navbar --}}
    <header class="fixed inset-x-0 top-0 z-50 border-b border-slate-200 bg-white/95 backdrop-blur">       
        <nav class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4 lg:px-8">

            {{-- Logo --}}
            <a href="#" class="flex items-center gap-2">
                <img
                        src="{{ asset('images/Logo.png') }}"
                        alt="Logo Pusat Pengetahuan"
                        class="h-25 w-25 object-contain"
                    >

                <div>
                    <p class="text-lg font-bold leading-tight">
                        Pusat Pengetahuan
                    </p>

                    <p class="text-xs text-slate-500">
                        Belajar, berbagi, dan berkembang
                    </p>
                </div>
            </a>

            {{-- Navigasi Desktop --}}
            <div class="hidden items-center gap-8 md:flex">
                <a
                    href="#beranda"
                    class="font-medium text-blue-900"
                >
                    Beranda
                </a>

                <a
                    href="#kategori"
                    class="font-medium text-slate-600 transition hover:text-blue-900"
                >
                    Kategori
                </a>

                <a
                    href="#tutorial"
                    class="font-medium text-slate-600 transition hover:text-blue-900"
                >
                    Tutorial
                </a>

                <a
                    href="#tentang"
                    class="font-medium text-slate-600 transition hover:text-blue-600"
                >
                    Tentang
                </a>
            </div>

            {{-- Tombol Aksi --}}
            <div class="hidden items-center gap-3 md:flex" >
                <a
                    href="/content"
                    class="rounded-lg px-4 py-2 font-medium text-slate-700 transition hover:bg-slate-100"
                >
                    Masuk
                </a>

                <a
                    href="/content"
                    class="rounded-lg bg-blue-900 px-5 py-2.5 font-semibold text-white transition hover:bg-blue-700"
                >
                    Mulai Sekarang
                </a>
            </div>

            {{-- Tombol Menu Mobile --}}
            <button
                id="mobileMenuButton"
                type="button"
                class="rounded-lg border border-slate-200 p-2 text-slate-700 md:hidden"
                aria-label="Buka menu navigasi"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-6 w-6"
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
            </button>
        </nav>

        {{-- Navigasi Mobile --}}
        <div
            id="mobileMenu"
            class="hidden border-t border-slate-200 bg-white px-6 py-4 md:hidden"
        >
            <div class="flex flex-col gap-4">
                <a href="#beranda" class="font-medium text-blue-600">
                    Beranda
                </a>

                <a href="#kategori" class="font-medium text-slate-600">
                    Kategori
                </a>

                <a href="#tutorial" class="font-medium text-slate-600">
                    Tutorial
                </a>

                <a href="#tentang" class="font-medium text-slate-600">
                    Tentang
                </a>

                <div class="mt-2 flex flex-col gap-3">
                    <a
                        href="#"
                        class="rounded-lg border border-slate-300 px-4 py-2 text-center font-medium"
                    >
                        Masuk
                    </a>

                    <a
                        href="#"
                        class="rounded-lg bg-blue-600 px-4 py-2 text-center font-semibold text-white"
                    >
                        Mulai Sekarang
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main>

        {{-- Hero --}}
        <section
            id="beranda"
            class="relative overflow-hidden bg-cover bg-center bg-no-repeat"
            style="background-image: url('{{ asset('images/pemkab-badung_169.png') }}');"
        >
            <div class="absolute inset-0 bg-gradient-to-b from-blue-50/70 to-white"></div>

            <div class="relative mx-auto max-w-5xl px-6 py-24 text-center lg:px-8 lg:py-32">

                <span class="inline-flex rounded-full bg-blue-100 px-4 py-2 text-sm font-semibold text-blue-900">
                    Pusat informasi dan pembelajaran terintegrasi
                </span>

                <h1 class="mx-auto mt-6 max-w-4xl text-4xl font-bold tracking-tight text-slate-900 sm:text-5xl lg:text-6xl">
                    Pusat informasi dan dokumentasi untuk sistem informasi
                    <span class="text-blue-900">
                        Kabupaten Badung
                    </span>
                </h1>

                <p class="mx-auto mt-6 max-w-2xl text-lg leading-8 text-slate-900">
                    Pusat bantuan, dokumentasi, video, untuk pengguna sistem informasi Kabupaten Badung.
                </p>

                {{-- Kolom Pencarian --}}
                <div class="mx-auto mt-10 max-w-3xl">
                    <div class="flex flex-col gap-3 rounded-2xl border border-slate-200 bg-white p-3 shadow-xl shadow-slate-200/50 sm:flex-row">

                        <div class="relative flex-1">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 text-slate-400"
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
                            </div>

                            <input
                                type="search"
                                placeholder="Cari tutorial, dokumen, atau topik..."
                                class="h-14 w-full rounded-xl border-0 bg-slate-50 pl-12 pr-4 text-slate-900 outline-none placeholder:text-slate-400 focus:bg-white focus:ring-2 focus:ring-blue-500"
                            >
                        </div>

                        <button
                            type="button"
                            class="h-14 rounded-xl bg-blue-900 px-8 font-semibold text-white transition hover:bg-blue-700"
                        >
                            Cari
                        </button>
                    </div>
                </div>

                {{-- Pencarian Populer --}}
                <div class="mt-5 flex flex-wrap items-center justify-center gap-2 text-sm">
                    <span class="text-slate-500">
                        Pencarian populer:
                    </span>

                    <a
                        href="#"
                        class="rounded-full bg-slate-100 px-3 py-1.5 text-slate-900 transition hover:bg-blue-100 hover:text-blue-700"
                    >
                        SAKIP
                    </a>

                    <a
                        href="#"
                        class="rounded-full bg-slate-100 px-3 py-1.5 text-slate-900 transition hover:bg-blue-100 hover:text-blue-700"
                    >
                        Website Desa
                    </a>

                    <a
                        href="#"
                        class="rounded-full bg-slate-100 px-3 py-1.5 text-slate-900 transition hover:bg-blue-100 hover:text-blue-700"
                    >
                        OPD
                    </a>

                    <a
                        href="#"
                        class="rounded-full bg-slate-100 px-3 py-1.5 text-slate-600 transition hover:bg-blue-100 hover:text-blue-700"
                    >
                        E-Lapor
                    </a>
                </div>
            </div>
        </section>

        {{-- Kategori --}}
        <section
            id="kategori"
            class="mx-auto max-w-7xl px-6 py-20 lg:px-8"
        >
            <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
                <div>
                    <p class="font-semibold text-blue-900">
                        Jelajahi pengetahuan Aplikasi
                    </p>

                    <h2 class="mt-2 text-3xl font-bold tracking-tight">
                        Temukan berdasarkan kategori
                    </h2>

                    <p class="mt-3 max-w-2xl text-slate-900">
                        Temukan materi pembelajaran berdasarkan bidang dan topik yang tersedia.
                    </p>
                </div>

                <a
                    href="#"
                    class="font-semibold text-blue-900 transition hover:text-blue-700"
                >
                    Lihat semua kategori →
                </a>
            </div>

            <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">

                {{-- Kategori 1 --}}
                <a
                    href="#"
                    class="group rounded-2xl border border-slate-200 bg-white p-6 transition hover:-translate-y-1 hover:border-blue-200 hover:shadow-xl hover:shadow-slate-200/60"
                >
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100 font-bold text-blue-700 transition group-hover:bg-blue-600 group-hover:text-white">
                        &lt;/&gt;
                    </div>

                    <h3 class="mt-5 text-xl font-bold">
                        Pengembangan Sistem
                    </h3>

                    <p class="mt-2 text-sm leading-6 text-slate-600">
                        Pemrograman, framework, API, dan pengembangan perangkat lunak.
                    </p>

                    <p class="mt-5 text-sm font-semibold text-blue-900">
                        24 tutorial
                    </p>
                </a>

                {{-- Kategori 2 --}}
                <a
                    href="#"
                    class="group rounded-2xl border border-slate-200 bg-white p-6 transition hover:-translate-y-1 hover:border-blue-200 hover:shadow-xl hover:shadow-slate-200/60"
                >
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100 font-bold text-blue-700 transition group-hover:bg-blue-600 group-hover:text-white">
                        ⚙
                    </div>

                    <h3 class="mt-5 text-xl font-bold">
                        Infrastruktur
                    </h3>

                    <p class="mt-2 text-sm leading-6 text-slate-600">
                        Docker, server, jaringan, deployment, dan DevOps.
                    </p>

                    <p class="mt-5 text-sm font-semibold text-blue-900">
                        16 tutorial
                    </p>
                </a>

                {{-- Kategori 3 --}}
                <a
                    href="#"
                    class="group rounded-2xl border border-slate-200 bg-white p-6 transition hover:-translate-y-1 hover:border-blue-200 hover:shadow-xl hover:shadow-slate-200/60"
                >
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100 font-bold text-blue-700 transition group-hover:bg-blue-600 group-hover:text-white">
                        DB
                    </div>

                    <h3 class="mt-5 text-xl font-bold">
                        Basis Data
                    </h3>

                    <p class="mt-2 text-sm leading-6 text-slate-600">
                        MySQL, migrasi, relasi, query, dan pengelolaan data.
                    </p>

                    <p class="mt-5 text-sm font-semibold text-blue-900">
                        12 tutorial
                    </p>
                </a>

                {{-- Kategori 4 --}}
                <a
                    href="#"
                    class="group rounded-2xl border border-slate-200 bg-white p-6 transition hover:-translate-y-1 hover:border-blue-200 hover:shadow-xl hover:shadow-slate-200/60"
                >
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100 text-sm font-bold text-blue-700 transition group-hover:bg-blue-600 group-hover:text-white">
                        DOC
                    </div>

                    <h3 class="mt-5 text-xl font-bold">
                        Dokumentasi
                    </h3>

                    <p class="mt-2 text-sm leading-6 text-slate-600">
                        Panduan, kebijakan, alur kerja, referensi, dan manual penggunaan.
                    </p>

                    <p class="mt-5 text-sm font-semibold text-blue-900">
                        18 tutorial
                    </p>
                </a>
            </div>
        </section>

        {{-- Tutorial Terbaru --}}
        <sectionid="tutorial" class="bg-white py-20">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
                    <div>
                        <p class="font-semibold text-blue-600">
                            Pengetahuan terbaru
                        </p>

                        <h2 class="mt-2 text-3xl font-bold tracking-tight">
                            Apilkasi terbaru
                        </h2>

                        <p class="mt-3 text-slate-600">
                            Temukan materi terbaru yang dibagikan oleh pengguna.
                        </p>
                    </div>

                    <a
                        href="#"
                        class="font-semibold text-blue-900 transition hover:text-blue-900"
                    >
                        Lihat semua Aplikasi →
                    </a>
                </div>

                <div class="mt-10 grid gap-6 md:grid-cols-2 lg:grid-cols-3">

                    {{-- Tutorial 1 --}}
                    <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white transition hover:-translate-y-1 hover:shadow-xl hover:shadow-slate-200/60">

                        <div class="flex h-44 items-center justify-center bg-gradient-to-br from-blue-100 via-white to-cyan-100">
                            <div class="rounded-2xl bg-white/80 px-5 py-3 text-sm font-bold text-blue-900 shadow-sm backdrop-blur">
                                SAKIP
                            </div>
                        </div>

                        <div class="p-6">
                            <span class="inline-flex rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                                Pengembangan Sistem
                            </span>

                            <h3 class="mt-4 text-xl font-bold leading-7">
                                <a href="#" class="transition hover:text-blue-900">
                                    Cara Menginstal Laravel Menggunakan Docker
                                </a>
                            </h3>

                            <p class="mt-3 text-sm leading-6 text-slate-600">
                                Pelajari cara menyiapkan lingkungan pengembangan Laravel menggunakan Docker Compose.
                            </p>

                            <div class="mt-6 flex items-center justify-between border-t border-slate-100 pt-4 text-xs text-slate-500">
                                <span>Oleh Admin</span>
                                <span>13 Juli 2026</span>
                            </div>
                        </div>
                    </article>

                    {{-- Tutorial 2 --}}
                    <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white transition hover:-translate-y-1 hover:shadow-xl hover:shadow-slate-200/60">

                        <div class="flex h-44 items-center justify-center bg-gradient-to-br from-blue-100 via-white to-cyan-100">
                            <div class="rounded-2xl bg-white/80 px-5 py-3 text-sm font-bold text-blue-900 shadow-sm backdrop-blur">
                                Website Desa
                            </div>
                        </div>

                        <div class="p-6">
                            <span class="inline-flex rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                                Basis Data
                            </span>

                            <h3 class="mt-4 text-xl font-bold leading-7">
                                <a href="#" class="transition hover:text-blue-600">
                                    Memahami Relasi Basis Data pada Laravel
                                </a>
                            </h3>

                            <p class="mt-3 text-sm leading-6 text-slate-600">
                                Panduan praktis mengenai relasi one-to-one, one-to-many, dan many-to-many.
                            </p>

                            <div class="mt-6 flex items-center justify-between border-t border-slate-100 pt-4 text-xs text-slate-500">
                                <span>Oleh Van</span>
                                <span>11 Juli 2026</span>
                            </div>
                        </div>
                    </article>

                    {{-- Tutorial 3 --}}
                    <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white transition hover:-translate-y-1 hover:shadow-xl hover:shadow-slate-200/60">

                        <div class="flex h-44 items-center justify-center bg-gradient-to-br from-blue-100 via-white to-cyan-100">
                            <div class="rounded-2xl bg-white/80 px-5 py-3 text-sm font-bold text-blue-900 shadow-sm backdrop-blur">
                                E-Lapor
                            </div>
                        </div>

                        <div class="p-6">
                            <span class="inline-flex rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                                Infrastruktur
                            </span>

                            <h3 class="mt-4 text-xl font-bold leading-7">
                                <a href="#" class="transition hover:text-blue-600">
                                    Dasar-Dasar Jaringan Docker
                                </a>
                            </h3>

                            <p class="mt-3 text-sm leading-6 text-slate-600">
                                Pelajari jaringan container, nama service, port, dan komunikasi internal Docker.
                            </p>

                            <div class="mt-6 flex items-center justify-between border-t border-slate-100 pt-4 text-xs text-slate-500">
                                <span>Oleh Admin</span>
                                <span>10 Juli 2026</span>
                            </div>
                        </div>
                    </article>
                </div>
            </div>
        </sectionid=>

        <!-- {{-- Tentang --}}
        <section
            id="tentang"
            class="mx-auto max-w-7xl px-6 py-20 lg:px-8"
        >

        </section> -->
    </main>

    {{-- Footer --}}
    <footer class="border-t border-slate-200 bg-white">
        <div class="mx-auto flex max-w-7xl flex-col gap-4 px-6 py-8 text-sm text-slate-500 sm:flex-row sm:items-center sm:justify-between lg:px-8">
            <p>
                © 2026 Pusat Pengetahuan. Seluruh hak cipta dilindungi.
            </p>

            <div class="flex gap-5">
                <a href="#" class="transition hover:text-blue-600">
                    Privasi
                </a>

                <a href="#" class="transition hover:text-blue-600">
                    Ketentuan
                </a>

                <a href="#" class="transition hover:text-blue-600">
                    Kontak
                </a>
            </div>
        </div>
    </footer>

    <script>
        const mobileMenuButton = document.getElementById('mobileMenuButton');
        const mobileMenu = document.getElementById('mobileMenu');

        mobileMenuButton?.addEventListener('click', () => {
            mobileMenu?.classList.toggle('hidden');
        });
    </script>
</body>
</html>
```
