import 'bootstrap-icons/font/bootstrap-icons.css';

const API_BASE_URL = '/api/admin';
const DEFAULT_LOGO_URL = '/images/Logo.png';

const state = {
    applications: [],
    search: '',
    currentPage: 1,
    lastPage: 1,
    total: 0,
    from: null,
    to: null,
    activeApplicationId: null,
};

const elements = {
    notification: document.getElementById('notification'),

    // Statistics
    statTotalApplications: document.getElementById(
        'stat-total-applications'
    ),
    statActiveApplications: document.getElementById(
        'stat-active-applications'
    ),
    statPublicApplications: document.getElementById(
        'stat-public-applications'
    ),
    statTotalVersions: document.getElementById(
        'stat-total-versions'
    ),

    // Application table
    applicationSearch: document.getElementById(
        'application-search'
    ),
    applicationCount: document.getElementById(
        'application-count'
    ),
    applicationTableWrapper: document.getElementById(
        'application-table-wrapper'
    ),
    applicationTableBody: document.getElementById(
        'application-table-body'
    ),
    applicationLoading: document.getElementById(
        'application-loading'
    ),
    applicationEmpty: document.getElementById(
        'application-empty'
    ),
    applicationError: document.getElementById(
        'application-error'
    ),
    applicationErrorMessage: document.getElementById(
        'application-error-message'
    ),
    applicationRetryButton: document.getElementById(
        'application-retry-button'
    ),

    // Pagination
    paginationWrapper: document.getElementById(
        'application-pagination-wrapper'
    ),
    pagination: document.getElementById(
        'application-pagination'
    ),
    pageInfo: document.getElementById(
        'application-page-info'
    ),

    // Application modal
    applicationFormModal: document.getElementById(
        'application-form-modal'
    ),
    openApplicationFormButton: document.getElementById(
        'open-application-form'
    ),
    applicationFormModalClose: document.getElementById(
        'application-form-modal-close'
    ),

    // Application form
    applicationForm: document.getElementById(
        'application-form'
    ),
    applicationFormTitle: document.getElementById(
        'application-form-title'
    ),
    applicationId: document.getElementById(
        'application-id'
    ),
    applicationName: document.getElementById(
        'application-name'
    ),
    applicationSlug: document.getElementById(
        'application-slug'
    ),
    applicationDescription: document.getElementById(
        'application-description'
    ),
    applicationCategory: document.getElementById(
        'application-category'
    ),
    applicationStatus: document.getElementById(
        'application-status'
    ),
    applicationIsPublic: document.getElementById(
        'application-is-public'
    ),
    applicationLogo: document.getElementById(
        'application-logo'
    ),
    applicationLogoPreview: document.getElementById(
        'application-logo-preview'
    ),
    applicationLogoPreviewWrapper: document.getElementById(
        'application-logo-preview-wrapper'
    ),
    applicationRemoveLogo: document.getElementById(
        'application-remove-logo'
    ),
    applicationSubmitButton: document.getElementById(
        'application-submit-button'
    ),
    applicationCancelButton: document.getElementById(
        'application-cancel-button'
    ),

    // Version modal
    versionModal: document.getElementById(
        'version-modal'
    ),
    versionModalClose: document.getElementById(
        'version-modal-close'
    ),
    versionModalApplicationName: document.getElementById(
        'version-modal-application-name'
    ),

    // Version form
    versionForm: document.getElementById(
        'version-form'
    ),
    versionFormTitle: document.getElementById(
        'version-form-title'
    ),
    versionId: document.getElementById(
        'version-id'
    ),
    versionApplicationId: document.getElementById(
        'version-application-id'
    ),
    versionNumber: document.getElementById(
        'version-number'
    ),
    versionReleaseDate: document.getElementById(
        'version-release-date'
    ),
    versionStatus: document.getElementById(
        'version-status'
    ),
    versionReleaseNotes: document.getElementById(
        'version-release-notes'
    ),
    versionIsCurrent: document.getElementById(
        'version-is-current'
    ),
    versionSubmitButton: document.getElementById(
        'version-submit-button'
    ),
    versionCancelButton: document.getElementById(
        'version-cancel-button'
    ),
    versionList: document.getElementById(
        'version-list'
    ),
    versionEmpty: document.getElementById(
        'version-empty'
    ),
};

