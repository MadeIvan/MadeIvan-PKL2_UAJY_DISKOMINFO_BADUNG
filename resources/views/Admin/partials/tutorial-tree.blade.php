@foreach ($nodes as $node)
    @php
        $children = collect($node['children'] ?? []);

        $hasChildren = $children->isNotEmpty();

        $isMateri =
            ($node['node_type'] ?? null) === 'materi';

        $isActive =
            $selectedMaterial &&
            (int) $selectedMaterial->id ===
            (int) $node['id'];

        $containsActiveMaterial = function ($items) use (
            &$containsActiveMaterial,
            $selectedMaterial
        ) {
            if (!$selectedMaterial) {
                return false;
            }

            foreach ($items as $item) {
                if (
                    (int) ($item['id'] ?? 0) ===
                    (int) $selectedMaterial->id
                ) {
                    return true;
                }

                if (
                    !empty($item['children']) &&
                    $containsActiveMaterial(
                        $item['children']
                    )
                ) {
                    return true;
                }
            }

            return false;
        };

        $hasActiveDescendant =
            $hasChildren &&
            $containsActiveMaterial(
                $children->all()
            );

        $shouldOpen =
            $isActive ||
            $hasActiveDescendant;

        $materialUrl =
            $isMateri
                ? route('admin.applications.preview', [
                    'application' => $application->slug,
                    'version' => $selectedVersion->id,
                    'materi' => $node['id'],
                ])
                : null;
    @endphp

    <div class="tree-item">

        @if ($isMateri)

            {{-- Materi --}}
            <div class="flex items-center">

                {{-- Expand button only when materi has children --}}
                @if ($hasChildren)
                    <button
                        type="button"
                        data-tree-toggle
                        aria-expanded="{{ $shouldOpen ? 'true' : 'false' }}"
                        class="flex h-8 w-7 shrink-0 items-center justify-center text-slate-400 transition hover:text-slate-700"
                        aria-label="Buka atau tutup sub materi"
                    >
                        <i
                            data-tree-arrow
                            class="bi {{ $shouldOpen ? 'bi-chevron-down' : 'bi-chevron-right' }} text-xs"
                        ></i>
                    </button>
                @else
                    <div class="w-7 shrink-0"></div>
                @endif

                {{-- Materi link --}}
                <a
                    href="{{ $materialUrl }}"
                    class="
                        flex min-w-0 flex-1 items-center gap-2 border-l-2 px-3 py-2 text-sm no-underline transition

                        {{ $isActive
                            ? 'border-blue-900 bg-blue-50 font-semibold text-blue-950'
                            : 'border-transparent text-slate-600 hover:bg-slate-50 hover:text-slate-950'
                        }}
                    "
                >
                    <i
                        class="bi bi-file-earmark-text shrink-0 {{ $isActive ? 'text-blue-900' : 'text-slate-400' }}"
                    ></i>

                    <span class="truncate">
                        {{ $node['title'] }}
                    </span>
                </a>
            </div>

            {{-- Materi children --}}
            @if ($hasChildren)
                <div
                    data-tree-children
                    class="{{ $shouldOpen ? '' : 'hidden' }} ml-5 border-l border-slate-200 pl-2"
                >
                    @include('Admin.partials.tutorial-tree', [
                        'nodes' => $children,
                        'application' => $application,
                        'selectedVersion' => $selectedVersion,
                        'selectedMaterial' => $selectedMaterial,
                    ])
                </div>
            @endif

        @else

            {{-- Kategori / Bagian --}}
            <div class="flex items-center">

                @if ($hasChildren)
                    <button
                        type="button"
                        data-tree-toggle
                        aria-expanded="{{ $shouldOpen ? 'true' : 'false' }}"
                        class="flex h-8 w-7 shrink-0 items-center justify-center text-slate-400 transition hover:text-slate-700"
                        aria-label="Buka atau tutup bagian"
                    >
                        <i
                            data-tree-arrow
                            class="bi {{ $shouldOpen ? 'bi-chevron-down' : 'bi-chevron-right' }} text-xs"
                        ></i>
                    </button>
                @else
                    <div class="w-7 shrink-0"></div>
                @endif

                <div class="flex min-w-0 flex-1 items-center gap-2 px-2 py-2 text-sm font-semibold text-slate-800">

                    @if (($node['node_type'] ?? null) === 'kategori')
                        <i class="bi bi-folder2 shrink-0 text-blue-700"></i>
                    @else
                        <i class="bi bi-folder2 shrink-0 text-violet-600"></i>
                    @endif

                    <span class="truncate">
                        {{ $node['title'] }}
                    </span>
                </div>
            </div>

            {{-- Kategori / Bagian children --}}
            @if ($hasChildren)
                <div
                    data-tree-children
                    class="{{ $shouldOpen ? '' : 'hidden' }} ml-5 border-l border-slate-200 pl-2"
                >
                    @include('Admin.partials.tutorial-tree', [
                        'nodes' => $children,
                        'application' => $application,
                        'selectedVersion' => $selectedVersion,
                        'selectedMaterial' => $selectedMaterial,
                    ])
                </div>
            @endif

        @endif
    </div>
@endforeach