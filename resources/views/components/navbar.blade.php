<header class="fixed inset-x-0 top-0 z-50 border-b border-slate-200 bg-white/95 backdrop-blur">
    <nav class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4 lg:px-8">
        {{-- Logo --}}
        <a href="/" class="flex shrink-0 items-center gap-3 transition hover:opacity-80">
            <img src="{{ asset('images/Logo.png') }}" alt="Logo" class="h-12 w-12 object-contain">
            <div>
                <p class="text-lg font-bold leading-tight text-slate-900">Pusat Pengetahuan</p>
                <p class="text-xs text-slate-500">Kabupaten Badung</p>
            </div>
        </a>

        {{-- Global Search Bar (Desktop) --}}
        <div class="hidden lg:block flex-1 max-w-md mx-8">
            <form action="/applications-demo" method="GET" class="relative group">
                <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-blue-600 transition"></i>
                <input type="search" name="search" placeholder="Cari aplikasi, panduan, atau layanan..." 
                    class="w-full rounded-full border border-slate-200 bg-slate-50/50 pl-11 pr-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10">
            </form>
        </div>

        {{-- Desktop Nav & Actions --}}
        <div class="hidden md:flex items-center gap-6">
            <a href="/" class="text-sm font-medium text-slate-600 hover:text-blue-900 transition {{ request()->is('/') ? 'text-blue-900 font-semibold' : '' }}">Beranda</a>
            <a href="/applications-demo" class="text-sm font-medium text-slate-600 hover:text-blue-900 transition {{ request()->is('applications-demo') ? 'text-blue-900 font-semibold' : '' }}">Aplikasi Badung</a>

            <div class="h-6 w-px bg-slate-200"></div>

            {{-- Authentication States --}}
            <div class="flex items-center gap-3 ml-2">
                {{-- Badges (Hidden by default, shown via JS) --}}
                <div id="nav-badges-desktop" class="hidden items-center gap-2">
                    <span id="badge-pegawai-desktop" class="hidden items-center gap-1.5 rounded-full bg-amber-50 border border-amber-200/60 px-3 py-1 text-xs font-semibold text-amber-700 shadow-sm" title="Anda memiliki akses untuk melihat materi internal">
                        <i class="bi bi-shield-check text-amber-500"></i> Akses Pegawai
                    </span>
                    <a href="/admin/materi" id="badge-admin-desktop" class="hidden items-center gap-1.5 rounded-full bg-blue-50 border border-blue-200/60 px-3 py-1 text-xs font-semibold text-blue-700 shadow-sm transition hover:bg-blue-100 hover:border-blue-300">
                        <i class="bi bi-speedometer2 text-blue-500"></i> Dasbor Admin
                    </a>
                </div>

                {{-- Logged Out State --}}
                <a href="/admin/login" id="nav-login-desktop" class="flex items-center gap-2 rounded-full border border-slate-200 bg-white py-1.5 pl-2 pr-4 shadow-sm transition hover:border-slate-300 hover:bg-slate-50 hover:text-blue-700 text-slate-700 group">
                    <i class="bi bi-person-circle text-slate-400 text-xl leading-none transition group-hover:text-blue-600"></i>
                    <span class="text-sm font-semibold">Akses sebagai pegawai</span>
                </a>

                {{-- Logged In State (Logout Button) --}}
                <div id="nav-user-desktop" class="hidden items-center ml-2">
                    <button type="button" id="public-logout-btn" class="flex h-9 w-9 items-center justify-center rounded-full border border-red-200 bg-white text-red-600 shadow-sm transition hover:bg-red-50 hover:border-red-300" title="Keluar Akun">
                        <i class="bi bi-box-arrow-right text-lg leading-none"></i>
                    </button>
                </div>
            </div>
        </div>

        {{-- Mobile Menu Button --}}
        <button id="mobileMenuButton" type="button" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white p-2 text-slate-600 shadow-sm transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500/20 md:hidden">
            <i class="bi bi-list text-xl"></i>
        </button>
    </nav>

    {{-- Mobile Menu --}}
    <div id="mobileMenu" class="hidden border-t border-slate-100 bg-white shadow-xl md:hidden">
        <div class="flex flex-col px-4 py-4 space-y-1">
            <a href="/" class="rounded-xl px-4 py-3 text-base font-medium transition {{ request()->is('/') ? 'bg-blue-50 text-blue-700' : 'text-slate-700 hover:bg-slate-50' }}">Beranda</a>
            <a href="/applications-demo" class="rounded-xl px-4 py-3 text-base font-medium transition {{ request()->is('applications-demo') ? 'bg-blue-50 text-blue-700' : 'text-slate-700 hover:bg-slate-50' }}">Katalog Aplikasi</a>
        </div>

        <div class="border-t border-slate-100 px-4 py-4">
            {{-- Mobile Logged Out State --}}
            <a href="/admin/login" id="nav-login-mobile" class="flex w-full items-center justify-center gap-2 rounded-xl bg-slate-100 px-4 py-3.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-200">
                <i class="bi bi-person-circle text-lg text-slate-500"></i> Akses sebagai pegawai
            </a>

            {{-- Mobile Logged In State --}}
            <div id="nav-user-mobile" class="hidden flex-col">

                <div id="badge-pegawai-mobile" class="px-4 mb-4 hidden">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 border border-amber-200 px-3 py-1 text-xs font-semibold text-amber-700">
                        <i class="bi bi-shield-check"></i> Akses Pegawai
                    </span>
                </div>
                
                <a href="/admin/materi" id="badge-admin-mobile" class="hidden items-center gap-3 rounded-xl px-4 py-3 text-base font-medium text-blue-700 bg-blue-50 transition mb-2">
                    <i class="bi bi-speedometer2"></i> Dasbor Admin
                </a>

                <button type="button" id="mobile-logout-btn" class="flex w-full items-center gap-3 rounded-xl px-4 py-3 text-base font-medium text-red-600 transition hover:bg-red-50">
                    <i class="bi bi-box-arrow-right text-red-500"></i> Keluar
                </button>
            </div>
        </div>
    </div>
