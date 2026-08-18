@extends('components.admin.layouts.admin')

@section('title', '500 Kesalahan Server')
@section('page-title', 'Kesalahan Server')

@section('content')
<div class="flex flex-col items-center justify-center min-h-[50vh] text-center">
    <div class="text-6xl font-bold text-red-500 mb-4">500</div>
    <h1 class="text-2xl font-semibold text-slate-800 mb-2">Kesalahan Internal Server</h1>
    <p class="text-slate-500 max-w-md mb-8">Maaf, terjadi kesalahan pada server kami. Silakan coba beberapa saat lagi.</p>
    <a href="{{ url('/admin/applications') }}" class="inline-flex items-center gap-2 bg-blue-900 text-white px-5 py-2.5 rounded-sm hover:bg-blue-800 transition">
        <i class="bi bi-arrow-left"></i>
        Kembali ke Beranda
    </a>
</div>
@endsection
