    <header class="fixed inset-x-0 top-0 z-50 border-b border-slate-200 bg-white/95 backdrop-blur">       
        <nav class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4 lg:px-8">

            {{-- Logo --}}
            <a href="/" class="flex items-center gap-2">
                <img
                        src="{{ asset('images/Logo.png') }}"
                        alt="Logo Pusat Pengetahuan"
                        class="h-15 w-15 object-contain"
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
            <div class="hidden items-center gap-8 md:flex text-decoration-none">
                <a
                    href="/"
                    class="font-medium text-blue-900"
                >
                    Beranda
                </a>

                <a
                    href="/app_list"
                    class="font-medium text-slate-600 transition hover:text-blue-900 text-decoration-none"
                >
                    App List
                </a>

                <a
                    href="/admin/Materi-demo"
                    class="font-medium text-slate-600 transition hover:text-blue-900 text-decoration-none"
                >
                    Admin View
                </a>
            </div>

            {{-- Tombol Aksi --}}
            <div class="hidden items-center gap-3 md:flex" >
                <a
                    href="/app_list"
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
                        href="/app-list"
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