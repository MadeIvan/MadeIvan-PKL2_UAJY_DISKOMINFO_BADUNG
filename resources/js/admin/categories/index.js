import 'bootstrap-icons/font/bootstrap-icons.css';

// Token Authentication
const token = localStorage.getItem('auth_token');

// UI Elements
const tableBody = document.getElementById('categoryTableBody');
const emptyResult = document.getElementById('emptyResult');
const skeletonLoader = document.getElementById('skeletonLoader');
const searchInput = document.getElementById('categorySearch');
const modal = document.getElementById('categoryModal');
const modalTitle = document.getElementById('modalTitle');
const formError = document.getElementById('formError');
const btnSaveText = document.getElementById('btnSaveText');
const saveCategoryBtn = document.getElementById('saveCategoryBtn');

const statTotalCategories = document.getElementById('statTotalCategories');
const statEmptyCategories = document.getElementById('statEmptyCategories');

const paginationContainer = document.getElementById('paginationContainer');
const paginationInfo = document.getElementById('paginationInfo');
const paginationButtons = document.getElementById('paginationButtons');
const pageStart = document.getElementById('pageStart');
const pageEnd = document.getElementById('pageEnd');
const pageTotal = document.getElementById('pageTotal');

// State
const state = {
    data: [],
    currentPage: 1,
    lastPage: 1,
    total: 0,
    search: ''
};

let searchTimeout = null;

// Initialization
function bindEvents() {
    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            state.search = e.target.value.trim();
            window.clearTimeout(searchTimeout);
            searchTimeout = window.setTimeout(() => {
                loadCategories(1);
            }, 400);
        });
    }

    if (paginationButtons) {
        paginationButtons.addEventListener('click', (e) => {
            const btn = e.target.closest('.pagination-button');
            if (!btn || btn.disabled) return;
            const page = Number(btn.dataset.page);
            if (page && page !== state.currentPage) {
                loadCategories(page);
            }
        });
    }
}

// Fetch Categories
async function loadCategories(page = state.currentPage) {
    if (!tableBody) return;
    
    tableBody.classList.add('hidden');
    emptyResult.classList.add('hidden');
    paginationContainer.classList.add('hidden');
    skeletonLoader.classList.remove('hidden');
    
    try {
        const params = new URLSearchParams();
        params.set('page', page);
        if (state.search) {
            params.set('search', state.search);
        }

        const res = await fetch(`/api/admin/categories?${params.toString()}`, {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });
        if(!res.ok) throw new Error('Gagal memuat kategori');
        
        const result = await res.json();
        
        state.data = Array.isArray(result.data) ? result.data : [];
        state.currentPage = Number(result.meta?.current_page || 1);
        state.lastPage = Number(result.meta?.last_page || 1);
        state.total = Number(result.meta?.total || 0);

        updateStatistics(result.meta);
        renderTable();
        renderPagination();
    } catch(e) {
        skeletonLoader.classList.add('hidden');
        tableBody.innerHTML = `<tr><td colspan="6" class="px-5 py-10 text-center text-red-500">Terjadi kesalahan: ${e.message}</td></tr>`;
        tableBody.classList.remove('hidden');
    }
}

function updateStatistics(meta) {
    if (meta && statTotalCategories && statEmptyCategories) {
        statTotalCategories.textContent = meta.total_categories || 0;
        statEmptyCategories.textContent = meta.empty_categories || 0;
    }
}

