<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Pusat Pengetahuan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="min-h-screen bg-slate-50 flex font-sans">
    
    <!-- Left Side: Login Form -->
    <div class="w-full lg:w-5/12 flex flex-col justify-center px-6 py-12 sm:px-12 lg:px-24 xl:px-32 relative z-20 bg-white lg:bg-transparent lg:shadow-none shadow-xl">
        <!-- Logo for mobile view -->
        <div class="lg:hidden text-center mb-10">
            <img src="{{ asset('images/Logo.png') }}" alt="Logo Pusat Pengetahuan" class="mx-auto h-16 w-16 object-contain mb-4">
            <h2 class="text-2xl font-bold tracking-tight text-slate-900">
                LMS Pemkab Badung
            </h2>
        </div>

        <div class="mx-auto w-full max-w-sm">
            <div class="mb-10 text-center lg:text-left">
                <img src="{{ asset('images/Logo.png') }}" alt="Logo" class="hidden lg:block h-12 w-12 object-contain mb-6">
                <h2 class="text-3xl font-bold tracking-tight text-slate-900 mb-2">Selamat Datang</h2>
                <p class="text-slate-500 text-sm">Masuk ke panel administrator LMS.</p>
            </div>
            
            <form id="loginForm" class="space-y-6">
                
                <!-- Error Alert (Hidden by default) -->
                <div id="errorAlert" class="hidden rounded-xl bg-red-50 p-4 border border-red-200 mb-6 transition-all">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mt-0.5">
                            <i class="bi bi-exclamation-triangle-fill text-red-500"></i>
                        </div>
                        <div class="ml-3">
                            <h3 id="errorMessage" class="text-sm font-medium text-red-800"></h3>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-sm font-semibold leading-6 text-slate-700">Alamat Email</label>
                    <div class="mt-2 relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <i class="bi bi-envelope text-slate-400"></i>
                        </div>
                        <input id="email" name="email" type="email" autocomplete="email" required 
                            class="block w-full rounded-xl border-0 py-3 pl-10 pr-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all duration-200 bg-white hover:bg-slate-50 focus:bg-white"
                            placeholder="admin@badungkab.go.id">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold leading-6 text-slate-700">Kata Sandi</label>
                    <div class="mt-2 relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <i class="bi bi-lock text-slate-400"></i>
                        </div>
                        <input id="password" name="password" type="password" autocomplete="current-password" required 
                            class="block w-full rounded-xl border-0 py-3 pl-10 pr-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all duration-200 bg-white hover:bg-slate-50 focus:bg-white"
                            placeholder="••••••••">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" id="submitBtn" 
                        class="group flex w-full justify-center rounded-xl bg-blue-600 px-4 py-3.5 text-sm font-semibold text-white shadow-md hover:bg-blue-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-all duration-200 items-center gap-2 hover:shadow-lg active:scale-[0.98]">
                        <span id="btnText">Masuk ke Sistem</span>
                        <i class="bi bi-arrow-right group-hover:translate-x-1 transition-transform" id="btnArrow"></i>
                        <i id="btnSpinner" class="bi bi-arrow-repeat animate-spin hidden text-lg"></i>
                    </button>
                </div>
            </form>
            
            <div class="mt-12 text-center text-xs text-slate-400">
                &copy; {{ date('Y') }} Dinas Komunikasi dan Informatika<br>Pemerintah Kabupaten Badung
            </div>
        </div>
    </div>

    <!-- Right Side: Image Panel -->
    <div class="hidden lg:flex lg:w-7/12 relative bg-slate-900 overflow-hidden shadow-2xl z-10">
        <div class="absolute inset-0 bg-blue-900/30 mix-blend-multiply z-10"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-slate-900/20 to-transparent z-10"></div>
        
        <img 
            src="{{ asset('images/pemkab-badung_169.png') }}" 
            alt="Pemkab Badung" 
            class="absolute inset-0 w-full h-full object-cover z-0"
        >

        <div class="relative z-20 flex flex-col justify-end p-12 w-full h-full text-white pb-20">
            <h1 class="text-4xl font-bold mb-4 tracking-tight shadow-sm">LMS Pemkab Badung</h1>
            <p class="text-lg text-slate-200 max-w-md leading-relaxed">Sistem Manajemen Pembelajaran terpadu untuk mendokumentasikan dan berbagi informasi di lingkungan Pemerintah Kabupaten Badung.</p>
        </div>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const submitBtn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const btnSpinner = document.getElementById('btnSpinner');
            const errorAlert = document.getElementById('errorAlert');
            const errorMessage = document.getElementById('errorMessage');

            // Loading state
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
            btnText.innerText = 'Memproses...';
            btnSpinner.classList.remove('hidden');
            errorAlert.classList.add('hidden');

            try {
                const response = await fetch('/api/auth/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ email, password })
                });

                const data = await response.json();

                if (response.ok) {
                    // Save token and user info
                    localStorage.setItem('auth_token', data.data.token);
                    if(data.data.user) {
                        localStorage.setItem('user', JSON.stringify(data.data.user));
                    }
                    
                    // Button success state
                    btnText.innerText = 'Berhasil!';
                    btnSpinner.classList.add('hidden');
                    
                    // Redirect based on role after brief delay
                    setTimeout(() => {
                        const roles = data.data.user.roles || [];
                        if (roles.includes('Pegawai')) {
                            window.location.href = '/';
                        } else {
                            window.location.href = '/admin/materi';
                        }
                    }, 500);
                } else {
                    // Handle validation or auth errors
                    throw new Error(data.message || data.errors?.email?.[0] || 'Gagal login, periksa kredensial Anda.');
                }
            } catch (error) {
                // Reset loading state and show error
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-75', 'cursor-not-allowed');
                btnText.innerText = 'Masuk ke Sistem';
                btnSpinner.classList.add('hidden');
                
                errorMessage.innerText = error.message;
                errorAlert.classList.remove('hidden');
            }
        });

        // Auto-redirect if already logged in
        if (localStorage.getItem('auth_token')) {
            const userStr = localStorage.getItem('user');
            if (userStr) {
                try {
                    const user = JSON.parse(userStr);
                    // If roles are undefined (old session), assume Admin for auto-redirect
                    // The dashboard layout will clear invalid sessions if needed
                    const roles = user.roles || ['Admin']; 
                    if (roles.includes('Pegawai')) {
                        window.location.href = '/';
                    } else {
                        window.location.href = '/admin/materi';
                    }
                } catch (e) {
                    window.location.href = '/admin/materi';
                }
            } else {
                window.location.href = '/admin/materi';
            }
        }
    </script>
</body>
</html>