let searchTimeout = null;
let notificationTimeout = null;

/**
 * Retrieve paginated applications from the admin API.
 */
async function fetchApplications(page = 1) {
    showApplicationLoading();

    try {
        const parameters = new URLSearchParams({
            page: String(page),
        });

        if (state.search !== '') {
            parameters.set('search', state.search);
        }

        const response = await fetch(
            `${API_BASE_URL}/applications-with-versions?${parameters.toString()}`,
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
            result.meta?.total ?? state.applications.length
        );

        state.from = result.meta?.from ?? null;
        state.to = result.meta?.to ?? null;

        renderApplications();
        renderPagination();
        renderStatistics(result.summary);

        refreshOpenedVersionModal();
    } catch (error) {
        showApplicationError(error.message);
    }
}

/**
 * Render application rows in the admin table.
 */
function renderApplications() {
    hideApplicationStates();

    elements.applicationCount.textContent =
        `${state.total} aplikasi ditemukan`;

    if (state.applications.length === 0) {
        elements.applicationTableBody.innerHTML = '';
        elements.applicationTableWrapper.classList.add(
            'hidden'
        );
        elements.applicationEmpty.classList.remove(
            'hidden'
        );
        elements.paginationWrapper.classList.add(
            'hidden'
        );

        return;
    }

    elements.applicationTableBody.innerHTML =
        state.applications
            .map(createApplicationRow)
            .join('');

    elements.applicationTableWrapper.classList.remove(
        'hidden'
    );
}

/**
 * Create one row for the application table.
 */
function createApplicationRow(application) {
    const logoUrl =
        application.logo_url || DEFAULT_LOGO_URL;

    const category =
        application.category_name || 'Tanpa Kategori';

    const description =
        application.description ||
        'Belum ada deskripsi aplikasi.';

    const currentVersion =
        application.current_version?.version_number || null;

    const versionsCount = Array.isArray(application.versions)
        ? application.versions.length
        : 0;

    const visibilityLabel = application.is_public
        ? 'Publik'
        : 'Privat';

    const visibilityClass = application.is_public
        ? 'border-emerald-200 bg-emerald-50 text-emerald-700'
        : 'border-slate-200 bg-slate-100 text-slate-600';

    const currentVersionLabel = currentVersion
        ? `v${escapeHtml(currentVersion)}`
        : 'Belum ada';

    return `
        <tr class="transition hover:bg-slate-50">
            <td class="whitespace-nowrap px-5 py-4 align-middle">
                <div class="flex h-12 w-12 items-center justify-center border border-slate-200 bg-white">
                    <img
                        src="${escapeAttribute(logoUrl)}"
                        alt="${escapeAttribute(application.name)}"
                        class="h-full w-full object-contain p-1.5"
                        loading="lazy"
                        onerror="this.onerror=null;this.src='${DEFAULT_LOGO_URL}';"
                    >
                </div>
            </td>

            <td class="px-5 py-4 align-middle">
                <div class="max-w-sm">
                    <p class="font-semibold text-slate-950">
                        ${escapeHtml(application.name)}
                    </p>

                    <p class="mt-1 line-clamp-2 text-xs leading-5 text-slate-500">
                        ${escapeHtml(description)}
                    </p>

                    ${
                        application.slug
                            ? `
                                <p class="mt-1 text-xs text-slate-400">
                                    /${escapeHtml(application.slug)}
                                </p>
                            `
                            : ''
                    }
                </div>
            </td>

            <td class="whitespace-nowrap px-5 py-4 align-middle">
                <span class="text-sm text-slate-700">
                    ${escapeHtml(category)}
                </span>
            </td>

            <td class="whitespace-nowrap px-5 py-4 align-middle">
                <span class="inline-flex border px-2.5 py-1 text-xs font-semibold ${getApplicationStatusClass(application.status)}">
                    ${escapeHtml(
                        getApplicationStatusLabel(
                            application.status
                        )
                    )}
                </span>
            </td>

            <td class="whitespace-nowrap px-5 py-4 align-middle">
                <span class="inline-flex border px-2.5 py-1 text-xs font-semibold ${visibilityClass}">
                    ${visibilityLabel}
                </span>
            </td>

            <td class="whitespace-nowrap px-5 py-4 align-middle">
                <div>
                    <p class="text-sm font-semibold text-slate-800">
                        ${currentVersionLabel}
                    </p>

                    <p class="mt-1 text-xs text-slate-500">
                        ${versionsCount} versi
                    </p>
                </div>
            </td>

            <td class="whitespace-nowrap px-5 py-4 text-right align-middle">
                <div class="inline-flex items-center justify-end gap-2">
                    <button
                        type="button"
                        class="application-edit-button flex h-9 w-9 items-center justify-center border border-blue-200 text-blue-800 transition hover:bg-blue-50"
                        data-id="${application.id}"
                        title="Ubah aplikasi"
                        aria-label="Ubah aplikasi ${escapeAttribute(application.name)}"
                    >
                        <i class="bi bi-pencil-square"></i>
                    </button>

                    <button
                        type="button"
                        class="application-version-button flex h-9 w-9 items-center justify-center border border-violet-200 text-violet-700 transition hover:bg-violet-50"
                        data-id="${application.id}"
                        title="Kelola versi"
                        aria-label="Kelola versi ${escapeAttribute(application.name)}"
                    >
                        <i class="bi bi-tags"></i>
                    </button>

                    <button
                        type="button"
                        class="application-delete-button flex h-9 w-9 items-center justify-center border border-red-200 text-red-600 transition hover:bg-red-50"
                        data-id="${application.id}"
                        title="Hapus aplikasi"
                        aria-label="Hapus aplikasi ${escapeAttribute(application.name)}"
                    >
                        <i class="bi bi-trash3"></i>
                    </button>
                </div>
            </td>
        </tr>
    `;
}

