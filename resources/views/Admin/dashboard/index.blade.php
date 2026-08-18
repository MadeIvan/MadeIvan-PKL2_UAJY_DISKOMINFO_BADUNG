@extends('components.admin.layouts.admin')

@section('title', 'Dasbor Admin')
@section('page-title', 'Dasbor')

@section('content')
<div class="space-y-6">

    {{-- Welcome Banner --}}
    <section class="relative overflow-hidden bg-white border border-slate-200 shadow-sm">
        <div class="absolute inset-0 bg-gradient-to-r from-blue-900/5 to-transparent pointer-events-none"></div>
        <div class="relative flex flex-col sm:flex-row justify-between p-6 sm:p-8">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-blue-900 mb-2">
                    Selamat Datang
                </p>
                <h2 class="text-2xl font-bold text-slate-800 mb-2">Knowledge Management System</h2>
                <p class="text-sm text-slate-600">
                    {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM YYYY') }}
                </p>
            </div>
            <div class="mt-4 sm:mt-0 flex gap-3">
                <a href="{{ route('admin.applications.index') }}" class="inline-flex items-center gap-2 bg-white border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 hover:text-blue-900 shadow-sm">
                    <i class="bi bi-window-stack"></i>
                    Aplikasi
                </a>
                <a href="{{ route('admin.materi.index') }}" class="inline-flex items-center gap-2 bg-blue-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-800 shadow-sm border border-transparent">
                    <i class="bi bi-journal-text"></i>
                    Materi
                </a>
            </div>
        </div>
    </section>

    {{-- Metrics Grid --}}
    <section class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
        {{-- Card: Total Aplikasi --}}
        <div class="relative overflow-hidden rounded-2xl bg-blue-700 p-6 shadow-lg shadow-blue-900/20 transition-all hover:-translate-y-1 hover:shadow-xl hover:shadow-blue-900/30">
            <div class="absolute -right-4 -top-4 text-white/10 transition-transform duration-500 group-hover:scale-110 group-hover:rotate-12">
                <i class="bi bi-box-seam text-9xl"></i>
            </div>
            <div class="relative z-10 flex flex-col h-full justify-between">
                <div class="flex justify-between items-start mb-6">
                    <div class="rounded-lg bg-white/20 p-2 text-white backdrop-blur-sm">
                        <i class="bi bi-box-seam text-xl"></i>
                    </div>
                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-400/20 px-2.5 py-1 text-xs font-semibold text-emerald-100 backdrop-blur-md border border-emerald-400/30">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span> {{ $stats['active_applications'] }} Aktif
                    </span>
                </div>
                <div>
                    <h3 class="text-4xl font-black text-white tracking-tight">{{ $stats['total_applications'] }}</h3>
                    <p class="text-sm font-medium text-blue-100 mt-1">Total Aplikasi</p>
                </div>
            </div>
        </div>

        {{-- Card: Kategori --}}
        <div class="relative overflow-hidden rounded-2xl bg-violet-700 p-6 shadow-lg shadow-violet-900/20 transition-all hover:-translate-y-1 hover:shadow-xl hover:shadow-violet-900/30">
            <div class="absolute -right-4 -top-4 text-white/10 transition-transform duration-500 group-hover:scale-110 group-hover:rotate-12">
                <i class="bi bi-tags text-9xl"></i>
            </div>
            <div class="relative z-10 flex flex-col h-full justify-between">
                <div class="flex justify-between items-start mb-6">
                    <div class="rounded-lg bg-white/20 p-2 text-white backdrop-blur-sm">
                        <i class="bi bi-tags text-xl"></i>
                    </div>
                </div>
                <div>
                    <h3 class="text-4xl font-black text-white tracking-tight">{{ $stats['total_categories'] }}</h3>
                    <p class="text-sm font-medium text-violet-100 mt-1">Kategori Aplikasi</p>
                </div>
            </div>
        </div>

        {{-- Card: Materi --}}
        <div class="relative overflow-hidden rounded-2xl bg-orange-600 p-6 shadow-lg shadow-orange-900/20 transition-all hover:-translate-y-1 hover:shadow-xl hover:shadow-orange-900/30">
            <div class="absolute -right-4 -top-4 text-white/10 transition-transform duration-500 group-hover:scale-110 group-hover:rotate-12">
                <i class="bi bi-journal-bookmark text-9xl"></i>
            </div>
            <div class="relative z-10 flex flex-col h-full justify-between">
                <div class="flex justify-between items-start mb-6">
                    <div class="rounded-lg bg-white/20 p-2 text-white backdrop-blur-sm">
                        <i class="bi bi-journal-bookmark text-xl"></i>
                    </div>
                </div>
                <div>
                    <h3 class="text-4xl font-black text-white tracking-tight">{{ $stats['total_materi'] }}</h3>
                    <p class="text-sm font-medium text-orange-100 mt-1">Node Materi</p>
                </div>
            </div>
        </div>

        {{-- Card: Versi --}}
        <div class="relative overflow-hidden rounded-2xl bg-emerald-600 p-6 shadow-lg shadow-emerald-900/20 transition-all hover:-translate-y-1 hover:shadow-xl hover:shadow-emerald-900/30">
            <div class="absolute -right-4 -top-4 text-white/10 transition-transform duration-500 group-hover:scale-110 group-hover:rotate-12">
                <i class="bi bi-diagram-3 text-9xl"></i>
            </div>
            <div class="relative z-10 flex flex-col h-full justify-between">
                <div class="flex justify-between items-start mb-6">
                    <div class="rounded-lg bg-white/20 p-2 text-white backdrop-blur-sm">
                        <i class="bi bi-diagram-3 text-xl"></i>
                    </div>
                </div>
                <div>
                    <h3 class="text-4xl font-black text-white tracking-tight">{{ $stats['total_versions'] }}</h3>
                    <p class="text-sm font-medium text-emerald-100 mt-1">Total Versi Terdaftar</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Activity & Distribution --}}
    <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Recent Applications --}}
        <div class="lg:col-span-2 bg-white border border-slate-200 shadow-sm">
            <div class="border-b border-slate-200 px-6 py-4 flex justify-between items-center bg-slate-50/50">
                <h3 class="font-bold text-slate-800">Aplikasi Terbaru</h3>
                <a href="{{ route('admin.applications.index') }}" class="text-sm text-blue-700 hover:text-blue-900 font-medium">Lihat Semua</a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($recentApplications as $app)
                    <div class="px-6 py-4 flex items-center justify-between hover:bg-slate-50 transition">
                        <div class="flex items-center gap-4">
                            @if($app->logo_path)
                                <img src="{{ asset('storage/' . $app->logo_path) }}" alt="{{ $app->name }}" class="w-10 h-10 rounded border border-slate-200 object-cover bg-white">
                            @else
                                <div class="w-10 h-10 rounded border border-slate-200 bg-slate-100 flex items-center justify-center text-slate-400">
                                    <i class="bi bi-image"></i>
                                </div>
                            @endif
                            <div>
                                <h4 class="font-semibold text-slate-800">{{ $app->name }}</h4>
                                <p class="text-xs text-slate-500">{{ $app->category ? $app->category->name : 'Tanpa Kategori' }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center gap-1 rounded {{ $app->status === 'active' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-700' }} px-2 py-1 text-xs font-medium">
                                {{ ucfirst($app->status) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-slate-500 text-sm">
                        Belum ada aplikasi ditambahkan.
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Top Categories --}}
        <div class="bg-white border border-slate-200 shadow-sm flex flex-col">
            <div class="border-b border-slate-200 px-6 py-4 bg-slate-50/50">
                <h3 class="font-bold text-slate-800">Distribusi Kategori</h3>
            </div>
            <div class="p-6 flex-1 flex flex-col justify-center gap-4">
                @forelse($categoryDistribution as $cat)
                    <div>
                        <div class="flex justify-between items-end mb-2">
                            <span class="text-sm font-semibold text-slate-700">{{ $cat->name }}</span>
                            <span class="text-xs font-bold text-slate-500">{{ $cat->applications_count }} Aplikasi</span>
                        </div>
                        @php
                            $percentage = $stats['total_applications'] > 0 ? ($cat->applications_count / $stats['total_applications']) * 100 : 0;
                        @endphp
                        <div class="h-2 w-full bg-slate-100 overflow-hidden rounded-full">
                            <div class="h-full bg-blue-900 transition-all duration-500" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-slate-500 text-sm py-4">
                        Data kategori kosong.
                    </div>
                @endforelse
            </div>
        </div>
    </section>

</div>
@endsection
