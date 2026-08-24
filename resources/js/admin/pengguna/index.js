import 'bootstrap-icons/font/bootstrap-icons.css';
import { showToast, setButtonLoading, showDoubleConfirmModal } from '../utils.js';

// Token Authentication
const token = localStorage.getItem('auth_token');

// UI Elements
const tableBody = document.getElementById('userTableBody');
const emptyResult = document.getElementById('emptyResult');
const skeletonLoader = document.getElementById('skeletonLoader');
const searchInput = document.getElementById('userSearch');
const modal = document.getElementById('userModal');
const formError = document.getElementById('formError');
const btnSaveText = document.getElementById('btnSaveText');
const saveUserBtn = document.getElementById('saveUserBtn');

const statTotalUsers = document.getElementById('statTotalUsers');
const statTotalAdmins = document.getElementById('statTotalAdmins');

const paginationContainer = document.getElementById('paginationContainer');
const paginationInfo = document.getElementById('paginationInfo');
const paginationButtons = document.getElementById('paginationButtons');
const pageStart = document.getElementById('pageStart');
const pageEnd = document.getElementById('pageEnd');
const pageTotal = document.getElementById('pageTotal');

// State
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

// Initialization
function bindEvents() {
    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            state.searchQuery = e.target.value.trim();
            window.clearTimeout(searchTimeout);
            searchTimeout = window.setTimeout(() => {
                loadUsers(1);
            }, 400);
        });
    }

    if (paginationButtons) {
        paginationButtons.addEventListener('click', (e) => {
            const btn = e.target.closest('.pagination-button');
            if (!btn || btn.disabled) return;
            const page = Number(btn.dataset.page);
            if (page && page !== state.currentPage) {
                loadUsers(page);
            }
        });
    }
}

function updateConfirmationText() {
    const email = document.getElementById('userEmail').value;
    const role = document.getElementById('userRole').value;
    const confirmMsg = document.getElementById('confirmationMessage');
    const id = document.getElementById('userId').value;
    
    if (email.trim() === '') {
        confirmMsg.innerHTML = 'Masukkan email untuk melihat konfirmasi.';
    } else {
        const actionText = id ? 'diperbarui dan ditetapkan' : 'ditetapkan';
        confirmMsg.innerHTML = `Apakah email <strong>${email}</strong> akan ${actionText} sebagai <strong>${role}</strong>?`;
    }
}

function openModal(id = null) {
    const modalTitle = document.getElementById('modalTitle');
    modal.classList.remove('hidden');
    formError.classList.add('hidden');
    
    if (id) {
        modalTitle.textContent = 'Edit Pengguna';
        const user = state.users.find(u => u.id === id);
        if (user) {
            document.getElementById('userId').value = user.id;
            document.getElementById('userEmail').value = user.email;
            const role = user.roles && user.roles.length > 0 ? user.roles[0].name : 'Pegawai';
            document.getElementById('userRole').value = role;
        }
    } else {
        modalTitle.textContent = 'Tambah Pengguna';
        document.getElementById('userId').value = '';
        document.getElementById('userEmail').value = '';
        document.getElementById('userRole').value = 'Pegawai';
    }
    
    updateConfirmationText();
}

function closeModal() {
    modal.classList.add('hidden');
}