/**
 * Render the statistics cards.
 *
 * For accurate statistics across all pages, the API should return:
 *
 * summary: {
 *     total_applications: 20,
 *     active_applications: 15,
 *     public_applications: 12,
 *     total_versions: 36
 * }
 *
 * Without summary, total applications uses pagination total, while the
 * other values are calculated from the currently loaded page.
 */
function renderStatistics(summary = null) {
    const pageActiveApplications =
        state.applications.filter(
            (application) =>
                application.status === 'active'
        ).length;

    const pagePublicApplications =
        state.applications.filter(
            (application) =>
                Boolean(application.is_public)
        ).length;

    const pageTotalVersions =
        state.applications.reduce(
            (total, application) => {
                const versions = Array.isArray(
                    application.versions
                )
                    ? application.versions.length
                    : 0;

                return total + versions;
            },
            0
        );

    elements.statTotalApplications.textContent =
        Number(
            summary?.total_applications ??
            state.total
        );

    elements.statActiveApplications.textContent =
        Number(
            summary?.active_applications ??
            pageActiveApplications
        );

    elements.statPublicApplications.textContent =
        Number(
            summary?.public_applications ??
            pagePublicApplications
        );

    elements.statTotalVersions.textContent =
        Number(
            summary?.total_versions ??
            pageTotalVersions
        );
}

/**
 * Render server-side pagination.
 */
function renderPagination() {
    if (
        state.total === 0 ||
        state.lastPage <= 1
    ) {
        elements.paginationWrapper.classList.add(
            'hidden'
        );
        elements.pagination.innerHTML = '';

        return;
    }

    elements.paginationWrapper.classList.remove(
        'hidden'
    );

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
                <span class="flex h-10 min-w-10 items-center justify-center px-2 text-sm text-slate-400">
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
    const stateClass = active
        ? 'border-blue-950 bg-blue-950 text-white'
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
            class="pagination-button flex h-10 min-w-10 items-center justify-center border px-3 text-sm font-semibold transition ${stateClass} ${disabledClass}"
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

    elements.applicationTableWrapper
        ?.closest('section')
        ?.scrollIntoView({
            behavior: 'smooth',
            block: 'start',
        });
}

/**
 * Open application form for a new application.
 */
function openCreateApplicationModal() {
    resetApplicationForm();
    openApplicationModal();
}

/**
 * Open application form for editing.
 */