function renderTable() {
    skeletonLoader.classList.add('hidden');

    if(state.data.length === 0) {
        tableBody.innerHTML = '';
        tableBody.classList.add('hidden');
        emptyResult.classList.remove('hidden');
        return;
    }

    emptyResult.classList.add('hidden');
    let html = '';
    
    // Calculate starting index based on pagination
    const startIndex = (state.currentPage - 1) * 10;

    state.data.forEach((cat, index) => {
        html += `
            <tr class="hover:bg-slate-50 transition-colors">
                <td class="px-5 py-4 text-sm text-slate-500">${startIndex + index + 1}</td>
                <td class="px-5 py-4">
                    <p class="font-semibold text-slate-900">${cat.name}</p>
                </td>
                <td class="px-5 py-4 text-sm text-slate-600">${cat.slug}</td>
                <td class="px-5 py-4 text-sm text-slate-500 truncate max-w-[200px]">${cat.description || '-'}</td>
                <td class="px-5 py-4 text-center">
                    <span class="inline-flex items-center justify-center rounded-sm bg-blue-50 px-2.5 py-0.5 text-xs font-semibold text-blue-700">
                        ${cat.applications_count || 0} Aplikasi
                    </span>
                </td>
                <td class="px-5 py-4">
                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="editCategory(${cat.id})" class="flex h-9 w-9 items-center justify-center rounded-sm border border-slate-200 text-slate-600 hover:bg-amber-50 hover:text-amber-700 transition-colors" title="Edit">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <button type="button" onclick="deleteCategory(${cat.id}, '${cat.name.replace(/'/g, "\\'")}')" class="flex h-9 w-9 items-center justify-center rounded-sm border border-slate-200 text-slate-600 hover:bg-red-50 hover:text-red-600 transition-colors" title="Hapus">
                            <i class="bi bi-trash3"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });
    
    tableBody.innerHTML = html;
    tableBody.classList.remove('hidden');
}

function renderPagination() {
    if (state.total === 0) {
        paginationContainer.classList.add('hidden');
        return;
    }

    paginationContainer.classList.remove('hidden');

    const start = (state.currentPage - 1) * 10 + 1;
    const end = Math.min(state.currentPage * 10, state.total);
    
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
        // simple logic: show first, last, and +/- 2 from current
        if (i === 1 || i === state.lastPage || (i >= state.currentPage - 1 && i <= state.currentPage + 1)) {
            html += `
                <button type="button" data-page="${i}" class="pagination-button flex h-9 min-w-[36px] items-center justify-center rounded-sm px-2 text-sm font-semibold transition-colors ${state.currentPage === i ? 'bg-blue-900 text-white' : 'bg-white text-slate-700 hover:bg-slate-50 border border-slate-200'}">
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


// Modal Operations
function openModal(id = null) {
    formError.classList.add('hidden');
    if(id) {
        modalTitle.textContent = "Edit Kategori";
        const cat = state.data.find(c => c.id === id);
        if(!cat) return;
        document.getElementById('categoryId').value = cat.id;
        document.getElementById('categoryName').value = cat.name;
        document.getElementById('categoryDescription').value = cat.description || '';
    } else {
        modalTitle.textContent = "Tambah Kategori";
        document.getElementById('categoryId').value = '';
        document.getElementById('categoryName').value = '';
        document.getElementById('categoryDescription').value = '';
    }
    modal.classList.remove('hidden');
}

function closeModal() {
    modal.classList.add('hidden');
}

// Save/Update Category
async function saveCategory() {
    const id = document.getElementById('categoryId').value;
    const name = document.getElementById('categoryName').value.trim();
    const description = document.getElementById('categoryDescription').value.trim();
    
    if(!name) {
        formError.textContent = "Nama kategori wajib diisi.";
        formError.classList.remove('hidden');
        return;
    }

    saveCategoryBtn.disabled = true;
    btnSaveText.textContent = "Menyimpan...";

    const method = id ? 'PUT' : 'POST';
    const url = id ? `/api/admin/categories/${id}` : '/api/admin/categories';

    try {
        const res = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`
            },
            body: JSON.stringify({ name, description })
        });

        const data = await res.json();
        
        if(res.ok) {
            closeModal();
            loadCategories(state.currentPage);
            showNotification(id ? 'Kategori berhasil diperbarui.' : 'Kategori berhasil ditambahkan.');
        } else {
            let errorMsg = data.message || 'Terjadi kesalahan.';
            if (res.status === 422 && data.errors?.name) {
                errorMsg = 'Kategori dengan nama tersebut sudah ada. Silakan gunakan nama lain.';
            }
            formError.textContent = errorMsg;
            formError.classList.remove('hidden');
            showNotification(errorMsg, 'error');
        }
    } catch(e) {
        formError.textContent = "Koneksi gagal.";
        formError.classList.remove('hidden');
    } finally {
        saveCategoryBtn.disabled = false;
        btnSaveText.textContent = "Simpan";
    }
}

// Delete Category
async function deleteCategory(id, name) {
    if(!confirm(`Hapus master data kategori "${name}"? Tindakan ini tidak dapat dibatalkan.`)) return;
    
    try {
        const res = await fetch(`/api/admin/categories/${id}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`
            }
        });
        const data = await res.json();

        if(res.ok) {
            // Check if deleting last item on page
            if (state.data.length === 1 && state.currentPage > 1) {
                state.currentPage--;
            }
            loadCategories(state.currentPage);
            showNotification('Kategori berhasil dihapus.', 'success');
        } else {
            alert(data.message || 'Gagal menghapus kategori.');
        }
    } catch(e) {
        alert('Koneksi gagal.');
    }
}

// Refresh wrapper for inline onclick
function filterTable() {
    // Deprecated for direct manual call, handled by input listener now
    // Keep it here for the Refresh button backwards compatibility
    loadCategories(1);
}

let notificationTimeout;
function showNotification(message, type = 'success') {
    const notification = document.getElementById('notification');
    if (!notification) return;

    const styles = {
        success: 'border-emerald-200 bg-emerald-50 text-emerald-700',
        error: 'border-red-200 bg-red-50 text-red-700',
    };

    const selectedStyle = styles[type] || styles.success;
    
    window.clearTimeout(notificationTimeout);
    
    notification.className = `mb-6 rounded-sm border px-4 py-3 text-sm ${selectedStyle}`;
    notification.textContent = message;
    notification.classList.remove('hidden');

    notificationTimeout = window.setTimeout(() => {
        notification.classList.add('hidden');
    }, 5000);
}

// Expose functions to window for inline onclick handlers
window.loadCategories = loadCategories;
window.filterTable = filterTable;
window.openModal = openModal;
window.closeModal = closeModal;
window.saveCategory = saveCategory;
window.deleteCategory = deleteCategory;
window.editCategory = openModal;

// Initial load
if (document.getElementById('categoryTableBody')) {
    document.addEventListener('DOMContentLoaded', () => {
        bindEvents();
        loadCategories(1);
    });
}
