@extends('components.admin.layouts.admin')

@section('title', 'Daftar Pengguna - Panel Administrator')
@section('page-title', 'Kelola Pengguna')

@push('scripts')
    @vite('resources/js/admin/pengguna/index.js')
@endpush

@section('content')

<section class="border border-slate-200 bg-white shadow-sm mb-7">
    <div class="flex flex-col gap-5 p-5 sm:flex-row sm:items-center sm:justify-between sm:p-6">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-blue-900">
                Manajemen Pengguna
            </p>

            <h2 class="mt-2 text-2xl font-bold text-slate-950">
                Daftar Pengguna
            </h2>

            <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-500">
                Kelola data pengguna sistem dan hak akses mereka.
            </p>
        </div>

        <button
            onclick="openModal()"
            type="button"
            class="inline-flex shrink-0 items-center justify-center gap-2 bg-blue-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-900 rounded-sm"
        >
            <i class="bi bi-plus-lg"></i>
            Tambah Pengguna
        </button>
    </div>
</section>

<!-- Statistic Cards -->
<div class="mb-7 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
    <div class="flex items-center gap-4 rounded-sm border border-slate-200 bg-white p-5 shadow-sm">
        <div class="flex h-12 w-12 items-center justify-center rounded-sm bg-blue-50 text-blue-900">
            <i class="bi bi-people text-xl"></i>
        </div>
        <div>
            <p class="text-sm font-medium text-slate-500">Total Pengguna</p>
            <h3 id="statTotalUsers" class="text-2xl font-bold text-slate-900">0</h3>
        </div>
    </div>
    
    <div class="flex items-center gap-4 rounded-sm border border-slate-200 bg-white p-5 shadow-sm">
        <div class="flex h-12 w-12 items-center justify-center rounded-sm bg-green-50 text-green-600">
            <i class="bi bi-shield-check text-xl"></i>
        </div>
        <div>
            <p class="text-sm font-medium text-slate-500">Admin</p>
            <h3 id="statTotalAdmins" class="text-2xl font-bold text-slate-900">0</h3>
        </div>
    </div>
</div>

<section class="overflow-hidden rounded-sm border border-slate-200 bg-white shadow-sm">
    <div class="border-b border-slate-200 p-5">
        <div class="grid gap-4 lg:grid-cols-[1fr_auto]">
            <div class="relative">
                <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input id="userSearch" type="search" placeholder="Cari pengguna..."
                    class="w-full rounded-sm border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm outline-none focus:border-blue-900 focus:bg-white">
            </div>
            <button type="button" onclick="loadUsers()"
                class="inline-flex items-center justify-center gap-2 rounded-sm border border-slate-200 px-4 py-3 text-sm text-slate-600 hover:bg-slate-50">
                <i class="bi bi-arrow-clockwise"></i>Refresh
            </button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full min-w-200">
            <thead class="bg-slate-50">
                <tr class="text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                    <th class="px-5 py-4 w-16">No</th>
                    <th class="px-5 py-4">Nama</th>
                    <th class="px-5 py-4">Email</th>
                    <th class="px-5 py-4">Role</th>
                    <th class="px-5 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody id="userTableBody" class="divide-y divide-slate-100 hidden">
                <!-- JS will populate rows here -->
            </tbody>
            
            <!-- Skeleton Loading -->
            <tbody id="skeletonLoader" class="divide-y divide-slate-100">
                @for ($i = 0; $i < 5; $i++)
                <tr class="animate-pulse bg-white">
                    <td class="px-5 py-4"><div class="h-4 w-8 rounded bg-slate-200"></div></td>
                    <td class="px-5 py-4"><div class="h-4 w-32 rounded bg-slate-200"></div></td>
                    <td class="px-5 py-4"><div class="h-4 w-48 rounded bg-slate-200"></div></td>
                    <td class="px-5 py-4"><div class="h-4 w-24 rounded bg-slate-200"></div></td>
                    <td class="px-5 py-4 flex justify-end gap-2">
                        <div class="h-9 w-9 rounded-sm bg-slate-200"></div>
                        <div class="h-9 w-9 rounded-sm bg-slate-200"></div>
                    </td>
                </tr>
                @endfor
            </tbody>
        </table>
    </div>

    <div id="emptyResult" class="hidden px-6 py-16 text-center">
        <i class="bi bi-search text-2xl text-slate-400"></i>
        <p class="mt-4 font-semibold">Pengguna tidak ditemukan</p>
        <p class="mt-2 text-sm text-slate-500">Tidak ada pengguna yang cocok dengan pencarian, atau data masih kosong.</p>
    </div>

    <!-- Pagination UI -->
    <div id="paginationContainer" class="hidden border-t border-slate-200 p-5 sm:flex sm:items-center sm:justify-between">
        <p class="text-sm text-slate-500" id="paginationInfo">Menampilkan <span class="font-medium text-slate-900" id="pageStart">0</span> sampai <span class="font-medium text-slate-900" id="pageEnd">0</span> dari <span class="font-medium text-slate-900" id="pageTotal">0</span> Pengguna</p>
        <div class="mt-4 flex flex-wrap justify-center gap-1 sm:mt-0" id="paginationButtons">
            <!-- JS will populate pagination buttons -->
        </div>
    </div>