function startApplicationEdit(applicationId) {
    const application = findApplication(applicationId);

    if (!application) {
        showNotification(
            'Data aplikasi tidak ditemukan.',
            'error'
        );

        return;
    }

    resetApplicationForm();

    elements.applicationId.value = application.id;
    elements.applicationName.value =
        application.name || '';
    elements.applicationSlug.value =
        application.slug || '';
    elements.applicationDescription.value =
        application.description || '';
    elements.applicationCategory.value =
        application.category_name || '';
    elements.applicationStatus.value =
        application.status || 'active';
    elements.applicationIsPublic.checked =
        Boolean(application.is_public);

    elements.applicationLogo.value = '';
    elements.applicationRemoveLogo.checked = false;

    showExistingImage(
        elements.applicationLogoPreviewWrapper,
        elements.applicationLogoPreview,
        application.logo_url
    );

    elements.applicationFormTitle.textContent =
        'Ubah Aplikasi';

    setButtonContent(
        elements.applicationSubmitButton,
        'bi-pencil-square',
        'Perbarui Aplikasi'
    );

    openApplicationModal();
}

function openApplicationModal() {
    elements.applicationFormModal.classList.remove(
        'hidden'
    );
    elements.applicationFormModal.classList.add(
        'flex'
    );
    elements.applicationFormModal.setAttribute(
        'aria-hidden',
        'false'
    );

    lockBodyScroll();

    window.setTimeout(() => {
        elements.applicationName.focus();
    }, 50);
}

function closeApplicationModal() {
    elements.applicationFormModal.classList.add(
        'hidden'
    );
    elements.applicationFormModal.classList.remove(
        'flex'
    );
    elements.applicationFormModal.setAttribute(
        'aria-hidden',
        'true'
    );

    resetApplicationForm();
    unlockBodyScroll();
}

function resetApplicationForm() {
    elements.applicationForm.reset();

    elements.applicationId.value = '';
    elements.applicationStatus.value = 'active';
    elements.applicationLogo.value = '';
    elements.applicationRemoveLogo.checked = false;
    elements.applicationLogoPreview.src = '';

    elements.applicationLogoPreviewWrapper.classList.add(
        'hidden'
    );

    elements.applicationFormTitle.textContent =
        'Tambah Aplikasi';

    setButtonContent(
        elements.applicationSubmitButton,
        'bi-plus-lg',
        'Simpan Aplikasi'
    );

    setApplicationButtonLoading(false);
}

/**
 * Create or update an application.
 */
async function submitApplication(event) {
    event.preventDefault();

    const applicationId =
        elements.applicationId.value;

    const isEditing = applicationId !== '';

    const formData = new FormData();

    formData.append(
        'name',
        elements.applicationName.value.trim()
    );

    formData.append(
        'description',
        elements.applicationDescription.value.trim()
    );

    formData.append(
        'category_name',
        elements.applicationCategory.value.trim()
    );

    formData.append(
        'status',
        elements.applicationStatus.value
    );

    formData.append(
        'is_public',
        elements.applicationIsPublic.checked
            ? '1'
            : '0'
    );

    const slug =
        elements.applicationSlug.value.trim();

    if (slug !== '') {
        formData.append('slug', slug);
    }

    const logo =
        elements.applicationLogo.files[0];

    if (logo) {
        formData.append('logo', logo);
    }

    if (elements.applicationRemoveLogo.checked) {
        formData.append('remove_logo', '1');
    }

    let url = `${API_BASE_URL}/applications`;

    if (isEditing) {
        url =
            `${API_BASE_URL}/applications/${applicationId}`;

        formData.append('_method', 'PUT');
    }

    setApplicationButtonLoading(true);

    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                Accept: 'application/json',
            },
            body: formData,
        });

        const result = await parseResponse(response);

        closeApplicationModal();

        await fetchApplications(
            isEditing ? state.currentPage : 1
        );

        showNotification(
            result.message ||
            (
                isEditing
                    ? 'Aplikasi berhasil diperbarui.'
                    : 'Aplikasi berhasil ditambahkan.'
            ),
            'success'
        );
    } catch (error) {
        showNotification(error.message, 'error');
    } finally {
        setApplicationButtonLoading(false);
    }
}

/**
 * Delete an application.
 */
