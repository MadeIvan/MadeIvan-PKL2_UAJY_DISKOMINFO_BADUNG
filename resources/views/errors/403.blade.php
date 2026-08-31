@extends('components.admin.layouts.admin')

@section('title')
403 Akses Ditolak
@endsection
@section('page-title')
Akses Ditolak
@endsection

@section('content')
<div class="flex flex-col items-center justify-center min-h-[50vh] text-center">
    <div class="text-6xl font-bold text-slate-400 mb-4">403</div>
    <h1 class="text-2xl font-semibold text-slate-800 mb-2">Akses Ditolak</h1>
    <p class="text-slate-500 max-w-md mb-8">Maaf, Anda tidak memiliki izin untuk mengakses halaman ini.</p>
    <a href="{{ url('/admin/applications') }}" class="inline-flex items-center gap-2 bg-blue-900 text-white px-5 py-2.5 rounded-sm hover:bg-blue-800 transition">
        <i class="bi bi-arrow-left"></i>
        Kembali ke Beranda
    </a>
</div>
@endsection
