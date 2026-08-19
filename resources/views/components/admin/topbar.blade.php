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
            
            {{-- Dropdown Menu (Hidden by default) --}}
            <div id="profile-dropdown" class="absolute right-4 sm:right-6 lg:right-8 top-16 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none hidden z-50">
                <button type="button" id="logout-button" class="flex w-full items-center px-4 py-2 text-sm text-slate-700 hover:bg-slate-100 transition">
                    <i class="bi bi-box-arrow-right mr-3 text-slate-400"></i>
                    Keluar
                </button>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const profileBtn = document.getElementById('profile-btn');
            const dropdown = document.getElementById('profile-dropdown');
            const logoutBtn = document.getElementById('logout-button');
            const profileInitials = document.getElementById('profile-initials');
            const profileName = document.getElementById('profile-name');
            const profileEmail = document.getElementById('profile-email');
            
            // Populate user info from localStorage
            try {
                const userStr = localStorage.getItem('user');
                if (userStr) {
                    const user = JSON.parse(userStr);
                    if (profileName) profileName.textContent = user.name || 'User';
                    if (profileEmail) profileEmail.textContent = user.email || '';
                    if (profileInitials && user.name) {
                        profileInitials.textContent = user.name.substring(0, 2).toUpperCase();
                    }
                }
            } catch (e) {
                console.error('Failed to parse user info', e);
            }
            
            // Toggle dropdown
            if (profileBtn) {
                profileBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    dropdown.classList.toggle('hidden');
                });
            }
            
            // Close dropdown when clicking outside
            document.addEventListener('click', (e) => {
                if (dropdown && !dropdown.classList.contains('hidden') && !profileBtn.contains(e.target)) {
                    dropdown.classList.add('hidden');
                }
            });
            
            // Handle Logout
            if (logoutBtn) {
                logoutBtn.addEventListener('click', async () => {
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
                    window.location.href = '/admin/login';
                });
            }
        });
    </script>
</header>