import 'bootstrap-icons/font/bootstrap-icons.css';
import { showToast, displayValidationErrors, clearValidationErrors, setButtonLoading, showDoubleConfirmModal } from '../utils.js';

const API_BASE_URL = '/api/admin';

const pageParameters = new URLSearchParams(
    window.location.search
);

const requestedAppIdentifier = pageParameters.get('app') || pageParameters.get('application_id');

document.addEventListener('DOMContentLoaded', () => {
    const state = {
        applications: [],
        selectedApplicationId: null,
        selectedVersionId: null,
        tree: [],
        flattenedNodes: [],
        formSortOrder: 0,
        reorderingNode: false,
    };

    let notificationTimeout = null;

    const elements = {


        applicationSearchWrapper:
            document.getElementById(
                'application-search-wrapper'
            ),

        applicationSearch:
            document.getElementById(
                'application-search'
            ),

        applicationId:
            document.getElementById(
                'application-id'
            ),

        applicationDropdownButton:
            document.getElementById(
                'application-dropdown-button'
            ),

        applicationDropdown:
            document.getElementById(
                'application-dropdown'
            ),

        applicationOptions:
            document.getElementById(
                'application-options'
            ),

        applicationEmpty:
            document.getElementById(
                'application-empty'
            ),

        applicationSearchError:
            document.getElementById(
                'application-search-error'
            ),

        applicationVersionSelect:
            document.getElementById(
                'application-version-select'
            ),

        applicationVersionHelp:
            document.getElementById(
                'application-version-help'
            ),

        applicationVersionError:
            document.getElementById(
                'application-version-error'
            ),

        selectedContext:
            document.getElementById(
                'selected-context'
            ),

        selectedContextText:
            document.getElementById(
                'selected-context-text'
            ),

        refreshTreeButton:
            document.getElementById(
                'refresh-tree-button'
            ),

        openRootNodeFormButton:
            document.getElementById(
                'open-root-node-form'
            ),

        emptyAddRootButton:
            document.getElementById(
                'empty-add-root-button'
            ),

        statTotalNodes:
            document.getElementById(
                'stat-total-nodes'
            ),

        statPublicNodes:
            document.getElementById(
                'stat-public-nodes'
            ),

        statPublishedNodes:
            document.getElementById(
                'stat-published-nodes'
            ),

        statMaterialNodes:
            document.getElementById(
                'stat-material-nodes'
            ),

        treeDescription:
            document.getElementById(
                'tree-description'
            ),

        treeInitial:
            document.getElementById(
                'tree-initial'
            ),

        treeLoading:
            document.getElementById(
                'tree-loading'
            ),

        treeEmpty:
            document.getElementById(
                'tree-empty'
            ),

        treeError:
            document.getElementById(
                'tree-error'
            ),

        treeErrorMessage:
            document.getElementById(
                'tree-error-message'
            ),

        treeRetryButton:
            document.getElementById(
                'tree-retry-button'
            ),

        tutorialTree:
            document.getElementById(
                'tutorial-tree'
            ),

        expandAllButton:
            document.getElementById(
                'expand-all-button'
            ),

        collapseAllButton:
            document.getElementById(
                'collapse-all-button'
            ),

        nodeFormModal:
            document.getElementById(
                'node-form-modal'
            ),

        nodeFormModalClose:
            document.getElementById(
                'node-form-modal-close'
            ),

        nodeForm:
            document.getElementById(
                'node-form'
            ),

        nodeFormTitle:
            document.getElementById(
                'node-form-title'
            ),

        nodeFormDescription:
            document.getElementById(
                'node-form-description'
            ),

        formApplicationName:
            document.getElementById(
                'form-application-name'
            ),

        formVersionName:
            document.getElementById(
                'form-version-name'
            ),

        nodeId:
            document.getElementById(
                'node-id'
            ),

        nodeParentId:
            document.getElementById(
                'node-parent-id'
            ),

        nodeTitle:
            document.getElementById(
                'node-title'
            ),

        nodeSlug:
            document.getElementById(
                'node-slug'
            ),

        nodeType:
            document.getElementById(
                'node-type'
            ),

        nodeTypeHelp:
            document.getElementById(
                'node-type-help'
            ),

        nodeTypeNotice:
            document.getElementById(
                'node-type-notice'
            ),

        nodeTypeNoticeText:
            document.getElementById(
                'node-type-notice-text'
            ),

        nodeStatus:
            document.getElementById(
                'node-status'
            ),

        nodeDescription:
            document.getElementById(
                'node-description'
            ),

        nodeIsPublic:
            document.getElementById(
                'node-is-public'
            ),

        nodeSubmitButton:
            document.getElementById(
                'node-submit-button'
            ),

        nodeCancelButton:
            document.getElementById(
                'node-cancel-button'
            ),

        parentInformation:
            document.getElementById(
                'parent-information'
            ),

        parentInformationTitle:
            document.getElementById(
                'parent-information-title'
            ),
    };

    const missingElements =
        Object.entries(elements)
            .filter(([, element]) => !element)
            .map(([name]) => name);

    if (missingElements.length > 0) {
        console.error(
            'Materi page: elemen Blade tidak ditemukan:',
            missingElements
        );

        return;
    }

    initializePage();

    async function initializePage() {
        bindEvents();
        resetNodeForm();
        showInitialState();

        await fetchApplications();
        await applyApplicationFromUrl();
    }

    function bindEvents() {
        elements.applicationSearch.addEventListener(
            'focus',
            () => {
                filterApplications(
                    elements.applicationSearch.value
                );

                openApplicationDropdown();
            }
        );

        elements.applicationSearch.addEventListener(
            'input',
            handleApplicationInput
        );

        elements.applicationSearch.addEventListener(
            'keydown',
            handleApplicationKeydown
        );

        elements.applicationSearch.addEventListener(
            'blur',
            () => {
                window.setTimeout(() => {
                    validateTypedApplication();
                }, 200);
            }
        );

        elements.applicationDropdownButton.addEventListener(
            'click',
            toggleApplicationDropdown
        );

        elements.applicationOptions.addEventListener(
            'click',
            handleApplicationOptionClick
        );

        elements.applicationVersionSelect.addEventListener(
            'change',
            handleVersionChange
        );

        elements.nodeStatus.addEventListener(
            'change',
            handleStatusChange
        );

        document.addEventListener(
            'click',
            (event) => {
                if (
                    !elements.applicationSearchWrapper.contains(
                        event.target
                    )
                ) {
                    closeApplicationDropdown();
                }
            }
        );

        elements.openRootNodeFormButton.addEventListener(
            'click',
            openCreateRootNodeModal
        );

        elements.emptyAddRootButton.addEventListener(
            'click',
            openCreateRootNodeModal
        );

        elements.refreshTreeButton.addEventListener(
            'click',
            fetchTree
        );

        elements.treeRetryButton.addEventListener(
            'click',
            fetchTree
        );

        elements.expandAllButton.addEventListener(
            'click',
            () => setAllNodesCollapsed(false)
        );

        elements.collapseAllButton.addEventListener(
            'click',
            () => setAllNodesCollapsed(true)
        );

        elements.tutorialTree.addEventListener(
            'click',
            handleTreeClick
        );

        elements.nodeForm.addEventListener(
            'submit',
            submitNode
        );

        elements.nodeFormModalClose.addEventListener(
            'click',
            closeNodeModal
        );

        elements.nodeCancelButton.addEventListener(
            'click',
            closeNodeModal
        );

        elements.nodeFormModal.addEventListener(
            'click',
            (event) => {
                if (
                    event.target ===
                    elements.nodeFormModal
                ) {
                    closeNodeModal();
                }
            }
        );

        document.addEventListener(
            'keydown',
            (event) => {
                if (
                    event.key === 'Escape' &&
                    !elements.nodeFormModal.classList.contains(
                        'hidden'
                    )
                ) {
                    closeNodeModal();
                }
            }
        );
    }

    function handleStatusChange() {
        updatePublicCheckboxState();
    }

    function updatePublicCheckboxState() {
        const isPublished =
            elements.nodeStatus.value ===
            'published';

        const publicSettingContainer =
            elements.nodeIsPublic.closest(
                'label'
            );

        elements.nodeIsPublic.disabled =
            !isPublished;

        if (!isPublished) {
            elements.nodeIsPublic.checked =
                false;
        }

        if (!publicSettingContainer) {
            return;
        }

        publicSettingContainer.classList.toggle(
            'opacity-60',
            !isPublished
        );

        publicSettingContainer.classList.toggle(
            'cursor-not-allowed',
            !isPublished
        );

        publicSettingContainer.classList.toggle(
            'bg-slate-50',
            !isPublished
        );
    }

    async function fetchApplications() {
        elements.applicationSearch.placeholder =
            'Memuat daftar aplikasi...';

        try {
            const response = await fetch(
                `${API_BASE_URL}/applications/options`,
                {
                    headers: {
                        Accept: 'application/json',
                    },
                }
            );

            const result =
                await parseResponse(response);

            state.applications =
                extractApplications(result);

            renderApplicationOptions(
                state.applications
            );

            elements.applicationSearch.placeholder =
                state.applications.length > 0
                    ? 'Ketik nama aplikasi...'
                    : 'Belum ada aplikasi tersedia';
        } catch (error) {
            state.applications = [];

            renderApplicationOptions([]);

            elements.applicationSearch.placeholder =
                'Gagal memuat aplikasi';

            showToast(
                error.message,
                'error'
            );
        }
    }

    async function applyApplicationFromUrl() {
        if (!requestedAppIdentifier) {
            return;
        }

        const application =
            state.applications.find(
                (item) =>
                    item.slug === requestedAppIdentifier ||
                    String(item.id) === requestedAppIdentifier
            );

        if (!application) {
            showToast(
                'Aplikasi yang dipilih tidak ditemukan.',
                'error'
            );

            return;
        }

        selectApplication(
            application.id,
            {
                selectLatestVersion: true,
            }
        );
    }

    function extractApplications(result) {
        if (Array.isArray(result?.data)) {
            return result.data;
        }

        if (
            Array.isArray(
                result?.data?.data
            )
        ) {
            return result.data.data;
        }

        if (
            Array.isArray(
                result?.applications
            )
        ) {
            return result.applications;
        }

        return [];
    }

    function renderApplicationOptions(
        applications
    ) {
        if (applications.length === 0) {
            elements.applicationOptions.innerHTML =
                '';

            elements.applicationEmpty.classList.remove(
                'hidden'
            );

            return;
        }

        elements.applicationEmpty.classList.add(
            'hidden'
        );

        elements.applicationOptions.innerHTML =
            applications
                .map((application) => {
                    const versionCount =
                        getApplicationVersions(
                            application
                        ).length;

                    return `
                        <button
                            type="button"
                            class="application-option flex w-full items-center justify-between gap-4 px-4 py-3 text-left text-sm transition hover:bg-slate-100"
                            data-id="${application.id}"
                        >
                            <span class="min-w-0">
                                <span class="block truncate font-semibold text-slate-800">
                                    ${escapeHtml(
                                        application.name
                                    )}
                                </span>

                                <span class="mt-1 block text-xs text-slate-500">
                                    ${versionCount} versi tersedia
                                </span>
                            </span>

                            <i class="bi bi-chevron-right shrink-0 text-slate-400"></i>
                        </button>
                    `;
                })
                .join('');
    }

    function filterApplications(keyword) {
        const normalizedKeyword =
            String(keyword)
                .trim()
                .toLowerCase();

        if (!normalizedKeyword) {
            renderApplicationOptions(
                state.applications
            );

            return;
        }

        const filteredApplications =
            state.applications.filter(
                (application) =>
                    String(application.name)
                        .toLowerCase()
                        .includes(
                            normalizedKeyword
                        )
            );

        renderApplicationOptions(
            filteredApplications
        );
    }

    function handleApplicationInput(event) {
        elements.applicationSearchError.classList.add(
            'hidden'
        );

        filterApplications(
            event.target.value
        );

        openApplicationDropdown();

        const selectedApplication =
            getSelectedApplication();

        if (
            selectedApplication &&
            event.target.value !==
                selectedApplication.name
        ) {
            clearSelectedApplication(false);
        }

        if (
            event.target.value.trim() === ''
        ) {
            clearSelectedApplication(true);
            openApplicationDropdown();
        }
    }

    function handleApplicationKeydown(event) {
        if (event.key === 'Enter') {
            event.preventDefault();

            const firstOption =
                elements.applicationOptions.querySelector(
                    '.application-option'
                );

            if (firstOption) {
                selectApplication(
                    firstOption.dataset.id
                );
            } else {
                validateTypedApplication();
            }
        }

        if (event.key === 'Escape') {
            closeApplicationDropdown();
        }
    }

    function toggleApplicationDropdown() {
        const isHidden =
            elements.applicationDropdown.classList.contains(
                'hidden'
            );

        if (isHidden) {
            filterApplications(
                elements.applicationSearch.value
            );

            openApplicationDropdown();
            elements.applicationSearch.focus();
        } else {
            closeApplicationDropdown();
        }
    }

    function handleApplicationOptionClick(
        event
    ) {
        const option =
            event.target.closest(
                '.application-option'
            );

        if (!option) {
            return;
        }

        selectApplication(
            option.dataset.id
        );
    }

    function openApplicationDropdown() {
        elements.applicationDropdown.classList.remove(
            'hidden'
        );
    }

    function closeApplicationDropdown() {
        elements.applicationDropdown.classList.add(
            'hidden'
        );
    }

    function selectApplication(
        applicationId,
        options = {}
    ) {
        const application =
            state.applications.find(
                (item) =>
                    Number(item.id) ===
                    Number(applicationId)
            );

        if (!application) {
            return;
        }

        const {
            selectLatestVersion = false,
        } = options;

        state.selectedApplicationId =
            Number(application.id);

        state.selectedVersionId = null;

        elements.applicationId.value =
            String(application.id);

        elements.applicationSearch.value =
            application.name;

        elements.applicationSearchError.classList.add(
            'hidden'
        );

        elements.applicationVersionError.classList.add(
            'hidden'
        );

        closeApplicationDropdown();

        populateVersionSelector(
            application
        );

        clearTreeData();
        updateContextDisplay();
        updateActionAvailability();
        showInitialState();

        if (selectLatestVersion) {
            selectLatestApplicationVersion(
                application
            );
        }
    }

    function populateVersionSelector(
        application
    ) {
        const versions =
            getApplicationVersions(
                application
            );

        elements.applicationVersionSelect.disabled =
            versions.length === 0;

        if (versions.length === 0) {
            elements.applicationVersionSelect.innerHTML = `
                <option value="">
                    Belum ada versi aplikasi
                </option>
            `;

            elements.applicationVersionHelp.textContent =
                'Tambahkan versi aplikasi terlebih dahulu.';

            return;
        }

        elements.applicationVersionSelect.innerHTML =
            [
                `
                    <option value="">
                        Pilih versi aplikasi
                    </option>
                `,

                ...versions.map(
                    (version) => `
                        <option value="${version.id}">
                            ${escapeHtml(
                                getVersionLabel(
                                    version
                                )
                            )}
                        </option>
                    `
                ),
            ].join('');

        elements.applicationVersionHelp.textContent =
            `${versions.length} versi tersedia untuk aplikasi ini.`;
    }

    function selectLatestApplicationVersion(
        application
    ) {
        const latestVersion =
            getLatestApplicationVersion(
                application
            );

        if (!latestVersion) {
            showToast(
                `Aplikasi "${application.name}" belum memiliki versi.`,
                'error'
            );

            return;
        }

        state.selectedVersionId =
            Number(latestVersion.id);

        elements.applicationVersionSelect.value =
            String(latestVersion.id);

        elements.applicationVersionError.classList.add(
            'hidden'
        );

        updateContextDisplay();
        updateActionAvailability();

        fetchTree();
    }

    function getLatestApplicationVersion(
        application
    ) {
        const versions =
            getApplicationVersions(
                application
            );

        if (versions.length === 0) {
            return null;
        }

        const currentVersion =
            versions.find(
                (version) =>
                    Boolean(version.is_current)
            );

        if (currentVersion) {
            return currentVersion;
        }

        return [...versions].sort(
            compareVersionsNewestFirst
        )[0];
    }

    function compareVersionsNewestFirst(
        firstVersion,
        secondVersion
    ) {
        const firstDate =
            getVersionTimestamp(
                firstVersion
            );

        const secondDate =
            getVersionTimestamp(
                secondVersion
            );

        if (firstDate !== secondDate) {
            return secondDate - firstDate;
        }

        return (
            Number(secondVersion.id) -
            Number(firstVersion.id)
        );
    }

    function getVersionTimestamp(
        version
    ) {
        const dateValue =
            version.release_date ||
            version.created_at ||
            null;

        if (!dateValue) {
            return 0;
        }

        const timestamp =
            new Date(dateValue).getTime();

        return Number.isNaN(timestamp)
            ? 0
            : timestamp;
    }

    function handleVersionChange(event) {
        elements.applicationVersionError.classList.add(
            'hidden'
        );

        const versionId =
            Number(event.target.value);

        if (
            !Number.isInteger(versionId) ||
            versionId <= 0
        ) {
            state.selectedVersionId = null;

            clearTreeData();
            updateContextDisplay();
            updateActionAvailability();
            showInitialState();

            return;
        }

        const version =
            getSelectedApplicationVersions().find(
                (item) =>
                    Number(item.id) ===
                    versionId
            );

        if (!version) {
            state.selectedVersionId = null;

            elements.applicationVersionError.classList.remove(
                'hidden'
            );

            updateActionAvailability();

            return;
        }

        state.selectedVersionId =
            versionId;

        updateContextDisplay();
        updateActionAvailability();

        fetchTree();
    }

    function clearSelectedApplication(
        clearInput = true
    ) {
        state.selectedApplicationId = null;
        state.selectedVersionId = null;

        elements.applicationId.value = '';

        if (clearInput) {
            elements.applicationSearch.value =
                '';
        }

        elements.applicationVersionSelect.disabled =
            true;

        elements.applicationVersionSelect.innerHTML = `
            <option value="">
                Pilih aplikasi terlebih dahulu
            </option>
        `;

        elements.applicationVersionHelp.textContent =
            'Struktur materi akan dibedakan berdasarkan versi.';

        elements.applicationSearchError.classList.add(
            'hidden'
        );

        elements.applicationVersionError.classList.add(
            'hidden'
        );

        clearTreeData();
        updateContextDisplay();
        updateActionAvailability();
        showInitialState();
    }

    function validateTypedApplication() {
        const value =
            elements.applicationSearch.value
                .trim()
                .toLowerCase();

        if (!value) {
            clearSelectedApplication(true);

            return;
        }

        const exactApplication =
            state.applications.find(
                (application) =>
                    String(application.name)
                        .trim()
                        .toLowerCase() ===
                    value
            );

        if (exactApplication) {
            if (
                Number(
                    state.selectedApplicationId
                ) !==
                Number(
                    exactApplication.id
                )
            ) {
                selectApplication(
                    exactApplication.id
                );
            }

            return;
        }

        elements.applicationSearchError.classList.remove(
            'hidden'
        );
    }

    async function fetchTree() {
        if (!hasCompleteSelection()) {
            showToast(
                'Pilih aplikasi dan versi aplikasi terlebih dahulu.',
                'error'
            );

            showInitialState();

            return;
        }

        showTreeLoading();

        const query =
            new URLSearchParams({
                application_id:
                    String(
                        state.selectedApplicationId
                    ),

                application_version_id:
                    String(
                        state.selectedVersionId
                    ),
            });

        try {
            const response = await fetch(
                `${API_BASE_URL}/tutorial-nodes/tree?${query.toString()}`,
                {
                    headers: {
                        Accept: 'application/json',
                    },
                }
            );

            const result =
                await parseResponse(response);

            state.tree =
                Array.isArray(result.data)
                    ? result.data
                    : [];

            state.flattenedNodes =
                flattenTree(
                    state.tree
                );

            renderTree();
            renderStatistics();
        } catch (error) {
            showTreeError(
                error.message
            );
        }
    }

    function renderTree() {
        hideTreeStates();

        const application =
            getSelectedApplication();

        const version =
            getSelectedVersion();

        elements.treeDescription.textContent =
            application && version
                ? `Struktur materi ${application.name} versi ${getVersionLabel(version)}.`
                : 'Struktur materi aplikasi.';

        if (state.tree.length === 0) {
            elements.tutorialTree.innerHTML =
                '';

            elements.tutorialTree.classList.add(
                'hidden'
            );

            elements.treeEmpty.classList.remove(
                'hidden'
            );

            setTreeActionAvailability(false);

            return;
        }

        elements.tutorialTree.innerHTML =
            state.tree
                .map(
                    (node, index) =>
                        createNodeHtml(
                            node,
                            state.tree,
                            index
                        )
                )
                .join('');

        elements.tutorialTree.classList.remove(
            'hidden'
        );

        setTreeActionAvailability(true);
    }

    function createNodeHtml(
        node,
        siblings,
        siblingIndex
    ) {
        const children =
            getNodeChildren(node);

        const hasChildren =
            children.length > 0;

        const isFirst =
            siblingIndex === 0;

        const isLast =
            siblingIndex ===
            siblings.length - 1;

        const descendantCount =
            countDescendants(node);

        const childrenHtml =
            hasChildren
                ? `
                    <div
                        data-node-children="${node.id}"
                        class="ml-5 space-y-3 border-l border-slate-200 pl-4"
                    >
                        ${children
                            .map(
                                (child, index) =>
                                    createNodeHtml(
                                        child,
                                        children,
                                        index
                                    )
                            )
                            .join('')}
                    </div>
                `
                : '';

        const toggleButton =
            hasChildren
                ? `
                    <button
                        type="button"
                        class="node-toggle-button flex h-8 w-8 shrink-0 items-center justify-center border border-slate-300 text-slate-600 hover:bg-white"
                        data-id="${node.id}"
                        aria-label="Buka atau tutup child"
                    >
                        <i class="bi bi-chevron-down"></i>
                    </button>
                `
                : `
                    <div class="h-8 w-8 shrink-0"></div>
                `;

        return `
            <div
                class="tutorial-node space-y-3"
                data-node-id="${node.id}"
            >
                <article class="border border-slate-200 bg-white p-4 transition hover:bg-slate-50">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        <div class="flex min-w-0 items-start gap-3">
                            ${toggleButton}

                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <h4 class="font-bold text-slate-950">
                                        ${escapeHtml(
                                            node.title
                                        )}
                                    </h4>

                                    <span class="border px-2 py-1 text-xs font-semibold ${getNodeTypeClass(
                                        node.node_type
                                    )}">
                                        ${escapeHtml(
                                            getNodeTypeLabel(
                                                node.node_type
                                            )
                                        )}
                                    </span>

                                    <span class="border px-2 py-1 text-xs font-semibold ${getNodeStatusClass(
                                        node.status
                                    )}">
                                        ${escapeHtml(
                                            getNodeStatusLabel(
                                                node.status
                                            )
                                        )}
                                    </span>

                                    ${
                                        node.is_public
                                            ? `
                                                <span class="border border-emerald-200 bg-emerald-50 px-2 py-1 text-xs font-semibold text-emerald-700">
                                                    Publik
                                                </span>
                                            `
                                            : ''
                                    }
                                </div>

                                <p class="mt-2 text-sm leading-6 text-slate-500">
                                    ${escapeHtml(
                                        node.description ||
                                        'Belum ada deskripsi.'
                                    )}
                                </p>

                                <div class="mt-2 flex flex-wrap gap-4 text-xs text-slate-400">
                                    <span>
                                        Posisi: ${siblingIndex + 1}
                                    </span>

                                    <span>
                                        ${children.length} child langsung
                                    </span>

                                    ${
                                        descendantCount > 0
                                            ? `
                                                <span>
                                                    ${descendantCount} total turunan
                                                </span>
                                            `
                                            : ''
                                    }
                                </div>
                            </div>
                        </div>

                        <div class="flex shrink-0 flex-wrap items-center gap-2 pl-11 lg:pl-0">
                            <div class="flex overflow-hidden border border-slate-300">
                                <button
                                    type="button"
                                    class="node-move-up-button flex h-9 w-9 items-center justify-center text-slate-600 transition hover:bg-slate-100 disabled:cursor-not-allowed disabled:opacity-30"
                                    data-id="${node.id}"
                                    data-boundary-disabled="${isFirst ? 'true' : 'false'}"
                                    ${isFirst ? 'disabled' : ''}
                                    aria-label="Pindahkan materi ke atas"
                                    title="Pindahkan ke atas"
                                >
                                    <i class="bi bi-caret-up-fill"></i>
                                </button>

                                <button
                                    type="button"
                                    class="node-move-down-button flex h-9 w-9 items-center justify-center border-l border-slate-300 text-slate-600 transition hover:bg-slate-100 disabled:cursor-not-allowed disabled:opacity-30"
                                    data-id="${node.id}"
                                    data-boundary-disabled="${isLast ? 'true' : 'false'}"
                                    ${isLast ? 'disabled' : ''}
                                    aria-label="Pindahkan materi ke bawah"
                                    title="Pindahkan ke bawah"
                                >
                                    <i class="bi bi-caret-down-fill"></i>
                                </button>
                            </div>

                            <button
                                type="button"
                                class="node-add-child-button inline-flex items-center gap-2 border border-emerald-200 px-3 py-2 text-sm font-semibold text-emerald-700 hover:bg-emerald-50"
                                data-id="${node.id}"
                            >
                                <i class="bi bi-plus-lg"></i>
                                Child
                            </button>

                            ${
                                node.node_type ===
                                'materi'
                                    ? `
                                        <a
                                            href="/admin/materi/${node.id}/content"
                                            class="inline-flex items-center gap-2 border border-violet-200 px-3 py-2 text-sm font-semibold text-violet-700 hover:bg-violet-50"
                                        >
                                            <i class="bi bi-journal-richtext"></i>
                                            Kelola Isi
                                        </a>
                                    `
                                    : ''
                            }

                            <button
                                type="button"
                                class="node-edit-button flex h-9 w-9 items-center justify-center border border-blue-200 text-blue-800 hover:bg-blue-50"
                                data-id="${node.id}"
                                aria-label="Ubah materi"
                                title="Ubah materi"
                            >
                                <i class="bi bi-pencil-square"></i>
                            </button>

                            <button
                                type="button"
                                class="node-delete-button flex h-9 w-9 items-center justify-center border border-red-200 text-red-600 hover:bg-red-50 disabled:cursor-not-allowed disabled:opacity-50"
                                data-id="${node.id}"
                                aria-label="Hapus materi"
                                title="${
                                    descendantCount > 0
                                        ? `Hapus bersama ${descendantCount} turunannya`
                                        : 'Hapus materi'
                                }"
                            >
                                <i class="bi bi-trash3"></i>
                            </button>
                        </div>
                    </div>
                </article>

                ${childrenHtml}
            </div>
        `;
    }

    function handleTreeClick(event) {
        const toggleButton =
            event.target.closest(
                '.node-toggle-button'
            );

        const moveUpButton =
            event.target.closest(
                '.node-move-up-button'
            );

        const moveDownButton =
            event.target.closest(
                '.node-move-down-button'
            );

        const addChildButton =
            event.target.closest(
                '.node-add-child-button'
            );

        const editButton =
            event.target.closest(
                '.node-edit-button'
            );

        const deleteButton =
            event.target.closest(
                '.node-delete-button'
            );

        if (toggleButton) {
            toggleNode(
                toggleButton.dataset.id
            );

            return;
        }

        if (moveUpButton) {
            moveNode(
                Number(
                    moveUpButton.dataset.id
                ),
                -1
            );

            return;
        }

        if (moveDownButton) {
            moveNode(
                Number(
                    moveDownButton.dataset.id
                ),
                1
            );

            return;
        }

        if (addChildButton) {
            openCreateChildNodeModal(
                addChildButton.dataset.id
            );

            return;
        }

        if (editButton) {
            openEditNodeModal(
                editButton.dataset.id
            );

            return;
        }

        if (deleteButton) {
            deleteNode(
                Number(
                    deleteButton.dataset.id
                )
            );
        }
    }

    async function moveNode(
        nodeId,
        direction
    ) {
        if (state.reorderingNode) {
            return;
        }

        const siblings =
            findSiblingGroup(
                state.tree,
                nodeId
            );

        if (!siblings) {
            showToast(
                'Kelompok materi tidak ditemukan.',
                'error'
            );

            return;
        }

        const currentIndex =
            siblings.findIndex(
                (node) =>
                    Number(node.id) ===
                    Number(nodeId)
            );

        const targetIndex =
            currentIndex + direction;

        if (
            currentIndex < 0 ||
            targetIndex < 0 ||
            targetIndex >= siblings.length
        ) {
            return;
        }

        const reorderedSiblings = [
            ...siblings,
        ];

        [
            reorderedSiblings[currentIndex],
            reorderedSiblings[targetIndex],
        ] = [
            reorderedSiblings[targetIndex],
            reorderedSiblings[currentIndex],
        ];

        state.reorderingNode = true;

        setReorderButtonsDisabled(true);

        try {
            for (
                let index = 0;
                index < reorderedSiblings.length;
                index += 1
            ) {
                const sibling =
                    reorderedSiblings[index];

                const response = await fetch(
                    `${API_BASE_URL}/tutorial-nodes/${sibling.id}`,
                    {
                        method: 'PUT',

                        headers: {
                            Accept:
                                'application/json',

                            'Content-Type':
                                'application/json',
                        },

                        body: JSON.stringify({
                            sort_order: index,
                        }),
                    }
                );

                await parseResponse(response);
            }

            await fetchTree();

            showToast(
                direction < 0
                    ? 'Materi berhasil dipindahkan ke atas.'
                    : 'Materi berhasil dipindahkan ke bawah.',
                'success'
            );
        } catch (error) {
            await fetchTree();

            showToast(
                `Urutan gagal diperbarui: ${error.message}`,
                'error'
            );
        } finally {
            state.reorderingNode = false;

            restoreReorderButtonStates();
        }
    }

    function findSiblingGroup(
        nodes,
        nodeId
    ) {
        if (
            nodes.some(
                (node) =>
                    Number(node.id) ===
                    Number(nodeId)
            )
        ) {
            return nodes;
        }

        for (const node of nodes) {
            const children =
                getNodeChildren(node);

            if (children.length === 0) {
                continue;
            }

            const result =
                findSiblingGroup(
                    children,
                    nodeId
                );

            if (result) {
                return result;
            }
        }

        return null;
    }

    function setReorderButtonsDisabled(
        disabled
    ) {
        document
            .querySelectorAll(
                '.node-move-up-button, .node-move-down-button'
            )
            .forEach((button) => {
                button.disabled =
                    disabled;
            });
    }

    function restoreReorderButtonStates() {
        document
            .querySelectorAll(
                '.node-move-up-button, .node-move-down-button'
            )
            .forEach((button) => {
                button.disabled =
                    button.dataset
                        .boundaryDisabled ===
                    'true';
            });
    }

    function resetNodeTypeOptions() {
        Array.from(
            elements.nodeType.options
        ).forEach((option) => {
            option.disabled = false;
        });

        elements.nodeType.disabled =
            false;

        elements.nodeTypeNotice.classList.add(
            'hidden'
        );

        elements.nodeTypeNoticeText.textContent =
            '';

        elements.nodeTypeHelp.textContent =
            'Pilih jenis materi sesuai posisi dalam struktur.';

        elements.nodeTypeHelp.className =
            'mt-2 text-xs text-slate-500';
    }

    function configureRootNodeType() {
        resetNodeTypeOptions();

        elements.nodeType.value =
            'kategori';

        elements.nodeType.disabled =
            true;

        elements.nodeTypeHelp.textContent =
            'Jenis materi ditentukan otomatis untuk materi utama.';

        elements.nodeTypeNoticeText.textContent =
            'Materi utama merupakan tingkat paling atas pada struktur sehingga wajib menggunakan jenis Kategori. Bagian dan Materi hanya dapat ditambahkan sebagai child.';

        elements.nodeTypeNotice.classList.remove(
            'hidden'
        );
    }

    function configureChildNodeType() {
        resetNodeTypeOptions();

        const categoryOption =
            elements.nodeType.querySelector(
                'option[value="kategori"]'
            );

        if (categoryOption) {
            categoryOption.disabled =
                true;
        }

        elements.nodeType.value =
            'bagian';

        elements.nodeTypeHelp.textContent =
            'Child hanya dapat menggunakan jenis Bagian atau Materi.';

        elements.nodeTypeNoticeText.textContent =
            'Kategori hanya boleh digunakan sebagai materi utama. Untuk child, pilih Bagian atau Materi.';

        elements.nodeTypeNotice.classList.remove(
            'hidden'
        );
    }

    function lockNodeTypeBecauseOfChildren(
        childCount
    ) {
        elements.nodeType.disabled =
            true;

        elements.nodeTypeHelp.textContent =
            'Jenis materi tidak dapat diubah.';

        elements.nodeTypeNoticeText.textContent =
            `Node ini memiliki ${childCount} child langsung. Jenis materi dikunci untuk menjaga konsistensi hierarki.`;

        elements.nodeTypeNotice.classList.remove(
            'hidden'
        );
    }

    function openCreateRootNodeModal() {
        if (!hasCompleteSelection()) {
            showToast(
                'Pilih aplikasi dan versi aplikasi terlebih dahulu.',
                'error'
            );

            return;
        }

        resetNodeForm();
        populateFormContext();
        configureRootNodeType();

        state.formSortOrder =
            getNextSortOrder(
                state.tree
            );

        elements.nodeFormTitle.textContent =
            'Tambah Materi Utama';

        elements.nodeFormDescription.textContent =
            'Materi utama wajib berupa Kategori dan akan ditempatkan pada urutan terakhir.';

        openNodeModal();
    }

    function openCreateChildNodeModal(
        parentId
    ) {
        if (!hasCompleteSelection()) {
            showToast(
                'Pilih aplikasi dan versi aplikasi terlebih dahulu.',
                'error'
            );

            return;
        }

        const parent =
            findNode(parentId);

        if (!parent) {
            showToast(
                'Parent materi tidak ditemukan.',
                'error'
            );

            return;
        }

        if (
            Number(
                parent.application_id
            ) !==
                Number(
                    state.selectedApplicationId
                ) ||
            Number(
                parent.application_version_id
            ) !==
                Number(
                    state.selectedVersionId
                )
        ) {
            showToast(
                'Parent tidak berasal dari aplikasi dan versi yang sedang dipilih.',
                'error'
            );

            return;
        }

        resetNodeForm();
        populateFormContext();
        configureChildNodeType();

        const children =
            getNodeChildren(parent);

        state.formSortOrder =
            getNextSortOrder(
                children
            );

        elements.nodeParentId.value =
            String(parent.id);

        elements.parentInformationTitle.textContent =
            parent.title;

        elements.parentInformation.classList.remove(
            'hidden'
        );

        elements.nodeFormTitle.textContent =
            'Tambah Child Materi';

        elements.nodeFormDescription.textContent =
            `Tambahkan Bagian atau Materi di bawah "${parent.title}".`;

        openNodeModal();
    }

    function openEditNodeModal(nodeId) {
        const node =
            findNode(nodeId);

        if (!node) {
            showToast(
                'Materi tidak ditemukan.',
                'error'
            );

            return;
        }

        resetNodeForm();
        populateFormContext();
        resetNodeTypeOptions();

        state.formSortOrder =
            Number(
                node.sort_order ?? 0
            );

        elements.nodeId.value =
            String(node.id);

        elements.nodeParentId.value =
            node.parent_id
                ? String(node.parent_id)
                : '';

        elements.nodeTitle.value =
            node.title || '';

        elements.nodeSlug.value =
            node.slug || '';

        elements.nodeType.value =
            node.node_type ||
            'materi';

        elements.nodeStatus.value =
            node.status ||
            'draft';

        elements.nodeDescription.value =
            node.description ||
            '';

        elements.nodeIsPublic.checked =
            Boolean(node.is_public);

        updatePublicCheckboxState();

        const children =
            getNodeChildren(node);

        const hasChildren =
            children.length > 0;

        const isRootNode =
            node.parent_id === null ||
            node.parent_id === undefined;

        if (isRootNode) {
            configureRootNodeType();
        } else {
            const categoryOption =
                elements.nodeType.querySelector(
                    'option[value="kategori"]'
                );

            if (categoryOption) {
                categoryOption.disabled =
                    true;
            }

            elements.nodeType.value =
                node.node_type;

            if (hasChildren) {
                lockNodeTypeBecauseOfChildren(
                    children.length
                );
            } else {
                elements.nodeTypeHelp.textContent =
                    'Jenis child dapat diubah menjadi Bagian atau Materi.';

                elements.nodeTypeNoticeText.textContent =
                    'Kategori tidak dapat digunakan sebagai child.';

                elements.nodeTypeNotice.classList.remove(
                    'hidden'
                );
            }
        }

        if (node.parent_id) {
            const parent =
                findNode(
                    node.parent_id
                );

            elements.parentInformationTitle.textContent =
                parent?.title ||
                'Parent materi';

            elements.parentInformation.classList.remove(
                'hidden'
            );
        }

        elements.nodeFormTitle.textContent =
            'Ubah Materi';

        if (isRootNode) {
            elements.nodeFormDescription.textContent =
                'Perbarui informasi materi utama. Jenis materi wajib tetap Kategori.';
        } else if (hasChildren) {
            elements.nodeFormDescription.textContent =
                'Perbarui informasi materi. Jenis materi dikunci karena node memiliki child.';
        } else {
            elements.nodeFormDescription.textContent =
                'Perbarui informasi materi. Jenis dapat diubah menjadi Bagian atau Materi.';
        }

        setButtonContent(
            elements.nodeSubmitButton,
            'bi-pencil-square',
            'Perbarui Materi'
        );

        openNodeModal();
    }

    function getNextSortOrder(nodes) {
        if (
            !Array.isArray(nodes) ||
            nodes.length === 0
        ) {
            return 0;
        }

        const highestSortOrder =
            Math.max(
                ...nodes.map(
                    (node) =>
                        Number(
                            node.sort_order ??
                            0
                        )
                )
            );

        return highestSortOrder + 1;
    }

    function populateFormContext() {
        const application =
            getSelectedApplication();

        const version =
            getSelectedVersion();

        elements.formApplicationName.textContent =
            application?.name ||
            'Aplikasi tidak diketahui';

        elements.formVersionName.textContent =
            version
                ? getVersionLabel(version)
                : 'Versi tidak diketahui';
    }

    async function submitNode(event) {
        event.preventDefault();

        if (!hasCompleteSelection()) {
            showToast(
                'Pilih aplikasi dan versi aplikasi terlebih dahulu.',
                'error'
            );

            return;
        }

        const nodeId =
            elements.nodeId.value;

        const isEditing =
            nodeId !== '';

        const title =
            elements.nodeTitle.value.trim();

        if (!title) {
            showToast(
                'Judul materi wajib diisi.',
                'error'
            );

            elements.nodeTitle.focus();

            return;
        }

        const parentId =
            elements.nodeParentId.value
                ? Number(
                    elements.nodeParentId.value
                )
                : null;

        const nodeType =
            parentId === null
                ? 'kategori'
                : elements.nodeType.value;

        if (
            parentId !== null &&
            nodeType === 'kategori'
        ) {
            showToast(
                'Kategori hanya dapat digunakan sebagai materi utama.',
                'error'
            );

            return;
        }

        const isPublished =
            elements.nodeStatus.value ===
            'published';

        const payload = {
            application_id:
                Number(
                    state.selectedApplicationId
                ),

            application_version_id:
                Number(
                    state.selectedVersionId
                ),

            parent_id:
                parentId,

            title,

            slug:
                elements.nodeSlug.value.trim() ||
                null,

            description:
                elements.nodeDescription.value.trim() ||
                null,

            node_type:
                nodeType,

            sort_order:
                Number(
                    state.formSortOrder
                ),

            status:
                elements.nodeStatus.value,

            is_public:
                isPublished
                    ? elements.nodeIsPublic.checked
                    : false,
        };

        const url =
            isEditing
                ? `${API_BASE_URL}/tutorial-nodes/${nodeId}`
                : `${API_BASE_URL}/tutorial-nodes`;

        setButtonLoading(elements.nodeSubmitButton, true);
        clearValidationErrors(elements.nodeForm);

        try {
            const response = await fetch(
                url,
                {
                    method:
                        isEditing
                            ? 'PUT'
                            : 'POST',

                    headers: {
                        Accept:
                            'application/json',

                        'Content-Type':
                            'application/json',
                    },

                    body: JSON.stringify(
                        payload
                    ),
                }
            );

            const result =
                await parseResponse(response);

            closeNodeModal();

            await fetchTree();

            showToast(
                result.message ||
                'Materi berhasil disimpan.',
                'success'
            );
        } catch (error) {
            showToast(
                error.message,
                'error'
            );
            
            if (error.validationErrors) {
                displayValidationErrors(error.validationErrors, elements.nodeForm, 'node-');
            }
        } finally {
            setButtonLoading(elements.nodeSubmitButton, false);
        }
    }

    async function deleteNode(nodeId) {
        const node =
            findNode(nodeId);

        if (!node) {
            showToast(
                'Materi tidak ditemukan.',
                'error'
            );

            return;
        }

        const descendantCount =
            countDescendants(node);

        const confirmed = await showDoubleConfirmModal(
            descendantCount > 0 
                ? 'Konfirmasi Hapus Materi dan Turunannya' 
                : 'Konfirmasi Hapus Materi',
            node.title
        );

        if (!confirmed) {
            return;
        }

        setDeleteButtonLoading(
            nodeId,
            true
        );

        try {
            const response = await fetch(
                `${API_BASE_URL}/tutorial-nodes/${nodeId}`,
                {
                    method: 'DELETE',

                    headers: {
                        Accept:
                            'application/json',
                    },
                }
            );

            const result =
                await parseResponse(response);

            await fetchTree();

            showToast(
                descendantCount > 0
                    ? result.message ||
                        `Materi beserta ${descendantCount} turunannya berhasil dihapus.`
                    : result.message ||
                        'Materi berhasil dihapus.',
                'success'
            );
        } catch (error) {
            showToast(
                error.message,
                'error'
            );

            setDeleteButtonLoading(
                nodeId,
                false
            );
        }
    }

    function setDeleteButtonLoading(
        nodeId,
        isLoading
    ) {
        const button =
            elements.tutorialTree.querySelector(
                `.node-delete-button[data-id="${nodeId}"]`
            );

        if (!button) {
            return;
        }

        button.disabled =
            isLoading;

        button.innerHTML =
            isLoading
                ? `
                    <i class="bi bi-arrow-repeat animate-spin"></i>
                `
                : `
                    <i class="bi bi-trash3"></i>
                `;
    }

    function countDescendants(node) {
        const children =
            getNodeChildren(node);

        return children.reduce(
            (total, child) =>
                total +
                1 +
                countDescendants(child),
            0
        );
    }

    function openNodeModal() {
        elements.nodeFormModal.classList.remove(
            'hidden'
        );

        elements.nodeFormModal.classList.add(
            'flex'
        );

        elements.nodeFormModal.setAttribute(
            'aria-hidden',
            'false'
        );

        document.body.classList.add(
            'overflow-hidden'
        );

        window.setTimeout(() => {
            elements.nodeTitle.focus();
        }, 50);
    }

    function closeNodeModal() {
        elements.nodeFormModal.classList.add(
            'hidden'
        );

        elements.nodeFormModal.classList.remove(
            'flex'
        );

        elements.nodeFormModal.setAttribute(
            'aria-hidden',
            'true'
        );

        document.body.classList.remove(
            'overflow-hidden'
        );

        resetNodeForm();
    }

    function resetNodeForm() {
        elements.nodeForm.reset();

        state.formSortOrder = 0;

        elements.nodeId.value =
            '';

        elements.nodeParentId.value =
            '';

        elements.nodeStatus.value =
            'draft';

        elements.nodeIsPublic.checked =
            false;

        updatePublicCheckboxState();

        resetNodeTypeOptions();

        elements.nodeType.value =
            'materi';

        elements.parentInformation.classList.add(
            'hidden'
        );

        elements.parentInformationTitle.textContent =
            '';

        elements.formApplicationName.textContent =
            '';

        elements.formVersionName.textContent =
            '';

        elements.nodeFormTitle.textContent =
            'Tambah Materi';

        elements.nodeFormDescription.textContent =
            'Isi informasi materi.';

        setButtonContent(
            elements.nodeSubmitButton,
            'bi-plus-lg',
            'Simpan Materi'
        );

        setButtonLoading(elements.nodeSubmitButton, false);
        clearValidationErrors(elements.nodeForm);
    }

    function updateContextDisplay() {
        const application =
            getSelectedApplication();

        const version =
            getSelectedVersion();

        if (
            !application ||
            !version
        ) {
            elements.selectedContext.classList.add(
                'hidden'
            );

            elements.selectedContextText.textContent =
                '';

            return;
        }

        elements.selectedContextText.textContent =
            `${application.name} — Versi ${getVersionLabel(version)}`;

        elements.selectedContext.classList.remove(
            'hidden'
        );
    }

    function updateActionAvailability() {
        const enabled =
            hasCompleteSelection();

        elements.openRootNodeFormButton.disabled =
            !enabled;

        elements.refreshTreeButton.disabled =
            !enabled;

        if (!enabled) {
            setTreeActionAvailability(false);
        }
    }

    function hasCompleteSelection() {
        return (
            Number.isInteger(
                state.selectedApplicationId
            ) &&
            state.selectedApplicationId > 0 &&
            Number.isInteger(
                state.selectedVersionId
            ) &&
            state.selectedVersionId > 0
        );
    }

    function getSelectedApplication() {
        return state.applications.find(
            (application) =>
                Number(application.id) ===
                Number(
                    state.selectedApplicationId
                )
        );
    }

    function getSelectedApplicationVersions() {
        return getApplicationVersions(
            getSelectedApplication()
        );
    }

    function getSelectedVersion() {
        return getSelectedApplicationVersions().find(
            (version) =>
                Number(version.id) ===
                Number(
                    state.selectedVersionId
                )
        );
    }

    function getApplicationVersions(
        application
    ) {
        return Array.isArray(
            application?.versions
        )
            ? application.versions
            : [];
    }

    function getVersionLabel(version) {
        return (
            version?.version_number ||
            version?.version ||
            version?.name ||
            `Versi ${version?.id ?? ''}`
        );
    }

    function getNodeChildren(node) {
        if (
            Array.isArray(
                node.children_recursive
            )
        ) {
            return node.children_recursive;
        }

        if (
            Array.isArray(
                node.children
            )
        ) {
            return node.children;
        }

        return [];
    }

    function flattenTree(nodes) {
        return nodes.flatMap(
            (node) => [
                node,

                ...flattenTree(
                    getNodeChildren(node)
                ),
            ]
        );
    }

    function findNode(nodeId) {
        return state.flattenedNodes.find(
            (node) =>
                Number(node.id) ===
                Number(nodeId)
        );
    }

    function renderStatistics() {
        const nodes =
            state.flattenedNodes;

        elements.statTotalNodes.textContent =
            String(nodes.length);

        elements.statPublicNodes.textContent =
            String(
                nodes.filter(
                    (node) =>
                        node.status ===
                            'published' &&
                        Boolean(
                            node.is_public
                        )
                ).length
            );

        elements.statPublishedNodes.textContent =
            String(
                nodes.filter(
                    (node) =>
                        node.status ===
                        'published'
                ).length
            );

        elements.statMaterialNodes.textContent =
            String(
                nodes.filter(
                    (node) =>
                        node.node_type ===
                        'materi'
                ).length
            );
    }

    function clearTreeData() {
        state.tree = [];
        state.flattenedNodes = [];

        elements.tutorialTree.innerHTML =
            '';

        renderStatistics();
    }

    function showInitialState() {
        hideTreeStates();

        elements.treeInitial.classList.remove(
            'hidden'
        );

        elements.tutorialTree.classList.add(
            'hidden'
        );

        elements.treeDescription.textContent =
            'Pilih aplikasi dan versi untuk menampilkan materi.';

        setTreeActionAvailability(false);
    }

    function showTreeLoading() {
        hideTreeStates();

        elements.treeLoading.classList.remove(
            'hidden'
        );

        elements.tutorialTree.classList.add(
            'hidden'
        );
    }

    function showTreeError(message) {
        hideTreeStates();

        elements.treeError.classList.remove(
            'hidden'
        );

        elements.tutorialTree.classList.add(
            'hidden'
        );

        elements.treeErrorMessage.textContent =
            message;

        setTreeActionAvailability(false);
    }

    function hideTreeStates() {
        elements.treeInitial.classList.add(
            'hidden'
        );

        elements.treeLoading.classList.add(
            'hidden'
        );

        elements.treeEmpty.classList.add(
            'hidden'
        );

        elements.treeError.classList.add(
            'hidden'
        );
    }

    function setTreeActionAvailability(
        enabled
    ) {
        elements.expandAllButton.disabled =
            !enabled;

        elements.collapseAllButton.disabled =
            !enabled;
    }

    function toggleNode(nodeId) {
        const childrenContainer =
            document.querySelector(
                `[data-node-children="${nodeId}"]`
            );

        const toggleButton =
            document.querySelector(
                `.node-toggle-button[data-id="${nodeId}"]`
            );

        if (
            !childrenContainer ||
            !toggleButton
        ) {
            return;
        }

        const icon =
            toggleButton.querySelector('i');

        const isHidden =
            childrenContainer.classList.contains(
                'hidden'
            );

        childrenContainer.classList.toggle(
            'hidden'
        );

        icon.classList.toggle(
            'bi-chevron-down',
            isHidden
        );

        icon.classList.toggle(
            'bi-chevron-right',
            !isHidden
        );
    }

    function setAllNodesCollapsed(
        collapsed
    ) {
        document
            .querySelectorAll(
                '[data-node-children]'
            )
            .forEach((container) => {
                container.classList.toggle(
                    'hidden',
                    collapsed
                );
            });

        document
            .querySelectorAll(
                '.node-toggle-button i'
            )
            .forEach((icon) => {
                icon.classList.toggle(
                    'bi-chevron-right',
                    collapsed
                );

                icon.classList.toggle(
                    'bi-chevron-down',
                    !collapsed
                );
            });
    }



    async function parseResponse(response) {
        const result =
            await response
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

    function getNodeTypeLabel(type) {
        return {
            kategori: 'Kategori',
            bagian: 'Bagian',
            materi: 'Materi',
        }[type] || type;
    }

    function getNodeStatusLabel(status) {
        return {
            draft: 'Draf',
            published: 'Dipublikasikan',
            archived: 'Diarsipkan',
        }[status] || status;
    }

    function getNodeTypeClass(type) {
        return {
            kategori:
                'border-blue-200 bg-blue-50 text-blue-800',

            bagian:
                'border-violet-200 bg-violet-50 text-violet-700',

            materi:
                'border-amber-200 bg-amber-50 text-amber-700',
        }[type] ||
            'border-slate-200 bg-slate-100 text-slate-600';
    }

    function getNodeStatusClass(status) {
        return {
            draft:
                'border-slate-200 bg-slate-100 text-slate-600',

            published:
                'border-emerald-200 bg-emerald-50 text-emerald-700',

            archived:
                'border-red-200 bg-red-50 text-red-600',
        }[status] ||
            'border-slate-200 bg-slate-100 text-slate-600';
    }

    function escapeHtml(value) {
        return String(value ?? '')
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }
});