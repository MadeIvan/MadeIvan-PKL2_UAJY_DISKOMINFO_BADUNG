import 'bootstrap-icons/font/bootstrap-icons.css';

const API_BASE_URL = '/api/admin';

const state = {
    applications: [],
    search: '',
    activeApplicationId: null,
};

const elements = {
    notification: document.getElementById('notification'),

    applicationForm: document.getElementById('application-form'),
    applicationId: document.getElementById('application-id'),
    applicationName: document.getElementById('application-name'),
    applicationSlug: document.getElementById('application-slug'),
    applicationCategory: document.getElementById(
        'application-category'
    ),
    applicationDescription: document.getElementById(
        'application-description'
    ),
    applicationStatus: document.getElementById(
        'application-status'
    ),
    applicationIsPublic: document.getElementById(
        'application-is-public'
    ),
    applicationFormTitle: document.getElementById(
        'application-form-title'
    ),
    applicationSubmitButton: document.getElementById(
        'application-submit-button'
    ),
    cancelApplicationEdit: document.getElementById(
        'cancel-application-edit'
    ),

    applicationSearch: document.getElementById(
        'application-search'
    ),
    applicationCount: document.getElementById(
        'application-count'
    ),
    applicationLoading: document.getElementById(
        'application-loading'
    ),
    applicationEmpty: document.getElementById(
        'application-empty'
    ),
    applicationList: document.getElementById(
        'application-list'
    ),
    refreshApplications: document.getElementById(
        'refresh-applications'
    ),

    versionModal: document.getElementById('version-modal'),
    closeVersionModal: document.getElementById(
        'close-version-modal'
    ),
    versionApplicationName: document.getElementById(
        'version-application-name'
    ),
    versionCount: document.getElementById('version-count'),
    versionEmpty: document.getElementById('version-empty'),
    versionList: document.getElementById('version-list'),

    versionForm: document.getElementById('version-form'),
    versionApplicationId: document.getElementById(
        'version-application-id'
    ),
    versionId: document.getElementById('version-id'),
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
    versionFormTitle: document.getElementById(
        'version-form-title'
    ),
    versionSubmitButton: document.getElementById(
        'version-submit-button'
    ),
    cancelVersionEdit: document.getElementById(
        'cancel-version-edit'
    ),
};