async function deleteApplication(applicationId) {
    const application = findApplication(applicationId);

    if (!application) {
        showNotification(
            'Data aplikasi tidak ditemukan.',
            'error'
        );

        return;
    }

    const confirmed = window.confirm(
        `Apakah Anda yakin ingin menghapus aplikasi "${application.name}"?`
    );

    if (!confirmed) {
        return;
    }

    try {
        const response = await fetch(
            `${API_BASE_URL}/applications/${applicationId}`,
            {
                method: 'DELETE',
                headers: {
                    Accept: 'application/json',
                },
            }
        );

        const result = await parseResponse(response);

        const shouldMoveToPreviousPage =
            state.applications.length === 1 &&
            state.currentPage > 1;

        const targetPage = shouldMoveToPreviousPage
            ? state.currentPage - 1
            : state.currentPage;

        await fetchApplications(targetPage);

        showNotification(
            result.message ||
            'Aplikasi berhasil dihapus.',
            'success'
        );
    } catch (error) {
        showNotification(error.message, 'error');
    }
}

/**
 * Open the version management modal.
 */
function openVersionModal(applicationId) {
    const application = findApplication(applicationId);

    if (!application) {
        showNotification(
            'Data aplikasi tidak ditemukan.',
            'error'
        );

        return;
    }

    state.activeApplicationId = Number(applicationId);

    resetVersionForm();

    elements.versionApplicationId.value =
        application.id;

    renderVersionModal(application.id);

    elements.versionModal.classList.remove('hidden');
    elements.versionModal.classList.add('flex');
    elements.versionModal.setAttribute(
        'aria-hidden',
        'false'
    );

    lockBodyScroll();
}

function closeVersionModal() {
    state.activeApplicationId = null;

    elements.versionModal.classList.add('hidden');
    elements.versionModal.classList.remove('flex');
    elements.versionModal.setAttribute(
        'aria-hidden',
        'true'
    );

    resetVersionForm();
    unlockBodyScroll();
}

function refreshOpenedVersionModal() {
    if (state.activeApplicationId === null) {
        return;
    }

    const application = findApplication(
        state.activeApplicationId
    );

    if (!application) {
        closeVersionModal();
        return;
    }

    renderVersionModal(application.id);
}

function renderVersionModal(applicationId) {
    const application = findApplication(applicationId);

    if (!application) {
        return;
    }

    elements.versionModalApplicationName.textContent =
        application.name;

    elements.versionApplicationId.value =
        application.id;

    const versions = Array.isArray(application.versions)
        ? application.versions
        : [];

    if (versions.length === 0) {
        elements.versionList.innerHTML = '';
        elements.versionEmpty.classList.remove(
            'hidden'
        );

        return;
    }

    elements.versionEmpty.classList.add('hidden');

    elements.versionList.innerHTML = versions
        .map(createVersionItem)
        .join('');
}

function createVersionItem(version) {
    const currentBadge = version.is_current
        ? `
            <span class="border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                Versi Saat Ini
            </span>
        `
        : '';

    return `
        <article class="border border-slate-200 bg-white p-4">
            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <div class="flex flex-wrap items-center gap-2">
                        <h4 class="font-bold text-slate-950">
                            v${escapeHtml(version.version_number)}
                        </h4>

                        ${currentBadge}

                        <span class="border px-2.5 py-1 text-xs font-semibold ${getVersionStatusClass(version.status)}">
                            ${escapeHtml(
                                getVersionStatusLabel(
                                    version.status
                                )
                            )}
                        </span>
                    </div>

                    <p class="mt-2 text-xs text-slate-500">
                        ${escapeHtml(
                            formatDate(version.release_date)
                        )}
                    </p>

                    <p class="mt-3 whitespace-pre-line text-sm leading-6 text-slate-600">
                        ${escapeHtml(
                            version.release_notes ||
                            'Belum ada catatan rilis.'
                        )}
                    </p>
                </div>

                <div class="flex shrink-0 gap-2">
                    <button
                        type="button"
                        class="version-edit-button flex h-9 w-9 items-center justify-center border border-blue-200 text-blue-800 transition hover:bg-blue-50"
                        data-id="${version.id}"
                        title="Ubah versi"
                    >
                        <i class="bi bi-pencil-square"></i>
                    </button>

                    <button
                        type="button"
                        class="version-delete-button flex h-9 w-9 items-center justify-center border border-red-200 text-red-600 transition hover:bg-red-50"
                        data-id="${version.id}"
                        title="Hapus versi"
                    >
                        <i class="bi bi-trash3"></i>
                    </button>
                </div>
            </div>
        </article>
    `;
}

/**
 * Create or update an application version.
 */
