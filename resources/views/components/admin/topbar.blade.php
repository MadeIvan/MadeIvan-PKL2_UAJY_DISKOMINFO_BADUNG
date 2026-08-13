<header
    class="
        sticky top-0 z-30
        h-20
        border-b border-slate-200
        bg-white/95
        backdrop-blur
    "
>
    <div
        class="
            flex h-full items-center justify-between
            gap-4
            px-4
            sm:px-6
            lg:px-8
        "
    >
        {{-- Left --}}
        <div class="flex min-w-0 items-center gap-3">

            {{-- Mobile Sidebar Button --}}
            <button
                id="sidebar-open-mobile"
                type="button"
                class="
                    flex h-10 w-10 shrink-0 items-center justify-center
                    rounded-xl
                    border border-slate-200
                    bg-white
                    text-slate-600
                    transition
                    hover:border-slate-300
                    hover:bg-slate-50
                    hover:text-slate-950
                    lg:hidden
                "
                aria-label="Buka sidebar"
            >
                <i class="bi bi-list text-xl"></i>
            </button>

            <div class="min-w-0">
                <p
                    class="
                        text-[11px] font-bold uppercase tracking-[0.18em]
                        text-blue-700
                    "
                >
                    Panel Administrator
                </p>

                <h1
                    class="
                        mt-1 truncate
                        text-lg font-bold
                        text-slate-950
                    "
                >
                    @yield('page-title', 'Dasbor')
                </h1>
            </div>
        </div>

        {{-- Right --}}
        <div class="flex shrink-0 items-center gap-2 sm:gap-3">

            {{-- Notification --}}
            <button
                type="button"
                class="
                    relative
                    flex h-10 w-10 items-center justify-center
                    rounded-xl
                    border border-slate-200
                    bg-white
                    text-slate-600
                    transition
                    hover:border-slate-300
                    hover:bg-slate-50
                    hover:text-slate-950
                "
                aria-label="Notifikasi"
            >
                <i class="bi bi-bell"></i>

                <span
                    class="
                        absolute right-2 top-2
                        h-2 w-2
                        rounded-full
                        bg-red-500
                        ring-2 ring-white
                    "
                ></span>
            </button>

            {{-- Profile --}}
            <button
                type="button"
                class="
                    flex items-center gap-3
                    rounded-xl
                    px-2 py-1.5
                    transition
                    hover:bg-slate-100
                "
            >
                <div
                    class="
                        flex h-10 w-10 shrink-0 items-center justify-center
                        rounded-xl
                        bg-blue-950
                        text-sm font-bold
                        text-white
                    "
                >
                    AD
                </div>

                <div class="hidden text-left md:block">
                    <p
                        class="
                            text-sm font-semibold
                            text-slate-900
                        "
                    >
                        Administrator
                    </p>

                    <p
                        class="
                            mt-0.5 text-xs
                            text-slate-500
                        "
                    >
                        admin@example.com
                    </p>
                </div>

                <i
                    class="
                        bi bi-chevron-down
                        hidden text-xs
                        text-slate-400
                        md:block
                    "
                ></i>
            </button>
        </div>
    </div>
</header>