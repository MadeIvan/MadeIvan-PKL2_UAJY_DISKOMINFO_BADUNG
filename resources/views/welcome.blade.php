<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Sistem Manajemen Pengetahuan - Kabupaten Badung</title>

    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/public/welcome.js'])
</head>

<body class="min-h-screen bg-slate-50 pt-20 text-slate-900 font-sans selection:bg-blue-200 selection:text-blue-900">    

    {{-- Navbar --}}
    @include('components.navbar')

    <main>
        {{-- Hero Section --}}
        <section
            id="beranda"
            class="relative overflow-hidden bg-cover bg-center bg-no-repeat"
            style="background-image: url('{{ asset('images/pemkab-badung_169.png') }}');"
        >
            {{-- Modern Background Decorative Elements --}}
            <div class="absolute inset-0 bg-white/80 backdrop-blur-sm pointer-events-none z-0"></div>
            <div class="absolute inset-0 pointer-events-none z-0">
                <div class="absolute -top-40 -right-40 w-96 h-96 rounded-full bg-blue-100 blur-[100px] opacity-70"></div>
                <div class="absolute top-40 -left-40 w-96 h-96 rounded-full bg-cyan-100 blur-[100px] opacity-70"></div>
                <div class="absolute bottom-0 right-1/4 w-125 h-125 rounded-full bg-slate-100 blur-[120px] opacity-80"></div>
            </div>

            <div class="relative mx-auto max-w-6xl px-6 py-24 text-center lg:px-8 lg:py-36 z-10">
                <div class="animate-fade-in-up">
                    <span class="inline-flex rounded-full bg-blue-50 border border-blue-100 px-4 py-2 text-sm font-semibold text-blue-800 shadow-sm mb-6">
                        <i class="bi bi-stars mr-2 text-amber-500"></i> Pusat Pengetahuan & Informasi
                    </span>
                </div>

                <h1 class="mx-auto mt-2 max-w-4xl text-4xl font-extrabold tracking-tight text-slate-900 sm:text-5xl lg:text-6xl leading-tight">
                    Platform Sistem Informasi
                    <br class="hidden md:block" />
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-700 to-cyan-500">
                        Kabupaten Badung
                    </span>
                </h1>

                <p class="mx-auto mt-6 max-w-2xl text-lg leading-relaxed text-slate-500">
                    Satu portal untuk semua panduan, dokumentasi teknis, dan informasi aplikasi yang digunakan di lingkungan Pemerintah Kabupaten Badung.
                </p>

                {{-- Kolom Pencarian --}}
                <div class="mx-auto mt-10 max-w-2xl">
                    <form action="/applications-demo" method="GET" class="flex flex-col gap-2 rounded-2xl bg-white/80 backdrop-blur-xl p-2 shadow-2xl shadow-blue-900/5 ring-1 ring-slate-200 sm:flex-row transition-all hover:shadow-blue-900/10">
                        <div class="relative flex-1">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                <i class="bi bi-search text-slate-400 text-lg"></i>
                            </div>

                            <input
                                type="search"
                                name="search"
                                placeholder="Cari aplikasi atau panduan..."
                                class="h-14 w-full rounded-xl border-0 bg-transparent pl-12 pr-4 text-slate-900 outline-none placeholder:text-slate-400 focus:ring-0"
                            >
                        </div>

                        <button
                            type="submit"
                            class="h-14 rounded-xl bg-blue-900 px-8 font-semibold text-white transition hover:bg-blue-800 shadow-sm hover:shadow-md"
                        >
                            Temukan
                        </button>
                    </form>
                </div>

                {{-- Pencarian Populer --}}
                <div class="mt-8 flex flex-wrap items-center justify-center gap-3 text-sm">
                    <span class="text-slate-400 font-medium">Sering dicari:</span>
                    <a href="#" class="rounded-full border border-slate-200 bg-white px-4 py-1.5 text-slate-600 transition hover:border-blue-300 hover:bg-blue-50 hover:text-blue-800 shadow-sm">SAKIP</a>
                    <a href="#" class="rounded-full border border-slate-200 bg-white px-4 py-1.5 text-slate-600 transition hover:border-blue-300 hover:bg-blue-50 hover:text-blue-800 shadow-sm">Website Desa</a>
                    <a href="#" class="rounded-full border border-slate-200 bg-white px-4 py-1.5 text-slate-600 transition hover:border-blue-300 hover:bg-blue-50 hover:text-blue-800 shadow-sm">Kepegawaian</a>
                </div>
            </div>
        </section>

        {{-- Aplikasi Terbaru --}}
        <section id="recent-apps" class="bg-slate-50 py-24 relative z-20 border-t border-slate-200/60">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
                    <div>
                        <p class="font-bold text-sm uppercase tracking-widest text-blue-600">
                            Terbaru & Terkini
                        </p>
                        <h2 class="mt-2 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">
                            Aplikasi Terbaru
                        </h2>
                        <p class="mt-3 text-slate-500 max-w-xl text-lg">
                            Daftar aplikasi dan sistem informasi yang baru saja ditambahkan atau diperbarui dalam sistem.
                        </p>
                    </div>

                    <a href="/applications-demo" class="inline-flex items-center gap-2 font-semibold text-blue-700 transition hover:text-blue-900 group">
                        Lihat semua aplikasi 
                        <i class="bi bi-arrow-right transition-transform group-hover:translate-x-1"></i>
                    </a>
                </div>

                {{-- JS Injected Container --}}
                <div id="recent-apps-container" class="mt-12 grid gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 hidden">
                    <!-- Populated by welcome.js -->
                </div>

                {{-- Skeleton Loader --}}
                <div id="recent-apps-skeleton" class="mt-12 grid gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5">
                    @for ($i = 0; $i < 10; $i++)
                        <div class="flex flex-col items-center justify-center p-6 text-center rounded-2xl bg-white border border-slate-100 shadow-sm animate-pulse">
                            <div class="mb-4 h-16 w-16 rounded-2xl bg-slate-200"></div>
                            <div class="h-4 w-3/4 rounded bg-slate-200 mb-2"></div>
                            <div class="h-3 w-1/2 rounded bg-slate-100 mt-2"></div>
                            <div class="h-3 w-full rounded bg-slate-100 mt-2"></div>
                        </div>
                    @endfor
                </div>
            </div>
        </section>

        {{-- CTA Banner --}}
        <section class="py-24 bg-white relative overflow-hidden border-t border-slate-200/60">
            <div class="absolute inset-0 bg-blue-900/5"></div>
            <div class="mx-auto max-w-7xl px-6 lg:px-8 relative z-10 text-center">
                <h2 class="text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl mb-4">
                    Tidak menemukan apa yang Anda cari?
                </h2>
                <p class="text-lg text-slate-600 mb-8 max-w-2xl mx-auto">
                    Jelajahi seluruh daftar aplikasi dan layanan yang tersedia di Pemerintahan Kabupaten Badung.
                </p>
                <a href="/applications-demo" class="inline-flex h-14 items-center justify-center rounded-xl bg-slate-900 px-8 text-base font-semibold text-white shadow-sm transition hover:bg-slate-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-slate-900">
                    Jelajahi Katalog Aplikasi
                </a>
            </div>
        </section>

    </main>

    {{-- Footer --}}
    <footer class="border-t border-slate-200 bg-white">
        <div class="mx-auto flex max-w-7xl flex-col gap-4 px-6 py-10 text-sm text-slate-500 sm:flex-row sm:items-center sm:justify-between lg:px-8">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/Logo.png') }}" alt="Logo" class="h-8 w-auto grayscale opacity-50">
                <p>
                    © 2026 Pemerintah Kabupaten Badung. Hak cipta dilindungi.
                </p>
            </div>

            <div class="flex gap-6 font-medium">
                <a href="#" class="transition hover:text-blue-600">Beranda</a>
                <a href="/applications-demo" class="transition hover:text-blue-600">Katalog</a>
                <a href="#" class="transition hover:text-blue-600">Kontak Bantuan</a>
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
