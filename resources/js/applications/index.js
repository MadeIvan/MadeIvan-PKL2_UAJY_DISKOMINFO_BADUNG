import 'bootstrap-icons/font/bootstrap-icons.css';

const API_APPS_URL = '/api/applications';
const API_MATERI_URL = '/api/public/materi';

const urlParams = new URLSearchParams(window.location.search);

const state = {
    activeTab: 'applications', // 'applications' | 'materi'
    categoryId: null, // Still supported by API if accessed via direct link
    
    data: [], // Stores apps or materi
    search: urlParams.get('search') || '',
    sort: 'latest',
    currentPage: 1,
    lastPage: 1,
    total: 0,
    from: null,
    to: null,
};

const elements = {
    // Tabs
    tabApps: document.getElementById('tab-applications'),
    tabMateri: document.getElementById('tab-materi'),
    headerApps: document.getElementById('header-applications'),
    mainTitle: document.getElementById('mainTitle'),

    // Shared controls
    search: document.getElementById('applicationSearch'),
    sort: document.getElementById('applicationSort'),
    
    // Grids
    appGrid: document.getElementById('applicationGrid'),
    materiGrid: document.getElementById('materiGrid'),

    // States
    loading: document.getElementById('applicationLoading'),
    empty: document.getElementById('emptyResult'),
    emptyTitle: document.getElementById('emptyResultTitle'),
    error: document.getElementById('applicationError'),
    retry: document.getElementById('applicationRetry'),
    count: document.getElementById('applicationCount'),

    // Pagination
    paginationWrapper: document.getElementById('applicationPaginationWrapper'),
    pagination: document.getElementById('applicationPagination'),
    pageInfo: document.getElementById('applicationPageInfo'),
};

let searchTimeout = null;

if (state.search) {
    elements.search.value = state.search;
}

// Bind Tabs
elements.tabApps?.addEventListener('click', () => switchTab('applications'));
elements.tabMateri?.addEventListener('click', () => switchTab('materi'));

function switchTab(tab) {
    if (state.activeTab === tab && state.categoryId === null) return;
    
    state.activeTab = tab;
    state.categoryId = null; // reset any previous application-level filters
    state.currentPage = 1;
    
    // Update UI tabs
    if (tab === 'applications') {
        elements.tabApps.className = "inline-flex shrink-0 items-center gap-2 border-b-2 border-blue-600 px-1 pb-4 text-sm font-semibold text-blue-600 transition-colors";
        elements.tabMateri.className = "inline-flex shrink-0 items-center gap-2 border-b-2 border-transparent px-1 pb-4 text-sm font-semibold text-slate-500 hover:border-slate-300 hover:text-slate-700 transition-colors";
        elements.mainTitle.textContent = "Semua Aplikasi";
    } else {
        elements.tabMateri.className = "inline-flex shrink-0 items-center gap-2 border-b-2 border-blue-600 px-1 pb-4 text-sm font-semibold text-blue-600 transition-colors";
        elements.tabApps.className = "inline-flex shrink-0 items-center gap-2 border-b-2 border-transparent px-1 pb-4 text-sm font-semibold text-slate-500 hover:border-slate-300 hover:text-slate-700 transition-colors";
        elements.mainTitle.textContent = "Semua Materi";
    }

    fetchData(1);
}

async function fetchData(page = 1) {
    showLoading();

    try {
        const parameters = new URLSearchParams({
            page: String(page),
            sort: state.sort,
        });

        if (state.search !== '') {
            parameters.set('search', state.search);
        }

        if (state.activeTab === 'applications' && state.categoryId) {
            parameters.set('category_id', state.categoryId);
        }

        const headers = { Accept: 'application/json' };
        const token = localStorage.getItem('auth_token');
        if (token) {
            headers['Authorization'] = `Bearer ${token}`;
        }

        const url = state.activeTab === 'applications' ? API_APPS_URL : API_MATERI_URL;

        const response = await fetch(`${url}?${parameters.toString()}`, { headers });
        const result = await parseResponse(response);

        state.data = Array.isArray(result.data) ? result.data : [];
        state.currentPage = Number(result.meta?.current_page ?? 1);
        state.lastPage = Number(result.meta?.last_page ?? 1);
        state.total = Number(result.meta?.total ?? 0);
        state.from = result.meta?.from ?? null;
        state.to = result.meta?.to ?? null;

        if (result.filters?.sort) {
            state.sort = normalizeSort(result.filters.sort);
            elements.sort.value = state.sort;
        }

        renderGrid();
        renderPagination();
    } catch (error) {
        showError(error.message);
    }
}

