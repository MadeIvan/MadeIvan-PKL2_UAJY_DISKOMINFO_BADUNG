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

            {{-- Go to Public Page --}}
            <a
                href="{{ url('/') }}"
                class="
                    flex h-10 w-10 items-center justify-center
                    rounded-xl
                    border border-slate-200
                    bg-white
                    text-slate-600
                    no-underline
                    transition
                    hover:border-blue-200
                    hover:bg-blue-50
                    hover:text-blue-700
                "
                aria-label="Kembali ke halaman utama"
                title="Kembali ke halaman utama"
            >
                <i class="bi bi-door-open text-lg"></i>
            </a>

            {{-- Profile --}}
            <button
                type="button"
                id="profile-btn"
                class="
                    flex items-center gap-3
                    rounded-xl
                    px-2 py-1.5
                    transition
                    hover:bg-slate-100
                "
            >
                <div
                    id="profile-initials"
                    class="
                        flex h-10 w-10 shrink-0 items-center justify-center
                        rounded-xl
                        bg-blue-950
                        text-sm font-bold
                        text-white
                    "
                >
                    --
                </div>

                <div class="hidden text-left md:block">
                    <p
                        id="profile-name"
                        class="
                            text-sm font-semibold
                            text-slate-900
                        "
                    >
                        ...
                    </p>

                    <p
                        id="profile-email"
                        class="
                            mt-0.5 text-xs
                            text-slate-500
                        "
                    >
                        ...
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

            {{-- Dropdown Menu --}}
            <div
                id="profile-dropdown"
                class="
                    absolute
                    right-4
                    top-16
                    z-50
                    mt-2
                    hidden
                    w-48
                    origin-top-right
                    rounded-md
                    bg-white
                    py-1
                    shadow-lg
                    ring-1
                    ring-black
                    ring-opacity-5
                    sm:right-6
                    lg:right-8
                "
            >
                <button
                    type="button"
                    id="logout-button"
                    class="
                        flex w-full
                        items-center
                        px-4 py-2
                        text-sm
                        text-slate-700
                        transition
                        hover:bg-slate-100
                    "
                >
                    <i class="bi bi-box-arrow-right mr-3 text-slate-400"></i>
                    Keluar
                </button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener(
            'DOMContentLoaded',
            () => {
                const profileBtn =
                    document.getElementById(
                        'profile-btn'
                    );

                const dropdown =
                    document.getElementById(
                        'profile-dropdown'
                    );

                const logoutBtn =
                    document.getElementById(
                        'logout-button'
                    );

                const profileInitials =
                    document.getElementById(
                        'profile-initials'
                    );

                const profileName =
                    document.getElementById(
                        'profile-name'
                    );

                const profileEmail =
                    document.getElementById(
                        'profile-email'
                    );

                /*
                |--------------------------------------------------------------------------
                | Populate User Info
                |--------------------------------------------------------------------------
                */

                try {
                    const userStr =
                        localStorage.getItem(
                            'user'
                        );

                    if (userStr) {
                        const user =
                            JSON.parse(
                                userStr
                            );

                        if (profileName) {
                            profileName.textContent =
                                user.name ||
                                'User';
                        }

                        if (profileEmail) {
                            profileEmail.textContent =
                                user.email ||
                                '';
                        }

                        if (
                            profileInitials &&
                            user.name
                        ) {
                            profileInitials.textContent =
                                user.name
                                    .substring(
                                        0,
                                        2
                                    )
                                    .toUpperCase();
                        }
                    }
                } catch (error) {
                    console.error(
                        'Failed to parse user info',
                        error
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Profile Dropdown
                |--------------------------------------------------------------------------
                */

                if (profileBtn) {
                    profileBtn.addEventListener(
                        'click',
                        (event) => {
                            event.stopPropagation();

                            dropdown?.classList.toggle(
                                'hidden'
                            );
                        }
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Close Dropdown Outside
                |--------------------------------------------------------------------------
                */

                document.addEventListener(
                    'click',
                    (event) => {
                        if (
                            dropdown &&
                            !dropdown.classList.contains(
                                'hidden'
                            ) &&
                            profileBtn &&
                            !profileBtn.contains(
                                event.target
                            )
                        ) {
                            dropdown.classList.add(
                                'hidden'
                            );
                        }
                    }
                );

                /*
                |--------------------------------------------------------------------------
                | Logout Modal
                |--------------------------------------------------------------------------
                */

                if (logoutBtn) {
                    logoutBtn.addEventListener(
                        'click',
                        () => {
                            const modal =
                                document.getElementById(
                                    'admin-logout-confirm-modal'
                                );

                            if (modal) {
                                modal.classList.remove(
                                    'hidden'
                                );

                                modal.classList.add(
                                    'flex'
                                );
                            }
                        }
                    );
                }
            }
        );
    </script>
</header>


{{-- =========================================================
    ADMIN LOGOUT CONFIRMATION MODAL
========================================================== --}}
<div
    id="admin-logout-confirm-modal"
    class="
        fixed inset-0
        z-[9999]
        hidden
        items-center
        justify-center
    "
    role="dialog"
    aria-modal="true"
    aria-labelledby="admin-logout-modal-title"
>
    {{-- Backdrop --}}
    <div
        id="admin-logout-modal-backdrop"
        class="
            absolute inset-0
            bg-black/40
            backdrop-blur-sm
        "
    ></div>

    {{-- Modal --}}
    <div
        class="
            relative z-10
            mx-4
            w-full max-w-sm
            rounded-2xl
            border border-slate-200
            bg-white
            p-8
            shadow-2xl
        "
    >
        <div class="flex flex-col items-center text-center">
            <div
                class="
                    mb-4
                    flex h-14 w-14
                    items-center justify-center
                    rounded-full
                    bg-red-50
                "
            >
                <i class="bi bi-box-arrow-right text-2xl text-red-500"></i>
            </div>

            <h2
                id="admin-logout-modal-title"
                class="
                    text-lg font-bold
                    text-slate-900
                "
            >
                Keluar dari Akun?
            </h2>

            <p
                class="
                    mt-2
                    text-sm
                    text-slate-500
                "
            >
                Apakah Anda yakin ingin keluar?
                Sesi Anda akan diakhiri.
            </p>

            <div class="mt-6 flex w-full gap-3">
                <button
                    id="admin-logout-cancel-btn"
                    type="button"
                    class="
                        flex-1
                        rounded-xl
                        border border-slate-200
                        bg-white
                        px-4 py-2.5
                        text-sm font-semibold
                        text-slate-700
                        transition
                        hover:bg-slate-50
                    "
                >
                    Batal
                </button>

                <button
                    id="admin-logout-confirm-btn"
                    type="button"
                    class="
                        flex-1
                        rounded-xl
                        bg-red-600
                        px-4 py-2.5
                        text-sm font-semibold
                        text-white
                        transition
                        hover:bg-red-700
                    "
                >
                    Ya, Keluar
                </button>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener(
        'DOMContentLoaded',
        () => {
            const modal =
                document.getElementById(
                    'admin-logout-confirm-modal'
                );

            const backdrop =
                document.getElementById(
                    'admin-logout-modal-backdrop'
                );

            const cancelBtn =
                document.getElementById(
                    'admin-logout-cancel-btn'
                );

            const confirmBtn =
                document.getElementById(
                    'admin-logout-confirm-btn'
                );

            const hideModal = () => {
                modal?.classList.add(
                    'hidden'
                );

                modal?.classList.remove(
                    'flex'
                );
            };

            backdrop?.addEventListener(
                'click',
                hideModal
            );

            cancelBtn?.addEventListener(
                'click',
                hideModal
            );

            confirmBtn?.addEventListener(
                'click',
                async () => {
                    const token =
                        localStorage.getItem(
                            'auth_token'
                        );

                    if (token) {
                        try {
                            await fetch(
                                '/api/auth/logout',
                                {
                                    method:
                                        'POST',

                                    headers: {
                                        'Authorization':
                                            `Bearer ${token}`,

                                        'Accept':
                                            'application/json',
                                    },
                                }
                            );
                        } catch (error) {
                            console.error(
                                'Logout error',
                                error
                            );
                        }
                    }

                    localStorage.removeItem(
                        'auth_token'
                    );

                    localStorage.removeItem(
                        'user'
                    );

                    window.location.href =
                        '/admin/login';
                }
            );
        }
    );
</script>