async function submitVersion(event) {
    event.preventDefault();

    const applicationId =
        elements.versionApplicationId.value ||
        state.activeApplicationId;

    const versionId =
        elements.versionId.value;

    const isEditing = versionId !== '';

    const payload = {
        version_number:
            elements.versionNumber.value.trim(),

        release_date:
            elements.versionReleaseDate.value || null,

        release_notes:
            elements.versionReleaseNotes.value.trim() ||
            null,

        status:
            elements.versionStatus.value,

        is_current:
            elements.versionIsCurrent.checked,
    };

    const url = isEditing
        ? `${API_BASE_URL}/application-versions/${versionId}`
        : `${API_BASE_URL}/applications/${applicationId}/versions`;

    setVersionButtonLoading(true);

    try {
        const response = await fetch(url, {
            method: isEditing ? 'PUT' : 'POST',
            headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(payload),
        });

        const result = await parseResponse(response);

        resetVersionForm();

        await fetchApplications(state.currentPage);

        showNotification(
            result.message ||
            (
                isEditing
                    ? 'Versi berhasil diperbarui.'
                    : 'Versi berhasil ditambahkan.'
            ),
            'success'
        );
    } catch (error) {
        showNotification(error.message, 'error');
    } finally {
        setVersionButtonLoading(false);
    }
}

function startVersionEdit(versionId) {
    const application = findApplication(
        state.activeApplicationId
    );

    const version = application?.versions?.find(
        (item) =>
            Number(item.id) === Number(versionId)
    );

    if (!version) {
        showNotification(
            'Data versi tidak ditemukan.',
            'error'
        );

        return;
    }

    elements.versionId.value = version.id;

    elements.versionApplicationId.value =
        application.id;

    elements.versionNumber.value =
        version.version_number || '';

    elements.versionReleaseDate.value =
        version.release_date
            ? String(version.release_date).slice(0, 10)
            : '';

    elements.versionStatus.value =
        version.status || 'draft';

    elements.versionReleaseNotes.value =
        version.release_notes || '';

    elements.versionIsCurrent.checked =
        Boolean(version.is_current);

    elements.versionFormTitle.textContent =
        'Ubah Versi';

    elements.versionCancelButton.classList.remove(
        'hidden'
    );

    elements.versionCancelButton.classList.add(
        'inline-flex'
    );

    setButtonContent(
        elements.versionSubmitButton,
        'bi-pencil-square',
        'Perbarui Versi'
    );

    elements.versionNumber.focus();
}

function resetVersionForm() {
    elements.versionForm.reset();

    elements.versionId.value = '';

    elements.versionApplicationId.value =
        state.activeApplicationId ?? '';

    elements.versionStatus.value = 'draft';

    elements.versionFormTitle.textContent =
        'Tambah Versi';

    elements.versionCancelButton.classList.add(
        'hidden'
    );

    elements.versionCancelButton.classList.remove(
        'inline-flex'
    );

    setButtonContent(
        elements.versionSubmitButton,
        'bi-plus-lg',
        'Simpan Versi'
    );

    setVersionButtonLoading(false);
}

async function deleteVersion(versionId) {
    const confirmed = window.confirm(
        'Apakah Anda yakin ingin menghapus versi ini?'
    );

    if (!confirmed) {
        return;
    }

    try {
        const response = await fetch(
            `${API_BASE_URL}/application-versions/${versionId}`,
            {
                method: 'DELETE',
                headers: {
                    Accept: 'application/json',
                },
            }
        );

        const result = await parseResponse(response);

        await fetchApplications(state.currentPage);

        showNotification(
            result.message ||
            'Versi berhasil dihapus.',
            'success'
        );
    } catch (error) {
        showNotification(error.message, 'error');
    }
}

/**
 * UI state helpers.
 */
function showApplicationLoading() {
    elements.applicationLoading.classList.remove(
        'hidden'
    );

    elements.applicationEmpty.classList.add(
        'hidden'
    );

    elements.applicationError.classList.add(
        'hidden'
    );

    elements.applicationTableWrapper.classList.add(
        'hidden'
    );

    elements.paginationWrapper.classList.add(
        'hidden'
    );
}

function hideApplicationStates() {
    elements.applicationLoading.classList.add(
        'hidden'
    );

    elements.applicationEmpty.classList.add(
        'hidden'
    );

    elements.applicationError.classList.add(
        'hidden'
    );
}

