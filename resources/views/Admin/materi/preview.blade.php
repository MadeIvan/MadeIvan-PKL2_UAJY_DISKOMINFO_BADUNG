<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Preview {{ $tutorialNode->title }}
    </title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js',
    ])

    <style>
        .tutorial-rich-content {
            color: #334155;
            font-size: 1rem;
            line-height: 1.85;
        }

        .tutorial-rich-content h1,
        .tutorial-rich-content h2,
        .tutorial-rich-content h3,
        .tutorial-rich-content h4 {
            color: #0f172a;
            font-weight: 700;
            line-height: 1.3;
            margin-bottom: 0.75rem;
            margin-top: 1.75rem;
        }

        .tutorial-rich-content h1 {
            font-size: 2rem;
        }

        .tutorial-rich-content h2 {
            font-size: 1.5rem;
        }

        .tutorial-rich-content h3 {
            font-size: 1.25rem;
        }

        .tutorial-rich-content h4 {
            font-size: 1.125rem;
        }

        .tutorial-rich-content p {
            margin-bottom: 1rem;
        }

        .tutorial-rich-content ul {
            list-style-type: disc;
            margin-bottom: 1rem;
            padding-left: 1.5rem;
        }

        .tutorial-rich-content ol {
            list-style-type: decimal;
            margin-bottom: 1rem;
            padding-left: 1.5rem;
        }

        .tutorial-rich-content li {
            margin-bottom: 0.35rem;
        }

        .tutorial-rich-content blockquote {
            border-left: 4px solid #1e3a8a;
            color: #475569;
            margin: 1.5rem 0;
            padding-left: 1rem;
        }

        .tutorial-rich-content table {
            border-collapse: collapse;
            margin: 1.5rem 0;
            width: 100%;
        }

        .tutorial-rich-content th,
        .tutorial-rich-content td {
            border: 1px solid #cbd5e1;
            padding: 0.75rem;
            text-align: left;
            vertical-align: top;
        }

        .tutorial-rich-content th {
            background: #f1f5f9;
            color: #0f172a;
            font-weight: 700;
        }

        .tutorial-rich-content pre {
            background: #0f172a;
            color: #f8fafc;
            margin: 1.5rem 0;
            overflow-x: auto;
            padding: 1rem;
        }

        .tutorial-rich-content code {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        }

        .tutorial-rich-content a {
            color: #1d4ed8;
            text-decoration: underline;
        }

        .tutorial-rich-content img {
            height: auto;
            max-width: 100%;
        }
    </style>
</head>

