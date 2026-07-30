import 'bootstrap-icons/font/bootstrap-icons.css';

const API_URL = '/api/applications';

const state = {
    applications: [],
    search: '',
    currentPage: 1,
    lastPage: 1,
    total: 0,
    from: null,
    to: null,
};

const elements = {
    search: document.getElementById('applicationSearch'),
    grid: document.getElementById('applicationGrid'),
    loading: document.getElementById('applicationLoading'),
    empty: document.getElementById('emptyResult'),
    error: document.getElementById('applicationError'),
    retry: document.getElementById('applicationRetry'),
    count: document.getElementById('applicationCount'),

    paginationWrapper: document.getElementById(
        'applicationPaginationWrapper'
    ),

    pagination: document.getElementById(
        'applicationPagination'
    ),

    pageInfo: document.getElementById(
        'applicationPageInfo'
    ),
};

let searchTimeout = null;

async function fetchApplications(page = 1) {
    showLoading();

    try {
        const parameters = new URLSearchParams({
            page: String(page),
        });

        if (state.search !== '') {
            parameters.set('search', state.search);
        }

        const response = await fetch(
            `${API_URL}?${parameters.toString()}`,
            {
                headers: {
                    Accept: 'application/json',
                },
            }
        );

        const result = await parseResponse(response);

        state.applications = Array.isArray(result.data)
            ? result.data
            : [];

        state.currentPage = Number(
            result.meta?.current_page ?? 1
        );

        state.lastPage = Number(
            result.meta?.last_page ?? 1
        );

        state.total = Number(
            result.meta?.total ?? 0
        );

        state.from = result.meta?.from ?? null;
        state.to = result.meta?.to ?? null;

        renderApplications();
        renderPagination();
    } catch (error) {
        showError(error.message);
    }
}

function renderApplications() {
    hideStates();

    elements.count.textContent =
        `${state.total} aplikasi ditemukan`;

    if (state.applications.length === 0) {
        elements.grid.innerHTML = '';
        elements.empty.classList.remove('hidden');
        elements.paginationWrapper.classList.add('hidden');

        return;
    }

    elements.grid.innerHTML = state.applications
        .map(createApplicationCard)
        .join('');
}

function createApplicationCard(application) {
    const logoUrl =
        application.logo_url || '/images/Logo.png';

    const category =
        application.category_name || 'Tanpa Kategori';

    const description =
        application.description ||
        'Belum ada deskripsi untuk aplikasi ini.';

    const versionBadge = application.current_version
        ? `
            <span
                class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700"
                title="Versi aplikasi yang sedang digunakan"
            >
                v${escapeHtml(
                    application.current_version.version_number
                )}
            </span>
        `
        : `
            <span
                class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-500"
            >
                Belum ada versi
            </span>
        `;

    const applicationUrl = application.slug
        ? `/applications/${encodeURIComponent(application.slug)}`
        : '#';

    return `
        <article
            class="group flex h-full flex-col overflow-hidden rounded-2xl bg-white shadow-lg ring-1 ring-slate-200 transition duration-200 hover:-translate-y-1 hover:shadow-xl"
        >
            <div class="flex aspect-video items-center justify-center overflow-hidden bg-slate-50">
                <img
                    src="${escapeAttribute(logoUrl)}"
                    alt="${escapeAttribute(application.name)}"
                    class="h-full w-full object-contain p-10 transition duration-300 group-hover:scale-105"
                    loading="lazy"
                    onerror="this.onerror=null;this.src='/images/Logo.png';"
                >
            </div>

            <div class="flex flex-1 flex-col px-6 py-5">
                <div class="mb-3">
                    <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-800">
                        ${escapeHtml(category)}
                    </span>
                </div>

                <div class="mb-2 flex flex-wrap items-center gap-2">
                    <h2 class="text-xl font-bold text-slate-900">
                        ${escapeHtml(application.name)}
                    </h2>

                    ${versionBadge}
                </div>

                <p class="flex-1 text-base leading-7 text-slate-600">
                    ${escapeHtml(description)}
                </p>

                <a
                    href="${escapeAttribute(applicationUrl)}"
                    class="mt-5 inline-flex items-center justify-center gap-2 rounded-xl bg-blue-900 px-4 py-3 text-sm font-semibold text-white no-underline transition hover:bg-blue-950"
                >
                    Lihat Tutorial

                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </article>
    `;
}

