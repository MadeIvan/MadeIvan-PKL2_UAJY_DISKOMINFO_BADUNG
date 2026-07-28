import 'bootstrap-icons/font/bootstrap-icons.css';

const API_BASE_URL = '/api/admin';

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

    applicationForm: document.getElementById('application-form'),
    applicationFormTitle: document.getElementById(
        'application-form-title'
    ),
    applicationId: document.getElementById('application-id'),
    applicationName: document.getElementById('application-name'),
    applicationSlug: document.getElementById('application-slug'),
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

    applicationSearch: document.getElementById(
        'application-search'
    ),
    applicationCount: document.getElementById(
        'application-count'
    ),
    applicationGrid: document.getElementById(
        'application-grid'
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

    paginationWrapper: document.getElementById(
        'application-pagination-wrapper'
    ),
    pagination: document.getElementById(
        'application-pagination'
    ),
    pageInfo: document.getElementById(
        'application-page-info'
    ),

    versionModal: document.getElementById('version-modal'),
    versionModalClose: document.getElementById(
        'version-modal-close'
    ),
    versionModalApplicationName: document.getElementById(
        'version-modal-application-name'
    ),
    versionForm: document.getElementById('version-form'),
    versionFormTitle: document.getElementById(
        'version-form-title'
    ),
    versionId: document.getElementById('version-id'),
    versionApplicationId: document.getElementById(
        'version-application-id'
    ),
    versionNumber: document.getElementById('version-number'),
    versionReleaseDate: document.getElementById(
        'version-release-date'
    ),
    versionStatus: document.getElementById('version-status'),
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
    versionList: document.getElementById('version-list'),
    versionEmpty: document.getElementById('version-empty'),
};

let searchTimeout = null;

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
            result.meta?.total ?? 0
        );

        state.from = result.meta?.from ?? null;
        state.to = result.meta?.to ?? null;

        renderApplications();
        renderPagination();

        if (state.activeApplicationId !== null) {
            const application = findApplication(
                state.activeApplicationId
            );

            if (application) {
                renderVersionModal(application.id);
            } else {
                closeVersionModal();
            }
        }
    } catch (error) {
        showApplicationError(error.message);
    }
}

function renderApplications() {
    hideApplicationStates();

    elements.applicationCount.textContent =
        `${state.total} aplikasi ditemukan`;

    if (state.applications.length === 0) {
        elements.applicationGrid.innerHTML = '';
        elements.applicationGrid.classList.add('hidden');
        elements.applicationEmpty.classList.remove('hidden');
        elements.paginationWrapper.classList.add('hidden');

        return;
    }

    elements.applicationGrid.innerHTML = state.applications
        .map(createApplicationCard)
        .join('');

    elements.applicationGrid.classList.remove('hidden');
}

