import 'bootstrap-icons/font/bootstrap-icons.css';

import {
    showToast,
    setButtonLoading,
    showDoubleConfirmModal
} from '../utils.js';


// ============================================================
// AUTHENTICATION
// ============================================================

const token = localStorage.getItem('auth_token');


// ============================================================
// UI ELEMENTS
// ============================================================

const tableBody =
    document.getElementById('userTableBody');

const emptyResult =
    document.getElementById('emptyResult');

const skeletonLoader =
    document.getElementById('skeletonLoader');

const searchInput =
    document.getElementById('userSearch');

const modal =
    document.getElementById('userModal');

const formError =
    document.getElementById('formError');

const btnSaveText =
    document.getElementById('btnSaveText');

const saveUserBtn =
    document.getElementById('saveUserBtn');

const statTotalUsers =
    document.getElementById('statTotalUsers');

const statTotalAdmins =
    document.getElementById('statTotalAdmins');

const paginationContainer =
    document.getElementById('paginationContainer');

const paginationButtons =
    document.getElementById('paginationButtons');

const pageStart =
    document.getElementById('pageStart');

const pageEnd =
    document.getElementById('pageEnd');

const pageTotal =
    document.getElementById('pageTotal');


// ============================================================
// PASSWORD ELEMENTS
// ============================================================

const userPassword =
    document.getElementById('userPassword');

const toggleUserPassword =
    document.getElementById('toggleUserPassword');

const userPasswordEyeIcon =
    document.getElementById('userPasswordEyeIcon');

const passwordLabel =
    document.getElementById('passwordLabel');

const passwordRequiredIndicator =
    document.getElementById('passwordRequiredIndicator');

const passwordOptionalBadge =
    document.getElementById('passwordOptionalBadge');

const passwordCreateHelp =
    document.getElementById('passwordCreateHelp');

const passwordEditHelp =
    document.getElementById('passwordEditHelp');

const modalDescription =
    document.getElementById('modalDescription');


// ============================================================
// STATE
// ============================================================

const state = {
    users: [],
    currentPage: 1,
    lastPage: 1,
    total: 0,
    perPage: 10,
    searchQuery: '',
    isLoading: false
};

let searchTimeout = null;


// ============================================================
// EVENT BINDING
// ============================================================

function bindEvents() {

    // Search
    if (searchInput) {
        searchInput.addEventListener(
            'input',
            (event) => {
                state.searchQuery =
                    event.target.value.trim();

                window.clearTimeout(
                    searchTimeout
                );

                searchTimeout =
                    window.setTimeout(
                        () => {
                            loadUsers(1);
                        },
                        400
                    );
            }
        );
    }


    // Pagination
    if (paginationButtons) {
        paginationButtons.addEventListener(
            'click',
            (event) => {
                const button =
                    event.target.closest(
                        '.pagination-button'
                    );

                if (
                    !button ||
                    button.disabled
                ) {
                    return;
                }

                const page =
                    Number(
                        button.dataset.page
                    );

                if (
                    page &&
                    page !== state.currentPage
                ) {
                    loadUsers(page);
                }
            }
        );
    }


    // Password visibility
    if (
        toggleUserPassword &&
        userPassword
    ) {
        toggleUserPassword.addEventListener(
            'click',
            () => {
                const isHidden =
                    userPassword.type ===
                    'password';

                userPassword.type =
                    isHidden
                        ? 'text'
                        : 'password';

                if (userPasswordEyeIcon) {
                    userPasswordEyeIcon.className =
                        isHidden
                            ? 'bi bi-eye-slash'
                            : 'bi bi-eye';
                }
            }
        );
    }
}


// ============================================================
// PASSWORD MODE
// ============================================================

