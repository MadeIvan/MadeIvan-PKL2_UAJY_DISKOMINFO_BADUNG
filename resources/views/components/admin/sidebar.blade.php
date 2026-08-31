@php
    $dashboardActive = request()->is('admin');

    $applicationsActive =
        request()->is('admin/applications*') ||
        request()->is('admin/aplikasi*');

    $categoriesActive =
        request()->is('admin/categories*');

    $materialsActive =
        request()->is('admin/materi*');

    $usersActive =
        request()->is('admin/pengguna*');
@endphp

<aside
    id="admin-sidebar"
    class="
        fixed inset-y-0 left-0 z-50
        flex w-72 -translate-x-full flex-col
        overflow-hidden lg:overflow-visible
        bg-[#071f45]
        text-white
        shadow-2xl
        transition-[width,transform] duration-300 ease-in-out

        lg:w-20
        lg:translate-x-0
        lg:shadow-none
    "
    aria-label="Navigasi admin"
    data-sidebar-pinned="false"
    data-sidebar-expanded="false"
>
    {{-- Sidebar Header --}}
    <div
        class="
            flex h-20 shrink-0 items-center justify-between
            border-b border-white/10
            px-4
            gap-2
        "
    >
        <a
            href="{{ url('/admin') }}"
            class="
                flex min-w-0 shrink-0 items-center gap-3
                overflow-hidden
                no-underline
            "
        >
            {{-- System Logo --}}
            <div
                class="
                    flex h-11 w-11 shrink-0 items-center justify-center
                    overflow-hidden
                    rounded-xl
                    bg-white
                    p-1.5
                    shadow-sm
                "
            >
                <img
                    src="{{ asset('images/Logo.png') }}"
                    alt="Logo Pusat Pengetahuan"
                    class="h-full w-full object-contain"
                >
            </div>

            {{-- System Name --}}
            <div
                data-sidebar-label
                class="
                    hidden min-w-0 whitespace-nowrap
                    opacity-0
                    transition-all duration-200
                "
            >
                <p class="truncate text-sm font-bold text-white">
                    Pusat Pengetahuan
                </p>

                <p class="mt-0.5 truncate text-xs text-blue-200">
                    Panel Administrator
                </p>
            </div>
        </a>

        {{-- Desktop Hamburger Toggle --}}
        <div data-sidebar-label class="hidden opacity-0 transition-all duration-200 lg:block ml-auto">
            <button
                id="sidebar-collapse"
                type="button"
                class="
                    flex h-9 w-9 shrink-0 items-center justify-center
                    rounded-lg
                    text-blue-100
                    transition
                    hover:bg-white/10
                    hover:text-white
                "
                aria-label="Toggle sidebar"
                title="Toggle Sidebar"
            >
                <i class="bi bi-list text-xl"></i>
            </button>
        </div>

        {{-- Mobile Close --}}
        <button
            id="sidebar-close-mobile"
            type="button"
            class="
                ml-2 flex h-9 w-9 shrink-0 items-center justify-center
                rounded-lg
                text-blue-100
                transition
                hover:bg-white/10
                hover:text-white
                lg:hidden
            "
            aria-label="Tutup sidebar"
        >
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    {{-- Navigation --}}
    <nav
        class="
            flex-1
            overflow-x-hidden
            overflow-y-auto
            px-3 py-5
        "
    >
        <p
            data-sidebar-label
            class="
                mb-3 hidden px-3
                text-[11px] font-bold uppercase tracking-[0.18em]
                text-blue-300
                opacity-0
                transition-all duration-200
            "
        >
            Menu Utama
        </p>

        <div class="space-y-1.5">

            {{-- Dashboard --}}
            <a
                href="{{ url('/admin') }}"
                title="Dasbor"
                class="
                    group flex min-h-12 items-center gap-3
                    overflow-hidden
                    rounded-xl
                    px-3 py-3
                    text-sm
                    no-underline
                    transition

                    {{ $dashboardActive
                        ? 'bg-blue-600 font-semibold text-white shadow-sm'
                        : 'font-medium text-blue-100 hover:bg-white/10 hover:text-white'
                    }}
                "
            >
                <span class="flex h-6 w-6 shrink-0 items-center justify-center">
                    <i class="bi bi-grid-fill text-lg"></i>
                </span>

                <span
                    data-sidebar-label
                    class="
                        hidden whitespace-nowrap
                        opacity-0
                        transition-all duration-200
                    "
                >
                    Dasbor
                </span>
            </a>

            {{-- Applications --}}
            <a
                href="{{ url('/admin/aplikasi') }}"
                title="Aplikasi"
                class="
                    group flex min-h-12 items-center gap-3
                    overflow-hidden
                    rounded-xl
                    px-3 py-3
                    text-sm
                    no-underline
                    transition

                    {{ $applicationsActive
                        ? 'bg-blue-600 font-semibold text-white shadow-sm'
                        : 'font-medium text-blue-100 hover:bg-white/10 hover:text-white'
                    }}
                "
            >
                <span class="flex h-6 w-6 shrink-0 items-center justify-center">
                    <i class="bi bi-window-stack text-lg"></i>
                </span>

                <span
                    data-sidebar-label
                    class="
                        hidden whitespace-nowrap
                        opacity-0
                        transition-all duration-200
                    "
                >
                    Aplikasi
                </span>
            </a>

            {{-- Materials --}}
            <a
                href="{{ url('/admin/materi') }}"
                title="Kelola Materi"
                class="
                    group flex min-h-12 items-center gap-3
                    overflow-hidden
                    rounded-xl
                    px-3 py-3
                    text-sm
                    no-underline
                    transition

                    {{ $materialsActive
                        ? 'bg-blue-600 font-semibold text-white shadow-sm'
                        : 'font-medium text-blue-100 hover:bg-white/10 hover:text-white'
                    }}
                "
            >
                <span class="flex h-6 w-6 shrink-0 items-center justify-center">
                    <i class="bi bi-journal-text text-lg"></i>
                </span>

                <span
                    data-sidebar-label
                    class="
                        hidden whitespace-nowrap
                        opacity-0
                        transition-all duration-200
                    "
                >
                    Kelola Materi
                </span>
            </a>

            {{-- Categories --}}
            <a
                href="{{ url('/admin/categories') }}"
                title="Kategori"
                class="
                    group flex min-h-12 items-center gap-3
                    overflow-hidden
                    rounded-xl
                    px-3 py-3
                    text-sm
                    no-underline
                    transition

                    {{ $categoriesActive
                        ? 'bg-blue-600 font-semibold text-white shadow-sm'
                        : 'font-medium text-blue-100 hover:bg-white/10 hover:text-white'
                    }}
                "
            >
                <span class="flex h-6 w-6 shrink-0 items-center justify-center">
                    <i class="bi bi-tags text-lg"></i>
                </span>

                <span
                    data-sidebar-label
                    class="
                        hidden whitespace-nowrap
                        opacity-0
                        transition-all duration-200
                    "
                >
                    Kategori
                </span>
            </a>

            {{-- Users --}}
            <a
                href="{{ url('/admin/pengguna') }}"
                title="Pengguna"
                class="
                    group flex min-h-12 items-center gap-3
                    overflow-hidden
                    rounded-xl
                    px-3 py-3
                    text-sm
                    no-underline
                    transition

                    {{ $usersActive
                        ? 'bg-blue-600 font-semibold text-white shadow-sm'
                        : 'font-medium text-blue-100 hover:bg-white/10 hover:text-white'
                    }}
                "
            >
                <span class="flex h-6 w-6 shrink-0 items-center justify-center">
                    <i class="bi bi-people text-lg"></i>
                </span>

                <span
                    data-sidebar-label
                    class="
                        hidden whitespace-nowrap
                        opacity-0
                        transition-all duration-200
                    "
                >
                    Pengguna
                </span>
            </a>
        </div>

    </nav>

    {{-- Sidebar Footer --}}
</aside>

{{-- Mobile Overlay --}}
<div
    id="sidebar-overlay"
    class="
        fixed inset-0 z-40
        hidden
        bg-slate-950/60
        backdrop-blur-[1px]
        lg:hidden
    "
></div>