<body class="min-h-screen bg-slate-100 text-slate-900">
    @php
        $applicationName =
            $tutorialNode->application?->name
            ?? 'Aplikasi';

        $versionNumber =
            $tutorialNode->applicationVersion?->version_number
            ?? 'Tidak diketahui';

        $parentTitle =
            $tutorialNode->parent?->title;

        $blocks =
            $tutorialNode->contentBlocks
                ->sortBy([
                    ['sort_order', 'asc'],
                    ['id', 'asc'],
                ])
                ->values();

        $getYoutubeId = function (?string $url): ?string {
            if (!$url) {
                return null;
            }

            $parts = parse_url($url);

            if (!$parts) {
                return null;
            }

            $host =
                strtolower(
                    preg_replace(
                        '/^www\./i',
                        '',
                        $parts['host'] ?? ''
                    )
                );

            $path =
                trim(
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

                $segments =
                    array_values(
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

    <header class="sticky top-0 z-40 border-b border-slate-200 bg-white/95 backdrop-blur">
        <div class="mx-auto flex max-w-6xl items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
            <div class="min-w-0">
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-blue-900">
                    Preview Admin
                </p>

                <p class="mt-1 truncate text-sm text-slate-500">
                    Tampilan penuh materi seperti yang akan dibaca pengguna.
                </p>
            </div>

            <a
                href="{{ route('admin.materi.content', ['tutorialNode' => $tutorialNode->id]) }}"
                class="inline-flex shrink-0 items-center justify-center gap-2 border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
            >
                <span aria-hidden="true">←</span>
                Kembali ke Editor
            </a>
        </div>
    </header>

    <main class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8 lg:py-12">
        <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_260px]">
            <article class="min-w-0 border border-slate-200 bg-white shadow-sm">
                <header class="border-b border-slate-200 px-5 py-8 sm:px-8 lg:px-12 lg:py-12">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="border border-blue-200 bg-blue-50 px-2.5 py-1 text-xs font-bold text-blue-900">
                            {{ $applicationName }}
                        </span>

                        <span class="border border-slate-200 bg-slate-50 px-2.5 py-1 text-xs font-semibold text-slate-600">
                            Versi {{ $versionNumber }}
                        </span>

                        <span class="border border-amber-200 bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700">
                            {{ ucfirst($tutorialNode->status) }}
                        </span>

                        @if ($tutorialNode->is_public)
                            <span class="border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                                Publik
                            </span>
                        @else
                            <span class="border border-slate-200 bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">
                                Tidak Publik
                            </span>
                        @endif
                    </div>

                    <h1 class="mt-5 text-3xl font-bold leading-tight text-slate-950 sm:text-4xl">
                        {{ $tutorialNode->title }}
                    </h1>

                    @if ($tutorialNode->description)
                        <p class="mt-4 max-w-3xl text-base leading-8 text-slate-600">
                            {{ $tutorialNode->description }}
                        </p>
                    @endif

                    <div class="mt-6 flex flex-wrap gap-x-6 gap-y-2 text-sm text-slate-500">
                        @if ($parentTitle)
                            <span>
                                Bagian:
                                <strong class="font-semibold text-slate-700">
                                    {{ $parentTitle }}
                                </strong>
                            </span>
                        @endif

                        <span>
                            Total:
                            <strong class="font-semibold text-slate-700">
                                {{ $blocks->count() }} blok
                            </strong>
                        </span>
                    </div>
                </header>

                <div class="px-5 py-8 sm:px-8 lg:px-12 lg:py-12">
                    @if ($blocks->isEmpty())
                        <div class="border border-dashed border-slate-300 bg-slate-50 px-5 py-16 text-center">
                            <p class="text-lg font-bold text-slate-900">
                                Materi belum memiliki isi
                            </p>

                            <p class="mt-2 text-sm text-slate-500">
                                Kembali ke editor untuk menambahkan content block.
                            </p>
                        </div>
                    @else
                        <div class="space-y-10">
                            @foreach ($blocks as $block)
                                <section
                                    id="block-{{ $block->id }}"
                                    class="scroll-mt-28"
                                >
                                    @if ($block->block_type === 'text')
                                        <div class="tutorial-rich-content">
                                            {!! $block->content !!}
                                        </div>
                                    @elseif ($block->block_type === 'image')
                                        @if ($block->file_path)
                                            <figure class="space-y-3">
                                                <img
                                                    src="{{ asset(
                                                        'storage/' . ltrim(
                                                            $block->file_path,
                                                            '/'
                                                        )
                                                    ) }}"
                                                    alt="{{ $block->alt_text ?: ($block->caption ?: 'Gambar materi') }}"
                                                    class="max-h-[720px] w-full border border-slate-200 bg-slate-50 object-contain"
                                                >

                                                @if ($block->caption)
                                                    <figcaption class="text-center text-sm italic text-slate-500">
                                                        {{ $block->caption }}
                                                    </figcaption>
                                                @endif
                                            </figure>
                                        @else
                                            <div class="border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                                                File gambar tidak tersedia.
                                            </div>
                                        @endif
                                    @elseif ($block->block_type === 'youtube')
                                        @php
                                            $videoId =
                                                $getYoutubeId(
                                                    $block->external_url
                                                );
                                        @endphp

                                        <div class="space-y-4">
                                            @if ($block->title)
                                                <h2 class="text-xl font-bold text-slate-950">
                                                    {{ $block->title }}
                                                </h2>
                                            @endif

                                            @if ($videoId)
                                                <div class="aspect-video w-full overflow-hidden bg-slate-950">
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
                                                <div class="border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                                                    Tautan YouTube tidak valid.
                                                </div>
                                            @endif
                                        </div>
                                    @elseif ($block->block_type === 'pdf')
                                        @php
                                            $pdfUrl =
                                                $block->file_path
                                                    ? asset(
                                                        'storage/' . ltrim(
                                                            $block->file_path,
                                                            '/'
                                                        )
                                                    )
                                                    : null;
                                        @endphp

                                        <div class="border border-slate-200 bg-slate-50 p-5">
                                            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                                <div class="min-w-0">
                                                    <p class="truncate font-bold text-slate-900">
                                                        {{ $block->original_file_name ?: 'Dokumen PDF' }}
                                                    </p>

                                                    <p class="mt-1 text-xs text-slate-500">
                                                        {{ $formatFileSize($block->file_size) }}
                                                    </p>

                                                    @if ($block->caption)
                                                        <p class="mt-3 text-sm leading-6 text-slate-600">
                                                            {{ $block->caption }}
                                                        </p>
                                                    @endif
                                                </div>

                                                @if ($pdfUrl)
                                                    <a
                                                        href="{{ $pdfUrl }}"
                                                        target="_blank"
                                                        rel="noopener noreferrer"
                                                        class="inline-flex shrink-0 items-center justify-center gap-2 border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-100"
                                                    >
                                                        Buka PDF
                                                        <span aria-hidden="true">↗</span>
                                                    </a>
                                                @endif
                                            </div>

                                            @if ($pdfUrl)
                                                <iframe
                                                    src="{{ $pdfUrl }}"
                                                    title="{{ $block->original_file_name ?: 'Dokumen PDF' }}"
                                                    class="mt-5 h-[680px] w-full border border-slate-300 bg-white"
                                                ></iframe>
                                            @endif
                                        </div>
                                    @endif
                                </section>
                            @endforeach
                        </div>
                    @endif
                </div>
            </article>

            <aside class="hidden lg:block">
                <div class="sticky top-28 border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-blue-900">
                        Informasi Preview
                    </p>

                    <dl class="mt-5 space-y-4 text-sm">
                        <div>
                            <dt class="text-slate-500">
                                Aplikasi
                            </dt>

                            <dd class="mt-1 font-semibold text-slate-900">
                                {{ $applicationName }}
                            </dd>
                        </div>

                        <div>
                            <dt class="text-slate-500">
                                Versi
                            </dt>

                            <dd class="mt-1 font-semibold text-slate-900">
                                {{ $versionNumber }}
                            </dd>
                        </div>

                        <div>
                            <dt class="text-slate-500">
                                Status
                            </dt>

                            <dd class="mt-1 font-semibold text-slate-900">
                                {{ ucfirst($tutorialNode->status) }}
                            </dd>
                        </div>

                        <div>
                            <dt class="text-slate-500">
                                Akses
                            </dt>

                            <dd class="mt-1 font-semibold text-slate-900">
                                {{ $tutorialNode->is_public ? 'Publik' : 'Tidak Publik' }}
                            </dd>
                        </div>

                        <div>
                            <dt class="text-slate-500">
                                Jumlah Blok
                            </dt>

                            <dd class="mt-1 font-semibold text-slate-900">
                                {{ $blocks->count() }}
                            </dd>
                        </div>
                    </dl>

                    <a
                        href="{{ route(
                            'admin.materi.content',
                            ['tutorialNode' => $tutorialNode->id]
                        ) }}"
                        class="mt-6 inline-flex w-full items-center justify-center border border-blue-900 bg-blue-950 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-900"
                    >
                        Kembali Mengedit
                    </a>
                </div>
            </aside>
        </div>
    </main>
</body>
</html>