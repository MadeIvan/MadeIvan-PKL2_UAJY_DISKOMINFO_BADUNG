import 'bootstrap-icons/font/bootstrap-icons.css';

const API_BASE_URL = '/api/admin';

document.addEventListener('DOMContentLoaded', () => {
    const state = {
        applications: [],
        selectedApplicationId: null,
        tree: [],
        flattenedNodes: [],
    };

    const elements = {
        notification: document.getElementById('notification'),

        applicationSearchWrapper: document.getElementById(
            'application-search-wrapper'
        ),
        applicationSearch: document.getElementById(
            'application-search'
        ),
        applicationId: document.getElementById(
            'application-id'
        ),
        applicationDropdownButton: document.getElementById(
            'application-dropdown-button'
        ),
        applicationDropdown: document.getElementById(
            'application-dropdown'
        ),
        applicationOptions: document.getElementById(
            'application-options'
        ),
        applicationEmpty: document.getElementById(
            'application-empty'
        ),
        applicationSearchError: document.getElementById(
            'application-search-error'
        ),

        refreshTreeButton: document.getElementById(
            'refresh-tree-button'
        ),
        openRootNodeFormButton: document.getElementById(
            'open-root-node-form'
        ),
        emptyAddRootButton: document.getElementById(
            'empty-add-root-button'
        ),

        statTotalNodes: document.getElementById(
            'stat-total-nodes'
        ),
        statPublicNodes: document.getElementById(
            'stat-public-nodes'
        ),
        statPublishedNodes: document.getElementById(
            'stat-published-nodes'
        ),
        statTutorialNodes: document.getElementById(
            'stat-tutorial-nodes'
        ),

        treeDescription: document.getElementById(
            'tree-description'
        ),
        treeInitial: document.getElementById(
            'tree-initial'
        ),
        treeLoading: document.getElementById(
            'tree-loading'
        ),
        treeEmpty: document.getElementById(
            'tree-empty'
        ),
        treeError: document.getElementById(
            'tree-error'
        ),
        treeErrorMessage: document.getElementById(
            'tree-error-message'
        ),
        treeRetryButton: document.getElementById(
            'tree-retry-button'
        ),
        tutorialTree: document.getElementById(
            'tutorial-tree'
        ),

        expandAllButton: document.getElementById(
            'expand-all-button'
        ),
        collapseAllButton: document.getElementById(
            'collapse-all-button'
        ),

        nodeFormModal: document.getElementById(
            'node-form-modal'
        ),
        nodeFormModalClose: document.getElementById(
            'node-form-modal-close'
        ),
        nodeForm: document.getElementById(
            'node-form'
        ),
        nodeFormTitle: document.getElementById(
            'node-form-title'
        ),
        nodeFormDescription: document.getElementById(
            'node-form-description'
        ),

        nodeId: document.getElementById('node-id'),
        nodeParentId: document.getElementById(
            'node-parent-id'
        ),
        nodeTitle: document.getElementById(
            'node-title'
        ),
        nodeSlug: document.getElementById(
            'node-slug'
        ),
        nodeType: document.getElementById(
            'node-type'
        ),
        nodeSortOrder: document.getElementById(
            'node-sort-order'
        ),
        nodeStatus: document.getElementById(
            'node-status'
        ),
        nodeApplicationVersion: document.getElementById(
            'node-application-version'
        ),
        nodeDescription: document.getElementById(
            'node-description'
        ),
        nodeIsPublic: document.getElementById(
            'node-is-public'
        ),
        nodeSubmitButton: document.getElementById(
            'node-submit-button'
        ),
        nodeCancelButton: document.getElementById(
            'node-cancel-button'
        ),

        parentInformation: document.getElementById(
            'parent-information'
        ),
        parentInformationTitle: document.getElementById(
            'parent-information-title'
        ),
    };

    const missingElements = Object.entries(elements)
        .filter(([, element]) => !element)
        .map(([name]) => name);

    if (missingElements.length > 0) {
        console.error(
            'Materi page: elemen Blade tidak ditemukan:',
            missingElements
        );

        return;
    }

    let notificationTimeout = null;

    async function initializePage() {
        resetNodeForm();
        showInitialState();
        await fetchApplications();
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

            const result = await parseResponse(response);

            state.applications = extractApplications(
                result
            );

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

            showNotification(
                error.message,
                'error'
            );
        }
    }

    function extractApplications(result) {
        if (Array.isArray(result?.data)) {
            return result.data;
        }

        if (Array.isArray(result?.data?.data)) {
            return result.data.data;
        }

        if (Array.isArray(result?.applications)) {
            return result.applications;
        }

        return [];
    }

    function renderApplicationOptions(
        applications
    ) {
        if (applications.length === 0) {
            elements.applicationOptions.innerHTML = '';

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
                .map((application) => `
                    <button
                        type="button"
                        class="application-option flex w-full items-center justify-between gap-4 px-4 py-3 text-left text-sm transition hover:bg-slate-100"
                        data-id="${application.id}"
                    >
                        <span class="min-w-0">
                            <span class="block truncate font-semibold text-slate-800">
                                ${escapeHtml(application.name)}
                            </span>

                            ${
                                application.description
                                    ? `
                                        <span class="mt-1 block truncate text-xs text-slate-500">
                                            ${escapeHtml(application.description)}
                                        </span>
                                    `
                                    : ''
                            }
                        </span>

                        <i class="bi bi-chevron-right shrink-0 text-slate-400"></i>
                    </button>
                `)
                .join('');
    }

    function filterApplications(keyword) {
        const normalizedKeyword = String(
            keyword
        )
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
                        .includes(normalizedKeyword)
            );

        renderApplicationOptions(
            filteredApplications
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

    function selectApplication(applicationId) {
        const application = state.applications.find(
            (item) =>
                Number(item.id) ===
                Number(applicationId)
        );

        if (!application) {
            return;
        }

        state.selectedApplicationId = Number(
            application.id
        );

        elements.applicationId.value = String(
            application.id
        );

        elements.applicationSearch.value =
            application.name;

        elements.applicationSearchError.classList.add(
            'hidden'
        );

        closeApplicationDropdown();
        setApplicationSelected(true);

        fetchTree();
    }

    function clearSelectedApplication() {
        state.selectedApplicationId = null;

        elements.applicationId.value = '';

        elements.applicationSearchError.classList.add(
            'hidden'
        );

        setApplicationSelected(false);
        showInitialState();
    }

    function validateTypedApplication() {
        const value =
            elements.applicationSearch.value
                .trim()
                .toLowerCase();

        if (!value) {
            clearSelectedApplication();
            return;
        }

        const exactApplication =
            state.applications.find(
                (application) =>
                    String(application.name)
                        .trim()
                        .toLowerCase() === value
            );

        if (exactApplication) {
            selectApplication(
                exactApplication.id
            );

            return;
        }

        elements.applicationSearchError.classList.remove(
            'hidden'
        );
    }

    async function fetchTree() {
        if (!state.selectedApplicationId) {
            showInitialState();
            return;
        }

        showTreeLoading();

        try {
            const response = await fetch(
                `${API_BASE_URL}/tutorial-nodes/tree?application_id=${state.selectedApplicationId}`,
                {
                    headers: {
                        Accept: 'application/json',
                    },
                }
            );

            const result = await parseResponse(
                response
            );

            state.tree = Array.isArray(result.data)
                ? result.data
                : [];

            state.flattenedNodes = flattenTree(
                state.tree
            );

            renderTree();
            renderStatistics();
        } catch (error) {
            showTreeError(error.message);
        }
    }

    function renderTree() {
        hideTreeStates();

        const application =
            getSelectedApplication();

        elements.treeDescription.textContent =
            application
                ? `Struktur materi untuk ${application.name}.`
                : 'Struktur materi aplikasi.';

        if (state.tree.length === 0) {
            elements.tutorialTree.innerHTML = '';

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
                .map((node) =>
                    createNodeHtml(node)
                )
                .join('');

        elements.tutorialTree.classList.remove(
            'hidden'
        );

        setTreeActionAvailability(true);
    }

    function createNodeHtml(node) {
        const children = getNodeChildren(node);
        const hasChildren = children.length > 0;

        const childrenHtml = hasChildren
            ? `
                <div
                    data-node-children="${node.id}"
                    class="ml-5 space-y-3 border-l border-slate-200 pl-4"
                >
                    ${children
                        .map((child) =>
                            createNodeHtml(child)
                        )
                        .join('')}
                </div>
            `
            : '';

        const toggleButton = hasChildren
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
                                        ${escapeHtml(node.title)}
                                    </h4>

                                    <span class="border px-2 py-1 text-xs font-semibold ${getNodeTypeClass(node.node_type)}">
                                        ${escapeHtml(
                                            getNodeTypeLabel(
                                                node.node_type
                                            )
                                        )}
                                    </span>

                                    <span class="border px-2 py-1 text-xs font-semibold ${getNodeStatusClass(node.status)}">
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
                                        Urutan: ${Number(
                                            node.sort_order ?? 0
                                        )}
                                    </span>

                                    <span>
                                        ${children.length} child
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="flex shrink-0 flex-wrap gap-2 pl-11 lg:pl-0">
                            <button
                                type="button"
                                class="node-add-child-button inline-flex items-center gap-2 border border-emerald-200 px-3 py-2 text-sm font-semibold text-emerald-700 hover:bg-emerald-50"
                                data-id="${node.id}"
                            >
                                <i class="bi bi-plus-lg"></i>
                                Child
                            </button>
                            

                            ${
                                ['tutorial', 'step'].includes(node.node_type)
                                    ? `
                                        <a
                                            href="/admin/Materi-demo/${node.id}/content"
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
                            >
                                <i class="bi bi-pencil-square"></i>
                            </button>

                            <button
                                type="button"
                                class="node-delete-button flex h-9 w-9 items-center justify-center border border-red-200 text-red-600 hover:bg-red-50"
                                data-id="${node.id}"
                                aria-label="Hapus materi"
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

    function getNodeChildren(node) {
        if (
            Array.isArray(
                node.children_recursive
            )
        ) {
            return node.children_recursive;
        }

        if (Array.isArray(node.children)) {
            return node.children;
        }

        return [];
    }

    function renderStatistics() {
        const nodes = state.flattenedNodes;

        elements.statTotalNodes.textContent =
            nodes.length;

        elements.statPublicNodes.textContent =
            nodes.filter(
                (node) => Boolean(node.is_public)
            ).length;

        elements.statPublishedNodes.textContent =
            nodes.filter(
                (node) =>
                    node.status === 'published'
            ).length;

        elements.statTutorialNodes.textContent =
            nodes.filter(
                (node) =>
                    node.node_type === 'tutorial'
            ).length;
    }

    function openCreateRootNodeModal() {
        if (!state.selectedApplicationId) {
            showNotification(
                'Pilih aplikasi terlebih dahulu.',
                'error'
            );

            return;
        }

        resetNodeForm();
        populateVersionOptions();

        elements.nodeFormTitle.textContent =
            'Tambah Materi Utama';

        elements.nodeFormDescription.textContent =
            'Materi ini tidak memiliki parent.';

        openNodeModal();
    }

    function openCreateChildNodeModal(
        parentId
    ) {
        const parent = findNode(parentId);

        if (!parent) {
            showNotification(
                'Parent materi tidak ditemukan.',
                'error'
            );

            return;
        }

        resetNodeForm();
        populateVersionOptions();

        elements.nodeParentId.value = String(
            parent.id
        );

        elements.parentInformationTitle.textContent =
            parent.title;

        elements.parentInformation.classList.remove(
            'hidden'
        );

        elements.nodeFormTitle.textContent =
            'Tambah Child Materi';

        elements.nodeFormDescription.textContent =
            `Tambahkan materi di bawah "${parent.title}".`;

        openNodeModal();
    }

    function openEditNodeModal(nodeId) {
        const node = findNode(nodeId);

        if (!node) {
            showNotification(
                'Materi tidak ditemukan.',
                'error'
            );

            return;
        }

        resetNodeForm();
        populateVersionOptions();

        elements.nodeId.value = String(node.id);

        elements.nodeParentId.value =
            node.parent_id
                ? String(node.parent_id)
                : '';

        elements.nodeTitle.value =
            node.title || '';

        elements.nodeSlug.value =
            node.slug || '';

        elements.nodeType.value =
            node.node_type || 'tutorial';

        elements.nodeSortOrder.value =
            Number(node.sort_order ?? 0);

        elements.nodeStatus.value =
            node.status || 'draft';

        elements.nodeApplicationVersion.value =
            node.application_version_id ?? '';

        elements.nodeDescription.value =
            node.description || '';

        elements.nodeIsPublic.checked =
            Boolean(node.is_public);

        if (node.parent_id) {
            const parent = findNode(
                node.parent_id
            );

            elements.parentInformationTitle.textContent =
                parent?.title || 'Parent materi';

            elements.parentInformation.classList.remove(
                'hidden'
            );
        }

        elements.nodeFormTitle.textContent =
            'Ubah Materi';

        elements.nodeFormDescription.textContent =
            'Perbarui informasi materi.';

        setButtonContent(
            elements.nodeSubmitButton,
            'bi-pencil-square',
            'Perbarui Materi'
        );

        openNodeModal();
    }

    async function submitNode(event) {
        event.preventDefault();

        if (!state.selectedApplicationId) {
            showNotification(
                'Pilih aplikasi terlebih dahulu.',
                'error'
            );

            return;
        }

        const nodeId =
            elements.nodeId.value;

        const isEditing = nodeId !== '';

        const payload = {
            application_id: Number(
                state.selectedApplicationId
            ),

            application_version_id:
                elements.nodeApplicationVersion.value
                    ? Number(
                        elements.nodeApplicationVersion
                            .value
                    )
                    : null,

            parent_id:
                elements.nodeParentId.value
                    ? Number(
                        elements.nodeParentId.value
                    )
                    : null,

            title:
                elements.nodeTitle.value.trim(),

            slug:
                elements.nodeSlug.value.trim() ||
                null,

            description:
                elements.nodeDescription.value.trim() ||
                null,

            node_type:
                elements.nodeType.value,

            sort_order:
                Number(
                    elements.nodeSortOrder.value || 0
                ),

            status:
                elements.nodeStatus.value,

            is_public:
                elements.nodeIsPublic.checked,
        };

        const url = isEditing
            ? `${API_BASE_URL}/tutorial-nodes/${nodeId}`
            : `${API_BASE_URL}/tutorial-nodes`;

        setNodeSubmitLoading(true);

        try {
            const response = await fetch(url, {
                method: isEditing
                    ? 'PUT'
                    : 'POST',

                headers: {
                    Accept: 'application/json',
                    'Content-Type':
                        'application/json',
                },

                body: JSON.stringify(payload),
            });

            const result = await parseResponse(
                response
            );

            closeNodeModal();
            await fetchTree();

            showNotification(
                result.message ||
                'Materi berhasil disimpan.',
                'success'
            );
        } catch (error) {
            showNotification(
                error.message,
                'error'
            );
        } finally {
            setNodeSubmitLoading(false);
        }
    }

    async function deleteNode(nodeId) {
        const node = findNode(nodeId);

        if (!node) {
            return;
        }

        const confirmed = window.confirm(
            `Hapus materi "${node.title}"?`
        );

        if (!confirmed) {
            return;
        }

        try {
            const response = await fetch(
                `${API_BASE_URL}/tutorial-nodes/${nodeId}`,
                {
                    method: 'DELETE',
                    headers: {
                        Accept: 'application/json',
                    },
                }
            );

            const result = await parseResponse(
                response
            );

            await fetchTree();

            showNotification(
                result.message ||
                'Materi berhasil dihapus.',
                'success'
            );
        } catch (error) {
            showNotification(
                error.message,
                'error'
            );
        }
    }

    function populateVersionOptions() {
        const application =
            getSelectedApplication();

        const versions = Array.isArray(
            application?.versions
        )
            ? application.versions
            : [];

        elements.nodeApplicationVersion.innerHTML =
            [
                `
                    <option value="">
                        Semua versi
                    </option>
                `,
                ...versions.map((version) => `
                    <option value="${version.id}">
                        ${escapeHtml(
                            version.version_number ||
                            version.version ||
                            `Versi ${version.id}`
                        )}
                    </option>
                `),
            ].join('');
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

        elements.nodeId.value = '';
        elements.nodeParentId.value = '';
        elements.nodeSortOrder.value = '0';
        elements.nodeStatus.value = 'draft';

        elements.parentInformation.classList.add(
            'hidden'
        );

        elements.parentInformationTitle.textContent =
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

        elements.nodeSubmitButton.disabled = false;
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

    function setAllNodesCollapsed(collapsed) {
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

    function flattenTree(nodes) {
        return nodes.flatMap((node) => [
            node,
            ...flattenTree(
                getNodeChildren(node)
            ),
        ]);
    }

    function findNode(nodeId) {
        return state.flattenedNodes.find(
            (node) =>
                Number(node.id) ===
                Number(nodeId)
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

    function setApplicationSelected(selected) {
        elements.openRootNodeFormButton.disabled =
            !selected;

        elements.refreshTreeButton.disabled =
            !selected;

        if (!selected) {
            setTreeActionAvailability(false);
        }
    }

    function setTreeActionAvailability(enabled) {
        elements.expandAllButton.disabled =
            !enabled;

        elements.collapseAllButton.disabled =
            !enabled;
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
            'Pilih aplikasi untuk menampilkan materi.';

        state.tree = [];
        state.flattenedNodes = [];

        renderStatistics();
        setApplicationSelected(false);
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

    function setNodeSubmitLoading(isLoading) {
        elements.nodeSubmitButton.disabled =
            isLoading;

        if (isLoading) {
            setButtonContent(
                elements.nodeSubmitButton,
                'bi-arrow-repeat animate-spin',
                'Menyimpan...'
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

        window.clearTimeout(
            notificationTimeout
        );

        elements.notification.className =
            `border px-4 py-3 text-sm ${styles[type]}`;

        elements.notification.textContent =
            message;

        elements.notification.classList.remove(
            'hidden'
        );

        notificationTimeout =
            window.setTimeout(() => {
                elements.notification.classList.add(
                    'hidden'
                );
            }, 5000);
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
            category: 'Kategori',
            section: 'Bagian',
            tutorial: 'Tutorial',
            step: 'Langkah',
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
            category:
                'border-blue-200 bg-blue-50 text-blue-800',

            section:
                'border-violet-200 bg-violet-50 text-violet-700',

            tutorial:
                'border-amber-200 bg-amber-50 text-amber-700',

            step:
                'border-emerald-200 bg-emerald-50 text-emerald-700',
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
        (event) => {
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
                state.selectedApplicationId =
                    null;

                elements.applicationId.value =
                    '';

                setApplicationSelected(false);
            }

            if (
                event.target.value.trim() === ''
            ) {
                clearSelectedApplication();
                openApplicationDropdown();
            }
        }
    );

    elements.applicationSearch.addEventListener(
        'keydown',
        (event) => {
            if (event.key === 'Enter') {
                event.preventDefault();

                const firstOption =
                    elements.applicationOptions
                        .querySelector(
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
        () => {
            const isHidden =
                elements.applicationDropdown
                    .classList
                    .contains('hidden');

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
    );

    elements.applicationOptions.addEventListener(
        'click',
        (event) => {
            const option = event.target.closest(
                '.application-option'
            );

            if (!option) {
                return;
            }

            selectApplication(
                option.dataset.id
            );
        }
    );

    document.addEventListener(
        'click',
        (event) => {
            if (
                !elements.applicationSearchWrapper
                    .contains(event.target)
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
        (event) => {
            const toggleButton =
                event.target.closest(
                    '.node-toggle-button'
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
                    deleteButton.dataset.id
                );
            }
        }
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
                !elements.nodeFormModal
                    .classList
                    .contains('hidden')
            ) {
                closeNodeModal();
            }
        }
    );

    initializePage();
});