</section>

<!-- Modal Pengguna -->
<div id="userModal" class="fixed inset-0 z-100 hidden overflow-y-auto">
    <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
        <div class="fixed inset-0 bg-slate-900/50 transition-opacity" onclick="closeModal()"></div>
        
        <div class="relative transform overflow-hidden rounded-sm bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
            <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start w-full">
                    <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                        <h3 class="text-xl font-bold leading-6 text-slate-900" id="modalTitle">Tambah Pengguna</h3>
                        <p class="mt-1 text-sm text-slate-500">Silakan isi formulir di bawah ini.</p>
                        
                        <div class="mt-5">
                            <form id="userForm" class="space-y-4">
                                <input type="hidden" id="userId">
                                
                                <div id="formError" class="hidden rounded-sm bg-red-50 p-3 text-sm text-red-600 border border-red-100 mb-4"></div>

                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Email <span class="text-red-500">*</span></label>
                                    <input type="email" id="userEmail" required oninput="updateConfirmationText()" class="w-full rounded-sm border border-slate-200 px-4 py-2.5 text-sm focus:border-blue-900 focus:ring-2 focus:ring-blue-900 focus:ring-opacity-20 outline-none transition-all">
                                </div>
                                <div class="mt-4">
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Role <span class="text-red-500">*</span></label>
                                    <select id="userRole" required onchange="updateConfirmationText()" class="w-full rounded-sm border border-slate-200 px-4 py-2.5 text-sm focus:border-blue-900 focus:ring-2 focus:ring-blue-900 focus:ring-opacity-20 outline-none transition-all">
                                        <option value="Pegawai">Pegawai</option>
                                        <option value="Admin">Admin</option>
                                    </select>
                                </div>
                                
                                <div class="mt-4 rounded-sm bg-blue-50 p-4 border border-blue-100">
                                    <div class="flex items-start gap-3">
                                        <i class="bi bi-question-circle text-blue-600 text-lg"></i>
                                        <p id="confirmationMessage" class="text-sm text-blue-900">Masukkan email untuk melihat konfirmasi.</p>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-slate-50 px-4 py-4 sm:flex sm:flex-row-reverse sm:px-6">
                <button type="button" id="saveUserBtn" onclick="saveUser()" class="inline-flex w-full justify-center items-center gap-2 rounded-sm bg-blue-900 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-950 sm:ml-3 sm:w-auto transition-colors">
                    <span id="btnSaveText">Simpan</span>
                </button>
                <button type="button" onclick="closeModal()" class="mt-3 inline-flex w-full justify-center rounded-sm bg-white px-5 py-2.5 text-sm font-semibold text-slate-900 shadow-sm border border-slate-200 hover:bg-slate-50 sm:mt-0 sm:w-auto transition-colors">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>



@endsection
