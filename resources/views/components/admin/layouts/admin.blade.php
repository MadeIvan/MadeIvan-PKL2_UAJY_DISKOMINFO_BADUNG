<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        @yield('title', 'Panel Administrator')
    </title>

    @vite([
        'resources/css/app.css',
        'resources/js/admin/sidebar.js',
    ])

    @stack('scripts')

    <script>
        (function() {
            const token = localStorage.getItem('auth_token');
            if (!token) {
                window.location.href = '/admin/login';
                return;
            }
            
            try {
                const userStr = localStorage.getItem('user');
                if (userStr) {
                    const user = JSON.parse(userStr);
                    // Support old sessions by assuming Admin if roles is undefined
                    const roles = user.roles || ['Admin'];
                    if (!roles.includes('Admin')) {
                        // Not an admin, kick them out
                        localStorage.removeItem('auth_token');
                        localStorage.removeItem('user');
                        window.location.href = '/admin/login';
                    }
                } else {
                    localStorage.removeItem('auth_token');
                    window.location.href = '/admin/login';
                }
            } catch (e) {
                localStorage.removeItem('auth_token');
                localStorage.removeItem('user');
                window.location.href = '/admin/login';
            }
            
            // Global fetch interceptor to handle 401 Unauthorized
            const originalFetch = window.fetch;
            window.fetch = async function(...args) {
                // Add Authorization header globally to all API requests if token exists
                const token = localStorage.getItem('auth_token');
                if (token && typeof args[0] === 'string' && args[0].startsWith('/api/')) {
                    args[1] = args[1] || {};
                    args[1].headers = args[1].headers || {};
                    if (args[1].headers instanceof Headers) {
                        args[1].headers.set('Authorization', `Bearer ${token}`);
                    } else {
                        args[1].headers['Authorization'] = `Bearer ${token}`;
                    }
                }
                
                try {
                    const response = await originalFetch.apply(this, args);
                    if (response.status === 401 || response.status === 419) {
                        localStorage.removeItem('auth_token');
                        localStorage.removeItem('user');
                        window.location.href = '/admin/login';
                    }
                    return response;
                } catch (error) {
                    throw error;
                }
            };
        })();
    </script>
</head>

<body class="min-h-screen bg-slate-100 text-slate-900">
    @include('components.admin.sidebar')

    <div
        id="admin-main"
        class="min-h-screen transition-[margin] duration-300 ease-in-out lg:ml-20"
    >
        @include('components.admin.topbar')

        <main class="p-4 sm:p-6 lg:p-8">
            <div class="mx-auto max-w-[1600px]">
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>