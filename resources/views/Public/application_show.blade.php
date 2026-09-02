<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="{{ asset('images/Logo.png') }}">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="description"
        content="{{ $application->description ?: $application->name }}"
    >

    <title>{{ $application->name }} - Dokumentasi</title>

    @vite([
        'resources/css/app.css',
        'resources/js/applications/show.js',
    ])
</head>

<body class="min-h-screen bg-slate-50 text-slate-900">
    @include('components.navbar')

    @php
        $blocks = $selectedMaterial
            ? $selectedMaterial->contentBlocks
                ->sortBy([
                    ['sort_order', 'asc'],
                    ['id', 'asc'],
                ])
                ->values()
            : collect();

        $getYoutubeId = function (?string $url): ?string {
            if (!$url) {
                return null;
            }

            $parts = parse_url($url);

            if (!$parts) {
                return null;
            }

            $host = strtolower(
                preg_replace(
                    '/^www\./i',
                    '',
                    $parts['host'] ?? ''
                )
            );

            $path = trim(
                $parts['path'] ?? '',
                '/'
            );

            if ($host === 'youtu.be') {
                return explode('/', $path)[0] ?? null;
            }

            if (
                in_array(
                    $host,
                    [
                        'youtube.com',
                        'm.youtube.com',
                    ],
                    true
                )
            ) {
                if ($path === 'watch') {
                    parse_str(
                        $parts['query'] ?? '',
                        $query
                    );

                    return $query['v'] ?? null;
                }

                $segments = array_values(
                    array_filter(
                        explode('/', $path)
                    )
                );

                if (
                    isset($segments[0], $segments[1]) &&
                    in_array(
                        $segments[0],
                        [
                            'embed',
                            'shorts',
                            'live',
                        ],
                        true
                    )
                ) {
                    return $segments[1];
                }
            }

            return null;
        };

        $formatFileSize = function (?int $bytes): string {
            $size = (int) $bytes;

            if ($size <= 0) {
                return 'Ukuran tidak diketahui';
            }

            if ($size < 1024) {
                return $size . ' B';
            }

            if ($size < 1024 * 1024) {
                return number_format(
                    $size / 1024,
                    1
                ) . ' KB';
            }

            return number_format(
                $size / (1024 * 1024),
                1
            ) . ' MB';
        };
    @endphp

    <main class="min-h-screen pt-20">



        @if ($versions->isEmpty())

            {{-- No Version --}}
            <div class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
                <section class="rounded-2xl border border-slate-200 bg-white px-6 py-20 text-center shadow-sm">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                        <i class="bi bi-journals text-2xl"></i>
                    </div>

                    <h2 class="mt-5 text-xl font-bold text-slate-950">
                        Dokumentasi belum tersedia
                    </h2>

                    <p class="mx-auto mt-2 max-w-lg text-sm leading-6 text-slate-500">
                        Aplikasi ini belum memiliki versi dokumentasi.
                    </p>

                    <a
                        href="{{ route('applications.index') }}"
                        class="mt-6 inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 no-underline transition hover:bg-slate-50"
                    >
                        <i class="bi bi-arrow-left"></i>
                        Kembali ke Daftar Aplikasi
                    </a>
                </section>
            </div>

        @else

            {{-- Older Version Warning Toast --}}
            @if ($isOlderVersion && $preferredVersion)
                <div id="version-warning-toast" class="fixed bottom-6 right-6 z-100 w-full max-w-sm animate-fade-in-up">
                    <div class="rounded-2xl border border-amber-200 bg-amber-50 px-5 py-4 shadow-2xl">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-start gap-3">
                                <i class="bi bi-exclamation-triangle mt-0.5 text-xl text-amber-700"></i>
                                <div>
                                    <p class="font-bold text-amber-950">
                                        Versi Lama
                                    </p>
                                    <p class="mt-1 text-sm leading-5 text-amber-800">
                                        Anda sedang melihat dokumentasi versi lama. Versi terbaru (v{{ $preferredVersion->version_number }}) telah tersedia.
                                    </p>
                                </div>
                            </div>
                            <button type="button" onclick="document.getElementById('version-warning-toast').style.display='none'" class="shrink-0 text-amber-700 transition hover:text-amber-900" aria-label="Tutup peringatan">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                        <div class="mt-4 flex">
                            <a
                                href="{{ route('applications.show', [
                                    'application' => $application->slug,
                                    'version' => $preferredVersion->id,
                                ]) }}"
                                class="w-full inline-flex items-center justify-center rounded-xl bg-amber-900 px-4 py-2.5 text-sm font-semibold text-white no-underline transition hover:bg-amber-950"
                            >
                                Lihat Versi Terbaru
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Non-Stable Version Warning Toast --}}
            @if ($isNonStableVersion && !$isOlderVersion)
                <div id="non-stable-warning-toast" class="fixed bottom-6 right-6 z-100 w-full max-w-sm animate-fade-in-up">
                    <div class="rounded-2xl border border-amber-200 bg-amber-50 px-5 py-4 shadow-2xl">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-start gap-3">
                                <i class="bi bi-exclamation-triangle mt-0.5 text-xl text-amber-700"></i>
                                <div>
                                    <p class="font-bold text-amber-950">
                                        Bukan Versi Stabil
                                    </p>
                                    <p class="mt-1 text-sm leading-5 text-amber-800">
                                        Anda sedang melihat dokumentasi pada versi yang belum stabil. Versi stabil direkomendasikan untuk penggunaan resmi.
                                    </p>
                                </div>
                            </div>
                            <button type="button" onclick="document.getElementById('non-stable-warning-toast').style.display='none'" class="shrink-0 text-amber-700 transition hover:text-amber-900" aria-label="Tutup peringatan">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Full Width Documentation Layout --}}
            <div
                id="documentationLayout"
                class="relative flex w-full"
            >

                {{-- Mobile Overlay --}}
                <div
                    id="sidebarOverlay"
                    class="fixed inset-0 top-20 z-40 hidden bg-slate-950/40 lg:hidden"
                ></div>

                {{-- Sidebar --}}
                <aside
                    id="documentationSidebar"
                    class="fixed bottom-0 left-0 top-20 z-50 w-72 -translate-x-full overflow-y-auto border-r border-slate-200 bg-white transition-all duration-300 lg:sticky lg:top-20 lg:z-20 lg:h-[calc(100vh-5rem)] lg:w-72 lg:shrink-0 lg:translate-x-0"
                >
                    <div class="p-4">

                        {{-- Sidebar Header --}}
                        <div class="flex items-start justify-between gap-3">

                            <div
                                data-sidebar-detail
                                class="min-w-0"
                            >
                                <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-400">
                                    Dokumentasi
                                </p>

                                <h2 class="mt-1 text-lg font-bold text-slate-950">
                                    Daftar Materi
                                </h2>
                            </div>

                            {{-- Desktop Collapse --}}
                            <button
                                id="toggleDesktopSidebar"
                                type="button"
                                class="hidden h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 transition hover:bg-slate-100 hover:text-slate-900 lg:flex"
                                aria-label="Ciutkan sidebar"
                                aria-expanded="true"
                                title="Ciutkan sidebar"
                            >
                                <i
                                    id="desktopSidebarIcon"
                                    class="bi bi-layout-sidebar-inset"
                                ></i>
                            </button>

                            {{-- Mobile Close --}}
                            <button
                                id="closeSidebar"
                                type="button"
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-slate-200 text-slate-500 transition hover:bg-slate-100 lg:hidden"
                                aria-label="Tutup sidebar"
                            >
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>

                        {{-- Sidebar Content --}}
                        <div data-sidebar-detail>

                            {{-- Version Selector --}}
                            <div class="mt-6">
                                <label
                                    for="applicationVersion"
                                    class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500"
                                >
                                    Versi Aplikasi
                                </label>

                                <div class="relative">
                                    <select
                                        id="applicationVersion"
                                        data-application-slug="{{ $application->slug }}"
                                        class="w-full appearance-none rounded-xl border border-slate-200 bg-white py-3 pl-3 pr-10 text-sm font-semibold text-slate-700 outline-none transition hover:border-slate-300 focus:border-blue-900 focus:ring-2 focus:ring-blue-900/10"
                                    >
                                        @foreach ($versions as $version)
                                            <option
                                                value="{{ $version->id }}"
                                                @selected(
                                                    $selectedVersion &&
                                                    (int) $selectedVersion->id ===
                                                    (int) $version->id
                                                )
                                            >
                                                v{{ $version->version_number }}

                                                @if (
                                                    $preferredVersion &&
                                                    (int) $preferredVersion->id ===
                                                    (int) $version->id
                                                )
                                                    — Terbaru
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>

                                    <i class="bi bi-chevron-expand pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-sm text-slate-400"></i>
                                </div>
                            </div>

                            <div class="my-5 border-t border-slate-200"></div>

                            {{-- Tutorial Tree --}}
                            @if ($hasMaterials)
                                <nav
                                    id="tutorialNavigation"
                                    class="space-y-1"
                                >
                                    @include('Public.partials.tutorial_tree', [
                                        'nodes' => $tutorialTree,
                                        'application' => $application,
                                        'selectedVersion' => $selectedVersion,
                                        'selectedMaterial' => $selectedMaterial,
                                    ])
                                </nav>
                            @else
                                <div class="rounded-xl border border-dashed border-slate-200 bg-slate-50 px-4 py-8 text-center">
                                    <i class="bi bi-journal-x text-2xl text-slate-400"></i>

                                    <p class="mt-3 text-sm font-semibold text-slate-700">
                                        Belum ada materi
                                    </p>

                                    <p class="mt-1 text-xs leading-5 text-slate-500">
                                        Belum ada materi publik pada versi ini.
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </aside>

                {{-- Main Reader --}}
                <section class="min-w-0 flex-1 bg-slate-50">

                    <div class="mx-auto max-w-6xl px-4 py-7 sm:px-6 lg:px-10">
                        {{-- Application Name --}}
                        <div class="mb-5">
                            <h1 class="text-2xl font-bold tracking-tight text-slate-950 sm:text-3xl">
                                {{ $application->name }}
                            </h1>
                        </div>

                        {{-- Mobile Sidebar Button --}}
                        <button
                            id="openSidebar"
                            type="button"
                            class="mb-5 inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 lg:hidden"
                        >
                            <i class="bi bi-list"></i>
                            Daftar Materi
                        </button>

                        {{-- No Public Nodes --}}
                        @if (!$hasPublicNodes)
                            <section class="rounded-2xl border border-slate-200 bg-white px-6 py-20 text-center shadow-sm">
                                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                                    <i class="bi bi-journal-x text-2xl"></i>
                                </div>

                                <h2 class="mt-5 text-xl font-bold text-slate-950">
                                    Dokumentasi belum tersedia
                                </h2>

                                <p class="mx-auto mt-2 max-w-lg text-sm leading-6 text-slate-500">
                                    Belum ada dokumentasi publik untuk versi
                                    {{ $selectedVersion?->version_number }}.
                                </p>
                            </section>

                        {{-- No Material --}}
                        @elseif (!$hasMaterials)
                            <section class="rounded-2xl border border-slate-200 bg-white px-6 py-20 text-center shadow-sm">
                                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-50 text-blue-900">
                                    <i class="bi bi-journal-text text-2xl"></i>
                                </div>

                                <h2 class="mt-5 text-xl font-bold text-slate-950">
                                    Materi belum tersedia
                                </h2>

                                <p class="mx-auto mt-2 max-w-lg text-sm leading-6 text-slate-500">
                                    Struktur dokumentasi tersedia, tetapi belum terdapat
                                    materi yang dapat dibaca pada versi ini.
                                </p>
                            </section>

                        {{-- No Selected Material --}}
                        @elseif (!$selectedMaterial)
                            <section class="rounded-2xl border border-slate-200 bg-white px-6 py-20 text-center shadow-sm">
                                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-50 text-blue-900">
                                    <i class="bi bi-book text-2xl"></i>
                                </div>

                                <h2 class="mt-5 text-2xl font-bold text-slate-950">
                                    {{ $application->name }}
                                </h2>

                                <p class="mt-2 text-sm font-semibold text-blue-900">
                                    Versi {{ $selectedVersion->version_number }}
                                </p>

                                <p class="mx-auto mt-4 max-w-xl text-sm leading-7 text-slate-500">
                                    Pilih salah satu materi dari daftar dokumentasi
                                    untuk mulai membaca.
                                </p>
                            </section>

                        {{-- Selected Material --}}
                        @else

                            {{-- Breadcrumb --}}
                            <nav
                                aria-label="Breadcrumb"
                                class="mb-5 flex flex-wrap items-center gap-2 text-sm text-slate-500"
                            >
                                <a
                                    href="{{ route('applications.index') }}"
                                    class="no-underline transition hover:text-blue-900"
                                >
                                    Pengetahuan
                                </a>

                                <i class="bi bi-chevron-right text-xs text-slate-300"></i>

                                <a
                                    href="{{ route('applications.show', [
                                        'application' => $application->slug,
                                        'version' => $selectedVersion->id,
                                    ]) }}"
                                    class="no-underline transition hover:text-blue-900"
                                >
                                    {{ $application->name }}
                                </a>

                                @if ($selectedMaterial->parent)
                                    <i class="bi bi-chevron-right text-xs text-slate-300"></i>

                                    <span>
                                        {{ $selectedMaterial->parent->title }}
                                    </span>
                                @endif

                                <i class="bi bi-chevron-right text-xs text-slate-300"></i>

                                <span class="font-semibold text-slate-900">
                                    {{ $selectedMaterial->title }}
                                </span>
                            </nav>

                            {{-- Material Article --}}
                            <article class="rounded-2xl border border-slate-200 bg-white shadow-sm">

                                {{-- Material Header --}}
                                <header class="border-b border-slate-200 px-6 py-8 sm:px-8 lg:px-10">
                                    <div class="flex flex-wrap items-center gap-2">

                                        <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-900">
                                            {{ $application->name }}
                                        </span>

                                        <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                                            Versi {{ $selectedVersion->version_number }}
                                        </span>

                                        @if ($selectedMaterial->parent)
                                            <span class="inline-flex rounded-full bg-violet-100 px-3 py-1 text-xs font-semibold text-violet-700">
                                                {{ $selectedMaterial->parent->title }}
                                            </span>
                                        @endif
                                    </div>

                                    <div class="mt-5 flex flex-wrap items-start justify-between gap-4">
                                        <h1 class="text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">
                                            {{ $selectedMaterial->title }}
                                        </h1>

                                        @if (auth()->check() && auth()->user()->hasRole('Admin'))
                                            <a
                                                href="{{ route('admin.materi.content', $selectedMaterial->id) }}"
                                                class="inline-flex shrink-0 items-center gap-2 border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                                            >
                                                <i class="bi bi-pencil-square"></i>
                                                Edit Materi
                                            </a>
                                        @endif
                                    </div>

                                    @if ($selectedMaterial->description)
                                        <p class="mt-4 max-w-3xl text-base leading-8 text-slate-600">
                                            {{ $selectedMaterial->description }}
                                        </p>
                                    @endif
                                </header>

                                {{-- Content Blocks --}}
                                <div class="px-6 py-8 sm:px-8 lg:px-10 lg:py-10">

                                    @if ($blocks->isEmpty())
                                        <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-5 py-16 text-center">
                                            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-xl bg-white text-slate-400">
                                                <i class="bi bi-file-earmark-x text-xl"></i>
                                            </div>

                                            <p class="mt-4 text-lg font-bold text-slate-900">
                                                Konten belum tersedia
                                            </p>

                                            <p class="mt-2 text-sm text-slate-500">
                                                Materi ini belum memiliki content block.
                                            </p>
                                        </div>
                                    @else

                                        <div class="space-y-8">

                                            @foreach ($blocks as $block)
                                                <section
                                                    id="block-{{ $block->id }}"
                                                    data-content-block="{{ $block->id }}"
                                                    class="scroll-mt-28 rounded-2xl border border-transparent p-1 transition-all duration-300"
                                                >

                                                    {{-- Text --}}
                                                    @if ($block->block_type === 'text')
                                                        <div
                                                            class="
                                                                text-base leading-8 text-slate-700

                                                                [&_h1]:mb-4
                                                                [&_h1]:mt-7
                                                                [&_h1]:text-3xl
                                                                [&_h1]:font-bold
                                                                [&_h1]:text-slate-950

                                                                [&_h2]:mb-3
                                                                [&_h2]:mt-7
                                                                [&_h2]:text-2xl
                                                                [&_h2]:font-bold
                                                                [&_h2]:text-slate-950

                                                                [&_h3]:mb-3
                                                                [&_h3]:mt-6
                                                                [&_h3]:text-xl
                                                                [&_h3]:font-bold
                                                                [&_h3]:text-slate-950

                                                                [&_h4]:mb-2
                                                                [&_h4]:mt-5
                                                                [&_h4]:text-lg
                                                                [&_h4]:font-semibold
                                                                [&_h4]:text-slate-950

                                                                [&_p]:mb-4

                                                                [&_ul]:mb-4
                                                                [&_ul]:list-disc
                                                                [&_ul]:pl-6

                                                                [&_ol]:mb-4
                                                                [&_ol]:list-decimal
                                                                [&_ol]:pl-6

                                                                [&_li]:mb-1

                                                                [&_blockquote]:my-6
                                                                [&_blockquote]:border-l-4
                                                                [&_blockquote]:border-blue-900
                                                                [&_blockquote]:pl-4
                                                                [&_blockquote]:text-slate-600

                                                                [&_a]:font-medium
                                                                [&_a]:text-blue-700
                                                                [&_a]:underline

                                                                [&_pre]:my-6
                                                                [&_pre]:overflow-x-auto
                                                                [&_pre]:rounded-xl
                                                                [&_pre]:bg-slate-950
                                                                [&_pre]:p-5
                                                                [&_pre]:text-slate-50

                                                                [&_code]:font-mono

                                                                [&_table]:my-6
                                                                [&_table]:w-full
                                                                [&_table]:border-collapse

                                                                [&_th]:border
                                                                [&_th]:border-slate-300
                                                                [&_th]:bg-slate-100
                                                                [&_th]:p-3
                                                                [&_th]:text-left
                                                                [&_th]:font-bold
                                                                [&_th]:text-slate-950

                                                                [&_td]:border
                                                                [&_td]:border-slate-300
                                                                [&_td]:p-3
                                                                [&_td]:align-top

                                                                [&_img]:h-auto
                                                                [&_img]:max-w-full
                                                            "
                                                        >
                                                            {!! $block->content !!}
                                                        </div>

                                                    {{-- Image --}}
                                                    @elseif ($block->block_type === 'image')

                                                        @if ($block->file_path)
                                                            <figure class="space-y-3">

                                                                @if ($block->title)
                                                                    <h2 class="text-2xl font-bold text-slate-950">
                                                                        {{ $block->title }}
                                                                    </h2>
                                                                @endif

                                                                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-slate-50">
                                                                    <img
                                                                        src="{{ asset(
                                                                            'storage/' .
                                                                            ltrim(
                                                                                $block->file_path,
                                                                                '/'
                                                                            )
                                                                        ) }}"
                                                                        alt="{{ $block->alt_text ?: ($block->caption ?: 'Gambar materi') }}"
                                                                        class="max-h-[720px] w-full object-contain"
                                                                    >
                                                                </div>

                                                                @if ($block->caption)
                                                                    <figcaption class="text-center text-sm italic text-slate-500">
                                                                        {{ $block->caption }}
                                                                    </figcaption>
                                                                @endif
                                                            </figure>
                                                        @endif

                                                    {{-- YouTube --}}
                                                    @elseif ($block->block_type === 'youtube')

                                                        @php
                                                            $videoId = $getYoutubeId(
                                                                $block->external_url
                                                            );
                                                        @endphp

                                                        <div class="space-y-4">

                                                            @if ($block->title)
                                                                <h2 class="text-2xl font-bold text-slate-950">
                                                                    {{ $block->title }}
                                                                </h2>
                                                            @endif

                                                            @if ($videoId)
                                                                <div class="aspect-video overflow-hidden rounded-2xl bg-slate-950">
                                                                    <iframe
                                                                        src="https://www.youtube.com/embed/{{ $videoId }}"
                                                                        title="{{ $block->title ?: 'Video YouTube' }}"
                                                                        class="h-full w-full"
                                                                        loading="lazy"
                                                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                                                        allowfullscreen
                                                                    ></iframe>
                                                                </div>
                                                            @else
                                                                <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                                                                    Tautan YouTube tidak valid.
                                                                </div>
                                                            @endif
                                                        </div>

                                                    {{-- PDF --}}
                                                    @elseif ($block->block_type === 'pdf')

                                                        @php
                                                            $pdfUrl = $block->file_path
                                                                ? asset(
                                                                    'storage/' .
                                                                    ltrim(
                                                                        $block->file_path,
                                                                        '/'
                                                                    )
                                                                )
                                                                : null;
                                                        @endphp

                                                        @if ($pdfUrl)
                                                            <div class="space-y-4">

                                                                @if ($block->title)
                                                                    <h2 class="text-2xl font-bold text-slate-950">
                                                                        {{ $block->title }}
                                                                    </h2>
                                                                @endif

                                                                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">

                                                                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                                                                        <div class="flex min-w-0 items-center gap-4">

                                                                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-red-100 text-sm font-bold text-red-700">
                                                                                PDF
                                                                            </div>

                                                                            <div class="min-w-0">

                                                                                <p class="truncate font-bold text-slate-900">
                                                                                    {{ $block->original_file_name ?: 'Dokumen PDF' }}
                                                                                </p>

                                                                                <p class="mt-1 text-xs text-slate-500">
                                                                                    {{ $formatFileSize($block->file_size) }}
                                                                                </p>

                                                                                @if ($block->caption)
                                                                                    <p class="mt-2 text-sm leading-6 text-slate-600">
                                                                                        {{ $block->caption }}
                                                                                    </p>
                                                                                @endif
                                                                            </div>
                                                                        </div>

                                                                        <a
                                                                            href="{{ $pdfUrl }}"
                                                                            target="_blank"
                                                                            rel="noopener noreferrer"
                                                                            class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 no-underline transition hover:bg-slate-100"
                                                                        >
                                                                            Buka PDF
                                                                            <i class="bi bi-box-arrow-up-right"></i>
                                                                        </a>
                                                                    </div>
                                                                </div>

                                                                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
                                                                    <iframe
                                                                        src="{{ $pdfUrl }}"
                                                                        title="{{ $block->original_file_name ?: 'Dokumen PDF' }}"
                                                                        class="h-[680px] w-full"
                                                                    ></iframe>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endif
                                                </section>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                                {{-- Previous / Next --}}
                                @if ($previousMaterial || $nextMaterial)
                                    <footer class="grid gap-4 border-t border-slate-200 px-6 py-6 sm:grid-cols-2 sm:px-8 lg:px-10">

                                        <div>
                                            @if ($previousMaterial)
                                                <a
                                                    href="{{ route('applications.show', [
                                                        'application' => $application->slug,
                                                        'version' => $selectedVersion->id,
                                                        'materi' => $previousMaterial->id,
                                                    ]) }}"
                                                    class="block h-full rounded-xl border border-slate-200 p-4 no-underline transition hover:border-blue-200 hover:bg-blue-50"
                                                >
                                                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                                                        Sebelumnya
                                                    </p>

                                                    <p class="mt-2 flex items-center gap-2 font-semibold text-slate-800">
                                                        <i class="bi bi-arrow-left"></i>
                                                        {{ $previousMaterial->title }}
                                                    </p>
                                                </a>
                                            @endif
                                        </div>

                                        <div>
                                            @if ($nextMaterial)
                                                <a
                                                    href="{{ route('applications.show', [
                                                        'application' => $application->slug,
                                                        'version' => $selectedVersion->id,
                                                        'materi' => $nextMaterial->id,
                                                    ]) }}"
                                                    class="block h-full rounded-xl border border-slate-200 p-4 text-right no-underline transition hover:border-blue-200 hover:bg-blue-50"
                                                >
                                                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                                                        Berikutnya
                                                    </p>

                                                    <p class="mt-2 flex items-center justify-end gap-2 font-semibold text-slate-800">
                                                        {{ $nextMaterial->title }}
                                                        <i class="bi bi-arrow-right"></i>
                                                    </p>
                                                </a>
                                            @endif
                                        </div>
                                    </footer>
                                @endif
                            </article>
                        @endif
                    </div>
                </section>
            </div>
        @endif
    </main>

    <footer class="border-t border-slate-200 bg-white">
        <div class="px-4 py-6 text-center text-sm text-slate-500 sm:px-6 lg:px-8">
            {{ $application->name }} · Knowledge Management System
        </div>
    </footer>
</body>
</html>