function renderPagination() {
    if (
        state.total === 0 ||
        state.lastPage <= 1
    ) {
        elements.paginationWrapper.classList.add('hidden');
        elements.pagination.innerHTML = '';

        return;
    }

    elements.paginationWrapper.classList.remove('hidden');

    elements.pageInfo.textContent =
        `Menampilkan ${state.from ?? 0}–${state.to ?? 0} dari ${state.total} aplikasi`;

    const buttons = [];

    buttons.push(
        createPaginationButton({
            label: '<i class="bi bi-chevron-left"></i>',
            page: state.currentPage - 1,
            disabled: state.currentPage === 1,
            title: 'Halaman sebelumnya',
        })
    );

    getVisiblePages().forEach((page) => {
        if (page === '...') {
            buttons.push(`
                <span
                    class="flex h-10 min-w-10 items-center justify-center px-2 text-sm text-slate-400"
                >
                    ...
                </span>
            `);

            return;
        }

        buttons.push(
            createPaginationButton({
                label: String(page),
                page,
                active: page === state.currentPage,
                title: `Halaman ${page}`,
            })
        );
    });

    buttons.push(
        createPaginationButton({
            label: '<i class="bi bi-chevron-right"></i>',
            page: state.currentPage + 1,
            disabled:
                state.currentPage === state.lastPage,
            title: 'Halaman berikutnya',
        })
    );

    elements.pagination.innerHTML = buttons.join('');
}

function createPaginationButton({
    label,
    page,
    active = false,
    disabled = false,
    title = '',
}) {
    const activeClass = active
        ? 'border-blue-900 bg-blue-900 text-white'
        : 'border-slate-300 bg-white text-slate-700 hover:bg-slate-50';

    const disabledClass = disabled
        ? 'cursor-not-allowed opacity-40'
        : '';

    return `
        <button
            type="button"
            data-page="${page}"
            title="${escapeAttribute(title)}"
            ${disabled ? 'disabled' : ''}
            class="pagination-button flex h-10 min-w-10 items-center justify-center rounded-xl border px-3 text-sm font-semibold transition ${activeClass} ${disabledClass}"
        >
            ${label}
        </button>
    `;
}

function getVisiblePages() {
    const current = state.currentPage;
    const last = state.lastPage;

    if (last <= 7) {
        return Array.from(
            { length: last },
            (_, index) => index + 1
        );
    }

    if (current <= 4) {
        return [
            1,
            2,
            3,
            4,
            5,
            '...',
            last,
        ];
    }

    if (current >= last - 3) {
        return [
            1,
            '...',
            last - 4,
            last - 3,
            last - 2,
            last - 1,
            last,
        ];
    }

    return [
        1,
        '...',
        current - 1,
        current,
        current + 1,
        '...',
        last,
    ];
}

function changePage(page) {
    if (
        !Number.isInteger(page) ||
        page < 1 ||
        page > state.lastPage ||
        page === state.currentPage
    ) {
        return;
    }

    fetchApplications(page);

    window.scrollTo({
        top: 0,
        behavior: 'smooth',
    });
}

async function parseResponse(response) {
    const result = await response.json().catch(() => ({}));

    if (response.ok) {
        return result;
    }

    throw new Error(
        result.message ||
        `Gagal mengambil data aplikasi (${response.status}).`
    );
}

function showLoading() {
    elements.loading.classList.remove('hidden');
    elements.empty.classList.add('hidden');
    elements.error.classList.add('hidden');
    elements.paginationWrapper.classList.add('hidden');

    elements.grid.innerHTML = '';
    elements.count.textContent = 'Memuat aplikasi...';
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

    elements.grid.innerHTML = '';
    elements.count.textContent = 'Data gagal dimuat';

    const errorMessage = elements.error.querySelector(
        '[data-error-message]'
    );

    if (errorMessage) {
        errorMessage.textContent = message;
    }
}

function escapeHtml(value) {
    return String(value ?? '')
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
}

function escapeAttribute(value) {
    return escapeHtml(value);
}

elements.search.addEventListener(
    'input',
    (event) => {
        state.search = event.target.value.trim();

        window.clearTimeout(searchTimeout);

        searchTimeout = window.setTimeout(() => {
            fetchApplications(1);
        }, 400);
    }
);

elements.pagination.addEventListener(
    'click',
    (event) => {
        const button = event.target.closest(
            '.pagination-button'
        );

        if (!button || button.disabled) {
            return;
        }

        const page = Number(button.dataset.page);

        changePage(page);
    }
);

elements.retry.addEventListener(
    'click',
    () => {
        fetchApplications(state.currentPage);
    }
);

fetchApplications();   