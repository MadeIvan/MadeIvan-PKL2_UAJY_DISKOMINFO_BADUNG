import 'bootstrap-icons/font/bootstrap-icons.css';

document.addEventListener('DOMContentLoaded', () => {
    fetchRecentApps();
});

async function fetchRecentApps() {
    const container = document.getElementById('recent-apps-container');
    const skeleton = document.getElementById('recent-apps-skeleton');
    
    if (!container || !skeleton) return;

    try {
        const response = await fetch('/api/applications?sort=latest&per_page=10', {
            headers: {
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error('Gagal mengambil data aplikasi');
        }

        const result = await response.json();
        const applications = result.data;

        renderApps(applications, container);
    } catch (error) {
        container.innerHTML = `
            <div class="col-span-full py-10 text-center text-red-500 bg-red-50 rounded-2xl border border-red-100">
                <i class="bi bi-exclamation-triangle text-2xl mb-2 inline-block"></i>
                <p>Gagal memuat aplikasi terbaru. Silakan coba muat ulang halaman.</p>
            </div>
        `;
        container.classList.remove('hidden');
    } finally {
        skeleton.classList.add('hidden');
    }
}

function renderApps(apps, container) {
    if (apps.length === 0) {
        container.innerHTML = `
            <div class="col-span-full py-10 text-center text-slate-500 bg-slate-50 rounded-2xl border border-slate-200">
                <i class="bi bi-inbox text-3xl mb-2 inline-block"></i>
                <p>Belum ada aplikasi yang ditambahkan.</p>
            </div>
        `;
        container.classList.remove('hidden');
        return;
    }

    let html = '';
    
    apps.forEach(app => {
        // App URL based on the slug
        const appUrl = `/applications/${app.slug}`;
        
        html += `
            <a href="${appUrl}" class="group relative flex flex-col items-center justify-center p-6 text-center transition-all duration-300 rounded-2xl bg-white border border-slate-200 hover:border-blue-200 hover:shadow-xl hover:shadow-blue-900/5 hover:-translate-y-1">
                <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-50 border border-slate-100 transition-colors group-hover:bg-blue-50 group-hover:border-blue-100 p-2 overflow-hidden shadow-sm">
                    <img src="${app.logo_url}" alt="Logo ${app.name}" class="object-contain w-full h-full" onerror="this.src='/images/Logo.png'">
                </div>
                <h3 class="text-sm font-bold text-slate-900 group-hover:text-blue-900 line-clamp-1 transition-colors">
                    ${app.name}
                </h3>
                <p class="mt-1 text-xs text-slate-500 line-clamp-2">
                    ${app.description || 'Tidak ada deskripsi tersedia.'}
                </p>
                <div class="mt-4 opacity-0 group-hover:opacity-100 transition-opacity flex items-center text-xs font-semibold text-blue-700">
                    Lihat Dokumentasi <i class="bi bi-arrow-right-short ml-1 text-lg"></i>
                </div>
            </a>
        `;
    });

    container.innerHTML = html;
    container.classList.remove('hidden');
}
