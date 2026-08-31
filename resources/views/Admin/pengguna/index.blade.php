@extends('components.admin.layouts.admin')

@section('title')
Daftar Pengguna - Panel Administrator
@endsection

@section('page-title')
Kelola Pengguna
@endsection

@push('scripts')
    @vite('resources/js/admin/pengguna/index.js')
@endpush

@section('content')

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}
    <section class="mb-7 border border-slate-200 bg-white shadow-sm">
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
                class="inline-flex shrink-0 items-center justify-center gap-2 rounded-sm bg-blue-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-900"
            >
                <i class="bi bi-plus-lg"></i>
                Tambah Pengguna
            </button>
        </div>
    </section>


    {{-- =========================================================
        STATISTIC CARDS
    ========================================================== --}}
    <div class="mb-7 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">

        {{-- Total Users --}}
        <div class="flex items-center gap-4 rounded-sm border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex h-12 w-12 items-center justify-center rounded-sm bg-blue-50 text-blue-900">
                <i class="bi bi-people text-xl"></i>
            </div>

            <div>
                <p class="text-sm font-medium text-slate-500">
                    Total Pengguna
                </p>

                <h3
                    id="statTotalUsers"
                    class="text-2xl font-bold text-slate-900"
                >
                    0
                </h3>
            </div>
        </div>


        {{-- Admin --}}
        <div class="flex items-center gap-4 rounded-sm border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex h-12 w-12 items-center justify-center rounded-sm bg-green-50 text-green-600">
                <i class="bi bi-shield-check text-xl"></i>
            </div>

            <div>
                <p class="text-sm font-medium text-slate-500">
                    Admin
                </p>

                <h3
                    id="statTotalAdmins"
                    class="text-2xl font-bold text-slate-900"
                >
                    0
                </h3>
            </div>
        </div>
    </div>


    {{-- =========================================================
        USER TABLE
    ========================================================== --}}
    <section class="overflow-hidden rounded-sm border border-slate-200 bg-white shadow-sm">

        {{-- Search --}}
        <div class="border-b border-slate-200 p-5">
            <div class="grid gap-4 lg:grid-cols-[1fr_auto]">

                <div class="relative">
                    <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>

                    <input
                        id="userSearch"
                        type="search"
                        placeholder="Cari pengguna..."
                        autocomplete="off"
                        class="w-full rounded-sm border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm outline-none transition focus:border-blue-900 focus:bg-white"
                    >
                </div>

                <button
                    type="button"
                    onclick="loadUsers()"
                    class="inline-flex items-center justify-center gap-2 rounded-sm border border-slate-200 px-4 py-3 text-sm text-slate-600 transition hover:bg-slate-50"
                >
                    <i class="bi bi-arrow-clockwise"></i>
                    Refresh
                </button>
            </div>
        </div>


        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full min-w-[800px]">
                <thead class="bg-slate-50">
                    <tr class="text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                        <th class="w-16 px-5 py-4">
                            No
                        </th>

                        <th class="px-5 py-4">
                            Nama
                        </th>

                        <th class="px-5 py-4">
                            Email
                        </th>

                        <th class="px-5 py-4">
                            Role
                        </th>

                        <th class="px-5 py-4 text-right">
                            Aksi
                        </th>
                    </tr>
                </thead>


                {{-- Data --}}
                <tbody
                    id="userTableBody"
                    class="hidden divide-y divide-slate-100"
                >
                </tbody>


                {{-- Skeleton Loader --}}
                <tbody
                    id="skeletonLoader"
                    class="divide-y divide-slate-100"
                >
                    @for ($i = 0; $i < 5; $i++)
                        <tr class="animate-pulse bg-white">
                            <td class="px-5 py-4">
                                <div class="h-4 w-8 rounded bg-slate-200"></div>
                            </td>

                            <td class="px-5 py-4">
                                <div class="h-4 w-32 rounded bg-slate-200"></div>
                            </td>

                            <td class="px-5 py-4">
                                <div class="h-4 w-48 rounded bg-slate-200"></div>
                            </td>

                            <td class="px-5 py-4">
                                <div class="h-4 w-24 rounded bg-slate-200"></div>
                            </td>

                            <td class="flex justify-end gap-2 px-5 py-4">
                                <div class="h-9 w-9 rounded-sm bg-slate-200"></div>
                                <div class="h-9 w-9 rounded-sm bg-slate-200"></div>
                            </td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>


        {{-- Empty --}}
        <div
            id="emptyResult"
            class="hidden px-6 py-16 text-center"
        >
            <i class="bi bi-search text-2xl text-slate-400"></i>

            <p class="mt-4 font-semibold">
                Pengguna tidak ditemukan
            </p>

            <p class="mt-2 text-sm text-slate-500">
                Tidak ada pengguna yang cocok dengan pencarian,
                atau data masih kosong.
            </p>
        </div>


        {{-- Pagination --}}
        <div
            id="paginationContainer"
            class="hidden border-t border-slate-200 p-5 sm:flex sm:items-center sm:justify-between"
        >
            <p
                id="paginationInfo"
                class="text-sm text-slate-500"
            >
                Menampilkan

                <span
                    id="pageStart"
                    class="font-medium text-slate-900"
                >
                    0
                </span>

                sampai

                <span
                    id="pageEnd"
                    class="font-medium text-slate-900"
                >
                    0
                </span>

                dari

                <span
                    id="pageTotal"
                    class="font-medium text-slate-900"
                >
                    0
                </span>

                Pengguna
            </p>

            <div
                id="paginationButtons"
                class="mt-4 flex flex-wrap justify-center gap-1 sm:mt-0"
            >
            </div>
        </div>
    </section>


    {{-- =========================================================
        USER MODAL
    ========================================================== --}}
    <div
        id="userModal"
        class="fixed inset-0 z-[100] hidden overflow-y-auto"
    >
        <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">

            {{-- Overlay --}}
            <div
                class="fixed inset-0 bg-slate-900/50 transition-opacity"
                onclick="closeModal()"
            ></div>


            {{-- Modal --}}
            <div class="relative transform overflow-hidden rounded-sm bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">

                {{-- Modal Content --}}
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6">
                    <div class="w-full">

                        {{-- Heading --}}
                        <div>
                            <p class="text-xs font-bold uppercase tracking-[0.18em] text-blue-900">
                                Form Pengguna
                            </p>

                            <h3
                                id="modalTitle"
                                class="mt-2 text-xl font-bold leading-6 text-slate-900"
                            >
                                Tambah Pengguna
                            </h3>

                            <p
                                id="modalDescription"
                                class="mt-2 text-sm leading-6 text-slate-500"
                            >
                                Silakan isi formulir di bawah ini.
                            </p>
                        </div>


                        {{-- Form --}}
                        <div class="mt-5">
                            <form
                                id="userForm"
                                class="space-y-5"
                            >
                                <input
                                    type="hidden"
                                    id="userId"
                                >


                                {{-- Error --}}
                                <div
                                    id="formError"
                                    class="hidden rounded-sm border border-red-100 bg-red-50 p-3 text-sm text-red-600"
                                ></div>


                                {{-- Email --}}
                                <div>
                                    <label
                                        for="userEmail"
                                        class="mb-2 block text-sm font-semibold text-slate-700"
                                    >
                                        Email
                                        <span class="text-red-500">*</span>
                                    </label>

                                    <input
                                        type="email"
                                        id="userEmail"
                                        required
                                        autocomplete="email"
                                        oninput="updateConfirmationText()"
                                        placeholder="contoh@badungkab.go.id"
                                        class="w-full rounded-sm border border-slate-200 px-4 py-2.5 text-sm outline-none transition-all focus:border-blue-900 focus:ring-2 focus:ring-blue-900/20"
                                    >
                                </div>


                                {{-- Role --}}
                                <div>
                                    <label
                                        for="userRole"
                                        class="mb-2 block text-sm font-semibold text-slate-700"
                                    >
                                        Role
                                        <span class="text-red-500">*</span>
                                    </label>

                                    <select
                                        id="userRole"
                                        required
                                        onchange="updateConfirmationText()"
                                        class="w-full rounded-sm border border-slate-200 bg-white px-4 py-2.5 text-sm outline-none transition-all focus:border-blue-900 focus:ring-2 focus:ring-blue-900/20"
                                    >
                                        <option value="Pegawai">
                                            Pegawai
                                        </option>

                                        <option value="Admin">
                                            Admin
                                        </option>
                                    </select>
                                </div>


                                {{-- =================================================
                                    PASSWORD
                                ================================================== --}}
                                <div>
                                    <div class="mb-2 flex items-center justify-between gap-3">
                                        <label
                                            for="userPassword"
                                            class="block text-sm font-semibold text-slate-700"
                                        >
                                            <span id="passwordLabel">
                                                Password
                                            </span>

                                            <span
                                                id="passwordRequiredIndicator"
                                                class="text-red-500"
                                            >
                                                *
                                            </span>
                                        </label>

                                        <span
                                            id="passwordOptionalBadge"
                                            class="hidden text-[11px] font-medium text-slate-400"
                                        >
                                            Opsional saat edit
                                        </span>
                                    </div>

                                    <div class="relative">
                                        <i
                                            class="
                                                bi bi-lock
                                                pointer-events-none
                                                absolute left-4 top-1/2
                                                -translate-y-1/2
                                                text-slate-400
                                            "
                                        ></i>

                                        <input
                                            type="password"
                                            id="userPassword"
                                            minlength="8"
                                            autocomplete="new-password"
                                            placeholder="Minimal 8 karakter"
                                            oninput="updateConfirmationText()"
                                            class="w-full rounded-sm border border-slate-200 py-2.5 pl-11 pr-12 text-sm outline-none transition-all focus:border-blue-900 focus:ring-2 focus:ring-blue-900/20"
                                        >

                                        <button
                                            id="toggleUserPassword"
                                            type="button"
                                            class="absolute right-0 top-0 flex h-full w-12 items-center justify-center text-slate-400 transition hover:text-blue-900"
                                            aria-label="Tampilkan atau sembunyikan password"
                                        >
                                            <i
                                                id="userPasswordEyeIcon"
                                                class="bi bi-eye"
                                            ></i>
                                        </button>
                                    </div>

                                    {{-- Password Help --}}
                                    <div
                                        id="passwordCreateHelp"
                                        class="mt-2 flex items-start gap-2 text-xs leading-5 text-slate-500"
                                    >
                                        <i class="bi bi-info-circle mt-0.5 shrink-0 text-blue-700"></i>

                                        <p>
                                            Password wajib diisi untuk pengguna baru
                                            dengan minimal 8 karakter.
                                        </p>
                                    </div>

                                    {{-- Edit Password Help --}}
                                    <div
                                        id="passwordEditHelp"
                                        class="mt-2 hidden border border-amber-200 bg-amber-50 p-3"
                                    >
                                        <div class="flex items-start gap-2">
                                            <i class="bi bi-shield-lock mt-0.5 shrink-0 text-amber-700"></i>

                                            <p class="text-xs leading-5 text-slate-600">
                                                <strong class="font-bold text-slate-900">
                                                    Kosongkan password jika tidak ingin mengubahnya.
                                                </strong>

                                                Password lama akan tetap digunakan.
                                                Isi kolom ini hanya apabila Anda ingin
                                                menetapkan password baru untuk pengguna.
                                            </p>
                                        </div>
                                    </div>
                                </div>


                                {{-- Role Information --}}
                                <div class="border border-slate-200 bg-slate-50 p-4">
                                    <div class="flex items-start gap-3">
                                        <i class="bi bi-info-circle mt-0.5 text-slate-500"></i>

                                        <div>
                                            <p class="text-sm font-semibold text-slate-700">
                                                Informasi Hak Akses
                                            </p>

                                            <div class="mt-2 space-y-2 text-xs leading-5 text-slate-500">
                                                <p>
                                                    <strong class="font-bold text-slate-800">
                                                        Pegawai
                                                    </strong>
                                                    — dapat masuk ke LMS dan mengakses
                                                    materi yang tersedia bagi pegawai.
                                                </p>

                                                <p>
                                                    <strong class="font-bold text-slate-800">
                                                        Admin
                                                    </strong>
                                                    — dapat mengakses Panel Administrator
                                                    dan melakukan pengelolaan data sistem.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                {{-- Confirmation --}}
                                <div class="rounded-sm border border-blue-100 bg-blue-50 p-4">
                                    <div class="flex items-start gap-3">
                                        <i class="bi bi-question-circle text-lg text-blue-600"></i>

                                        <p
                                            id="confirmationMessage"
                                            class="text-sm leading-6 text-blue-900"
                                        >
                                            Masukkan email untuk melihat konfirmasi.
                                        </p>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>


                {{-- Modal Footer --}}
                <div class="bg-slate-50 px-4 py-4 sm:flex sm:flex-row-reverse sm:px-6">

                    <button
                        type="button"
                        id="saveUserBtn"
                        onclick="saveUser()"
                        class="inline-flex w-full items-center justify-center gap-2 rounded-sm bg-blue-900 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-blue-950 disabled:cursor-not-allowed disabled:opacity-60 sm:ml-3 sm:w-auto"
                    >
                        <span id="btnSaveText">
                            Simpan
                        </span>
                    </button>

                    <button
                        type="button"
                        onclick="closeModal()"
                        class="mt-3 inline-flex w-full justify-center rounded-sm border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-900 shadow-sm transition-colors hover:bg-slate-50 sm:mt-0 sm:w-auto"
                    >
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection