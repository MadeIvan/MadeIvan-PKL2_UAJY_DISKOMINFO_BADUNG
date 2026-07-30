<aside
    id="admin-sidebar"
    class="fixed inset-y-0 left-0 z-50 flex w-64 -translate-x-full flex-col border-r border-slate-200 bg-white transition-all duration-300 lg:translate-x-0"
>
    {{-- Header sidebar --}}
    <div class="flex h-16 items-center justify-between border-b border-slate-200 px-4">
        <a
            href="{{ url('/admin') }}"
            class="flex min-w-0 items-center gap-3 no-underline"
        >
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-950 text-white">
                <i class="bi bi-grid-fill text-lg"></i>
            </div>

            <div
                data-sidebar-label
                class="min-w-0"
            >
                <p class="truncate text-sm font-bold text-slate-900">
                    Admin Panel
                </p>

                <p class="truncate text-xs text-slate-500">
                    Pusat Pengetahuan
                </p>
            </div>
        </a>

        <button
            id="sidebar-close-mobile"
            type="button"
            class="flex h-9 w-9 items-center justify-center rounded-lg text-slate-500 hover:bg-slate-100 lg:hidden"
            aria-label="Tutup sidebar"
        >
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    {{-- Navigasi --}}
    <nav class="flex-1 overflow-y-auto p-3">
        <div class="space-y-1">
            <a
                href="{{ url('/admin') }}"
                class="flex items-center gap-3 rounded-lg px-3 py-3 text-sm font-medium text-slate-600 no-underline transition hover:bg-slate-100 hover:text-slate-900"
            >
                <i class="bi bi-house-door text-lg"></i>

                <span data-sidebar-label>
                    Dasbor
                </span>
            </a>

            <a
                href="{{ url('/admin/applications') }}"
                class="flex items-center gap-3 rounded-lg bg-blue-950 px-3 py-3 text-sm font-semibold text-white no-underline"
            >
                <i class="bi bi-window-stack text-lg"></i>

                <span data-sidebar-label>
                    Aplikasi
                </span>
            </a>

            <a
                href="#"
                class="flex items-center gap-3 rounded-lg px-3 py-3 text-sm font-medium text-slate-600 no-underline transition hover:bg-slate-100 hover:text-slate-900"
            >
                <i class="bi bi-book text-lg"></i>

                <span data-sidebar-label>
                    Tutorial
                </span>
            </a>

            <a
                href="#"
                class="flex items-center gap-3 rounded-lg px-3 py-3 text-sm font-medium text-slate-600 no-underline transition hover:bg-slate-100 hover:text-slate-900"
            >
                <i class="bi bi-tags text-lg"></i>

                <span data-sidebar-label>
                    Kategori
                </span>
            </a>

            <a
                href="#"
                class="flex items-center gap-3 rounded-lg px-3 py-3 text-sm font-medium text-slate-600 no-underline transition hover:bg-slate-100 hover:text-slate-900"
            >
                <i class="bi bi-people text-lg"></i>

                <span data-sidebar-label>
                    Pengguna
                </span>
            </a>
        </div>

        <div class="my-4 border-t border-slate-200"></div>

        <div class="space-y-1">
            <a
                href="#"
                class="flex items-center gap-3 rounded-lg px-3 py-3 text-sm font-medium text-slate-600 no-underline transition hover:bg-slate-100 hover:text-slate-900"
            >
                <i class="bi bi-gear text-lg"></i>

                <span data-sidebar-label>
                    Pengaturan
                </span>
            </a>
        </div>
    </nav>

    {{-- Footer sidebar --}}
    <div class="border-t border-slate-200 p-3">
        <button
            id="sidebar-collapse"
            type="button"
            class="hidden w-full items-center gap-3 rounded-lg px-3 py-3 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900 lg:flex"
        >
            <i
                id="sidebar-collapse-icon"
                class="bi bi-chevron-left text-lg"
            ></i>

            <span data-sidebar-label>
                Perkecil Sidebar
            </span>
        </button>
    </div>
</aside>

<div
    id="sidebar-overlay"
    class="fixed inset-0 z-40 hidden bg-slate-950/50 lg:hidden"
></div>