function setPasswordMode(isEdit) {

    if (!userPassword) {
        return;
    }

    // Always clear password when modal opens.
    // Never populate an old password.
    userPassword.value = '';
    userPassword.type = 'password';

    if (userPasswordEyeIcon) {
        userPasswordEyeIcon.className =
            'bi bi-eye';
    }


    if (isEdit) {

        // Edit mode:
        // password optional.
        userPassword.required = false;

        if (passwordLabel) {
            passwordLabel.textContent =
                'Password Baru';
        }

        if (passwordRequiredIndicator) {
            passwordRequiredIndicator
                .classList
                .add('hidden');
        }

        if (passwordOptionalBadge) {
            passwordOptionalBadge
                .classList
                .remove('hidden');
        }

        if (passwordCreateHelp) {
            passwordCreateHelp
                .classList
                .add('hidden');
        }

        if (passwordEditHelp) {
            passwordEditHelp
                .classList
                .remove('hidden');
        }

        userPassword.placeholder =
            'Kosongkan jika tidak ingin mengubah password';

    } else {

        // Create mode:
        // password required.
        userPassword.required = true;

        if (passwordLabel) {
            passwordLabel.textContent =
                'Password';
        }

        if (passwordRequiredIndicator) {
            passwordRequiredIndicator
                .classList
                .remove('hidden');
        }

        if (passwordOptionalBadge) {
            passwordOptionalBadge
                .classList
                .add('hidden');
        }

        if (passwordCreateHelp) {
            passwordCreateHelp
                .classList
                .remove('hidden');
        }

        if (passwordEditHelp) {
            passwordEditHelp
                .classList
                .add('hidden');
        }

        userPassword.placeholder =
            'Minimal 8 karakter';
    }
}


// ============================================================
// CONFIRMATION TEXT
// ============================================================

function updateConfirmationText() {

    const email =
        document
            .getElementById('userEmail')
            ?.value || '';

    const role =
        document
            .getElementById('userRole')
            ?.value || 'Pegawai';

    const id =
        document
            .getElementById('userId')
            ?.value || '';

    const password =
        userPassword?.value || '';

    const confirmMsg =
        document.getElementById(
            'confirmationMessage'
        );

    if (!confirmMsg) {
        return;
    }


    if (email.trim() === '') {
        confirmMsg.innerHTML =
            'Masukkan email untuk melihat konfirmasi.';

        return;
    }


    // Edit user
    if (id) {

        const passwordInfo =
            password.length > 0
                ? ' Password pengguna juga akan <strong>diperbarui</strong>.'
                : ' Password lama akan <strong>tetap digunakan</strong>.';

        confirmMsg.innerHTML = `
            Pengguna dengan email
            <strong>${escapeHtml(email)}</strong>
            akan diperbarui dan ditetapkan sebagai
            <strong>${escapeHtml(role)}</strong>.
            ${passwordInfo}
        `;

        return;
    }


    // Create user
    confirmMsg.innerHTML = `
        Pengguna baru dengan email
        <strong>${escapeHtml(email)}</strong>
        akan dibuat sebagai
        <strong>${escapeHtml(role)}</strong>.
    `;
}


// ============================================================
// OPEN MODAL
// ============================================================

function openModal(id = null) {

    const modalTitle =
        document.getElementById(
            'modalTitle'
        );

    const userId =
        document.getElementById(
            'userId'
        );

    const userEmail =
        document.getElementById(
            'userEmail'
        );

    const userRole =
        document.getElementById(
            'userRole'
        );


    modal?.classList.remove(
        'hidden'
    );


    formError?.classList.add(
        'hidden'
    );


    if (formError) {
        formError.textContent = '';
    }


    // ========================================================
    // EDIT USER
    // ========================================================

    if (id) {

        if (modalTitle) {
            modalTitle.textContent =
                'Edit Pengguna';
        }

        if (modalDescription) {
            modalDescription.textContent =
                'Perbarui email, role, atau password pengguna.';
        }


        const user =
            state.users.find(
                (item) =>
                    Number(item.id) ===
                    Number(id)
            );


        if (user) {

            if (userId) {
                userId.value =
                    user.id;
            }

            if (userEmail) {
                userEmail.value =
                    user.email || '';
            }


            const role =
                user.roles &&
                user.roles.length > 0
                    ? user.roles[0].name
                    : 'Pegawai';


            if (userRole) {
                userRole.value =
                    role;
            }
        }


        setPasswordMode(true);

    }

    // ========================================================
    // CREATE USER
    // ========================================================

    else {

        if (modalTitle) {
            modalTitle.textContent =
                'Tambah Pengguna';
        }

        if (modalDescription) {
            modalDescription.textContent =
                'Tambahkan akun baru dan tentukan hak akses pengguna.';
        }


        if (userId) {
            userId.value = '';
        }

        if (userEmail) {
            userEmail.value = '';
        }

        if (userRole) {
            userRole.value =
                'Pegawai';
        }


        setPasswordMode(false);
    }


    updateConfirmationText();


    window.setTimeout(
        () => {
            userEmail?.focus();
        },
        100
    );
}