function showApplicationError(message) {
    elements.applicationLoading.classList.add(
        'hidden'
    );

    elements.applicationTableWrapper.classList.add(
        'hidden'
    );

    elements.applicationEmpty.classList.add(
        'hidden'
    );

    elements.applicationError.classList.remove(
        'hidden'
    );

    elements.paginationWrapper.classList.add(
        'hidden'
    );

    elements.applicationErrorMessage.textContent =
        message;

    elements.applicationCount.textContent =
        'Data gagal dimuat';
}

function setApplicationButtonLoading(isLoading) {
    elements.applicationSubmitButton.disabled =
        isLoading;

    if (isLoading) {
        setButtonContent(
            elements.applicationSubmitButton,
            'bi-arrow-repeat animate-spin',
            'Menyimpan...'
        );
    }
}

function setVersionButtonLoading(isLoading) {
    elements.versionSubmitButton.disabled =
        isLoading;

    if (isLoading) {
        setButtonContent(
            elements.versionSubmitButton,
            'bi-arrow-repeat animate-spin',
            'Menyimpan...'
        );
    }
}

function lockBodyScroll() {
    document.body.classList.add('overflow-hidden');
}

function unlockBodyScroll() {
    const applicationModalOpen =
        !elements.applicationFormModal.classList.contains(
            'hidden'
        );

    const versionModalOpen =
        !elements.versionModal.classList.contains(
            'hidden'
        );

    if (!applicationModalOpen && !versionModalOpen) {
        document.body.classList.remove(
            'overflow-hidden'
        );
    }
}

/**
 * Request and utility helpers.
 */
async function parseResponse(response) {
    const result = await response
        .json()
        .catch(() => ({}));

    if (response.ok) {
        return result;
    }

    if (result.errors) {
        throw new Error(
            Object.values(result.errors)
                .flat()
                .join(' ')
        );
    }

    throw new Error(
        result.message ||
        `Permintaan gagal (${response.status}).`
    );
}

function findApplication(applicationId) {
    return state.applications.find(
        (application) =>
            Number(application.id) ===
            Number(applicationId)
    );
}

function previewSelectedImage(
    input,
    wrapper,
    preview
) {
    const file = input.files[0];

    if (!file) {
        return;
    }

    const temporaryUrl =
        URL.createObjectURL(file);

    preview.src = temporaryUrl;
    wrapper.classList.remove('hidden');

    preview.onload = () => {
        URL.revokeObjectURL(temporaryUrl);
    };
}

function showExistingImage(
    wrapper,
    preview,
    url
) {
    if (!url) {
        preview.src = '';
        wrapper.classList.add('hidden');

        return;
    }

    preview.src = url;
    wrapper.classList.remove('hidden');
}

function getApplicationStatusClass(status) {
    return {
        active:
            'border-emerald-200 bg-emerald-50 text-emerald-700',

        inactive:
            'border-amber-200 bg-amber-50 text-amber-700',

        archived:
            'border-slate-300 bg-slate-100 text-slate-600',
    }[status] ||
        'border-slate-200 bg-slate-100 text-slate-600';
}

function getApplicationStatusLabel(status) {
    return {
        active: 'Aktif',
        inactive: 'Tidak Aktif',
        archived: 'Diarsipkan',
    }[status] || status || 'Tidak diketahui';
}

function getVersionStatusClass(status) {
    return {
        draft:
            'border-slate-200 bg-slate-100 text-slate-600',

        beta:
            'border-amber-200 bg-amber-50 text-amber-700',

        stable:
            'border-emerald-200 bg-emerald-50 text-emerald-700',

        deprecated:
            'border-red-200 bg-red-50 text-red-600',
    }[status] ||
        'border-slate-200 bg-slate-100 text-slate-600';
}

function getVersionStatusLabel(status) {
    return {
        draft: 'Draf',
        beta: 'Beta',
        stable: 'Stabil',
        deprecated: 'Tidak Digunakan',
    }[status] || status || 'Tidak diketahui';
}

function setButtonContent(
    button,
    iconClass,
    label
) {
    button.innerHTML = `
        <i class="bi ${iconClass}"></i>
        <span>${escapeHtml(label)}</span>
    `;
}