</header>

{{-- Logout Confirmation Modal --}}
<div
    id="logout-confirm-modal"
    class="fixed inset-0 z-[9999] hidden items-center justify-center"
    role="dialog"
    aria-modal="true"
    aria-labelledby="logout-modal-title"
>
    {{-- Backdrop --}}
    <div id="logout-modal-backdrop" class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>

    {{-- Dialog --}}
    <div class="relative z-10 w-full max-w-sm rounded-2xl border border-slate-200 bg-white p-8 shadow-2xl mx-4">
        <div class="flex flex-col items-center text-center">
            <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-red-50">
                <i class="bi bi-box-arrow-right text-2xl text-red-500"></i>
            </div>

            <h2 id="logout-modal-title" class="text-lg font-bold text-slate-900">
                Keluar dari Akun?
            </h2>

            <p class="mt-2 text-sm text-slate-500">
                Apakah Anda yakin ingin keluar? Sesi Anda akan diakhiri.
            </p>

            <div class="mt-6 flex w-full gap-3">
                <button
                    id="logout-cancel-btn"
                    type="button"
                    class="flex-1 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                >
                    Batal
                </button>

                <button
                    id="logout-confirm-btn"
                    type="button"
                    class="flex-1 rounded-xl bg-red-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-red-700"
                >
                    Ya, Keluar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Handle Auth State
        const token = localStorage.getItem('auth_token');
        const userStr = localStorage.getItem('user');

        if (token && userStr) {
            try {
                const user = JSON.parse(userStr);
                const roles = user.roles || [];
                
                // Show logged in elements
                document.getElementById('nav-login-desktop')?.classList.add('hidden');
                document.getElementById('nav-login-mobile')?.classList.add('hidden');
                
                document.getElementById('nav-user-desktop')?.classList.replace('hidden', 'flex');
                document.getElementById('nav-user-mobile')?.classList.replace('hidden', 'flex');
                document.getElementById('nav-badges-desktop')?.classList.replace('hidden', 'flex');

                if (document.getElementById('nav-email-mobile')) {
                    document.getElementById('nav-email-mobile').textContent = user.email || '';
                }

                if (roles.includes('Pegawai')) {
                    document.getElementById('badge-pegawai-desktop')?.classList.replace('hidden', 'inline-flex');
                    document.getElementById('badge-pegawai-mobile')?.classList.remove('hidden');
                }
                
                if (roles.includes('Admin')) {
                    document.getElementById('badge-admin-desktop')?.classList.replace('hidden', 'inline-flex');
                    document.getElementById('badge-admin-mobile')?.classList.replace('hidden', 'flex');
                }
            } catch (e) {
                console.error("Error parsing user data", e);
            }
        }

        // Mobile Menu Toggle
        const mobileMenuButton = document.getElementById('mobileMenuButton');
        const mobileMenu = document.getElementById('mobileMenu');
        if(mobileMenuButton && mobileMenu) {
            mobileMenuButton.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });
        }

        // Logout Confirmation Modal
        const logoutModal = document.getElementById('logout-confirm-modal');
        const logoutCancelBtn = document.getElementById('logout-cancel-btn');
        const logoutConfirmBtn = document.getElementById('logout-confirm-btn');
        const logoutModalBackdrop = document.getElementById('logout-modal-backdrop');

        const showLogoutModal = () => {
            logoutModal.classList.remove('hidden');
            logoutModal.classList.add('flex');
        };

        const hideLogoutModal = () => {
            logoutModal.classList.add('hidden');
            logoutModal.classList.remove('flex');
        };

        logoutCancelBtn?.addEventListener('click', hideLogoutModal);
        logoutModalBackdrop?.addEventListener('click', hideLogoutModal);

        // Logout Handlers
        const doLogout = async () => {
            const token = localStorage.getItem('auth_token');
            if (token) {
                try {
                    await fetch('/api/auth/logout', {
                        method: 'POST',
                        headers: {
                            'Authorization': `Bearer ${token}`,
                            'Accept': 'application/json'
                        }
                    });
                } catch (e) {
                    console.error('Logout error', e);
                }
            }
            localStorage.removeItem('auth_token');
            localStorage.removeItem('user');
            window.location.href = '/';
        };

        logoutConfirmBtn?.addEventListener('click', doLogout);

        const logoutBtn = document.getElementById('public-logout-btn');
        const mobileLogoutBtn = document.getElementById('mobile-logout-btn');

        if(logoutBtn) logoutBtn.addEventListener('click', showLogoutModal);
        if(mobileLogoutBtn) mobileLogoutBtn.addEventListener('click', showLogoutModal);
    });
</script>