function createApplicationCard(application) {
    const logoUrl =
        application.logo_url || '/images/Logo.png';

    const currentVersion =
        application.current_version?.version_number || null;

    const versionsCount = Array.isArray(application.versions)
        ? application.versions.length
        : 0;

    const visibilityLabel = application.is_public
        ? 'Publik'
        : 'Privat';

    const visibilityClass = application.is_public
        ? 'bg-emerald-100 text-emerald-700'
        : 'bg-slate-100 text-slate-600';

    const versionBadge = currentVersion
        ? `
            <span class="rounded-full bg-blue-100 px-2.5 py-1 text-xs font-semibold text-blue-800">
                v${escapeHtml(currentVersion)}
            </span>
        `
        : `
            <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-500">
                Belum ada versi
            </span>
        `;

    return `
        <article class="flex h-full flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
            <div class="flex aspect-video items-center justify-center overflow-hidden bg-slate-50">
                <img
                    src="${escapeAttribute(logoUrl)}"
                    alt="${escapeAttribute(application.name)}"
                    class="h-full w-full object-contain p-10"
                    loading="lazy"
                    onerror="this.onerror=null;this.src='/images/Logo.png';"
                >
            </div>

            <div class="flex flex-1 flex-col p-5">
                <div class="flex flex-wrap items-center gap-2">
                    <h3 class="text-lg font-bold text-slate-900">
                        ${escapeHtml(application.name)}
                    </h3>

                    ${versionBadge}
                </div>

                <p class="mt-1 text-sm text-slate-500">
                    ${escapeHtml(
                        application.category_name ||
                        'Tanpa Kategori'
                    )}
                </p>

                <p class="mt-4 flex-1 text-sm leading-6 text-slate-600">
                    ${escapeHtml(
                        application.description ||
                        'Belum ada deskripsi aplikasi.'
                    )}
                </p>

                <div class="mt-4 flex flex-wrap gap-2">
                    <span class="rounded-full px-3 py-1 text-xs font-semibold ${getApplicationStatusClass(application.status)}">
                        ${escapeHtml(
                            getApplicationStatusLabel(
                                application.status
                            )
                        )}
                    </span>

                    <span class="rounded-full px-3 py-1 text-xs font-semibold ${visibilityClass}">
                        ${visibilityLabel}
                    </span>

                    <span class="rounded-full bg-violet-100 px-3 py-1 text-xs font-semibold text-violet-700">
                        ${versionsCount} versi
                    </span>
                </div>

                <div class="mt-5 grid grid-cols-3 gap-2">
                    <button
                        type="button"
                        class="application-edit-button inline-flex items-center justify-center gap-2 rounded-xl border border-blue-200 px-3 py-2 text-sm font-semibold text-blue-800 hover:bg-blue-50"
                        data-id="${application.id}"
                    >
                        <i class="bi bi-pencil-square"></i>
                        <span class="hidden sm:inline">Ubah</span>
                    </button>

                    <button
                        type="button"
                        class="application-version-button inline-flex items-center justify-center gap-2 rounded-xl border border-violet-200 px-3 py-2 text-sm font-semibold text-violet-700 hover:bg-violet-50"
                        data-id="${application.id}"
                    >
                        <i class="bi bi-tags"></i>
                        <span class="hidden sm:inline">Versi</span>
                    </button>

                    <button
                        type="button"
                        class="application-delete-button inline-flex items-center justify-center gap-2 rounded-xl border border-red-200 px-3 py-2 text-sm font-semibold text-red-600 hover:bg-red-50"
                        data-id="${application.id}"
                    >
                        <i class="bi bi-trash3"></i>
                        <span class="hidden sm:inline">Hapus</span>
                    </button>
                </div>
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
    const colorClass = active
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
            class="pagination-button flex h-10 min-w-10 items-center justify-center rounded-xl border px-3 text-sm font-semibold transition ${colorClass} ${disabledClass}"
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
        return [1, 2, 3, 4, 5, '...', last];
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

    document
        .getElementById('application-grid')
        ?.scrollIntoView({
            behavior: 'smooth',
            block: 'start',
        });
}

async function submitApplication(event) {
    event.preventDefault();

    const applicationId = elements.applicationId.value;
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
        elements.applicationIsPublic.checked ? '1' : '0'
    );

    const slug = elements.applicationSlug.value.trim();

    if (slug !== '') {
        formData.append('slug', slug);
    }

    const logo = elements.applicationLogo.files[0];

    if (logo) {
        formData.append('logo', logo);
    }

    if (elements.applicationRemoveLogo.checked) {
        formData.append('remove_logo', '1');
    }

    let url = `${API_BASE_URL}/applications`;

    if (isEditing) {
        url = `${API_BASE_URL}/applications/${applicationId}`;
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

        resetApplicationForm();

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

function startApplicationEdit(applicationId) {
    const application = findApplication(applicationId);

    if (!application) {
        showNotification(
            'Data aplikasi tidak ditemukan.',
            'error'
        );

        return;
    }

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

    elements.applicationCancelButton.classList.remove(
        'hidden'
    );

    elements.applicationCancelButton.classList.add(
        'inline-flex'
    );

    setButtonContent(
        elements.applicationSubmitButton,
        'bi-pencil-square',
        'Perbarui Aplikasi'
    );

    window.scrollTo({
        top: 0,
        behavior: 'smooth',
    });
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

    elements.applicationCancelButton.classList.add('hidden');
    elements.applicationCancelButton.classList.remove(
        'inline-flex'
    );

    setButtonContent(
        elements.applicationSubmitButton,
        'bi-plus-lg',
        'Simpan Aplikasi'
    );
}

async function deleteApplication(applicationId) {
    const application = findApplication(applicationId);

    if (!application) {
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

        await fetchApplications(state.currentPage);

        showNotification(
            result.message ||
            'Aplikasi berhasil dihapus.',
            'success'
        );
    } catch (error) {
        showNotification(error.message, 'error');
    }
}

function openVersionModal(applicationId) {
    const application = findApplication(applicationId);

    if (!application) {
        return;
    }

    state.activeApplicationId = Number(applicationId);

    resetVersionForm();

    elements.versionApplicationId.value =
        application.id;

    renderVersionModal(application.id);

    elements.versionModal.classList.remove('hidden');
    elements.versionModal.classList.add('flex');

    document.body.classList.add('overflow-hidden');
}

function closeVersionModal() {
    state.activeApplicationId = null;

    elements.versionModal.classList.add('hidden');
    elements.versionModal.classList.remove('flex');

    document.body.classList.remove('overflow-hidden');

    resetVersionForm();
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
        elements.versionEmpty.classList.remove('hidden');
        return;
    }

    elements.versionEmpty.classList.add('hidden');

    elements.versionList.innerHTML = versions
        .map(createVersionItem)
        .join('');
}

function createVersionItem(version) {
    return `
        <article class="rounded-2xl border border-slate-200 p-4">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <div class="flex flex-wrap items-center gap-2">
                        <h4 class="font-bold text-slate-900">
                            v${escapeHtml(version.version_number)}
                        </h4>

                        ${
                            version.is_current
                                ? `
                                    <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                                        Versi Saat Ini
                                    </span>
                                `
                                : ''
                        }
                    </div>

                    <p class="mt-2 text-xs text-slate-500">
                        ${escapeHtml(
                            formatDate(version.release_date)
                        )}
                    </p>

                    <p class="mt-3 text-sm text-slate-600">
                        ${escapeHtml(
                            version.release_notes ||
                            'Belum ada catatan rilis.'
                        )}
                    </p>
                </div>

                <div class="flex gap-2">
                    <button
                        type="button"
                        class="version-edit-button flex h-10 w-10 items-center justify-center rounded-xl border border-blue-200 text-blue-800 hover:bg-blue-50"
                        data-id="${version.id}"
                    >
                        <i class="bi bi-pencil-square"></i>
                    </button>

                    <button
                        type="button"
                        class="version-delete-button flex h-10 w-10 items-center justify-center rounded-xl border border-red-200 text-red-600 hover:bg-red-50"
                        data-id="${version.id}"
                    >
                        <i class="bi bi-trash3"></i>
                    </button>
                </div>
            </div>
        </article>
    `;
}

async function submitVersion(event) {
    event.preventDefault();

    const applicationId =
        elements.versionApplicationId.value ||
        state.activeApplicationId;

    const versionId = elements.versionId.value;
    const isEditing = versionId !== '';

    const payload = {
        version_number:
            elements.versionNumber.value.trim(),
        release_date:
            elements.versionReleaseDate.value || null,
        release_notes:
            elements.versionReleaseNotes.value.trim() || null,
        status: elements.versionStatus.value,
        is_current: elements.versionIsCurrent.checked,
    };

    const url = isEditing
        ? `${API_BASE_URL}/application-versions/${versionId}`
        : `${API_BASE_URL}/applications/${applicationId}/versions`;

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
        'Versi berhasil disimpan.',
        'success'
    );
}

function startVersionEdit(versionId) {
    const application = findApplication(
        state.activeApplicationId
    );

    const version = application?.versions?.find(
        (item) => Number(item.id) === Number(versionId)
    );

    if (!version) {
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

    elements.versionCancelButton.classList.remove('hidden');
    elements.versionCancelButton.classList.add('inline-flex');
}

function resetVersionForm() {
    elements.versionForm.reset();

    elements.versionId.value = '';
    elements.versionApplicationId.value =
        state.activeApplicationId ?? '';
    elements.versionStatus.value = 'draft';

    elements.versionFormTitle.textContent =
        'Tambah Versi';

    elements.versionCancelButton.classList.add('hidden');
    elements.versionCancelButton.classList.remove(
        'inline-flex'
    );
}

async function deleteVersion(versionId) {
    const confirmed = window.confirm(
        'Apakah Anda yakin ingin menghapus versi ini?'
    );

    if (!confirmed) {
        return;
    }

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
}

function showApplicationLoading() {
    elements.applicationLoading.classList.remove('hidden');
    elements.applicationEmpty.classList.add('hidden');
    elements.applicationError.classList.add('hidden');
    elements.applicationGrid.classList.add('hidden');
    elements.paginationWrapper.classList.add('hidden');
}

function hideApplicationStates() {
    elements.applicationLoading.classList.add('hidden');
    elements.applicationEmpty.classList.add('hidden');
    elements.applicationError.classList.add('hidden');
}

function showApplicationError(message) {
    elements.applicationLoading.classList.add('hidden');
    elements.applicationGrid.classList.add('hidden');
    elements.applicationError.classList.remove('hidden');
    elements.paginationWrapper.classList.add('hidden');

    elements.applicationErrorMessage.textContent = message;
}

async function parseResponse(response) {
    const result = await response.json().catch(() => ({}));

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
            Number(application.id) === Number(applicationId)
    );
}

function previewSelectedImage(input, wrapper, preview) {
    const file = input.files[0];

    if (!file) {
        return;
    }

    const temporaryUrl = URL.createObjectURL(file);

    preview.src = temporaryUrl;
    wrapper.classList.remove('hidden');

    preview.onload = () => {
        URL.revokeObjectURL(temporaryUrl);
    };
}

function showExistingImage(wrapper, preview, url) {
    if (!url) {
        wrapper.classList.add('hidden');
        return;
    }

    preview.src = url;
    wrapper.classList.remove('hidden');
}

function getApplicationStatusClass(status) {
    return {
        active: 'bg-emerald-100 text-emerald-700',
        inactive: 'bg-amber-100 text-amber-700',
        archived: 'bg-slate-200 text-slate-700',
    }[status] || 'bg-slate-100 text-slate-600';
}

function getApplicationStatusLabel(status) {
    return {
        active: 'Aktif',
        inactive: 'Tidak Aktif',
        archived: 'Diarsipkan',
    }[status] || status;
}

function setApplicationButtonLoading(isLoading) {
    elements.applicationSubmitButton.disabled = isLoading;
}

function setButtonContent(button, iconClass, label) {
    button.innerHTML = `
        <i class="bi ${iconClass}"></i>
        <span>${escapeHtml(label)}</span>
    `;
}

function showNotification(message, type = 'success') {
    const styles = {
        success:
            'border-emerald-200 bg-emerald-50 text-emerald-700',
        error:
            'border-red-200 bg-red-50 text-red-700',
    };

    elements.notification.className =
        `mb-6 rounded-xl border px-4 py-3 text-sm ${styles[type]}`;

    elements.notification.textContent = message;
    elements.notification.classList.remove('hidden');
}

function formatDate(value) {
    if (!value) {
        return 'Belum ditentukan';
    }

    return new Intl.DateTimeFormat('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    }).format(new Date(value));
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

elements.applicationForm.addEventListener(
    'submit',
    submitApplication
);

elements.applicationSearch.addEventListener(
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

        changePage(Number(button.dataset.page));
    }
);

elements.applicationRetryButton.addEventListener(
    'click',
    () => fetchApplications(state.currentPage)
);

elements.applicationCancelButton.addEventListener(
    'click',
    resetApplicationForm
);

elements.applicationGrid.addEventListener(
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
            startApplicationEdit(editButton.dataset.id);
        }

        if (versionButton) {
            openVersionModal(versionButton.dataset.id);
        }

        if (deleteButton) {
            deleteApplication(deleteButton.dataset.id);
        }
    }
);

elements.applicationLogo.addEventListener(
    'change',
    () => {
        previewSelectedImage(
            elements.applicationLogo,
            elements.applicationLogoPreviewWrapper,
            elements.applicationLogoPreview
        );
    }
);

elements.versionForm.addEventListener(
    'submit',
    submitVersion
);

elements.versionCancelButton.addEventListener(
    'click',
    resetVersionForm
);

elements.versionModalClose.addEventListener(
    'click',
    closeVersionModal
);

elements.versionList.addEventListener(
    'click',
    (event) => {
        const editButton = event.target.closest(
            '.version-edit-button'
        );

        const deleteButton = event.target.closest(
            '.version-delete-button'
        );

        if (editButton) {
            startVersionEdit(editButton.dataset.id);
        }

        if (deleteButton) {
            deleteVersion(deleteButton.dataset.id);
        }
    }
);

resetApplicationForm();
fetchApplications();