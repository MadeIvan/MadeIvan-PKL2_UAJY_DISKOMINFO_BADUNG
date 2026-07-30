<header class="sticky top-0 z-30 h-16 border-b border-slate-200 bg-white">
    <div class="flex h-full items-center justify-between px-4 sm:px-6">
        <div class="flex items-center gap-3">
            <button
                id="sidebar-open-mobile"
                type="button"
                class="flex h-10 w-10 items-center justify-center rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-100 lg:hidden"
                aria-label="Buka sidebar"
            >
                <i class="bi bi-list text-xl"></i>
            </button>

            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                    Panel Administrator
                </p>

                <h1 class="text-base font-bold text-slate-900">
                    @yield('page-title', 'Dasbor')
                </h1>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button
                type="button"
                class="relative flex h-10 w-10 items-center justify-center rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-100"
                aria-label="Notifikasi"
            >
                <i class="bi bi-bell"></i>

                <span class="absolute right-2 top-2 h-2 w-2 rounded-full bg-red-500"></span>
            </button>

            <div class="flex items-center gap-3 border-l border-slate-200 pl-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-slate-900 text-sm font-bold text-white">
                    AD
                </div>

                <div class="hidden sm:block">
                    <p class="text-sm font-semibold text-slate-900">
                        Administrator
                    </p>

                    <p class="text-xs text-slate-500">
                        admin@example.com
                    </p>
                </div>
            </div>
        </div>
    </div>
</header>