function showNotification(
    message,
    type = 'success'
) {
    const styles = {
        success:
            'border-emerald-200 bg-emerald-50 text-emerald-700',

        error:
            'border-red-200 bg-red-50 text-red-700',
    };

    window.clearTimeout(notificationTimeout);

    elements.notification.className =
        `border px-4 py-3 text-sm ${styles[type]}`;

    elements.notification.textContent = message;
    elements.notification.classList.remove('hidden');

    notificationTimeout = window.setTimeout(() => {
        elements.notification.classList.add('hidden');
    }, 5000);
}

function formatDate(value) {
    if (!value) {
        return 'Tanggal belum ditentukan';
    }

    const date = new Date(value);

    if (Number.isNaN(date.getTime())) {
        return 'Tanggal tidak valid';
    }

    return new Intl.DateTimeFormat('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    }).format(date);
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

/**
 * Event listeners.
 */
elements.openApplicationFormButton?.addEventListener(
    'click',
    openCreateApplicationModal
);

elements.applicationFormModalClose?.addEventListener(
    'click',
    closeApplicationModal
);

elements.applicationCancelButton?.addEventListener(
    'click',
    closeApplicationModal
);

elements.applicationForm?.addEventListener(
    'submit',
    submitApplication
);

elements.applicationSearch?.addEventListener(
    'input',
    (event) => {
        state.search =
            event.target.value.trim();

        window.clearTimeout(searchTimeout);

        searchTimeout = window.setTimeout(() => {
            fetchApplications(1);
        }, 400);
    }
);

elements.pagination?.addEventListener(
    'click',
    (event) => {
        const button = event.target.closest(
            '.pagination-button'
        );

        if (!button || button.disabled) {
            return;
        }

        changePage(
            Number(button.dataset.page)
        );
    }
);

elements.applicationRetryButton?.addEventListener(
    'click',
    () => fetchApplications(state.currentPage)
);

elements.applicationTableBody?.addEventListener(
    'click',
    (event) => {
        const editButton = event.target.closest(
            '.application-edit-button'
        );

        const versionButton = event.target.closest(
            '.application-version-button'
        );

        const deleteButton = event.target.closest(
            '.application-delete-button'
        );

        if (editButton) {
            startApplicationEdit(
                editButton.dataset.id
            );

            return;
        }

        if (versionButton) {
            openVersionModal(
                versionButton.dataset.id
            );

            return;
        }

        if (deleteButton) {
            deleteApplication(
                deleteButton.dataset.id
            );
        }
    }
);

elements.applicationLogo?.addEventListener(
    'change',
    () => {
        previewSelectedImage(
            elements.applicationLogo,
            elements.applicationLogoPreviewWrapper,
            elements.applicationLogoPreview
        );
    }
);

elements.versionForm?.addEventListener(
    'submit',
    submitVersion
);

elements.versionCancelButton?.addEventListener(
    'click',
    resetVersionForm
);

elements.versionModalClose?.addEventListener(
    'click',
    closeVersionModal
);

elements.versionList?.addEventListener(
    'click',
    (event) => {
        const editButton = event.target.closest(
            '.version-edit-button'
        );

        const deleteButton = event.target.closest(
            '.version-delete-button'
        );

        if (editButton) {
            startVersionEdit(
                editButton.dataset.id
            );

            return;
        }

        if (deleteButton) {
            deleteVersion(
                deleteButton.dataset.id
            );
        }
    }
);

// Close modals by clicking their dark backdrop.
elements.applicationFormModal?.addEventListener(
    'click',
    (event) => {
        if (
            event.target ===
            elements.applicationFormModal
        ) {
            closeApplicationModal();
        }
    }
);

elements.versionModal?.addEventListener(
    'click',
    (event) => {
        if (
            event.target ===
            elements.versionModal
        ) {
            closeVersionModal();
        }
    }
);

// Close the currently opened modal with Escape.
document.addEventListener('keydown', (event) => {
    if (event.key !== 'Escape') {
        return;
    }

    if (
        !elements.versionModal.classList.contains(
            'hidden'
        )
    ) {
        closeVersionModal();
        return;
    }

    if (
        !elements.applicationFormModal.classList.contains(
            'hidden'
        )
    ) {
        closeApplicationModal();
    }
});

/**
 * Initial page load.
 */
function initializePage() {
    resetApplicationForm();
    resetVersionForm();
    fetchApplications();
}

initializePage();