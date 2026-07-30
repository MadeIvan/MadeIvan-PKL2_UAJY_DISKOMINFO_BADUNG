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
</head>

<body class="min-h-screen bg-slate-100 text-slate-900">
    @include('components.admin.sidebar')

    <div
        id="admin-main"
        class="min-h-screen transition-all duration-300 lg:ml-64"
    >
        @include('components.admin.topbar')

        <main class="p-4 sm:p-6 lg:p-8">
            @yield('content')
        </main>
    </div>
</body>
</html>