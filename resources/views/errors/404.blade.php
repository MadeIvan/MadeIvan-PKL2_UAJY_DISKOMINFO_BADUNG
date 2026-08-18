@extends('components.admin.layouts.admin')

@section('title', '404 Tidak Ditemukan')
@section('page-title', 'Halaman Tidak Ditemukan')

@section('content')
<div class="flex flex-col items-center justify-center min-h-[50vh] text-center">
    <div class="text-6xl font-bold text-blue-900 mb-4">404</div>
    <h1 class="text-2xl font-semibold text-slate-800 mb-2">Halaman Tidak Ditemukan</h1>
    <p class="text-slate-500 max-w-md mb-8">Maaf, halaman yang Anda cari tidak ada atau telah dipindahkan.</p>
    <a href="{{ url('/admin/applications') }}" class="inline-flex items-center gap-2 bg-blue-900 text-white px-5 py-2.5 rounded-sm hover:bg-blue-800 transition">
        <i class="bi bi-arrow-left"></i>
        Kembali ke Beranda
    </a>
</div>
@endsection