// ============================================================
// CLOSE MODAL
// ============================================================

function closeModal() {

    modal?.classList.add(
        'hidden'
    );


    if (formError) {
        formError.classList.add(
            'hidden'
        );

        formError.textContent = '';
    }


    if (userPassword) {
        userPassword.value = '';
        userPassword.type =
            'password';
    }


    if (userPasswordEyeIcon) {
        userPasswordEyeIcon.className =
            'bi bi-eye';
    }
}


// ============================================================
// LOAD USERS
// ============================================================

async function loadUsers(
    page = state.currentPage
) {

    if (!tableBody) {
        return;
    }


    state.isLoading = true;

    updateTableUI();


    try {

        const url =
            new URL(
                '/api/admin/users',
                window.location.origin
            );


        url.searchParams.append(
            'page',
            page
        );

        url.searchParams.append(
            'per_page',
            state.perPage
        );


        if (state.searchQuery) {
            url.searchParams.append(
                'search',
                state.searchQuery
            );
        }


        const response =
            await fetch(
                url.toString(),
                {
                    headers: {
                        'Accept':
                            'application/json',

                        'Authorization':
                            `Bearer ${token}`
                    }
                }
            );


        if (!response.ok) {
            throw new Error(
                'Gagal memuat data pengguna'
            );
        }


        const data =
            await response.json();


        state.users =
            Array.isArray(data.data)
                ? data.data
                : [];


        state.currentPage =
            Number(
                data.current_page || 1
            );


        state.lastPage =
            Number(
                data.last_page || 1
            );


        state.total =
            Number(
                data.total || 0
            );


        updateStatsUI();

        updatePaginationUI();

    } catch (error) {

        console.error(
            'Error loading users:',
            error
        );


        skeletonLoader
            ?.classList
            .add('hidden');


        tableBody.innerHTML = `
            <tr>
                <td
                    colspan="5"
                    class="px-5 py-10 text-center text-red-500"
                >
                    Terjadi kesalahan:
                    ${escapeHtml(error.message)}
                </td>
            </tr>
        `;


        tableBody.classList.remove(
            'hidden'
        );

    } finally {

        state.isLoading = false;

        updateTableUI();
    }
}


// ============================================================
// UPDATE TABLE
// ============================================================

