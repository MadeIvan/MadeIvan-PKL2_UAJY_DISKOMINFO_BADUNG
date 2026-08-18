import 'bootstrap-icons/font/bootstrap-icons.css';
import { showToast, displayValidationErrors, clearValidationErrors, setButtonLoading, showDoubleConfirmModal } from '../utils.js';

const API_BASE_URL = '/api/admin';
const MATERIAL_PAGE_URL = '/admin/materi';
const DEFAULT_LOGO_URL = '/images/Logo.png';

const page = document.getElementById('application-page');

if (page) {
    initializeApplicationPage();
}

function initializeApplicationPage() {
    const state = {
        applications: [],
        search: '',
        sort: 'latest',
        currentPage: 1,
        lastPage: 1,
        total: 0,
        from: null,
        to: null,
        activeApplicationId: null,
        menuApplicationId: null,
        sourceMaterialTree: [],
        selectedSourceNodeIds: new Set(),
        pendingVersionPayload: null,
    };

    const elements = {


        statTotalApplications: document.getElementById(
            'stat-total-applications'
        ),

        statActiveApplications: document.getElementById(
            'stat-active-applications'
        ),

        statPublicApplications: document.getElementById(
            'stat-public-applications'
        ),

        statInactiveApplications: document.getElementById(
            'stat-inactive-applications'
        ),

        applicationSort: document.getElementById(
            'application-sort'
        ),

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

        paginationWrapper: document.getElementById(
            'application-pagination-wrapper'
        ),

        pagination: document.getElementById(
            'application-pagination'
        ),

        pageInfo: document.getElementById(
            'application-page-info'
        ),

        rowMenu: document.getElementById(
            'application-row-menu'
        ),

        viewMaterialButton: document.getElementById(
            'application-view-material'
        ),

        applicationFormModal: document.getElementById(
            'application-form-modal'
        ),

        openApplicationFormButton: document.getElementById(
            'open-application-form'
        ),

        applicationFormModalClose: document.getElementById(
            'application-form-modal-close'
        ),

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

        versionModal: document.getElementById(
            'version-modal'
        ),

        versionModalClose: document.getElementById(
            'version-modal-close'
        ),

        versionModalApplicationName: document.getElementById(
            'version-modal-application-name'
        ),

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

        versionCopySection: document.getElementById(
            'version-copy-section'
        ),

        versionCopyMaterials: document.getElementById(
            'version-copy-materials'
        ),

        versionCopyOptions: document.getElementById(
            'version-copy-options'
        ),

        versionSourceSelect: document.getElementById(
            'version-source-select'
        ),

        versionCopySelectedCount: document.getElementById(
            'version-copy-selected-count'
        ),

        versionCopySelectAll: document.getElementById(
            'version-copy-select-all'
        ),

        versionCopyClearAll: document.getElementById(
            'version-copy-clear-all'
        ),

        versionCopyTreeLoading: document.getElementById(
            'version-copy-tree-loading'
        ),

        versionCopyTreeEmpty: document.getElementById(
            'version-copy-tree-empty'
        ),

        versionCopyTreeError: document.getElementById(
            'version-copy-tree-error'
        ),

        versionCopyTree: document.getElementById(
            'version-copy-tree'
        ),

        versionCopyConfirmationModal: document.getElementById(
            'version-copy-confirmation-modal'
        ),

        versionCopyConfirmationText: document.getElementById(
            'version-copy-confirmation-text'
        ),

        versionCopyConfirmationCancel: document.getElementById(
            'version-copy-confirmation-cancel'
        ),

        versionCopyConfirmationSubmit: document.getElementById(
            'version-copy-confirmation-submit'
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

    const missingElements = Object.entries(elements)
        .filter(([, element]) => !element)
        .map(([name]) => name);

    if (missingElements.length > 0) {
        console.error(
            'Halaman aplikasi: elemen Blade tidak ditemukan:',
            missingElements
        );

        return;
    }

    let searchTimeout = null;
    let notificationTimeout = null;

    bindEvents();
    resetApplicationForm();
    resetVersionForm();
    initializeFilters();
    fetchApplications();
    fetchCategories();

    async function fetchCategories() {
        try {
            const response = await fetch('/api/admin/categories?all=true', {
                headers: { Accept: 'application/json' },
            });
            
            if (response.ok) {
                const categories = await response.json();
                let html = '<option value="">Tidak ada kategori</option>';
                categories.forEach(cat => {
                    html += `<option value="${cat.id}">${cat.name}</option>`;
                });
                elements.applicationCategory.innerHTML = html;
            } else {
                console.error('Failed to fetch categories');
            }
        } catch (error) {
            console.error('Error fetching categories:', error);
        }
    }

    function initializeFilters() {
        elements.applicationSort.value = state.sort;
        elements.applicationSearch.value = state.search;
    }

    function bindEvents() {
        elements.openApplicationFormButton.addEventListener(
            'click',
            openCreateApplicationModal
        );

        elements.applicationFormModalClose.addEventListener(
            'click',
            closeApplicationModal
        );

        elements.applicationCancelButton.addEventListener(
            'click',
            closeApplicationModal
        );

        elements.applicationForm.addEventListener(
            'submit',
            submitApplication
        );

        elements.applicationSort.addEventListener(
            'change',
            (event) => {
                state.sort = normalizeSort(
                    event.target.value
                );

                closeRowMenu();
                fetchApplications(1);
            }
        );

        elements.applicationSearch.addEventListener(
            'input',
            (event) => {
                state.search = event.target.value.trim();

                window.clearTimeout(searchTimeout);

                searchTimeout = window.setTimeout(() => {
                    closeRowMenu();
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

                changePage(
                    Number(button.dataset.page)
                );
            }
        );

        elements.applicationRetryButton.addEventListener(
            'click',
            () => {
                closeRowMenu();
                fetchApplications(state.currentPage);
            }
        );

        elements.applicationTableBody.addEventListener(
            'click',
            handleApplicationTableClick
        );

        elements.viewMaterialButton.addEventListener(
            'click',
            () => {
                goToSelectedApplicationMaterials();
            }
        );

        elements.applicationLogo.addEventListener(
            'change',
            previewSelectedImage
        );

        elements.versionForm.addEventListener(
            'submit',
            submitVersion
        );

        elements.versionCopyMaterials.addEventListener(
            'change',
            handleVersionCopyToggle
        );

        elements.versionSourceSelect.addEventListener(
            'change',
            handleSourceVersionChange
        );

        elements.versionCopyTree.addEventListener(
            'change',
            handleSourceTreeSelectionChange
        );

        elements.versionCopySelectAll.addEventListener(
            'click',
            selectAllSourceMaterials
        );

        elements.versionCopyClearAll.addEventListener(
            'click',
            clearAllSourceMaterials
        );

        elements.versionCopyConfirmationCancel.addEventListener(
            'click',
            closeVersionCopyConfirmation
        );

        elements.versionCopyConfirmationSubmit.addEventListener(
            'click',
            confirmVersionCreationWithCopy
        );

        elements.versionCopyConfirmationModal.addEventListener(
            'click',
            (event) => {
                if (
                    event.target ===
                    elements.versionCopyConfirmationModal
                ) {
                    closeVersionCopyConfirmation();
                }
            }
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
            handleVersionListClick
        );

        elements.applicationFormModal.addEventListener(
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

        elements.versionModal.addEventListener(
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

        document.addEventListener(
            'click',
            handleDocumentClick
        );

        document.addEventListener(
            'keydown',
            handleEscapeKey
        );

        window.addEventListener(
            'scroll',
            closeRowMenu,
            true
        );

        window.addEventListener(
            'resize',
            closeRowMenu
        );
    }

    function handleApplicationTableClick(event) {
        const row = event.target.closest(
            '.application-row'
        );

        if (!row) {
            return;
        }

        const editButton = event.target.closest(
            '.application-edit-button'
        );

        const versionButton = event.target.closest(
            '.application-version-button'
        );

        const deleteButton = event.target.closest(
            '.application-delete-button'
        );

        const menuButton = event.target.closest(
            '.application-menu-button'
        );

        if (editButton) {
            closeRowMenu();

            startApplicationEdit(
                editButton.dataset.id
            );

            return;
        }

        if (versionButton) {
            closeRowMenu();

            openVersionModal(
                versionButton.dataset.id
            );

            return;
        }

        if (deleteButton) {
            closeRowMenu();

            deleteApplication(
                deleteButton.dataset.id
            );

            return;
        }

        if (menuButton) {
            event.stopPropagation();

            openRowMenuFromButton(
                menuButton,
                menuButton.dataset.id
            );

            return;
        }

        if (event.target.closest('button, a, input, select, textarea, label')) {
            return;
        }

        openRowMenuAtPoint(
            event.clientX,
            event.clientY,
            row.dataset.id
        );
    }

    function handleVersionListClick(event) {
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
                deleteButton.dataset.id,
                deleteButton.dataset.name
            );
        }
    }

    function handleDocumentClick(event) {
        if (elements.rowMenu.classList.contains('hidden')) {
            return;
        }

        if (
            elements.rowMenu.contains(event.target) ||
            event.target.closest('.application-menu-button')
        ) {
            return;
        }

        closeRowMenu();
    }

    function handleEscapeKey(event) {
        if (event.key !== 'Escape') {
            return;
        }

        if (!elements.rowMenu.classList.contains('hidden')) {
            closeRowMenu();
            return;
        }

        if (
            !elements.versionCopyConfirmationModal.classList.contains(
                'hidden'
            )
        ) {
            closeVersionCopyConfirmation();
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
    }

    function openRowMenuFromButton(button, applicationId) {
        const rectangle = button.getBoundingClientRect();

        openRowMenuAtPoint(
            rectangle.right,
            rectangle.bottom + 6,
            applicationId,
            true
        );
    }

    function openRowMenuAtPoint(
        x,
        y,
        applicationId,
        alignRight = false
    ) {
        const id = Number(applicationId);

        if (!Number.isInteger(id) || id <= 0) {
            return;
        }

        state.menuApplicationId = id;

        elements.rowMenu.classList.remove('hidden');
        elements.rowMenu.setAttribute(
            'aria-hidden',
            'false'
        );

        const menuWidth =
            elements.rowMenu.offsetWidth || 176;

        const menuHeight =
            elements.rowMenu.offsetHeight || 48;

        const viewportPadding = 12;

        let left = alignRight
            ? x - menuWidth
            : x;

        let top = y;

        if (
            left + menuWidth >
            window.innerWidth - viewportPadding
        ) {
            left =
                window.innerWidth -
                menuWidth -
                viewportPadding;
        }

        if (left < viewportPadding) {
            left = viewportPadding;
        }

        if (
            top + menuHeight >
            window.innerHeight - viewportPadding
        ) {
            top =
                window.innerHeight -
                menuHeight -
                viewportPadding;
        }

        if (top < viewportPadding) {
            top = viewportPadding;
        }

        elements.rowMenu.style.left =
            `${Math.round(left)}px`;

        elements.rowMenu.style.top =
            `${Math.round(top)}px`;
    }

    function closeRowMenu() {
        state.menuApplicationId = null;

        elements.rowMenu.classList.add('hidden');

        elements.rowMenu.setAttribute(
            'aria-hidden',
            'true'
        );

        elements.rowMenu.style.left = '';
        elements.rowMenu.style.top = '';
    }

    function goToSelectedApplicationMaterials() {
        const applicationId =
            Number(state.menuApplicationId);

        if (
            !Number.isInteger(applicationId) ||
            applicationId <= 0
        ) {
            closeRowMenu();

            showToast(
                'Aplikasi tidak ditemukan.',
                'error'
            );

            return;
        }

        const application = state.applications.find(
            (app) => app.id === applicationId
        );

        if (!application) {
            closeRowMenu();
            return;
        }

        const identifier = application.slug || String(application.id);

        const parameters = new URLSearchParams({
            app: identifier,
        });

        closeRowMenu();

        window.location.href =
            `${MATERIAL_PAGE_URL}?${parameters.toString()}`;
    }

    async function fetchApplications(pageNumber = 1) {
        showApplicationLoading();

        try {
            const parameters = new URLSearchParams({
                page: String(pageNumber),
                sort: state.sort,
            });

            if (state.search !== '') {
                parameters.set(
                    'search',
                    state.search
                );
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
                result.meta?.total ??
                state.applications.length
            );

            state.from =
                result.meta?.from ?? null;

            state.to =
                result.meta?.to ?? null;

            if (result.filters?.sort) {
                state.sort = normalizeSort(
                    result.filters.sort
                );

                elements.applicationSort.value =
                    state.sort;
            }

            renderApplications();
            renderPagination();
            renderStatistics(
                result.summary ?? null
            );

            refreshOpenedVersionModal();
        } catch (error) {
            showApplicationError(
                error.message
            );
        }
    }

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

    function createApplicationRow(application) {
        const logoUrl =
            application.logo_url ||
            DEFAULT_LOGO_URL;

        const category =
            application.category?.name ||
            'Tanpa Kategori';

        const description =
            application.description ||
            'Belum ada deskripsi aplikasi.';

        const currentVersion =
            application.current_version?.version_number ||
            null;

        const versionsCount =
            Array.isArray(application.versions)
                ? application.versions.length
                : 0;

        const visibilityLabel =
            application.is_public
                ? 'Publik'
                : 'Privat';

        const visibilityClass =
            application.is_public
                ? 'border-emerald-200 bg-emerald-50 text-emerald-700'
                : 'border-slate-200 bg-slate-100 text-slate-600';

        const currentVersionLabel =
            currentVersion
                ? `v${escapeHtml(currentVersion)}`
                : 'Belum ada';

        return `
            <tr
                class="application-row cursor-pointer transition hover:bg-slate-50"
                data-id="${application.id}"
            >
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
                    <span class="inline-flex border px-2.5 py-1 text-xs font-semibold ${getApplicationStatusClass(
                        application.status
                    )}">
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
                            aria-label="Ubah aplikasi ${escapeAttribute(
                                application.name
                            )}"
                        >
                            <i class="bi bi-pencil-square"></i>
                        </button>

                        <button
                            type="button"
                            class="application-version-button flex h-9 w-9 items-center justify-center border border-violet-200 text-violet-700 transition hover:bg-violet-50"
                            data-id="${application.id}"
                            title="Kelola versi"
                            aria-label="Kelola versi ${escapeAttribute(
                                application.name
                            )}"
                        >
                            <i class="bi bi-tags"></i>
                        </button>

                        <button
                            type="button"
                            class="application-delete-button flex h-9 w-9 items-center justify-center border border-red-200 text-red-600 transition hover:bg-red-50"
                            data-id="${application.id}"
                            title="Hapus aplikasi"
                            aria-label="Hapus aplikasi ${escapeAttribute(
                                application.name
                            )}"
                        >
                            <i class="bi bi-trash3"></i>
                        </button>

                        <button
                            type="button"
                            class="application-menu-button flex h-9 w-9 items-center justify-center border border-slate-300 text-slate-600 transition hover:bg-slate-100 hover:text-slate-950 disabled:opacity-50 disabled:cursor-not-allowed"
                            data-id="${application.id}"
                            title="${versionsCount === 0 ? 'Tidak ada versi untuk melihat materi' : 'Menu lainnya'}"
                            aria-label="Menu lainnya untuk ${escapeAttribute(
                                application.name
                            )}"
                            aria-haspopup="menu"
                            ${versionsCount === 0 ? 'disabled' : ''}
                        >
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    }

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

        const pageInactiveApplications =
            state.applications.filter(
                (application) =>
                    application.status === 'inactive'
            ).length;

        elements.statTotalApplications.textContent =
            String(
                Number(
                    summary?.total_applications ??
                    state.total
                )
            );

        elements.statActiveApplications.textContent =
            String(
                Number(
                    summary?.active_applications ??
                    pageActiveApplications
                )
            );

        elements.statPublicApplications.textContent =
            String(
                Number(
                    summary?.public_applications ??
                    pagePublicApplications
                )
            );

        elements.statInactiveApplications.textContent =
            String(
                Number(
                    summary?.inactive_applications ??
                    pageInactiveApplications
                )
            );
    }

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
                label:
                    '<i class="bi bi-chevron-left"></i>',

                page:
                    state.currentPage - 1,

                disabled:
                    state.currentPage === 1,

                title:
                    'Halaman sebelumnya',
            })
        );

        getVisiblePages().forEach((visiblePage) => {
            if (visiblePage === '...') {
                buttons.push(`
                    <span class="flex h-10 min-w-10 items-center justify-center px-2 text-sm text-slate-400">
                        ...
                    </span>
                `);

                return;
            }

            buttons.push(
                createPaginationButton({
                    label: String(visiblePage),
                    page: visiblePage,
                    active:
                        visiblePage ===
                        state.currentPage,
                    title:
                        `Halaman ${visiblePage}`,
                })
            );
        });

        buttons.push(
            createPaginationButton({
                label:
                    '<i class="bi bi-chevron-right"></i>',

                page:
                    state.currentPage + 1,

                disabled:
                    state.currentPage ===
                    state.lastPage,

                title:
                    'Halaman berikutnya',
            })
        );

        elements.pagination.innerHTML =
            buttons.join('');
    }

    function createPaginationButton({
        label,
        page: targetPage,
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
                data-page="${targetPage}"
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

    function changePage(targetPage) {
        if (
            !Number.isInteger(targetPage) ||
            targetPage < 1 ||
            targetPage > state.lastPage ||
            targetPage === state.currentPage
        ) {
            return;
        }

        closeRowMenu();
        fetchApplications(targetPage);
    }

    function openCreateApplicationModal() {
        closeRowMenu();
        resetApplicationForm();
        openApplicationModal();
    }

    function startApplicationEdit(applicationId) {
        const application =
            findApplication(applicationId);

        if (!application) {
            showToast(
                'Data aplikasi tidak ditemukan.',
                'error'
            );

            return;
        }

        resetApplicationForm();

        elements.applicationId.value =
            application.id;

        elements.applicationName.value =
            application.name || '';

        elements.applicationSlug.value =
            application.slug || '';

        elements.applicationDescription.value =
            application.description || '';

        elements.applicationCategory.value =
            application.category_id || '';

        elements.applicationStatus.value =
            application.status || 'active';

        elements.applicationIsPublic.checked =
            Boolean(application.is_public);

        elements.applicationLogo.value = '';

        elements.applicationRemoveLogo.checked =
            false;

        showExistingImage(
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

        setButtonLoading(elements.applicationSubmitButton, false);
        clearValidationErrors(elements.applicationForm);
    }

    async function submitApplication(event) {
        event.preventDefault();

        const applicationId =
            elements.applicationId.value;

        const isEditing =
            applicationId !== '';

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
            'category_id',
            elements.applicationCategory.value
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

        if (
            elements.applicationRemoveLogo.checked
        ) {
            formData.append(
                'remove_logo',
                '1'
            );
        }

        let url =
            `${API_BASE_URL}/applications`;

        if (isEditing) {
            url =
                `${API_BASE_URL}/applications/${applicationId}`;

            formData.append('_method', 'PUT');
        }

        setButtonLoading(elements.applicationSubmitButton, true);
        clearValidationErrors(elements.applicationForm);

        try {
            const response = await fetch(url, {
                method: 'POST',

                headers: {
                    Accept: 'application/json',
                },

                body: formData,
            });

            const result =
                await parseResponse(response);

            closeApplicationModal();

            await fetchApplications(
                isEditing
                    ? state.currentPage
                    : 1
            );

            showToast(
                result.message ||
                (
                    isEditing
                        ? 'Aplikasi berhasil diperbarui.'
                        : 'Aplikasi berhasil ditambahkan.'
                ),
                'success'
            );
        } catch (error) {
            showToast(
                error.message,
                'error'
            );
            
            if (error.validationErrors) {
                displayValidationErrors(error.validationErrors, elements.applicationForm, 'application-');
            }
        } finally {
            setButtonLoading(elements.applicationSubmitButton, false);
        }
    }

    async function deleteApplication(applicationId) {
        const application =
            findApplication(applicationId);

        if (!application) {
            showToast(
                'Data aplikasi tidak ditemukan.',
                'error'
            );

            return;
        }

        const confirmed = await showDoubleConfirmModal(
            'Konfirmasi Hapus Aplikasi',
            application.name
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

            const result =
                await parseResponse(response);

            const targetPage =
                state.applications.length === 1 &&
                state.currentPage > 1
                    ? state.currentPage - 1
                    : state.currentPage;

            await fetchApplications(targetPage);

            showToast(
                result.message ||
                'Aplikasi berhasil dihapus.',
                'success'
            );
        } catch (error) {
            showToast(
                error.message,
                'error'
            );
        }
    }

    function openVersionModal(applicationId) {
        const application =
            findApplication(applicationId);

        if (!application) {
            showToast(
                'Data aplikasi tidak ditemukan.',
                'error'
            );

            return;
        }

        state.activeApplicationId =
            Number(applicationId);

        resetVersionForm();

        elements.versionApplicationId.value =
            application.id;

        renderVersionModal(application.id);

        elements.versionModal.classList.remove(
            'hidden'
        );

        elements.versionModal.classList.add(
            'flex'
        );

        elements.versionModal.setAttribute(
            'aria-hidden',
            'false'
        );

        lockBodyScroll();
    }

    function closeVersionModal() {
        state.activeApplicationId = null;
        state.pendingVersionPayload = null;

        elements.versionCopyConfirmationModal.classList.add(
            'hidden'
        );

        elements.versionCopyConfirmationModal.classList.remove(
            'flex'
        );

        elements.versionCopyConfirmationModal.setAttribute(
            'aria-hidden',
            'true'
        );

        elements.versionModal.classList.add(
            'hidden'
        );

        elements.versionModal.classList.remove(
            'flex'
        );

        elements.versionModal.setAttribute(
            'aria-hidden',
            'true'
        );

        resetVersionForm();
        unlockBodyScroll();
    }

    function refreshOpenedVersionModal() {
        if (
            state.activeApplicationId === null
        ) {
            return;
        }

        const application =
            findApplication(
                state.activeApplicationId
            );

        if (!application) {
            closeVersionModal();
            return;
        }

        renderVersionModal(application.id);
    }

    function renderVersionModal(applicationId) {
        const application =
            findApplication(applicationId);

        if (!application) {
            return;
        }

        elements.versionModalApplicationName.textContent =
            application.name;

        elements.versionApplicationId.value =
            application.id;

        const versions =
            Array.isArray(application.versions)
                ? application.versions
                : [];

        populateSourceVersionOptions(
            versions
        );

        if (versions.length === 0) {
            elements.versionList.innerHTML = '';

            elements.versionEmpty.classList.remove(
                'hidden'
            );

            return;
        }

        elements.versionEmpty.classList.add(
            'hidden'
        );

        elements.versionList.innerHTML =
            versions
                .map(createVersionItem)
                .join('');
    }

    function createVersionItem(version) {
        const currentBadge =
            version.is_current
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
                                v${escapeHtml(
                                    version.version_number
                                )}
                            </h4>

                            ${currentBadge}

                            <span class="border px-2.5 py-1 text-xs font-semibold ${getVersionStatusClass(
                                version.status
                            )}">
                                ${escapeHtml(
                                    getVersionStatusLabel(
                                        version.status
                                    )
                                )}
                            </span>
                        </div>

                        <p class="mt-2 text-xs text-slate-500">
                            ${escapeHtml(
                                formatDate(
                                    version.release_date
                                )
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
                            data-name="${escapeAttribute(version.version_name)}"
                            title="Hapus versi"
                        >
                            <i class="bi bi-trash3"></i>
                        </button>
                    </div>
                </div>
            </article>
        `;
    }

    function populateSourceVersionOptions(
        versions
    ) {
        const currentValue =
            elements.versionSourceSelect.value;

        elements.versionSourceSelect.innerHTML =
            [
                `
                    <option value="">
                        Pilih versi sumber
                    </option>
                `,

                ...versions.map(
                    (version) => `
                        <option value="${version.id}">
                            v${escapeHtml(
                                version.version_number
                            )}
                            ${
                                version.is_current
                                    ? ' — Saat Ini'
                                    : ''
                            }
                        </option>
                    `
                ),
            ].join('');

        if (
            versions.some(
                (version) =>
                    String(version.id) ===
                    String(currentValue)
            )
        ) {
            elements.versionSourceSelect.value =
                currentValue;
        }
    }

    function handleVersionCopyToggle() {
        const enabled =
            elements.versionCopyMaterials.checked;

        elements.versionCopyOptions.classList.toggle(
            'hidden',
            !enabled
        );

        if (!enabled) {
            resetSourceMaterialSelection();
        }
    }

    async function handleSourceVersionChange() {
        resetSourceMaterialSelection(
            false
        );

        const sourceVersionId = Number(
            elements.versionSourceSelect.value
        );

        if (
            !Number.isInteger(sourceVersionId) ||
            sourceVersionId <= 0
        ) {
            return;
        }

        await fetchSourceMaterialTree(
            sourceVersionId
        );
    }

    async function fetchSourceMaterialTree(
        sourceVersionId
    ) {
        const applicationId = Number(
            elements.versionApplicationId.value ||
            state.activeApplicationId
        );

        showSourceTreeState('loading');

        try {
            const parameters =
                new URLSearchParams({
                    application_id:
                        String(applicationId),

                    application_version_id:
                        String(sourceVersionId),
                });

            const response = await fetch(
                `${API_BASE_URL}/tutorial-nodes/tree?${parameters.toString()}`,
                {
                    headers: {
                        Accept:
                            'application/json',
                    },
                }
            );

            const result =
                await parseResponse(response);

            state.sourceMaterialTree =
                Array.isArray(result.data)
                    ? result.data
                    : [];

            state.selectedSourceNodeIds =
                new Set();

            renderSourceMaterialTree();
        } catch (error) {
            state.sourceMaterialTree = [];
            state.selectedSourceNodeIds =
                new Set();

            showSourceTreeState(
                'error',
                error.message
            );

            updateSourceSelectionCount();
        }
    }

    function renderSourceMaterialTree() {
        if (
            state.sourceMaterialTree.length === 0
        ) {
            elements.versionCopyTree.innerHTML =
                '';

            showSourceTreeState('empty');
            updateSourceSelectionCount();

            return;
        }

        elements.versionCopyTree.innerHTML =
            state.sourceMaterialTree
                .map(
                    (node) =>
                        createSourceMaterialNodeHtml(
                            node,
                            0
                        )
                )
                .join('');

        showSourceTreeState('tree');
        applySourceCheckboxStates();
        updateSourceSelectionCount();
    }

    function createSourceMaterialNodeHtml(
        node,
        depth
    ) {
        const children =
            getSourceNodeChildren(node);

        return `
            <div
                class="source-material-node"
                data-source-node-id="${node.id}"
            >
                <label
                    class="flex cursor-pointer items-start gap-3 border border-slate-200 bg-white px-3 py-3 transition hover:bg-blue-50"
                    style="margin-left: ${depth * 18}px"
                >
                    <input
                        type="checkbox"
                        class="source-material-checkbox mt-0.5 h-4 w-4 border-slate-300 text-blue-900 focus:ring-blue-900"
                        data-id="${node.id}"
                    >

                    <span class="min-w-0">
                        <span class="block text-sm font-semibold text-slate-800">
                            ${escapeHtml(
                                node.title
                            )}
                        </span>

                        <span class="mt-1 block text-xs text-slate-500">
                            ${escapeHtml(
                                getSourceNodeTypeLabel(
                                    node.node_type
                                )
                            )}
                            ${
                                children.length > 0
                                    ? ` • ${children.length} child`
                                    : ''
                            }
                        </span>
                    </span>
                </label>

                ${
                    children.length > 0
                        ? `
                            <div class="mt-2 space-y-2">
                                ${children
                                    .map(
                                        (child) =>
                                            createSourceMaterialNodeHtml(
                                                child,
                                                depth + 1
                                            )
                                    )
                                    .join('')}
                            </div>
                        `
                        : ''
                }
            </div>
        `;
    }

    function handleSourceTreeSelectionChange(
        event
    ) {
        const checkbox =
            event.target.closest(
                '.source-material-checkbox'
            );

        if (!checkbox) {
            return;
        }

        const nodeId = Number(
            checkbox.dataset.id
        );

        const node =
            findSourceNode(nodeId);

        if (!node) {
            return;
        }

        const affectedIds = [
            Number(node.id),
            ...getSourceDescendantIds(
                node
            ),
        ];

        affectedIds.forEach((id) => {
            if (checkbox.checked) {
                state.selectedSourceNodeIds.add(
                    id
                );
            } else {
                state.selectedSourceNodeIds.delete(
                    id
                );
            }
        });

        synchronizeSourceAncestors();
        applySourceCheckboxStates();
        updateSourceSelectionCount();
    }

    function synchronizeSourceAncestors() {
        const nodes =
            flattenSourceTree(
                state.sourceMaterialTree
            );

        nodes
            .slice()
            .reverse()
            .forEach((node) => {
                const children =
                    getSourceNodeChildren(node);

                if (children.length === 0) {
                    return;
                }

                const descendantIds =
                    getSourceDescendantIds(
                        node
                    );

                const allSelected =
                    descendantIds.length > 0 &&
                    descendantIds.every(
                        (id) =>
                            state.selectedSourceNodeIds.has(
                                id
                            )
                    );

                if (allSelected) {
                    state.selectedSourceNodeIds.add(
                        Number(node.id)
                    );
                } else {
                    state.selectedSourceNodeIds.delete(
                        Number(node.id)
                    );
                }
            });
    }

    function applySourceCheckboxStates() {
        elements.versionCopyTree
            .querySelectorAll(
                '.source-material-checkbox'
            )
            .forEach((checkbox) => {
                const nodeId = Number(
                    checkbox.dataset.id
                );

                const node =
                    findSourceNode(
                        nodeId
                    );

                const descendantIds =
                    node
                        ? getSourceDescendantIds(
                            node
                        )
                        : [];

                const selectedDescendantCount =
                    descendantIds.filter(
                        (id) =>
                            state.selectedSourceNodeIds.has(
                                id
                            )
                    ).length;

                checkbox.checked =
                    state.selectedSourceNodeIds.has(
                        nodeId
                    );

                checkbox.indeterminate =
                    !checkbox.checked &&
                    selectedDescendantCount > 0;
            });
    }

    function selectAllSourceMaterials() {
        flattenSourceTree(
            state.sourceMaterialTree
        ).forEach((node) => {
            state.selectedSourceNodeIds.add(
                Number(node.id)
            );
        });

        applySourceCheckboxStates();
        updateSourceSelectionCount();
    }

    function clearAllSourceMaterials() {
        state.selectedSourceNodeIds =
            new Set();

        applySourceCheckboxStates();
        updateSourceSelectionCount();
    }

    function resetSourceMaterialSelection(
        resetSourceSelect = true
    ) {
        state.sourceMaterialTree = [];
        state.selectedSourceNodeIds =
            new Set();

        if (resetSourceSelect) {
            elements.versionSourceSelect.value =
                '';
        }

        elements.versionCopyTree.innerHTML =
            '';

        showSourceTreeState('none');
        updateSourceSelectionCount();
    }

    function showSourceTreeState(
        stateName,
        errorMessage = ''
    ) {
        elements.versionCopyTreeLoading.classList.add(
            'hidden'
        );

        elements.versionCopyTreeEmpty.classList.add(
            'hidden'
        );

        elements.versionCopyTreeError.classList.add(
            'hidden'
        );

        elements.versionCopyTree.classList.add(
            'hidden'
        );

        if (stateName === 'loading') {
            elements.versionCopyTreeLoading.classList.remove(
                'hidden'
            );
        }

        if (stateName === 'empty') {
            elements.versionCopyTreeEmpty.classList.remove(
                'hidden'
            );
        }

        if (stateName === 'error') {
            elements.versionCopyTreeError.textContent =
                errorMessage;

            elements.versionCopyTreeError.classList.remove(
                'hidden'
            );
        }

        if (stateName === 'tree') {
            elements.versionCopyTree.classList.remove(
                'hidden'
            );
        }

        const hasTree =
            state.sourceMaterialTree.length > 0;

        elements.versionCopySelectAll.disabled =
            !hasTree;

        elements.versionCopyClearAll.disabled =
            !hasTree;
    }

    function updateSourceSelectionCount() {
        const selectedCount =
            state.selectedSourceNodeIds.size;

        elements.versionCopySelectedCount.textContent =
            `${selectedCount} node materi dipilih`;
    }

    function getSourceNodeChildren(node) {
        if (
            Array.isArray(
                node.children_recursive
            )
        ) {
            return node.children_recursive;
        }

        if (
            Array.isArray(node.children)
        ) {
            return node.children;
        }

        return [];
    }

    function flattenSourceTree(nodes) {
        return nodes.flatMap(
            (node) => [
                node,
                ...flattenSourceTree(
                    getSourceNodeChildren(node)
                ),
            ]
        );
    }

    function findSourceNode(nodeId) {
        return flattenSourceTree(
            state.sourceMaterialTree
        ).find(
            (node) =>
                Number(node.id) ===
                Number(nodeId)
        );
    }

    function getSourceDescendantIds(node) {
        return getSourceNodeChildren(node)
            .flatMap(
                (child) => [
                    Number(child.id),
                    ...getSourceDescendantIds(
                        child
                    ),
                ]
            );
    }

    function getSourceNodeTypeLabel(type) {
        return {
            kategori: 'Kategori',
            bagian: 'Bagian',
            materi: 'Materi',
        }[type] || type || 'Node';
    }

    async function submitVersion(event) {
        event.preventDefault();

        const applicationId =
            elements.versionApplicationId.value ||
            state.activeApplicationId;

        const versionId =
            elements.versionId.value;

        const isEditing =
            versionId !== '';

        const payload = {
            version_number:
                elements.versionNumber.value.trim(),

            release_date:
                elements.versionReleaseDate.value ||
                null,

            release_notes:
                elements.versionReleaseNotes.value.trim() ||
                null,

            status:
                elements.versionStatus.value,

            is_current:
                elements.versionIsCurrent.checked,
        };

        const wantsCopy =
            !isEditing &&
            elements.versionCopyMaterials.checked;

        if (wantsCopy) {
            const sourceVersionId = Number(
                elements.versionSourceSelect.value
            );

            if (
                !Number.isInteger(sourceVersionId) ||
                sourceVersionId <= 0
            ) {
                showToast(
                    'Pilih versi sumber materi terlebih dahulu.',
                    'error'
                );

                elements.versionSourceSelect.focus();
                return;
            }

            const selectedNodeIds = [
                ...state.selectedSourceNodeIds,
            ];

            if (selectedNodeIds.length === 0) {
                showToast(
                    'Pilih minimal satu materi yang akan disalin.',
                    'error'
                );

                return;
            }

            payload.copy_materials = true;
            payload.source_version_id =
                sourceVersionId;

            payload.selected_node_ids =
                selectedNodeIds;

            state.pendingVersionPayload = {
                applicationId:
                    Number(applicationId),

                payload,
            };

            openVersionCopyConfirmation();
            return;
        }

        await saveVersion(
            applicationId,
            versionId,
            isEditing,
            payload
        );
    }

    function openVersionCopyConfirmation() {
        const application =
            findApplication(
                state.activeApplicationId
            );

        const sourceVersion =
            application?.versions?.find(
                (version) =>
                    Number(version.id) ===
                    Number(
                        elements.versionSourceSelect.value
                    )
            );

        const targetVersion =
            elements.versionNumber.value.trim();

        const selectedCount =
            state.selectedSourceNodeIds.size;

        elements.versionCopyConfirmationText.textContent =
            [
                `Apakah Anda yakin ingin membawa ${selectedCount} node materi dari`,
                `“${application?.name || 'Aplikasi'}” versi ${sourceVersion?.version_number || '-'}`,
                `ke versi baru ${targetVersion || '-'}?`,
                '',
                'Materi terpilih, seluruh child dari parent yang dipilih, parent yang diperlukan, serta isi kontennya akan dibuat sebagai data baru.',
            ].join('\\n');

        elements.versionCopyConfirmationModal.classList.remove(
            'hidden'
        );

        elements.versionCopyConfirmationModal.classList.add(
            'flex'
        );

        elements.versionCopyConfirmationModal.setAttribute(
            'aria-hidden',
            'false'
        );

        lockBodyScroll();
    }

    function closeVersionCopyConfirmation() {
        state.pendingVersionPayload = null;

        elements.versionCopyConfirmationModal.classList.add(
            'hidden'
        );

        elements.versionCopyConfirmationModal.classList.remove(
            'flex'
        );

        elements.versionCopyConfirmationModal.setAttribute(
            'aria-hidden',
            'true'
        );
    }

    async function confirmVersionCreationWithCopy() {
        const pending =
            state.pendingVersionPayload;

        if (!pending) {
            closeVersionCopyConfirmation();
            return;
        }

        const {
            applicationId,
            payload,
        } = pending;

        elements.versionCopyConfirmationSubmit.disabled =
            true;

        setButtonContent(
            elements.versionCopyConfirmationSubmit,
            'bi-arrow-repeat animate-spin',
            'Menyalin Materi...'
        );

        try {
            await saveVersion(
                applicationId,
                '',
                false,
                payload,
                true
            );
        } finally {
            elements.versionCopyConfirmationSubmit.disabled =
                false;

            setButtonContent(
                elements.versionCopyConfirmationSubmit,
                'bi-files',
                'Ya, Salin Materi dan Buat Versi'
            );
        }
    }

    async function saveVersion(
        applicationId,
        versionId,
        isEditing,
        payload,
        closeConfirmationAfter = false
    ) {
        const url = isEditing
            ? `${API_BASE_URL}/application-versions/${versionId}`
            : `${API_BASE_URL}/applications/${applicationId}/versions`;

        setButtonLoading(elements.versionSubmitButton, true);
        clearValidationErrors(elements.versionForm);

        try {
            const response = await fetch(url, {
                method:
                    isEditing
                        ? 'PUT'
                        : 'POST',

                headers: {
                    Accept: 'application/json',
                    'Content-Type': 'application/json',
                },

                body: JSON.stringify(payload),
            });

            const result =
                await parseResponse(response);

            if (closeConfirmationAfter) {
                closeVersionCopyConfirmation();
            }

            resetVersionForm();

            await fetchApplications(
                state.currentPage
            );

            const copySummary =
                result.copy_summary;

            const summaryMessage =
                copySummary
                    ? ` ${copySummary.copied_nodes ?? 0} node, ${copySummary.copied_content_blocks ?? 0} blok konten, dan ${copySummary.copied_files ?? 0} file berhasil disalin.`
                    : '';

            showToast(
                (
                    result.message ||
                    (
                        isEditing
                            ? 'Versi berhasil diperbarui.'
                            : 'Versi berhasil ditambahkan.'
                    )
                ) + summaryMessage,
                'success'
            );
        } catch (error) {
            showToast(
                error.message,
                'error'
            );
            
            if (error.validationErrors) {
                displayValidationErrors(error.validationErrors, elements.versionForm, 'version-');
            }
        } finally {
            setButtonLoading(elements.versionSubmitButton, false);
        }
    }

    function startVersionEdit(versionId) {
        const application =
            findApplication(
                state.activeApplicationId
            );

        const version =
            application?.versions?.find(
                (item) =>
                    Number(item.id) ===
                    Number(versionId)
            );

        if (!version) {
            showToast(
                'Data versi tidak ditemukan.',
                'error'
            );

            return;
        }

        elements.versionId.value =
            version.id;

        elements.versionApplicationId.value =
            application.id;

        elements.versionNumber.value =
            version.version_number || '';

        elements.versionReleaseDate.value =
            version.release_date
                ? String(
                    version.release_date
                ).slice(0, 10)
                : '';

        elements.versionStatus.value =
            version.status || 'draft';

        elements.versionReleaseNotes.value =
            version.release_notes || '';

        elements.versionIsCurrent.checked =
            Boolean(version.is_current);

        elements.versionCopySection.classList.add(
            'hidden'
        );

        elements.versionCopyMaterials.checked =
            false;

        elements.versionCopyOptions.classList.add(
            'hidden'
        );

        resetSourceMaterialSelection();

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

        elements.versionCopySection.classList.remove(
            'hidden'
        );

        elements.versionCopyMaterials.checked =
            false;

        elements.versionCopyOptions.classList.add(
            'hidden'
        );

        resetSourceMaterialSelection();

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

        setButtonLoading(elements.versionSubmitButton, false);
        clearValidationErrors(elements.versionForm);
    }

    async function deleteVersion(versionId, versionName) {
        const confirmed = await showDoubleConfirmModal(
            'Konfirmasi Hapus Versi',
            versionName || 'Versi ini'
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

            const result =
                await parseResponse(response);

            await fetchApplications(
                state.currentPage
            );

            showToast(
                result.message ||
                'Versi berhasil dihapus.',
                'success'
            );
        } catch (error) {
            showToast(
                error.message,
                'error'
            );
        }
    }

    function showApplicationLoading() {
        closeRowMenu();

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





    function previewSelectedImage() {
        const file =
            elements.applicationLogo.files[0];

        if (!file) {
            return;
        }

        const temporaryUrl =
            URL.createObjectURL(file);

        elements.applicationLogoPreview.src =
            temporaryUrl;

        elements.applicationLogoPreviewWrapper.classList.remove(
            'hidden'
        );

        elements.applicationRemoveLogo.checked =
            false;

        elements.applicationLogoPreview.onload = () => {
            URL.revokeObjectURL(temporaryUrl);
        };
    }

    function showExistingImage(url) {
        if (!url) {
            elements.applicationLogoPreview.src = '';

            elements.applicationLogoPreviewWrapper.classList.add(
                'hidden'
            );

            return;
        }

        elements.applicationLogoPreview.src = url;

        elements.applicationLogoPreviewWrapper.classList.remove(
            'hidden'
        );
    }

    function lockBodyScroll() {
        document.body.classList.add(
            'overflow-hidden'
        );
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

        if (
            !applicationModalOpen &&
            !versionModalOpen
        ) {
            document.body.classList.remove(
                'overflow-hidden'
            );
        }
    }

    async function parseResponse(response) {
        const result = await response
            .json()
            .catch(() => ({}));

        if (response.ok) {
            return result;
        }

        if (result.errors) {
            const error = new Error(
                result.message || 'Terdapat kesalahan pada data yang dimasukkan.'
            );
            error.validationErrors = result.errors;
            throw error;
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

    function normalizeSort(value) {
        const allowedSorts = [
            'latest',
            'oldest',
            'name_asc',
            'name_desc',
        ];

        return allowedSorts.includes(value)
            ? value
            : 'latest';
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
        }[status] ||
            status ||
            'Tidak diketahui';
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
        }[status] ||
            status ||
            'Tidak diketahui';
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



    function formatDate(value) {
        if (!value) {
            return 'Tanggal belum ditentukan';
        }

        const date = new Date(value);

        if (Number.isNaN(date.getTime())) {
            return 'Tanggal tidak valid';
        }

        return new Intl.DateTimeFormat(
            'id-ID',
            {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
            }
        ).format(date);
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
}