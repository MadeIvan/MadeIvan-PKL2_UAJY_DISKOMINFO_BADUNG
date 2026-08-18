<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Pusat Pengetahuan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="min-h-screen bg-slate-100 text-slate-900 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    
    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center">
        <img src="{{ asset('images/Logo.png') }}" alt="Logo Pusat Pengetahuan" class="mx-auto h-16 w-16 object-contain">
        <h2 class="mt-6 text-2xl font-bold leading-9 tracking-tight text-slate-900">
            Pusat Pengetahuan
        </h2>
        <p class="mt-2 text-sm text-slate-500">
            Panel Administrator Login
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white px-6 py-8 shadow-sm border border-slate-200 sm:rounded-2xl sm:px-10">
            
            <form id="loginForm" class="space-y-6">
                
                <!-- Error Alert (Hidden by default) -->
                <div id="errorAlert" class="hidden rounded-xl bg-red-50 p-4 border border-red-200 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="bi bi-exclamation-triangle-fill text-red-500"></i>
                        </div>
                        <div class="ml-3">
                            <h3 id="errorMessage" class="text-sm font-medium text-red-800"></h3>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-sm font-semibold leading-6 text-slate-900">Alamat Email</label>
                    <div class="mt-2">
                        <input id="email" name="email" type="email" autocomplete="email" required 
                            class="block w-full rounded-xl border-0 py-2.5 px-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-blue-900 sm:text-sm sm:leading-6 transition"
                            placeholder="admin@example.com">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold leading-6 text-slate-900">Password</label>
                    <div class="mt-2">
                        <input id="password" name="password" type="password" autocomplete="current-password" required 
                            class="block w-full rounded-xl border-0 py-2.5 px-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-blue-900 sm:text-sm sm:leading-6 transition"
                            placeholder="••••••••">
                    </div>
                </div>

                <div>
                    <button type="submit" id="submitBtn" 
                        class="flex w-full justify-center rounded-xl bg-blue-900 px-4 py-3 text-sm font-semibold text-white shadow-sm hover:bg-blue-950 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-900 transition items-center gap-2">
                        <span id="btnText">Masuk</span>
                        <i id="btnSpinner" class="bi bi-arrow-repeat animate-spin hidden text-lg"></i>
                    </button>
                </div>
            </form>
            
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
                    
                    // Redirect to dashboard after brief delay
                    setTimeout(() => {
                        window.location.href = '/admin/materi';
                    }, 500);
                } else {
                    // Handle validation or auth errors
                    throw new Error(data.message || data.errors?.email?.[0] || 'Gagal login, periksa kredensial Anda.');
                }
            } catch (error) {
                // Reset loading state and show error
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-75', 'cursor-not-allowed');
                btnText.innerText = 'Masuk';
                btnSpinner.classList.add('hidden');
                
                errorMessage.innerText = error.message;
                errorAlert.classList.remove('hidden');
            }
        });

        // Auto-redirect if already logged in
        if(localStorage.getItem('auth_token')) {
            window.location.href = '/admin/admin-dashboard';
        }
    </script>
</body>
</html>