async function loadUsers(page = state.currentPage) {
    if (!tableBody) return;

    state.isLoading = true;
    updateTableUI();

    try {
        const url = new URL('/api/admin/users', window.location.origin);
        url.searchParams.append('page', page);
        url.searchParams.append('per_page', state.perPage);
        if (state.searchQuery) {
            url.searchParams.append('search', state.searchQuery);
        }

        const response = await fetch(url.toString(), {
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`
            }
        });

        if (!response.ok) throw new Error('Gagal memuat data pengguna');

        const data = await response.json();
        
        state.users = Array.isArray(data.data) ? data.data : [];
        state.currentPage = Number(data.current_page || 1);
        state.lastPage = Number(data.last_page || 1);
        state.total = Number(data.total || 0);

        updateStatsUI();
        updatePaginationUI();
    } catch (error) {
        console.error('Error loading users:', error);
        skeletonLoader.classList.add('hidden');
        tableBody.innerHTML = `<tr><td colspan="5" class="px-5 py-10 text-center text-red-500">Terjadi kesalahan: ${error.message}</td></tr>`;
        tableBody.classList.remove('hidden');
    } finally {
        state.isLoading = false;
        updateTableUI();
    }
}

function updateTableUI() {
    if (state.isLoading) {
        skeletonLoader.classList.remove('hidden');
        tableBody.classList.add('hidden');
        emptyResult.classList.add('hidden');
        paginationContainer.classList.add('hidden');
        return;
    }

    skeletonLoader.classList.add('hidden');

    if (state.users.length === 0) {
        tableBody.innerHTML = '';
        tableBody.classList.add('hidden');
        emptyResult.classList.remove('hidden');
        paginationContainer.classList.add('hidden');
        return;
    }

    emptyResult.classList.add('hidden');
    let html = '';

    const startIndex = (state.currentPage - 1) * state.perPage;

    state.users.forEach((user, index) => {
        const rowNumber = startIndex + index + 1;
        const roleName = user.roles && user.roles.length > 0 ? user.roles[0].name : 'Pegawai';
        const roleBadgeClass = roleName === 'Admin' ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-700';

        html += `
            <tr class="hover:bg-slate-50 transition-colors bg-white">
                <td class="px-5 py-4 text-sm text-slate-500">${rowNumber}</td>
                <td class="px-5 py-4">
                    <p class="font-semibold text-slate-900">${user.name}</p>
                </td>
                <td class="px-5 py-4 text-sm text-slate-600">${user.email}</td>
                <td class="px-5 py-4 text-sm">
                    <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset ${roleBadgeClass}">
                        ${roleName}
                    </span>
                </td>
                <td class="px-5 py-4">
                    <div class="flex justify-end gap-2">
                        <button class="flex h-9 w-9 items-center justify-center border border-blue-200 text-blue-800 transition hover:bg-blue-50" title="Edit" onclick="editUser(${user.id})"><i class="bi bi-pencil-square"></i></button>
                        <button class="flex h-9 w-9 items-center justify-center border border-red-200 text-red-600 transition hover:bg-red-50" title="Hapus" onclick="deleteUser(${user.id}, '${user.name.replace(/'/g, "\\'")}')"><i class="bi bi-trash3"></i></button>
                    </div>
                </td>
            </tr>
        `;
    });
    
    tableBody.innerHTML = html;
    tableBody.classList.remove('hidden');
    paginationContainer.classList.remove('hidden');
}

function updateStatsUI() {
    if (statTotalUsers) statTotalUsers.innerText = state.total;
    if (statTotalAdmins) {
        let adminCount = state.users.filter(u => u.roles && u.roles.some(r => r.name === 'Admin')).length;
        statTotalAdmins.innerText = adminCount + (state.total > state.perPage ? "+" : "");
    }
}

function updatePaginationUI() {
    if (state.total === 0) {
        paginationContainer.classList.add('hidden');
        return;
    }

    const start = (state.currentPage - 1) * state.perPage + 1;
    const end = Math.min(state.currentPage * state.perPage, state.total);

    if (pageStart) pageStart.textContent = start;
    if (pageEnd) pageEnd.textContent = end;
    if (pageTotal) pageTotal.textContent = state.total;

    let html = '';

    // Prev Button
    html += `
        <button type="button" data-page="${state.currentPage - 1}" ${state.currentPage === 1 ? 'disabled' : ''} class="pagination-button flex h-9 w-9 items-center justify-center rounded-sm border border-slate-200 bg-white text-slate-500 hover:bg-slate-50 disabled:opacity-50 disabled:cursor-not-allowed">
            <i class="bi bi-chevron-left"></i>
        </button>
    `;

    // Pages
    for (let i = 1; i <= state.lastPage; i++) {
        if (i === 1 || i === state.lastPage || (i >= state.currentPage - 1 && i <= state.currentPage + 1)) {
            html += `
                <button type="button" data-page="${i}" class="pagination-button flex h-9 min-w-9 items-center justify-center rounded-sm px-2 text-sm font-semibold transition-colors ${state.currentPage === i ? 'bg-blue-900 text-white' : 'bg-white text-slate-700 hover:bg-slate-50 border border-slate-200'}">
                    ${i}
                </button>
            `;
        } else if (i === state.currentPage - 2 || i === state.currentPage + 2) {
            html += `<span class="flex h-9 items-center justify-center px-2 text-slate-400">...</span>`;
        }
    }

    // Next Button
    html += `
        <button type="button" data-page="${state.currentPage + 1}" ${state.currentPage === state.lastPage ? 'disabled' : ''} class="pagination-button flex h-9 w-9 items-center justify-center rounded-sm border border-slate-200 bg-white text-slate-500 hover:bg-slate-50 disabled:opacity-50 disabled:cursor-not-allowed">
            <i class="bi bi-chevron-right"></i>
        </button>
    `;

    if (paginationButtons) paginationButtons.innerHTML = html;
}

async function saveUser() {
    const id = document.getElementById('userId').value;
    const email = document.getElementById('userEmail').value;
    const role = document.getElementById('userRole').value;
    
    if (!email) {
        formError.innerText = 'Email wajib diisi.';
        formError.classList.remove('hidden');
        return;
    }

    formError.classList.add('hidden');
    setButtonLoading(saveUserBtn, true);

    const method = id ? 'PUT' : 'POST';
    const endpoint = id ? `/api/admin/users/${id}` : '/api/admin/users';

    try {
        const response = await fetch(endpoint, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`
            },
            body: JSON.stringify({ email, role })
        });

        const data = await response.json();

        if (response.ok) {
            closeModal();
            loadUsers(state.currentPage);
            showToast(id ? 'Pengguna berhasil diperbarui.' : 'Pengguna berhasil ditambahkan.');
        } else {
            let errorMsg = data.message || 'Gagal menyimpan pengguna.';
            if (response.status === 422 && data.errors?.email) {
                errorMsg = data.errors.email[0];
            }
            throw new Error(errorMsg);
        }
    } catch (error) {
        formError.innerText = error.message;
        formError.classList.remove('hidden');
        showToast("Terjadi kesalahan: " + error.message, "error");
    } finally {
        setButtonLoading(saveUserBtn, false);
    }
}

async function deleteUser(id, name) {
    const confirmed = await showDoubleConfirmModal(
        'Konfirmasi Hapus Pengguna',
        name
    );
    
    if (!confirmed) return;
    
    try {
        const response = await fetch(`/api/admin/users/${id}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`
            }
        });

        if (response.ok) {
            // Adjust pagination if deleted last item on current page
            if (state.users.length === 1 && state.currentPage > 1) {
                state.currentPage--;
            }
            loadUsers(state.currentPage);
            showToast('Pengguna berhasil dihapus.', 'success');
        } else {
            const data = await response.json();
            showToast(data.message || 'Gagal menghapus pengguna.', 'error');
        }
    } catch (error) {
        showToast('Koneksi gagal.', 'error');
    }
}

// Expose functions for inline onclick handlers
window.loadUsers = loadUsers;
window.openModal = openModal;
window.closeModal = closeModal;
window.saveUser = saveUser;
window.editUser = openModal;
window.deleteUser = deleteUser;
window.updateConfirmationText = updateConfirmationText;

function init() {
    if (document.getElementById('userTableBody')) {
        bindEvents();
        loadUsers(1);
    }
}

// Initial load
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
} else {
    init();
}