function updateTableUI() {

    if (state.isLoading) {

        skeletonLoader
            ?.classList
            .remove('hidden');

        tableBody
            ?.classList
            .add('hidden');

        emptyResult
            ?.classList
            .add('hidden');

        paginationContainer
            ?.classList
            .add('hidden');

        return;
    }


    skeletonLoader
        ?.classList
        .add('hidden');


    if (state.users.length === 0) {

        tableBody.innerHTML = '';

        tableBody.classList.add(
            'hidden'
        );

        emptyResult
            ?.classList
            .remove('hidden');

        paginationContainer
            ?.classList
            .add('hidden');

        return;
    }


    emptyResult
        ?.classList
        .add('hidden');


    let html = '';


    const startIndex =
        (
            state.currentPage - 1
        ) *
        state.perPage;


    state.users.forEach(
        (user, index) => {

            const rowNumber =
                startIndex +
                index +
                1;


            const roleName =
                user.roles &&
                user.roles.length > 0
                    ? user.roles[0].name
                    : 'Pegawai';


            const roleBadgeClass =
                roleName === 'Admin'
                    ? 'bg-green-100 text-green-700 ring-green-200'
                    : 'bg-slate-100 text-slate-700 ring-slate-200';


            html += `
                <tr
                    class="
                        bg-white
                        transition-colors
                        hover:bg-slate-50
                    "
                >
                    <td
                        class="
                            px-5 py-4
                            text-sm
                            text-slate-500
                        "
                    >
                        ${rowNumber}
                    </td>

                    <td class="px-5 py-4">
                        <p
                            class="
                                font-semibold
                                text-slate-900
                            "
                        >
                            ${escapeHtml(user.name || '-')}
                        </p>
                    </td>

                    <td
                        class="
                            px-5 py-4
                            text-sm
                            text-slate-600
                        "
                    >
                        ${escapeHtml(user.email || '-')}
                    </td>

                    <td
                        class="
                            px-5 py-4
                            text-sm
                        "
                    >
                        <span
                            class="
                                inline-flex
                                items-center
                                rounded-md
                                px-2 py-1
                                text-xs
                                font-medium
                                ring-1
                                ring-inset
                                ${roleBadgeClass}
                            "
                        >
                            ${escapeHtml(roleName)}
                        </span>
                    </td>

                    <td class="px-5 py-4">
                        <div
                            class="
                                flex
                                justify-end
                                gap-2
                            "
                        >
                            <button
                                type="button"
                                class="
                                    flex h-9 w-9
                                    items-center
                                    justify-center
                                    border
                                    border-blue-200
                                    text-blue-800
                                    transition
                                    hover:bg-blue-50
                                "
                                title="Edit"
                                onclick="editUser(${Number(user.id)})"
                            >
                                <i class="bi bi-pencil-square"></i>
                            </button>

                            <button
                                type="button"
                                class="
                                    flex h-9 w-9
                                    items-center
                                    justify-center
                                    border
                                    border-red-200
                                    text-red-600
                                    transition
                                    hover:bg-red-50
                                "
                                title="Hapus"
                                onclick="deleteUser(
                                    ${Number(user.id)},
                                    '${escapeJsString(user.name || user.email || 'Pengguna')}'
                                )"
                            >
                                <i class="bi bi-trash3"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        }
    );


    tableBody.innerHTML =
        html;


    tableBody.classList.remove(
        'hidden'
    );


    paginationContainer
        ?.classList
        .remove('hidden');
}


// ============================================================
// STATISTICS
// ============================================================

function updateStatsUI() {

    if (statTotalUsers) {
        statTotalUsers.innerText =
            state.total;
    }


    if (statTotalAdmins) {

        const adminCount =
            state.users.filter(
                (user) =>
                    user.roles &&
                    user.roles.some(
                        (role) =>
                            role.name ===
                            'Admin'
                    )
            ).length;


        statTotalAdmins.innerText =
            adminCount +
            (
                state.total >
                state.perPage
                    ? '+'
                    : ''
            );
    }
}


// ============================================================
// PAGINATION
// ============================================================

function updatePaginationUI() {

    if (state.total === 0) {

        paginationContainer
            ?.classList
            .add('hidden');

        return;
    }


    const start =
        (
            state.currentPage - 1
        ) *
        state.perPage +
        1;


    const end =
        Math.min(
            state.currentPage *
            state.perPage,
            state.total
        );


    if (pageStart) {
        pageStart.textContent =
            start;
    }


    if (pageEnd) {
        pageEnd.textContent =
            end;
    }


    if (pageTotal) {
        pageTotal.textContent =
            state.total;
    }


    let html = '';


    // Previous
    html += `
        <button
            type="button"
            data-page="${state.currentPage - 1}"

            ${
                state.currentPage === 1
                    ? 'disabled'
                    : ''
            }

            class="
                pagination-button
                flex h-9 w-9
                items-center
                justify-center
                rounded-sm
                border
                border-slate-200
                bg-white
                text-slate-500
                hover:bg-slate-50
                disabled:cursor-not-allowed
                disabled:opacity-50
            "
        >
            <i class="bi bi-chevron-left"></i>
        </button>
    `;


    // Pages
    for (
        let i = 1;
        i <= state.lastPage;
        i++
    ) {

        if (
            i === 1 ||
            i === state.lastPage ||
            (
                i >=
                state.currentPage - 1 &&
                i <=
                state.currentPage + 1
            )
        ) {

            html += `
                <button
                    type="button"
                    data-page="${i}"

                    class="
                        pagination-button
                        flex h-9
                        min-w-9
                        items-center
                        justify-center
                        rounded-sm
                        px-2
                        text-sm
                        font-semibold
                        transition-colors

                        ${
                            state.currentPage === i
                                ? 'bg-blue-900 text-white'
                                : 'border border-slate-200 bg-white text-slate-700 hover:bg-slate-50'
                        }
                    "
                >
                    ${i}
                </button>
            `;

        } else if (
            i ===
                state.currentPage - 2 ||
            i ===
                state.currentPage + 2
        ) {

            html += `
                <span
                    class="
                        flex h-9
                        items-center
                        justify-center
                        px-2
                        text-slate-400
                    "
                >
                    ...
                </span>
            `;
        }
    }


    // Next
    html += `
        <button
            type="button"
            data-page="${state.currentPage + 1}"

            ${
                state.currentPage ===
                state.lastPage
                    ? 'disabled'
                    : ''
            }

            class="
                pagination-button
                flex h-9 w-9
                items-center
                justify-center
                rounded-sm
                border
                border-slate-200
                bg-white
                text-slate-500
                hover:bg-slate-50
                disabled:cursor-not-allowed
                disabled:opacity-50
            "
        >
            <i class="bi bi-chevron-right"></i>
        </button>
    `;


    if (paginationButtons) {
        paginationButtons.innerHTML =
            html;
    }
}


// ============================================================
// SAVE USER
// ============================================================

async function saveUser() {

    const id =
        document
            .getElementById('userId')
            ?.value || '';

    const email =
        document
            .getElementById('userEmail')
            ?.value
            .trim() || '';

    const role =
        document
            .getElementById('userRole')
            ?.value || '';

    const password =
        userPassword?.value || '';

    const isEdit =
        Boolean(id);


    // ========================================================
    // VALIDATION
    // ========================================================

    if (!email) {
        showFormError(
            'Email wajib diisi.'
        );

        return;
    }


    if (!role) {
        showFormError(
            'Role wajib dipilih.'
        );

        return;
    }


    // Password required when CREATE
    if (
        !isEdit &&
        !password
    ) {
        showFormError(
            'Password wajib diisi untuk pengguna baru.'
        );

        userPassword?.focus();

        return;
    }


    // Validate password length when:
    // - create
    // - or password filled during edit
    if (
        password &&
        password.length < 8
    ) {
        showFormError(
            'Password minimal harus terdiri dari 8 karakter.'
        );

        userPassword?.focus();

        return;
    }


    hideFormError();


    setButtonLoading(
        saveUserBtn,
        true
    );


    const method =
        isEdit
            ? 'PUT'
            : 'POST';


    const endpoint =
        isEdit
            ? `/api/admin/users/${id}`
            : '/api/admin/users';


    // ========================================================
    // BUILD PAYLOAD
    // ========================================================

    const payload = {
        email,
        role
    };


    /*
     * Create:
     * always send password.
     *
     * Edit:
     * only send password when field is filled.
     *
     * This prevents an empty password from overwriting
     * the user's current password.
     */
    if (
        !isEdit ||
        password.length > 0
    ) {
        payload.password =
            password;
    }


    // ========================================================
    // REQUEST
    // ========================================================

    try {

        const response =
            await fetch(
                endpoint,
                {
                    method,

                    headers: {
                        'Content-Type':
                            'application/json',

                        'Accept':
                            'application/json',

                        'Authorization':
                            `Bearer ${token}`
                    },

                    body:
                        JSON.stringify(
                            payload
                        )
                }
            );


        const data =
            await response.json();


        if (response.ok) {

            closeModal();


            await loadUsers(
                state.currentPage
            );


            showToast(
                isEdit
                    ? 'Pengguna berhasil diperbarui.'
                    : 'Pengguna berhasil ditambahkan.',
                'success'
            );


            return;
        }


        // Laravel validation
        if (
            response.status === 422 &&
            data.errors
        ) {

            const firstError =
                Object
                    .values(
                        data.errors
                    )
                    .flat()
                    .find(Boolean);


            throw new Error(
                firstError ||
                data.message ||
                'Data pengguna tidak valid.'
            );
        }


        throw new Error(
            data.message ||
            'Gagal menyimpan pengguna.'
        );

    } catch (error) {

        showFormError(
            error.message
        );


        showToast(
            'Terjadi kesalahan: ' +
            error.message,
            'error'
        );

    } finally {

        setButtonLoading(
            saveUserBtn,
            false
        );
    }
}


// ============================================================
// DELETE USER
// ============================================================

async function deleteUser(
    id,
    name
) {

    const confirmed =
        await showDoubleConfirmModal(
            'Konfirmasi Hapus Pengguna',
            name
        );


    if (!confirmed) {
        return;
    }


    try {

        const response =
            await fetch(
                `/api/admin/users/${id}`,
                {
                    method:
                        'DELETE',

                    headers: {
                        'Accept':
                            'application/json',

                        'Authorization':
                            `Bearer ${token}`
                    }
                }
            );


        if (response.ok) {

            if (
                state.users.length === 1 &&
                state.currentPage > 1
            ) {
                state.currentPage--;
            }


            await loadUsers(
                state.currentPage
            );


            showToast(
                'Pengguna berhasil dihapus.',
                'success'
            );


            return;
        }


        const data =
            await response.json();


        showToast(
            data.message ||
            'Gagal menghapus pengguna.',
            'error'
        );

    } catch (error) {

        showToast(
            'Koneksi gagal.',
            'error'
        );
    }
}


// ============================================================
// FORM ERROR
// ============================================================

function showFormError(message) {

    if (!formError) {
        return;
    }


    formError.textContent =
        message;


    formError.classList.remove(
        'hidden'
    );
}


function hideFormError() {

    if (!formError) {
        return;
    }


    formError.textContent = '';


    formError.classList.add(
        'hidden'
    );
}


// ============================================================
// SECURITY HELPERS
// ============================================================

function escapeHtml(value) {

    return String(value)
        .replaceAll(
            '&',
            '&amp;'
        )
        .replaceAll(
            '<',
            '&lt;'
        )
        .replaceAll(
            '>',
            '&gt;'
        )
        .replaceAll(
            '"',
            '&quot;'
        )
        .replaceAll(
            "'",
            '&#039;'
        );
}


function escapeJsString(value) {

    return String(value)
        .replaceAll(
            '\\',
            '\\\\'
        )
        .replaceAll(
            "'",
            "\\'"
        )
        .replaceAll(
            '\n',
            ' '
        )
        .replaceAll(
            '\r',
            ' '
        );
}


// ============================================================
// EXPOSE FUNCTIONS FOR INLINE HTML EVENTS
// ============================================================

window.loadUsers =
    loadUsers;

window.openModal =
    openModal;

window.closeModal =
    closeModal;

window.saveUser =
    saveUser;

window.editUser =
    openModal;

window.deleteUser =
    deleteUser;

window.updateConfirmationText =
    updateConfirmationText;


// ============================================================
// INIT
// ============================================================

function init() {

    if (
        document.getElementById(
            'userTableBody'
        )
    ) {

        bindEvents();

        loadUsers(1);
    }
}


// ============================================================
// INITIAL LOAD
// ============================================================

if (
    document.readyState ===
    'loading'
) {

    document.addEventListener(
        'DOMContentLoaded',
        init
    );

} else {

    init();
}