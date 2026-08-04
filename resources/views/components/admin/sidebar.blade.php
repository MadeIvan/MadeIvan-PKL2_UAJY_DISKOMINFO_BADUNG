@php
    $dashboardActive = request()->is('admin');
    $applicationsActive = request()->is('admin/applications*');
    $materialsActive = request()->is('admin/Materi-demo*');
@endphp

<aside
    id="admin-sidebar"
    class="fixed inset-y-0 left-0 z-50 flex w-72 -translate-x-full flex-col border-r border-slate-200 bg-white shadow-lg transition-[width,transform] duration-300 ease-in-out lg:w-20 lg:translate-x-0 lg:shadow-none"
    aria-label="Navigasi admin"
    data-sidebar-pinned="false"
>
    {{-- Header sidebar --}}
    <div
        id="sidebar-header"
        class="flex h-16 shrink-0 items-center justify-between border-b border-slate-200 px-4"
    >
        <a
            href="{{ url('/admin') }}"
            class="flex min-w-0 items-center gap-3 overflow-hidden no-underline"
        >
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-950 text-white">
                <i class="bi bi-grid-fill text-lg"></i>
            </div>

            <div
                data-sidebar-label
                class="min-w-0 whitespace-nowrap transition-all duration-200"
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
            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-slate-500 transition hover:bg-slate-100 hover:text-slate-900 lg:hidden"
            aria-label="Tutup sidebar"
        >
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    {{-- Navigasi --}}
    <nav class="flex-1 overflow-x-hidden overflow-y-auto p-3">
        <div class="space-y-1">
            <a
                href="{{ url('/admin') }}"
                title="Dasbor"
                class="group flex min-h-12 items-center gap-3 overflow-hidden rounded-lg px-3 py-3 text-sm no-underline transition
                    {{ $dashboardActive
                        ? 'bg-blue-950 font-semibold text-white'
                        : 'font-medium text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}"
            >
                <span class="flex h-6 w-6 shrink-0 items-center justify-center">
                    <i class="bi bi-house-door text-lg"></i>
                </span>

                <span
                    data-sidebar-label
                    class="whitespace-nowrap transition-all duration-200"
                >
                    Dasbor
                </span>
            </a>

            <a
                href="{{ url('/admin/aplikasi-demo') }}"
                title="Aplikasi"
                class="group flex min-h-12 items-center gap-3 overflow-hidden rounded-lg px-3 py-3 text-sm no-underline transition
                    {{ $applicationsActive
                        ? 'bg-blue-950 font-semibold text-white'
                        : 'font-medium text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}"
            >
                <span class="flex h-6 w-6 shrink-0 items-center justify-center">
                    <i class="bi bi-window-stack text-lg"></i>
                </span>

                <span
                    data-sidebar-label
                    class="whitespace-nowrap transition-all duration-200"
                >
                    Aplikasi
                </span>
            </a>

            <a
                href="{{ url('/admin/Materi-demo') }}"
                title="Kelola Materi"
                class="group flex min-h-12 items-center gap-3 overflow-hidden rounded-lg px-3 py-3 text-sm no-underline transition
                    {{ $materialsActive
                        ? 'bg-blue-950 font-semibold text-white'
                        : 'font-medium text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}"
            >
                <span class="flex h-6 w-6 shrink-0 items-center justify-center">
                    <i class="bi bi-journal-text text-lg"></i>
                </span>

                <span
                    data-sidebar-label
                    class="whitespace-nowrap transition-all duration-200"
                >
                    Kelola Materi
                </span>
            </a>

            <a
                href="#"
                title="Kategori"
                class="group flex min-h-12 items-center gap-3 overflow-hidden rounded-lg px-3 py-3 text-sm font-medium text-slate-600 no-underline transition hover:bg-slate-100 hover:text-slate-900"
            >
                <span class="flex h-6 w-6 shrink-0 items-center justify-center">
                    <i class="bi bi-tags text-lg"></i>
                </span>

                <span
                    data-sidebar-label
                    class="whitespace-nowrap transition-all duration-200"
                >
                    Kategori
                </span>
            </a>

            <a
                href="#"
                title="Pengguna"
                class="group flex min-h-12 items-center gap-3 overflow-hidden rounded-lg px-3 py-3 text-sm font-medium text-slate-600 no-underline transition hover:bg-slate-100 hover:text-slate-900"
            >
                <span class="flex h-6 w-6 shrink-0 items-center justify-center">
                    <i class="bi bi-people text-lg"></i>
                </span>

                <span
                    data-sidebar-label
                    class="whitespace-nowrap transition-all duration-200"
                >
                    Pengguna
                </span>
            </a>
        </div>

        <div class="my-4 border-t border-slate-200"></div>

        <div class="space-y-1">
            <a
                href="#"
                title="Pengaturan"
                class="group flex min-h-12 items-center gap-3 overflow-hidden rounded-lg px-3 py-3 text-sm font-medium text-slate-600 no-underline transition hover:bg-slate-100 hover:text-slate-900"
            >
                <span class="flex h-6 w-6 shrink-0 items-center justify-center">
                    <i class="bi bi-gear text-lg"></i>
                </span>

                <span
                    data-sidebar-label
                    class="whitespace-nowrap transition-all duration-200"
                >
                    Pengaturan
                </span>
            </a>
        </div>
    </nav>

    {{-- Footer sidebar --}}
    <div class="shrink-0 border-t border-slate-200 p-3">
        <button
            id="sidebar-collapse"
            type="button"
            class="hidden min-h-12 w-full items-center gap-3 overflow-hidden rounded-lg px-3 py-3 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900 lg:flex"
            aria-expanded="false"
            title="Buka sidebar secara permanen"
        >
            <span class="flex h-6 w-6 shrink-0 items-center justify-center">
                <i
                    id="sidebar-collapse-icon"
                    class="bi bi-pin-angle text-lg"
                ></i>
            </span>

            <span
                id="sidebar-collapse-label"
                data-sidebar-label
                class="whitespace-nowrap transition-all duration-200"
            >
                Kunci Sidebar
            </span>
        </button>
    </div>
</aside>

<div
    id="sidebar-overlay"
    class="fixed inset-0 z-40 hidden bg-slate-950/50 backdrop-blur-[1px] lg:hidden"
></div>