function renderGrid() {
    hideStates();

    elements.count.textContent = `${state.total} data ditemukan`;

    if (state.data.length === 0) {
        elements.appGrid.innerHTML = '';
        elements.appGrid.style.display = 'none';
        elements.materiGrid.innerHTML = '';
        elements.materiGrid.style.display = 'none';

        elements.emptyTitle.textContent = state.activeTab === 'applications' ? 'Aplikasi tidak ditemukan' : 'Materi tidak ditemukan';
        elements.empty.classList.remove('hidden');
        elements.paginationWrapper.classList.add('hidden');
        return;
    }

    if (state.activeTab === 'applications') {
        elements.materiGrid.style.display = 'none';
        elements.materiGrid.innerHTML = '';
        elements.appGrid.innerHTML = state.data.map(createApplicationCard).join('');
        elements.appGrid.style.display = '';
    } else {
        elements.appGrid.style.display = 'none';
        elements.appGrid.innerHTML = '';
        elements.materiGrid.innerHTML = state.data.map(createMateriCard).join('');
        elements.materiGrid.style.display = '';
    }
}

function createMateriCard(materi) {
    const description = materi.description || 'Tidak ada deskripsi.';
    const appName = materi.application?.name || 'Aplikasi';
    const appVersion = materi.application_version?.version_number || '';
    const logoUrl = materi.application?.logo_path 
        ? '/storage/' + materi.application.logo_path 
        : '/images/Logo.png';
    
    // Using the public material route
    const url = `/materi/${encodeURIComponent(materi.slug)}`;
    
    return `
        <a href="${escapeAttribute(url)}" class="block w-full no-underline">
            <article class="group flex flex-col sm:flex-row items-center gap-6 overflow-hidden rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200 transition duration-200 hover:-translate-y-1 hover:shadow-md cursor-pointer">
                
                <div class="flex h-20 w-20 shrink-0 items-center justify-center overflow-hidden rounded-xl bg-slate-50 border border-slate-100">
                    <img src="${escapeAttribute(logoUrl)}" alt="${escapeAttribute(appName)}" class="h-full w-full object-contain p-2 transition duration-300 group-hover:scale-110" loading="lazy" onerror="this.onerror=null;this.src='/images/Logo.png';">
                </div>

                <div class="flex flex-1 flex-col justify-center min-w-0">
                    <div class="mb-1 flex items-center gap-3">
                        <span class="inline-flex items-center rounded-full bg-blue-50 px-2 py-0.5 text-[10px] font-semibold text-blue-700">
                            ${escapeHtml(appName)} ${appVersion ? `v${escapeHtml(appVersion)}` : ''}
                        </span>
                    </div>
                    
                    <h3 class="font-bold text-slate-900 group-hover:text-blue-600 transition text-lg truncate">${escapeHtml(materi.title)}</h3>
                    
                    <p class="mt-1 text-sm text-slate-600 line-clamp-2">
                        ${escapeHtml(description)}
                    </p>
                </div>
                
                <div class="shrink-0 sm:ml-4 flex items-center justify-center">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-50 text-blue-600 transition group-hover:bg-blue-600 group-hover:text-white">
                        <i class="bi bi-arrow-right"></i>
                    </div>
                </div>

            </article>
        </a>
    `;
}

function createApplicationCard(application) {
    const logoUrl = application.logo_url || '/images/Logo.png';
    const category = application.category_name || 'Tanpa Kategori';
    const description = application.description || 'Belum ada deskripsi untuk aplikasi ini.';

    const versionBadge = application.current_version
            ? `
                <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700" title="Versi aplikasi yang sedang digunakan">
                    v${escapeHtml(application.current_version.version_number)}
                </span>
            `
            : `
                <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-500">
                    Belum ada versi
                </span>
            `;

    const applicationUrl = application.slug ? `/applications/${encodeURIComponent(application.slug)}` : '#';

    return `
        <article class="group flex h-full flex-col overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200 transition duration-200 hover:-translate-y-1 hover:shadow-md">
            <div class="flex aspect-video items-center justify-center overflow-hidden bg-slate-50">
                <img src="${escapeAttribute(logoUrl)}" alt="${escapeAttribute(application.name)}" class="h-full w-full object-contain p-10 transition duration-300 group-hover:scale-105" loading="lazy" onerror="this.onerror=null;this.src='/images/Logo.png';">
            </div>
            <div class="flex flex-1 flex-col px-6 py-5">
                <div class="mb-3">
                    <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-800">
                        ${escapeHtml(category)}
                    </span>
                </div>
                <div class="mb-2 flex flex-wrap items-center gap-2">
                    <h2 class="text-xl font-bold text-slate-900">${escapeHtml(application.name)}</h2>
                    ${versionBadge}
                </div>
                <p class="flex-1 text-base leading-7 text-slate-600">${escapeHtml(description)}</p>
                <a href="${escapeAttribute(applicationUrl)}" class="mt-5 inline-flex items-center justify-center gap-2 rounded-xl bg-blue-900 px-4 py-3 text-sm font-semibold text-white no-underline transition hover:bg-blue-950">
                    Lihat Tutorial
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </article>
    `;
}