async function fetchApplications() {
    setApplicationLoading(true);

    try {
        const response = await fetch(
            `${API_BASE_URL}/applications-with-versions`,
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

        renderApplications();

        if (state.activeApplicationId !== null) {
            renderVersionModal(
                state.activeApplicationId
            );
        }
    } catch (error) {
        state.applications = [];
        renderApplications();
        showNotification(error.message, 'error');
    } finally {
        setApplicationLoading(false);
    }
}

function renderApplications() {
    const keyword = state.search.trim().toLowerCase();

    const applications = state.applications.filter(
        (application) => {
            const versions = application.versions ?? [];

            const searchableText = [
                application.name,
                application.slug,
                application.category_name,
                application.description,
                application.status,
                ...versions.map(
                    (version) => version.version_number
                ),
            ]
                .filter(Boolean)
                .join(' ')
                .toLowerCase();

            return searchableText.includes(keyword);
        }
    );

    elements.applicationCount.textContent =
        `${applications.length} application(s) found`;

    if (applications.length === 0) {
        elements.applicationList.innerHTML = '';
        elements.applicationList.classList.add('hidden');
        elements.applicationEmpty.classList.remove('hidden');
        return;
    }

    elements.applicationEmpty.classList.add('hidden');
    elements.applicationList.classList.remove('hidden');

    elements.applicationList.innerHTML = applications
        .map(createApplicationCard)
        .join('');

    bindApplicationActions();
}

function createApplicationCard(application) {
    const versions = application.versions ?? [];

    const currentVersion =
        application.current_version ??
        versions.find((version) => version.is_current);

    return `
        <article class="p-6">
            <div class="flex flex-col justify-between gap-5 lg:flex-row">
                <div class="min-w-0 flex-1">
                    <div class="flex flex-wrap items-center gap-2">
                        <h3 class="text-lg font-semibold">
                            ${escapeHtml(application.name)}
                        </h3>

                        <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium">
                            ${escapeHtml(application.status)}
                        </span>

                        <span class="rounded-full px-2.5 py-1 text-xs font-medium ${
                            application.is_public
                                ? 'bg-green-100 text-green-700'
                                : 'bg-amber-100 text-amber-700'
                        }">
                            ${
                                application.is_public
                                    ? 'Public'
                                    : 'Private'
                            }
                        </span>
                    </div>

                    <p class="mt-1 text-sm text-slate-500">
                        ${escapeHtml(application.slug ?? '-')}
                    </p>

                    <p class="mt-3 text-sm leading-6 text-slate-700">
                        ${escapeHtml(
                            application.description ??
                                'No description available.'
                        )}
                    </p>

                    <div class="mt-4 grid gap-3 text-sm sm:grid-cols-3">
                        <div>
                            <span class="font-medium">
                                Category:
                            </span>

                            ${escapeHtml(
                                application.category_name ?? '-'
                            )}
                        </div>

                        <div>
                            <span class="font-medium">
                                Current version:
                            </span>

                            ${
                                currentVersion
                                    ? escapeHtml(
                                          currentVersion.version_number
                                      )
                                    : '-'
                            }
                        </div>

                        <div>
                            <span class="font-medium">
                                Total versions:
                            </span>

                            ${versions.length}
                        </div>
                    </div>
                </div>

                <div class="flex shrink-0 gap-2">
                    ${iconButton({
                        action: 'versions',
                        id: application.id,
                        icon: 'bi-stack',
                        title: 'Manage Versions',
                        className:
                            'bg-slate-900 text-white hover:bg-slate-700',
                    })}

                    ${iconButton({
                        action: 'edit',
                        id: application.id,
                        icon: 'bi-pencil-square',
                        title: 'Edit Application',
                        className:
                            'border border-blue-300 text-blue-700 hover:bg-blue-50',
                    })}

                    ${iconButton({
                        action: 'delete',
                        id: application.id,
                        icon: 'bi-trash',
                        title: 'Delete Application',
                        className:
                            'border border-red-300 text-red-700 hover:bg-red-50',
                    })}
                </div>
            </div>
        </article>
    `;
}

function iconButton({
    action,
    id,
    icon,
    title,
    className,
}) {
    return `
        <button
            type="button"
            data-action="${action}"
            data-id="${id}"
            title="${title}"
            aria-label="${title}"
            class="inline-flex h-10 w-10 items-center justify-center rounded-lg transition ${className}"
        >
            <i class="bi ${icon}"></i>
        </button>
    `;
}

function bindApplicationActions() {
    document
        .querySelectorAll('[data-action]')
        .forEach((button) => {
            button.addEventListener('click', () => {
                const applicationId = Number(
                    button.dataset.id
                );

                switch (button.dataset.action) {
                    case 'versions':
                        openVersionModal(applicationId);
                        break;

                    case 'edit':
                        startApplicationEdit(applicationId);
                        break;

                    case 'delete':
                        deleteApplication(applicationId);
                        break;
                }
            });
        });
}

function startApplicationEdit(applicationId) {
    const application = findApplication(applicationId);

    if (!application) {
        showNotification(
            'Application could not be found.',
            'error'
        );
        return;
    }

    elements.applicationId.value = application.id;
    elements.applicationName.value = application.name ?? '';
    elements.applicationSlug.value = application.slug ?? '';
    elements.applicationCategory.value =
        application.category_name ?? '';
    elements.applicationDescription.value =
        application.description ?? '';
    elements.applicationStatus.value =
        application.status ?? 'active';
    elements.applicationIsPublic.checked = Boolean(
        application.is_public
    );

    elements.applicationFormTitle.textContent =
        'Update Application';

    setButtonContent(
        elements.applicationSubmitButton,
        'bi-pencil-square',
        'Update Application'
    );

    elements.cancelApplicationEdit.classList.remove('hidden');
    elements.cancelApplicationEdit.classList.add('inline-flex');

    elements.applicationForm.scrollIntoView({
        behavior: 'smooth',
        block: 'start',
    });
}

function resetApplicationForm() {
    elements.applicationForm.reset();
    elements.applicationId.value = '';
    elements.applicationStatus.value = 'active';

    elements.applicationFormTitle.textContent =
        'Add Application';

    setButtonContent(
        elements.applicationSubmitButton,
        'bi-plus-lg',
        'Save Application'
    );

    elements.cancelApplicationEdit.classList.add('hidden');
    elements.cancelApplicationEdit.classList.remove(
        'inline-flex'
    );
}

async function submitApplication(event) {
    event.preventDefault();

    const applicationId = elements.applicationId.value;
    const isEditing = applicationId !== '';

    const payload = {
        name: elements.applicationName.value.trim(),
        description:
            elements.applicationDescription.value.trim() ||
            null,
        category_name:
            elements.applicationCategory.value.trim() || null,
        status: elements.applicationStatus.value,
        is_public: elements.applicationIsPublic.checked,
    };

    const slug = elements.applicationSlug.value.trim();

    if (slug !== '') {
        payload.slug = slug;
    }

    const url = isEditing
        ? `${API_BASE_URL}/applications/${applicationId}`
        : `${API_BASE_URL}/applications`;

    const method = isEditing ? 'PUT' : 'POST';

    setButtonLoading(
        elements.applicationSubmitButton,
        'Saving...'
    );

    try {
        const response = await fetch(url, {
            method,
            headers: jsonHeaders(),
            body: JSON.stringify(payload),
        });

        const result = await parseResponse(response);

        resetApplicationForm();
        await fetchApplications();

        showNotification(result.message, 'success');
    } catch (error) {
        showNotification(error.message, 'error');

        setButtonContent(
            elements.applicationSubmitButton,
            isEditing
                ? 'bi-pencil-square'
                : 'bi-plus-lg',
            isEditing
                ? 'Update Application'
                : 'Save Application'
        );
    } finally {
        elements.applicationSubmitButton.disabled = false;
    }
}

async function deleteApplication(applicationId) {
    const application = findApplication(applicationId);

    if (!application) {
        return;
    }

    const confirmed = window.confirm(
        `Delete "${application.name}"?`
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

        resetApplicationForm();
        await fetchApplications();

        showNotification(result.message, 'success');
    } catch (error) {
        showNotification(error.message, 'error');
    }
}

function openVersionModal(applicationId) {
    state.activeApplicationId = applicationId;
    elements.versionApplicationId.value = applicationId;

    resetVersionForm();
    renderVersionModal(applicationId);

    elements.versionModal.classList.remove('hidden');
    elements.versionModal.classList.add('flex');
    elements.versionModal.setAttribute(
        'aria-hidden',
        'false'
    );

    document.body.classList.add('overflow-hidden');
}

function closeVersionModal() {
    state.activeApplicationId = null;

    elements.versionModal.classList.add('hidden');
    elements.versionModal.classList.remove('flex');
    elements.versionModal.setAttribute(
        'aria-hidden',
        'true'
    );

    document.body.classList.remove('overflow-hidden');

    resetVersionForm();
}

function renderVersionModal(applicationId) {
    const application = findApplication(applicationId);

    if (!application) {
        closeVersionModal();
        return;
    }

    const versions = application.versions ?? [];

    elements.versionApplicationName.textContent =
        application.name;

    elements.versionCount.textContent =
        `${versions.length} version(s)`;

    if (versions.length === 0) {
        elements.versionList.innerHTML = '';
        elements.versionEmpty.classList.remove('hidden');
        return;
    }

    elements.versionEmpty.classList.add('hidden');

    elements.versionList.innerHTML = versions
        .map(createVersionCard)
        .join('');

    bindVersionActions();
}

function createVersionCard(version) {
    return `
        <article class="rounded-xl border border-slate-200 p-4">
            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <div class="flex flex-wrap items-center gap-2">
                        <h4 class="font-semibold">
                            Version ${escapeHtml(
                                version.version_number
                            )}
                        </h4>

                        <span class="rounded-full bg-slate-100 px-2 py-1 text-xs font-medium">
                            ${escapeHtml(version.status)}
                        </span>

                        ${
                            version.is_current
                                ? `
                                    <span class="rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-700">
                                        Current
                                    </span>
                                `
                                : ''
                        }
                    </div>

                    <p class="mt-2 text-sm text-slate-500">
                        Release date:
                        ${escapeHtml(
                            formatDate(version.release_date)
                        )}
                    </p>

                    <p class="mt-3 text-sm leading-6 text-slate-700">
                        ${escapeHtml(
                            version.release_notes ??
                                'No release notes.'
                        )}
                    </p>
                </div>

                <div class="flex shrink-0 gap-2">
                    <button
                        type="button"
                        data-version-action="edit"
                        data-version-id="${version.id}"
                        title="Edit Version"
                        class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-blue-300 text-blue-700 hover:bg-blue-50"
                    >
                        <i class="bi bi-pencil-square"></i>
                    </button>

                    <button
                        type="button"
                        data-version-action="delete"
                        data-version-id="${version.id}"
                        title="Delete Version"
                        class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-red-300 text-red-700 hover:bg-red-50"
                    >
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        </article>
    `;
}

function bindVersionActions() {
    document
        .querySelectorAll('[data-version-action]')
        .forEach((button) => {
            button.addEventListener('click', () => {
                const versionId = Number(
                    button.dataset.versionId
                );

                if (
                    button.dataset.versionAction === 'edit'
                ) {
                    startVersionEdit(versionId);
                }

                if (
                    button.dataset.versionAction === 'delete'
                ) {
                    deleteVersion(versionId);
                }
            });
        });
}

function startVersionEdit(versionId) {
    const application = findApplication(
        state.activeApplicationId
    );

    const version = application?.versions?.find(
        (item) => item.id === versionId
    );

    if (!version) {
        return;
    }

    elements.versionId.value = version.id;
    elements.versionNumber.value =
        version.version_number ?? '';
    elements.versionReleaseDate.value =
        normalizeDateForInput(version.release_date);
    elements.versionStatus.value =
        version.status ?? 'draft';
    elements.versionReleaseNotes.value =
        version.release_notes ?? '';
    elements.versionIsCurrent.checked = Boolean(
        version.is_current
    );

    elements.versionFormTitle.textContent =
        'Update Version';

    setButtonContent(
        elements.versionSubmitButton,
        'bi-pencil-square',
        'Update Version'
    );

    elements.cancelVersionEdit.classList.remove('hidden');
    elements.cancelVersionEdit.classList.add('inline-flex');
}

function resetVersionForm() {
    elements.versionForm.reset();
    elements.versionId.value = '';
    elements.versionStatus.value = 'draft';

    elements.versionFormTitle.textContent =
        'Add Version';

    setButtonContent(
        elements.versionSubmitButton,
        'bi-plus-lg',
        'Save Version'
    );

    elements.cancelVersionEdit.classList.add('hidden');
    elements.cancelVersionEdit.classList.remove(
        'inline-flex'
    );
}

async function submitVersion(event) {
    event.preventDefault();

    const applicationId =
        elements.versionApplicationId.value;

    const versionId = elements.versionId.value;
    const isEditing = versionId !== '';

    const payload = {
        version_number:
            elements.versionNumber.value.trim(),
        release_date:
            elements.versionReleaseDate.value || null,
        release_notes:
            elements.versionReleaseNotes.value.trim() ||
            null,
        status: elements.versionStatus.value,
        is_current: elements.versionIsCurrent.checked,
    };

    const url = isEditing
        ? `${API_BASE_URL}/application-versions/${versionId}`
        : `${API_BASE_URL}/applications/${applicationId}/versions`;

    const method = isEditing ? 'PUT' : 'POST';

    setButtonLoading(
        elements.versionSubmitButton,
        'Saving...'
    );

    try {
        const response = await fetch(url, {
            method,
            headers: jsonHeaders(),
            body: JSON.stringify(payload),
        });

        const result = await parseResponse(response);

        resetVersionForm();
        await fetchApplications();

        showNotification(result.message, 'success');
    } catch (error) {
        showNotification(error.message, 'error');

        setButtonContent(
            elements.versionSubmitButton,
            isEditing
                ? 'bi-pencil-square'
                : 'bi-plus-lg',
            isEditing
                ? 'Update Version'
                : 'Save Version'
        );
    } finally {
        elements.versionSubmitButton.disabled = false;
    }
}

async function deleteVersion(versionId) {
    const confirmed = window.confirm(
        'Delete this application version?'
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

        resetVersionForm();
        await fetchApplications();

        showNotification(result.message, 'success');
    } catch (error) {
        showNotification(error.message, 'error');
    }
}

function findApplication(applicationId) {
    return state.applications.find(
        (application) =>
            application.id === applicationId
    );
}

function setApplicationLoading(isLoading) {
    elements.applicationLoading.classList.toggle(
        'hidden',
        !isLoading
    );

    if (isLoading) {
        elements.applicationList.classList.add('hidden');
        elements.applicationEmpty.classList.add('hidden');
    }
}

function setButtonContent(button, icon, label) {
    button.innerHTML = `
        <i class="bi ${icon}"></i>
        <span>${escapeHtml(label)}</span>
    `;
}

function setButtonLoading(button, label) {
    button.disabled = true;

    button.innerHTML = `
        <i class="bi bi-arrow-repeat animate-spin"></i>
        <span>${escapeHtml(label)}</span>
    `;
}

function jsonHeaders() {
    return {
        Accept: 'application/json',
        'Content-Type': 'application/json',
    };
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
        result.message ??
            `Request failed with status ${response.status}.`
    );
}

