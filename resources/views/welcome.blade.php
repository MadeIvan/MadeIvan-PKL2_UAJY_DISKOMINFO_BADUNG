<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Sistem Manajemen Pengetahuan - Kabupaten Badung</title>

    <link rel="icon" type="image/png" href="{{ asset('images/Logo.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/public/welcome.js'])

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        @keyframes heroFade {
            from { opacity: 0; transform: translateY(14px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes waveFloat {
            0%, 100% { transform: translateX(0); }
            50% { transform: translateX(-28px); }
        }

        @keyframes waveFloatReverse {
            0%, 100% { transform: translateX(0); }
            50% { transform: translateX(34px); }
        }

        .hero-animate { animation: heroFade .5s ease-out both; }
        .wave-one { animation: waveFloat 13s ease-in-out infinite; }
        .wave-two { animation: waveFloatReverse 17s ease-in-out infinite; }
        .wave-three { animation: waveFloat 21s ease-in-out infinite; }

        @media (prefers-reduced-motion: reduce) {
            .hero-animate,
            .wave-one,
            .wave-two,
            .wave-three {
                animation: none;
            }
        }
    </style>
</head>

<body class="min-h-screen overflow-x-hidden bg-slate-50 font-sans text-slate-900">

    @include('components.navbar')

    <main>

        {{-- Hero --}}
        <section id="beranda" class="relative isolate min-h-screen overflow-hidden bg-[#061a3b]">

            {{-- Background --}}
            <div class="absolute inset-0 z-0 overflow-hidden">
                <img src="{{ asset('images/pemkab-badung_169.png') }}" alt="Pemerintah Kabupaten Badung" class="absolute inset-0 h-full w-full object-cover object-center">

                <div class="absolute inset-0 bg-slate-950/60"></div>
                <div class="absolute inset-0 bg-gradient-to-r from-[#020d20]/95 via-[#061a3b]/88 to-[#0b3264]/60"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-[#020817]/90 via-transparent to-[#061a3b]/35"></div>

                {{-- Waves --}}
                <div class="pointer-events-none absolute inset-0 overflow-hidden" aria-hidden="true">

                    <svg class="wave-one absolute -bottom-10 -left-[10%] h-[40%] w-[120%] opacity-80" viewBox="0 0 1600 500" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <linearGradient id="waveGradientOne" x1="0%" y1="0%" x2="100%" y2="0%">
                                <stop offset="0%" stop-color="#1e3a8a" stop-opacity="0.10"/>
                                <stop offset="35%" stop-color="#1d4ed8" stop-opacity="0.34"/>
                                <stop offset="70%" stop-color="#2563eb" stop-opacity="0.24"/>
                                <stop offset="100%" stop-color="#3b82f6" stop-opacity="0.08"/>
                            </linearGradient>
                        </defs>

                        <path d="M0,260 C220,180 360,180 560,240 C760,300 900,350 1110,265 C1300,190 1440,195 1600,245 L1600,500 L0,500 Z" fill="url(#waveGradientOne)"/>
                    </svg>

                    <svg class="wave-two absolute -bottom-4 -left-[8%] h-[35%] w-[116%] opacity-90" viewBox="0 0 1600 500" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <linearGradient id="waveGradientTwo" x1="0%" y1="0%" x2="100%" y2="0%">
                                <stop offset="0%" stop-color="#020617" stop-opacity="0.22"/>
                                <stop offset="30%" stop-color="#172554" stop-opacity="0.48"/>
                                <stop offset="65%" stop-color="#1e40af" stop-opacity="0.32"/>
                                <stop offset="100%" stop-color="#1d4ed8" stop-opacity="0.12"/>
                            </linearGradient>
                        </defs>

                        <path d="M0,300 C180,350 330,340 520,260 C710,180 880,165 1050,245 C1230,330 1400,345 1600,255 L1600,500 L0,500 Z" fill="url(#waveGradientTwo)"/>
                    </svg>

                    <svg class="wave-three absolute bottom-0 -left-[5%] h-[28%] w-[110%] opacity-95" viewBox="0 0 1600 500" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <linearGradient id="waveGradientThree" x1="0%" y1="0%" x2="100%" y2="0%">
                                <stop offset="0%" stop-color="#020617" stop-opacity="0.82"/>
                                <stop offset="45%" stop-color="#0f2557" stop-opacity="0.76"/>
                                <stop offset="80%" stop-color="#1e3a8a" stop-opacity="0.52"/>
                                <stop offset="100%" stop-color="#1d4ed8" stop-opacity="0.22"/>
                            </linearGradient>
                        </defs>

                        <path d="M0,330 C240,250 390,290 590,340 C790,390 960,365 1130,285 C1320,195 1440,220 1600,285 L1600,500 L0,500 Z" fill="url(#waveGradientThree)"/>
                    </svg>

                    <div class="absolute right-[5%] top-[12%] h-[420px] w-[420px] rounded-full bg-blue-500/10 blur-[110px] lg:h-[560px] lg:w-[560px]"></div>
                </div>

                <div class="absolute inset-0 bg-[#04152f]/15 lg:hidden"></div>
            </div>

            {{-- Hero Content --}}
            <div class="relative z-10 flex min-h-screen items-center px-4 pb-16 pt-28 sm:px-6 sm:pt-32 lg:px-12 lg:pt-28 xl:px-20 2xl:px-28">
                <div class="mx-auto w-full max-w-5xl text-center">

                    {{-- Badge --}}
                    <div class="hero-animate">
                        <span class="inline-flex items-center rounded-full border border-white/20 bg-[#071f45]/50 px-4 py-2 text-xs font-semibold text-blue-100 shadow-lg backdrop-blur-md">
                            <i class="bi bi-mortarboard-fill mr-2"></i>
                            Pusat Pengetahuan & Informasi
                        </span>
                    </div>

                    {{-- Title --}}
                    <h1 class="hero-animate mx-auto mt-7 max-w-5xl text-4xl font-extrabold leading-tight tracking-tight text-white drop-shadow-lg sm:text-5xl lg:text-6xl xl:text-7xl">
                        Pengetahuan Sistem Informasi
                        <br class="hidden sm:block">

                        <span class="bg-gradient-to-r from-blue-400 via-blue-500 to-blue-700 bg-clip-text text-transparent">
                            Kabupaten Badung
                        </span>
                    </h1>

                    {{-- Description --}}
                    <p class="hero-animate mx-auto mt-6 max-w-2xl text-base leading-7 text-blue-50/90 drop-shadow-sm sm:text-lg sm:leading-8">
                        Satu portal untuk semua panduan, dokumentasi teknis, dan informasi aplikasi yang digunakan di lingkungan Pemerintah Kabupaten Badung.
                    </p>

                    {{-- Search --}}
                    <div class="hero-animate mx-auto mt-9 max-w-2xl sm:mt-10">
                        <form id="hero-search-form" action="/applications-demo" method="GET" class="flex flex-col gap-2 rounded-2xl border border-white/20 bg-white/95 p-2 shadow-2xl shadow-slate-950/40 backdrop-blur-xl transition hover:shadow-slate-950/50 sm:flex-row">
                            <div class="relative flex-1">
                                <i class="bi bi-search pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-lg text-slate-400"></i>

                                <input id="hero-search-input" type="search" name="search" autocomplete="off" placeholder="Cari aplikasi atau panduan..." class="h-14 w-full rounded-xl border-0 bg-transparent pl-12 pr-4 text-sm text-slate-900 outline-none placeholder:text-slate-400 focus:ring-0 sm:text-base">
                            </div>

                            <button type="submit" class="h-14 rounded-xl bg-blue-950 px-8 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-900 hover:shadow-md sm:text-base">
                                Temukan
                            </button>
                        </form>
                    </div>

                    {{-- Recent Searches --}}
                    <div id="recent-searches-wrapper" class="hero-animate mx-auto mt-7 hidden max-w-3xl">
                        <div class="flex flex-wrap items-center justify-center gap-2 text-xs sm:gap-3 sm:text-sm">

                            <span class="inline-flex items-center gap-1.5 font-medium text-blue-100/80">
                                <i class="bi bi-clock-history"></i>
                                Pencarian terakhir:
                            </span>

                            <div id="recent-searches-list" class="contents"></div>

                            <button id="clear-recent-searches" type="button" class="ml-1 inline-flex h-7 w-7 items-center justify-center rounded-full border border-white/15 bg-white/5 text-blue-100/70 transition hover:border-red-300/40 hover:bg-red-500/20 hover:text-red-100" title="Hapus riwayat pencarian" aria-label="Hapus riwayat pencarian">
                                <i class="bi bi-x-lg text-[10px]"></i>
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </section>


        {{-- Aplikasi Terbaru --}}
        <section id="recent-apps" class="relative z-20 border-t border-slate-200/60 bg-slate-50 py-20 sm:py-24">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">

                <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
                    <div>
                        <p class="text-sm font-bold uppercase tracking-widest text-blue-950">
                            Terbaru & Terkini
                        </p>

                        <h2 class="mt-2 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">
                            Aplikasi Terbaru
                        </h2>

                        <p class="mt-3 max-w-xl text-base leading-7 text-slate-500 sm:text-lg">
                            Daftar aplikasi dan sistem informasi yang baru saja ditambahkan atau diperbarui dalam sistem.
                        </p>
                    </div>

                    <a href="/applications-demo" class="group inline-flex items-center gap-2 font-semibold text-blue-900 transition hover:text-blue-950">
                        Lihat semua aplikasi
                        <i class="bi bi-arrow-right transition-transform group-hover:translate-x-1"></i>
                    </a>
                </div>

                {{-- JS Injected Container --}}
                <div id="recent-apps-container" class="mt-12 hidden grid gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5">
                    {{-- Populated by welcome.js --}}
                </div>

                {{-- Skeleton Loader --}}
                <div id="recent-apps-skeleton" class="mt-12 grid gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5">
                    @for ($i = 0; $i < 10; $i++)
                        <div class="flex animate-pulse flex-col items-center justify-center rounded-2xl border border-slate-100 bg-white p-6 text-center shadow-sm">
                            <div class="mb-4 h-16 w-16 rounded-2xl bg-slate-200"></div>
                            <div class="mb-2 h-4 w-3/4 rounded bg-slate-200"></div>
                            <div class="mt-2 h-3 w-1/2 rounded bg-slate-100"></div>
                            <div class="mt-2 h-3 w-full rounded bg-slate-100"></div>
                        </div>
                    @endfor
                </div>
            </div>
        </section>


        {{-- CTA --}}
        <section class="relative overflow-hidden border-t border-slate-200/60 bg-white py-20 sm:py-24">
            <div class="absolute inset-0 bg-blue-950/[0.04]"></div>

            <div class="relative z-10 mx-auto max-w-7xl px-6 text-center lg:px-8">
                <h2 class="text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">
                    Tidak menemukan apa yang Anda cari?
                </h2>

                <p class="mx-auto mt-4 max-w-2xl text-base leading-7 text-slate-600 sm:text-lg">
                    Jelajahi seluruh daftar aplikasi dan layanan yang tersedia di Pemerintah Kabupaten Badung.
                </p>

                <a href="/applications-demo" class="mt-8 inline-flex h-14 items-center justify-center rounded-xl bg-blue-950 px-8 text-base font-semibold text-white shadow-sm transition hover:bg-blue-900">
                    Jelajahi Katalog Aplikasi
                </a>
            </div>
        </section>

    </main>


    {{-- Footer --}}
    <footer class="border-t border-slate-200 bg-white">
        <div class="mx-auto flex max-w-7xl flex-col gap-4 px-6 py-10 text-sm text-slate-500 sm:flex-row sm:items-center sm:justify-between lg:px-8">

            <div class="flex items-center gap-3">
                <img src="{{ asset('images/Logo.png') }}" alt="Logo Pemerintah Kabupaten Badung" class="h-8 w-auto grayscale opacity-50">

                <p>
                    © {{ date('Y') }} Pemerintah Kabupaten Badung. Hak cipta dilindungi.
                </p>
            </div>

            <div class="flex flex-wrap gap-6 font-medium">
                <a href="#beranda" class="transition hover:text-blue-900">
                    Beranda
                </a>

                <a href="/applications-demo" class="transition hover:text-blue-900">
                    Katalog
                </a>

                <a href="#" class="transition hover:text-blue-900">
                    Kontak Bantuan
                </a>
            </div>
        </div>
    </footer>


    {{-- Recent Search Logic --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const storageKey = 'kms_recent_searches';
            const maxRecentSearches = 5;

            const searchForm = document.getElementById('hero-search-form');
            const searchInput = document.getElementById('hero-search-input');
            const recentWrapper = document.getElementById('recent-searches-wrapper');
            const recentList = document.getElementById('recent-searches-list');
            const clearButton = document.getElementById('clear-recent-searches');

            const getRecentSearches = () => {
                try {
                    const stored = JSON.parse(localStorage.getItem(storageKey) || '[]');
                    return Array.isArray(stored) ? stored : [];
                } catch (error) {
                    console.error('Gagal membaca riwayat pencarian:', error);
                    return [];
                }
            };

            const saveRecentSearches = (searches) => {
                localStorage.setItem(storageKey, JSON.stringify(searches));
            };

            const addRecentSearch = (keyword) => {
                const cleanKeyword = keyword.trim();

                if (!cleanKeyword) return;

                let searches = getRecentSearches();

                searches = searches.filter(item => item.toLowerCase() !== cleanKeyword.toLowerCase());
                searches.unshift(cleanKeyword);
                searches = searches.slice(0, maxRecentSearches);

                saveRecentSearches(searches);
            };

            const renderRecentSearches = () => {
                const searches = getRecentSearches();

                if (!recentWrapper || !recentList) return;

                if (searches.length === 0) {
                    recentWrapper.classList.add('hidden');
                    recentList.innerHTML = '';
                    return;
                }

                recentWrapper.classList.remove('hidden');

                recentList.innerHTML = searches.map(keyword => {
                    const encodedKeyword = encodeURIComponent(keyword);
                    const safeKeyword = escapeHtml(keyword);

                    return `
                        <a href="/applications-demo?search=${encodedKeyword}" class="rounded-full border border-white/20 bg-white/10 px-4 py-1.5 text-blue-100 shadow-sm backdrop-blur-sm transition hover:border-white/40 hover:bg-white/20">
                            ${safeKeyword}
                        </a>
                    `;
                }).join('');
            };

            const escapeHtml = (value) => {
                return String(value)
                    .replaceAll('&', '&amp;')
                    .replaceAll('<', '&lt;')
                    .replaceAll('>', '&gt;')
                    .replaceAll('"', '&quot;')
                    .replaceAll("'", '&#039;');
            };

            searchForm?.addEventListener('submit', () => {
                const keyword = searchInput?.value || '';

                if (keyword.trim()) {
                    addRecentSearch(keyword);
                }
            });

            clearButton?.addEventListener('click', () => {
                localStorage.removeItem(storageKey);
                renderRecentSearches();
            });

            renderRecentSearches();
        });
    </script>

</body>
</html>