function renderPagination() {
    if (state.total === 0 || state.lastPage <= 1) {
        elements.paginationWrapper.classList.add('hidden');
        elements.pagination.innerHTML = '';
        return;
    }

    elements.paginationWrapper.classList.remove('hidden');
    elements.pageInfo.textContent = `Menampilkan ${state.from ?? 0}–${state.to ?? 0} dari ${state.total} data`;

    const buttons = [];

    buttons.push(createPaginationButton({
        label: '<i class="bi bi-chevron-left"></i>',
        page: state.currentPage - 1,
        disabled: state.currentPage === 1,
        title: 'Halaman sebelumnya',
    }));

    getVisiblePages().forEach((page) => {
        if (page === '...') {
            buttons.push(`<span class="flex h-10 min-w-10 items-center justify-center px-2 text-sm text-slate-400">...</span>`);
            return;
        }

        buttons.push(createPaginationButton({
            label: String(page),
            page,
            active: page === state.currentPage,
            title: `Halaman ${page}`,
        }));
    });

    buttons.push(createPaginationButton({
        label: '<i class="bi bi-chevron-right"></i>',
        page: state.currentPage + 1,
        disabled: state.currentPage === state.lastPage,
        title: 'Halaman berikutnya',
    }));

    elements.pagination.innerHTML = buttons.join('');
}

function createPaginationButton({ label, page, active = false, disabled = false, title = '' }) {
    const activeClass = active ? 'border-blue-900 bg-blue-900 text-white' : 'border-slate-300 bg-white text-slate-700 hover:bg-slate-50';
    const disabledClass = disabled ? 'cursor-not-allowed opacity-40' : '';

    return `
        <button type="button" data-page="${page}" title="${escapeAttribute(title)}" ${disabled ? 'disabled' : ''} class="pagination-button flex h-10 min-w-10 items-center justify-center rounded-xl border px-3 text-sm font-semibold transition ${activeClass} ${disabledClass}">
            ${label}
        </button>
    `;
}

function getVisiblePages() {
    const current = state.currentPage;
    const last = state.lastPage;

    if (last <= 7) return Array.from({ length: last }, (_, i) => i + 1);
    if (current <= 4) return [1, 2, 3, 4, 5, '...', last];
    if (current >= last - 3) return [1, '...', last - 4, last - 3, last - 2, last - 1, last];
    return [1, '...', current - 1, current, current + 1, '...', last];
}

function changePage(page) {
    if (!Number.isInteger(page) || page < 1 || page > state.lastPage || page === state.currentPage) return;
    fetchData(page);
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function normalizeSort(value) {
    const allowedSorts = ['latest', 'oldest', 'name_asc', 'name_desc'];
    return allowedSorts.includes(value) ? value : 'latest';
}

async function parseResponse(response) {
    const result = await response.json().catch(() => ({}));
    if (response.ok) return result;
    throw new Error(result.message || `Gagal mengambil data (${response.status}).`);
}

function showLoading() {
    elements.loading.classList.remove('hidden');
    elements.empty.classList.add('hidden');
    elements.error.classList.add('hidden');
    elements.paginationWrapper.classList.add('hidden');
    
    elements.appGrid.style.display = 'none';
    elements.materiGrid.style.display = 'none';
    
    elements.count.textContent = 'Memuat data...';
}

function hideStates() {
    elements.loading.classList.add('hidden');
    elements.empty.classList.add('hidden');
    elements.error.classList.add('hidden');
}

function showError(message) {
    elements.loading.classList.add('hidden');
    elements.empty.classList.add('hidden');
    elements.error.classList.remove('hidden');
    elements.paginationWrapper.classList.add('hidden');
    elements.appGrid.style.display = 'none';
    elements.materiGrid.style.display = 'none';
    
    elements.count.textContent = 'Data gagal dimuat';
    
    const errorMessage = elements.error.querySelector('[data-error-message]');
    if (errorMessage) errorMessage.textContent = message;
}

function escapeHtml(value) {
    return String(value ?? '').replaceAll('&', '&amp;').replaceAll('<', '&lt;').replaceAll('>', '&gt;').replaceAll('"', '&quot;').replaceAll("'", '&#039;');
}

function escapeAttribute(value) {
    return escapeHtml(value);
}

elements.search.addEventListener('input', (event) => {
    state.search = event.target.value.trim();
    window.clearTimeout(searchTimeout);
    searchTimeout = window.setTimeout(() => fetchData(1), 400);
});

elements.sort.addEventListener('change', (event) => {
    state.sort = normalizeSort(event.target.value);
    fetchData(1);
});

elements.pagination.addEventListener('click', (event) => {
    const button = event.target.closest('.pagination-button');
    if (!button || button.disabled) return;
    changePage(Number(button.dataset.page));
});

elements.retry.addEventListener('click', () => fetchData(state.currentPage));

fetchData();