function showNotification(message, type) {
    elements.notification.textContent = message;

    elements.notification.classList.remove(
        'hidden',
        'border-green-300',
        'bg-green-50',
        'text-green-700',
        'border-red-300',
        'bg-red-50',
        'text-red-700'
    );

    elements.notification.classList.add(
        type === 'success'
            ? 'border-green-300'
            : 'border-red-300',
        type === 'success'
            ? 'bg-green-50'
            : 'bg-red-50',
        type === 'success'
            ? 'text-green-700'
            : 'text-red-700'
    );
}

function formatDate(value) {
    if (!value) {
        return '-';
    }

    return String(value).slice(0, 10);
}

function normalizeDateForInput(value) {
    return value ? String(value).slice(0, 10) : '';
}

function escapeHtml(value) {
    return String(value ?? '')
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
}

elements.applicationForm.addEventListener(
    'submit',
    submitApplication
);

elements.cancelApplicationEdit.addEventListener(
    'click',
    resetApplicationForm
);

elements.refreshApplications.addEventListener(
    'click',
    fetchApplications
);

elements.applicationSearch.addEventListener(
    'input',
    (event) => {
        state.search = event.target.value;
        renderApplications();
    }
);

elements.versionForm.addEventListener(
    'submit',
    submitVersion
);

elements.cancelVersionEdit.addEventListener(
    'click',
    resetVersionForm
);

elements.closeVersionModal.addEventListener(
    'click',
    closeVersionModal
);

elements.versionModal.addEventListener(
    'click',
    (event) => {
        if (event.target === elements.versionModal) {
            closeVersionModal();
        }
    }
);

document.addEventListener('keydown', (event) => {
    if (
        event.key === 'Escape' &&
        !elements.versionModal.classList.contains('hidden')
    ) {
        closeVersionModal();
    }
});

fetchApplications();