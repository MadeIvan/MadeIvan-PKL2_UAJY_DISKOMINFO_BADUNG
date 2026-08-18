import 'bootstrap-icons/font/bootstrap-icons.css';
import { showToast, displayValidationErrors, clearValidationErrors, setButtonLoading } from '../utils.js';

import tinymce from 'tinymce';

import 'tinymce/icons/default';
import 'tinymce/themes/silver';
import 'tinymce/models/dom';

import 'tinymce/plugins/advlist';
import 'tinymce/plugins/autolink';
import 'tinymce/plugins/lists';
import 'tinymce/plugins/link';
import 'tinymce/plugins/table';
import 'tinymce/plugins/code';
import 'tinymce/plugins/preview';
import 'tinymce/plugins/searchreplace';
import 'tinymce/plugins/wordcount';
import 'tinymce/plugins/fullscreen';

import 'tinymce/skins/ui/oxide/skin.css';

import DOMPurify from 'dompurify';

document.addEventListener('DOMContentLoaded', () => {
    const API_BASE_URL = '/api/admin';

    const page = document.getElementById('content-page');

    if (!page) {
        return;
    }

    const nodeId = Number(page.dataset.nodeId);

    const state = {
        node: null,
        blocks: [],
        editingBlock: null,
        dirty: false,
        objectUrl: null,
        textEditor: null,
        loadingContent: false,
        reordering: false,
        deletingBlockId: null,
    };

    let notificationTimer = null;

    const elements = {


        nodeTitle:
            document.getElementById('node-title'),

        nodeMeta:
            document.getElementById('node-meta'),

        refreshButton:
            document.getElementById('refresh-button'),

        retryButton:
            document.getElementById('retry-button'),

        blockCount:
            document.getElementById('block-count'),

        reorderStatus:
            document.getElementById('reorder-status'),

        loadingState:
            document.getElementById('loading-state'),

        errorState:
            document.getElementById('error-state'),

        errorMessage:
            document.getElementById('error-message'),

        emptyState:
            document.getElementById('empty-state'),

        blocksContainer:
            document.getElementById('blocks-container'),

        addBlockButtons:
            document.querySelectorAll('.add-block-button'),

        blockModal:
            document.getElementById('block-modal'),

        blockForm:
            document.getElementById('block-form'),

        formTitle:
            document.getElementById('form-title'),

        formDescription:
            document.getElementById('form-description'),

        modalClose:
            document.getElementById('modal-close'),

        cancelButton:
            document.getElementById('cancel-button'),

        submitButton:
            document.getElementById('submit-button'),

        blockId:
            document.getElementById('block-id'),

        blockType:
            document.getElementById('block-type'),

        blockTitle:
            document.getElementById('block-title'),

        blockContent:
            document.getElementById('block-content'),

        blockUrl:
            document.getElementById('block-url'),

        blockFile:
            document.getElementById('block-file'),

        blockCaption:
            document.getElementById('block-caption'),

        blockAlt:
            document.getElementById('block-alt'),

        textFields:
            document.getElementById('text-fields'),

        youtubeFields:
            document.getElementById('youtube-fields'),

        fileFields:
            document.getElementById('file-fields'),

        captionFields:
            document.getElementById('caption-fields'),

        altWrapper:
            document.getElementById('alt-wrapper'),

        fileHelp:
            document.getElementById('file-help'),

        existingFile:
            document.getElementById('existing-file'),

        imagePreview:
            document.getElementById('image-preview'),

        imagePreviewElement:
            document.getElementById('image-preview-element'),

        youtubePreview:
            document.getElementById('youtube-preview'),

        youtubeIframe:
            document.getElementById('youtube-iframe'),

        youtubePreviewButton:
            document.getElementById('youtube-preview-button'),
    };

    const missingElements = Object.entries(elements)
        .filter(([key, value]) => {
            return key !== 'addBlockButtons' && !value;
        })
        .map(([key]) => key);

    if (missingElements.length > 0) {
        console.error(
            'Editor materi: elemen Blade tidak ditemukan:',
            missingElements
        );

        return;
    }

    if (!Number.isInteger(nodeId) || nodeId <= 0) {
        console.error('Tutorial node ID tidak valid.');

        return;
    }

    initializePage();

    async function initializePage() {
        bindEvents();

        await initializeTinyMce();

        await loadContent();
    }

    async function initializeTinyMce() {
        try {
            const editors = await tinymce.init({
                selector: '#block-content',

                license_key: 'gpl',

                height: 460,

                menubar: false,

                branding: false,

                promotion: false,

                skin: false,

                content_css: false,

                plugins: [
                    'advlist',
                    'autolink',
                    'lists',
                    'link',
                    'table',
                    'code',
                    'preview',
                    'searchreplace',
                    'wordcount',
                    'fullscreen',
                ],

                toolbar: [
                    'undo redo',
                    'blocks',
                    'bold italic underline',
                    'alignleft aligncenter alignright alignjustify',
                    'bullist numlist',
                    'outdent indent',
                    'link table',
                    'searchreplace',
                    'code preview fullscreen',
                ].join(' | '),

                block_formats: [
                    'Paragraf=p',
                    'Judul 1=h1',
                    'Judul 2=h2',
                    'Judul 3=h3',
                    'Judul 4=h4',
                    'Kutipan=blockquote',
                    'Kode=pre',
                ].join(';'),

                content_style: `
                    body {
                        font-family:
                            Inter,
                            ui-sans-serif,
                            system-ui,
                            -apple-system,
                            BlinkMacSystemFont,
                            "Segoe UI",
                            sans-serif;

                        font-size: 16px;
                        line-height: 1.75;
                        color: #334155;
                        padding: 16px;
                    }

                    h1,
                    h2,
                    h3,
                    h4 {
                        color: #0f172a;
                        line-height: 1.3;
                        font-weight: 700;
                    }

                    h1 {
                        font-size: 2rem;
                    }

                    h2 {
                        font-size: 1.5rem;
                    }

                    h3 {
                        font-size: 1.25rem;
                    }

                    h4 {
                        font-size: 1.1rem;
                    }

                    p {
                        margin-top: 0;
                        margin-bottom: 1rem;
                    }

                    blockquote {
                        border-left: 4px solid #1e3a8a;
                        margin-left: 0;
                        padding-left: 16px;
                        color: #475569;
                    }

                    table {
                        width: 100%;
                        border-collapse: collapse;
                    }

                    th,
                    td {
                        border: 1px solid #cbd5e1;
                        padding: 8px;
                        vertical-align: top;
                    }

                    th {
                        background: #f1f5f9;
                        color: #0f172a;
                    }

                    pre {
                        background: #0f172a;
                        color: #f8fafc;
                        padding: 16px;
                        overflow-x: auto;
                    }

                    a {
                        color: #1d4ed8;
                        text-decoration: underline;
                    }

                    img {
                        max-width: 100%;
                        height: auto;
                    }
                `,

                setup(editor) {
                    editor.on(
                        'change input undo redo',
                        () => {
                            editor.save();
                            state.dirty = true;
                        }
                    );
                },
            });

            state.textEditor = editors?.[0] ?? null;
        } catch (error) {
            console.error(
                'TinyMCE gagal dimuat:',
                error
            );

            showToast(
                'Editor teks gagal dimuat. Textarea biasa tetap dapat digunakan.',
                'error'
            );
        }
    }

    function bindEvents() {
        elements.refreshButton.addEventListener(
            'click',
            loadContent
        );

        elements.retryButton.addEventListener(
            'click',
            loadContent
        );

        elements.addBlockButtons.forEach((button) => {
            button.addEventListener('click', () => {
                openCreateBlock(
                    button.dataset.addType
                );
            });
        });

        elements.blocksContainer.addEventListener(
            'click',
            handleBlockAction
        );

        elements.blockForm.addEventListener(
            'submit',
            saveBlock
        );

        elements.blockForm.addEventListener(
            'input',
            () => {
                state.dirty = true;
            }
        );

        elements.blockForm.addEventListener(
            'change',
            () => {
                state.dirty = true;
            }
        );

        elements.modalClose.addEventListener(
            'click',
            requestCloseBlockModal
        );

        elements.cancelButton.addEventListener(
            'click',
            requestCloseBlockModal
        );

        elements.blockFile.addEventListener(
            'change',
            previewSelectedFile
        );

        elements.youtubePreviewButton.addEventListener(
            'click',
            previewYoutubeInForm
        );

        elements.blockModal.addEventListener(
            'click',
            (event) => {
                if (
                    event.target ===
                    elements.blockModal
                ) {
                    requestCloseBlockModal();
                }
            }
        );

        document.addEventListener(
            'keydown',
            (event) => {
                if (event.key !== 'Escape') {
                    return;
                }

                if (
                    !elements.blockModal.classList.contains(
                        'hidden'
                    )
                ) {
                    requestCloseBlockModal();
                }
            }
        );

        window.addEventListener(
            'beforeunload',
            (event) => {
                if (!state.dirty) {
                    return;
                }

                event.preventDefault();
                event.returnValue = '';
            }
        );
    }

    async function loadContent() {
        if (state.loadingContent) {
            return;
        }

        state.loadingContent = true;

        setPageLoading(true);
        showPageState('loading');

        try {
            const response = await fetch(
                `${API_BASE_URL}/tutorial-nodes/${nodeId}/content-blocks`,
                {
                    headers: {
                        Accept: 'application/json',
                    },
                }
            );

            const result = await parseResponse(
                response
            );

            state.node =
                result?.data?.tutorial_node ??
                null;

            state.blocks =
                Array.isArray(
                    result?.data?.blocks
                )
                    ? sortBlocks(
                        result.data.blocks
                    )
                    : [];

            renderHeader();
            renderBlocks();
        } catch (error) {
            elements.errorMessage.textContent =
                error.message;

            showPageState('error');

            showToast(
                error.message,
                'error'
            );
        } finally {
            state.loadingContent = false;

            setPageLoading(false);
        }
    }

    function sortBlocks(blocks) {
        return [...blocks].sort(
            (first, second) => {
                const sortDifference =
                    Number(
                        first.sort_order ?? 0
                    ) -
                    Number(
                        second.sort_order ?? 0
                    );

                if (sortDifference !== 0) {
                    return sortDifference;
                }

                return (
                    Number(first.id) -
                    Number(second.id)
                );
            }
        );
    }

    function renderHeader() {
        elements.nodeTitle.textContent =
            state.node?.title ??
            'Materi tidak ditemukan';

        const applicationName =
            state.node?.application?.name ??
            'Aplikasi tidak diketahui';

        const versionNumber =
            state.node?.application_version?.version_number ??
            'Tidak diketahui';

        const parentTitle =
            state.node?.parent?.title ??
            'Tanpa parent';

        const nodeType =
            nodeTypeLabel(
                state.node?.node_type
            );

        elements.nodeMeta.textContent =
            `${applicationName} • v${versionNumber} • ${parentTitle} • ${nodeType}`;
    }

    function renderBlocks() {
        elements.blockCount.textContent =
            `${state.blocks.length} blok`;

        if (state.blocks.length === 0) {
            elements.blocksContainer.innerHTML =
                '';

            showPageState('empty');

            return;
        }

        elements.blocksContainer.innerHTML =
            state.blocks
                .map((block, index) => {
                    return blockCardHtml(
                        block,
                        index
                    );
                })
                .join('');

        showPageState('blocks');

        updateReorderButtonAvailability();
    }

    function blockCardHtml(
        block,
        index
    ) {
        const isFirst =
            index === 0;

        const isLast =
            index ===
            state.blocks.length - 1;

        const isDeleting =
            Number(
                state.deletingBlockId
            ) === Number(block.id);

        return `
            <article
                class="content-block-card overflow-hidden border border-slate-200 bg-white transition hover:border-slate-300 hover:shadow-sm"
                data-id="${block.id}"
            >
                <div class="flex flex-col xl:flex-row xl:items-stretch">
                    <div class="min-w-0 flex-1 p-4 sm:p-5">
                        <div class="mb-4 flex flex-wrap items-center gap-2">
                            <span
                                class="border px-2 py-1 text-xs font-semibold ${blockTypeClass(
                                    block.block_type
                                )}"
                            >
                                ${escapeHtml(
                                    blockTypeLabel(
                                        block.block_type
                                    )
                                )}
                            </span>

                            <span
                                class="border border-slate-200 bg-slate-50 px-2 py-1 text-xs font-semibold text-slate-500"
                            >
                                Urutan ${index + 1}
                            </span>
                        </div>

                        ${renderBlockContent(block)}
                    </div>

                    <div class="flex shrink-0 items-center justify-between gap-3 border-t border-slate-200 bg-slate-50 px-4 py-3 xl:w-36 xl:flex-col xl:justify-center xl:border-l xl:border-t-0">
                        <div class="flex overflow-hidden border border-slate-300 bg-white">
                            <button
                                type="button"
                                class="move-block-up flex h-9 w-9 items-center justify-center text-slate-600 transition hover:bg-slate-100 disabled:cursor-not-allowed disabled:opacity-30"
                                data-id="${block.id}"
                                data-boundary-disabled="${
                                    isFirst
                                        ? 'true'
                                        : 'false'
                                }"
                                ${
                                    isFirst ||
                                    state.reordering
                                        ? 'disabled'
                                        : ''
                                }
                                aria-label="Pindahkan blok ke atas"
                                title="Pindahkan ke atas"
                            >
                                <i class="bi bi-caret-up-fill"></i>
                            </button>

                            <button
                                type="button"
                                class="move-block-down flex h-9 w-9 items-center justify-center border-l border-slate-300 text-slate-600 transition hover:bg-slate-100 disabled:cursor-not-allowed disabled:opacity-30"
                                data-id="${block.id}"
                                data-boundary-disabled="${
                                    isLast
                                        ? 'true'
                                        : 'false'
                                }"
                                ${
                                    isLast ||
                                    state.reordering
                                        ? 'disabled'
                                        : ''
                                }
                                aria-label="Pindahkan blok ke bawah"
                                title="Pindahkan ke bawah"
                            >
                                <i class="bi bi-caret-down-fill"></i>
                            </button>
                        </div>

                        <div class="flex items-center gap-2">
                            <button
                                type="button"
                                class="edit-block-button flex h-9 w-9 items-center justify-center border border-blue-200 bg-white text-blue-900 transition hover:bg-blue-50 disabled:cursor-not-allowed disabled:opacity-50"
                                data-id="${block.id}"
                                ${
                                    state.reordering ||
                                    isDeleting
                                        ? 'disabled'
                                        : ''
                                }
                                aria-label="Ubah blok"
                                title="Ubah blok"
                            >
                                <i class="bi bi-pencil-square"></i>
                            </button>

                            <button
                                type="button"
                                class="delete-block-button flex h-9 w-9 items-center justify-center border border-red-200 bg-white text-red-600 transition hover:bg-red-50 disabled:cursor-not-allowed disabled:opacity-50"
                                data-id="${block.id}"
                                ${
                                    state.reordering ||
                                    isDeleting
                                        ? 'disabled'
                                        : ''
                                }
                                aria-label="Hapus blok"
                                title="Hapus blok"
                            >
                                ${
                                    isDeleting
                                        ? `
                                            <i class="bi bi-arrow-repeat animate-spin"></i>
                                        `
                                        : `
                                            <i class="bi bi-trash3"></i>
                                        `
                                }
                            </button>
                        </div>
                    </div>
                </div>
            </article>
        `;
    }

    function renderBlockContent(block) {
        if (block.block_type === 'text') {
            const plainText =
                htmlToPlainText(
                    block.content || ''
                );

            return `
                <div class="flex min-w-0 flex-col gap-4 sm:flex-row sm:items-stretch">
                    <div class="flex h-40 w-full shrink-0 items-center justify-center border border-blue-200 bg-blue-50 text-blue-900 sm:h-28 sm:w-40">
                        <div class="text-center">
                            <i class="bi bi-type text-3xl"></i>

                            <p class="mt-2 text-xs font-semibold">
                                Konten Teks
                            </p>
                        </div>
                    </div>

                    <div class="min-w-0 flex-1 py-1">
                        <h4 class="font-semibold text-slate-900">
                            Blok Teks
                        </h4>

                        <p class="mt-2 line-clamp-3 text-sm leading-6 text-slate-600">
                            ${escapeHtml(
                                plainText ||
                                'Teks kosong.'
                            )}
                        </p>

                        <p class="mt-3 text-xs text-slate-400">
                            Klik tombol edit untuk melihat atau mengubah seluruh isi teks.
                        </p>
                    </div>
                </div>
            `;
        }

        if (block.block_type === 'image') {
            const url =
                storageUrl(
                    block.file_path
                );

            const title =
                block.caption ||
                block.original_file_name ||
                'Gambar Materi';

            const description =
                block.alt_text ||
                block.caption ||
                'Tidak ada keterangan gambar.';

            return `
                <div class="flex min-w-0 flex-col gap-4 sm:flex-row sm:items-stretch">
                    <div class="flex h-44 w-full shrink-0 items-center justify-center overflow-hidden border border-slate-200 bg-slate-100 sm:h-28 sm:w-40">
                        ${
                            url
                                ? `
                                    <img
                                        src="${escapeHtml(url)}"
                                        alt="${escapeHtml(
                                            description
                                        )}"
                                        class="h-full w-full object-cover"
                                        loading="lazy"
                                    >
                                `
                                : `
                                    <div class="px-4 text-center text-slate-400">
                                        <i class="bi bi-image text-3xl"></i>

                                        <p class="mt-2 text-xs">
                                            Gambar tidak tersedia
                                        </p>
                                    </div>
                                `
                        }
                    </div>

                    <div class="min-w-0 flex-1 py-1">
                        <div class="flex items-start gap-2">
                            <i class="bi bi-image mt-0.5 shrink-0 text-emerald-700"></i>

                            <h4 class="line-clamp-2 font-semibold leading-6 text-slate-900">
                                ${escapeHtml(title)}
                            </h4>
                        </div>

                        <p class="mt-2 line-clamp-2 text-sm leading-6 text-slate-600">
                            ${escapeHtml(description)}
                        </p>

                        ${
                            block.original_file_name
                                ? `
                                    <p class="mt-3 truncate text-xs text-slate-400">
                                        File: ${escapeHtml(
                                            block.original_file_name
                                        )}
                                    </p>
                                `
                                : ''
                        }
                    </div>
                </div>
            `;
        }

        if (block.block_type === 'youtube') {
            const videoId =
                youtubeId(
                    block.external_url
                );

            const title =
                block.title ||
                'Video YouTube';

            const thumbnailUrl =
                videoId
                    ? `https://img.youtube.com/vi/${encodeURIComponent(
                        videoId
                    )}/mqdefault.jpg`
                    : null;

            return `
                <div class="flex min-w-0 flex-col gap-4 sm:flex-row sm:items-stretch">
                    <div class="relative flex h-44 w-full shrink-0 items-center justify-center overflow-hidden border border-slate-200 bg-slate-950 sm:h-28 sm:w-40">
                        ${
                            thumbnailUrl
                                ? `
                                    <img
                                        src="${escapeHtml(
                                            thumbnailUrl
                                        )}"
                                        alt="${escapeHtml(title)}"
                                        class="h-full w-full object-cover"
                                        loading="lazy"
                                    >

                                    <div class="absolute inset-0 flex items-center justify-center bg-slate-950/25">
                                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-red-600 text-white shadow">
                                            <i class="bi bi-play-fill text-xl"></i>
                                        </div>
                                    </div>
                                `
                                : `
                                    <div class="px-4 text-center text-red-400">
                                        <i class="bi bi-youtube text-3xl"></i>

                                        <p class="mt-2 text-xs">
                                            URL tidak valid
                                        </p>
                                    </div>
                                `
                        }
                    </div>

                    <div class="min-w-0 flex-1 py-1">
                        <div class="flex items-start gap-2">
                            <i class="bi bi-youtube mt-0.5 shrink-0 text-red-600"></i>

                            <h4 class="line-clamp-2 font-semibold leading-6 text-slate-900">
                                ${escapeHtml(title)}
                            </h4>
                        </div>

                        <p class="mt-2 text-sm text-slate-500">
                            Video YouTube
                        </p>

                        ${
                            block.external_url
                                ? `
                                    <a
                                        href="${escapeHtml(
                                            block.external_url
                                        )}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="mt-3 inline-flex items-center gap-1.5 text-xs font-semibold text-blue-800 transition hover:underline"
                                    >
                                        <i class="bi bi-box-arrow-up-right"></i>
                                        Buka video
                                    </a>
                                `
                                : ''
                        }
                    </div>
                </div>
            `;
        }

        if (block.block_type === 'pdf') {
            const url =
                storageUrl(
                    block.file_path
                );

            const fileName =
                block.original_file_name ||
                'Dokumen PDF';

            const description =
                block.caption ||
                'Tidak ada keterangan dokumen.';

            return `
                <div class="flex min-w-0 flex-col gap-4 sm:flex-row sm:items-stretch">
                    <div class="flex h-44 w-full shrink-0 items-center justify-center border border-red-200 bg-red-50 sm:h-28 sm:w-40">
                        <div class="text-center text-red-600">
                            <i class="bi bi-file-earmark-pdf text-4xl"></i>

                            <p class="mt-2 text-xs font-bold uppercase tracking-wide">
                                PDF
                            </p>
                        </div>
                    </div>

                    <div class="min-w-0 flex-1 py-1">
                        <div class="flex items-start gap-2">
                            <i class="bi bi-file-earmark-pdf mt-0.5 shrink-0 text-red-600"></i>

                            <h4 class="truncate font-semibold text-slate-900">
                                ${escapeHtml(fileName)}
                            </h4>
                        </div>

                        <p class="mt-2 line-clamp-2 text-sm leading-6 text-slate-600">
                            ${escapeHtml(description)}
                        </p>

                        <div class="mt-3 flex flex-wrap items-center gap-3">
                            <span class="text-xs text-slate-400">
                                ${formatFileSize(
                                    block.file_size
                                )}
                            </span>

                            ${
                                url
                                    ? `
                                        <a
                                            href="${escapeHtml(url)}"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="inline-flex items-center gap-1.5 text-xs font-semibold text-blue-800 transition hover:underline"
                                        >
                                            <i class="bi bi-box-arrow-up-right"></i>
                                            Buka PDF
                                        </a>
                                    `
                                    : `
                                        <span class="text-xs font-semibold text-red-600">
                                            File tidak tersedia
                                        </span>
                                    `
                            }
                        </div>
                    </div>
                </div>
            `;
        }

        return `
            <div class="flex min-w-0 flex-col gap-4 sm:flex-row sm:items-center">
                <div class="flex h-40 w-full shrink-0 items-center justify-center border border-slate-200 bg-slate-100 text-slate-400 sm:h-28 sm:w-40">
                    <i class="bi bi-question-circle text-3xl"></i>
                </div>

                <div class="min-w-0 flex-1">
                    <p class="font-semibold text-slate-900">
                        Jenis blok tidak dikenali
                    </p>

                    <p class="mt-1 text-sm text-slate-500">
                        Data content block tidak dapat ditampilkan.
                    </p>
                </div>
            </div>
        `;
    }

    function handleBlockAction(event) {
        const editButton =
            event.target.closest(
                '.edit-block-button'
            );

        const deleteButton =
            event.target.closest(
                '.delete-block-button'
            );

        const moveUpButton =
            event.target.closest(
                '.move-block-up'
            );

        const moveDownButton =
            event.target.closest(
                '.move-block-down'
            );

        if (editButton) {
            openEditBlock(
                Number(
                    editButton.dataset.id
                )
            );

            return;
        }

        if (deleteButton) {
            deleteBlock(
                Number(
                    deleteButton.dataset.id
                )
            );

            return;
        }

        if (moveUpButton) {
            moveBlock(
                Number(
                    moveUpButton.dataset.id
                ),
                -1
            );

            return;
        }

        if (moveDownButton) {
            moveBlock(
                Number(
                    moveDownButton.dataset.id
                ),
                1
            );
        }
    }

    function openCreateBlock(type) {
        const validTypes = [
            'text',
            'image',
            'youtube',
            'pdf',
        ];

        if (!validTypes.includes(type)) {
            showToast(
                'Jenis blok tidak valid.',
                'error'
            );

            return;
        }

        resetBlockForm();

        elements.blockType.value =
            type;

        elements.formTitle.textContent =
            `Tambah Blok ${blockTypeLabel(type)}`;

        elements.formDescription.textContent =
            'Isi informasi blok konten baru. Blok akan ditempatkan pada urutan paling bawah.';

        configureFormFields(type);

        if (type === 'text') {
            setEditorContent('');
        }

        openBlockModal();
    }

    function openEditBlock(id) {
        const block =
            state.blocks.find((item) => {
                return (
                    Number(item.id) ===
                    Number(id)
                );
            });

        if (!block) {
            showToast(
                'Blok tidak ditemukan.',
                'error'
            );

            return;
        }

        resetBlockForm();

        state.editingBlock =
            block;

        elements.blockId.value =
            String(block.id);

        elements.blockType.value =
            block.block_type;

        elements.blockTitle.value =
            block.title ?? '';

        elements.blockContent.value =
            block.content ?? '';

        elements.blockUrl.value =
            block.external_url ?? '';

        elements.blockCaption.value =
            block.caption ?? '';

        elements.blockAlt.value =
            block.alt_text ?? '';

        if (
            block.block_type ===
            'text'
        ) {
            setEditorContent(
                block.content ?? ''
            );
        }

        if (block.file_path) {
            elements.existingFile.textContent =
                `File saat ini: ${
                    block.original_file_name ||
                    block.file_path
                }`;

            elements.existingFile.classList.remove(
                'hidden'
            );
        }

        if (
            block.block_type ===
                'image' &&
            block.file_path
        ) {
            const imageUrl =
                storageUrl(
                    block.file_path
                );

            if (imageUrl) {
                elements.imagePreviewElement.src =
                    imageUrl;

                elements.imagePreview.classList.remove(
                    'hidden'
                );
            }
        }

        elements.formTitle.textContent =
            `Ubah Blok ${blockTypeLabel(
                block.block_type
            )}`;

        elements.formDescription.textContent =
            'Perbarui informasi blok konten. Urutan blok tidak berubah ketika isinya diperbarui.';

        configureFormFields(
            block.block_type
        );

        state.dirty =
            false;

        state.textEditor?.setDirty(
            false
        );

        openBlockModal();
    }

    function configureFormFields(type) {
        elements.textFields.classList.toggle(
            'hidden',
            type !== 'text'
        );

        elements.youtubeFields.classList.toggle(
            'hidden',
            type !== 'youtube'
        );

        elements.fileFields.classList.toggle(
            'hidden',
            ![
                'image',
                'pdf',
            ].includes(type)
        );

        elements.captionFields.classList.toggle(
            'hidden',
            ![
                'image',
                'pdf',
            ].includes(type)
        );

        elements.altWrapper.classList.toggle(
            'hidden',
            type !== 'image'
        );

        elements.blockContent.required =
            type === 'text';

        elements.blockUrl.required =
            type === 'youtube';

        elements.blockTitle.required =
            type === 'youtube';

        elements.blockFile.required =
            !state.editingBlock &&
            [
                'image',
                'pdf',
            ].includes(type);

        if (type === 'image') {
            elements.blockFile.accept =
                'image/jpeg,image/png,image/webp';

            elements.fileHelp.textContent =
                'JPG, PNG, atau WEBP. Maksimal 20 MB.';

            return;
        }

        if (type === 'pdf') {
            elements.blockFile.accept =
                'application/pdf';

            elements.fileHelp.textContent =
                'Hanya PDF. Maksimal 20 MB.';

            return;
        }

        elements.blockFile.accept =
            '';

        elements.fileHelp.textContent =
            '';
    }

    async function saveBlock(event) {
        event.preventDefault();

        syncTinyMceToTextarea();

        const validationError =
            validateBlockForm();

        if (validationError) {
            showToast(
                validationError,
                'error'
            );

            return;
        }

        const formData =
            new FormData();

        formData.append(
            'block_type',
            elements.blockType.value
        );

        formData.append(
            'title',
            elements.blockTitle.value.trim()
        );

        formData.append(
            'content',
            getEditorContent()
        );

        formData.append(
            'external_url',
            elements.blockUrl.value.trim()
        );

        formData.append(
            'caption',
            elements.blockCaption.value.trim()
        );

        formData.append(
            'alt_text',
            elements.blockAlt.value.trim()
        );

        const file =
            elements.blockFile.files?.[0];

        if (file) {
            formData.append(
                'file',
                file
            );
        }

        let url =
            `${API_BASE_URL}/tutorial-nodes/${nodeId}/content-blocks`;

        if (state.editingBlock) {
            url =
                `${API_BASE_URL}/tutorial-content-blocks/${state.editingBlock.id}`;

            formData.append(
                '_method',
                'PUT'
            );
        }

        setSavingState(true);
        clearValidationErrors(elements.blockForm);

        try {
            const response = await fetch(
                url,
                {
                    method: 'POST',

                    headers: {
                        Accept:
                            'application/json',
                    },

                    body:
                        formData,
                }
            );

            const result =
                await parseResponse(
                    response
                );

            state.dirty =
                false;

            state.textEditor?.setDirty(
                false
            );

            closeBlockModal();

            await loadContent();

            showToast(
                result.message ||
                'Blok berhasil disimpan.',
                'success'
            );
        } catch (error) {
            showToast(
                error.message,
                'error'
            );
            
            if (error.validationErrors) {
                displayValidationErrors(error.validationErrors, elements.blockForm, 'block-');
            }
        } finally {
            setSavingState(false);
        }
    }

    function validateBlockForm() {
        const type =
            elements.blockType.value;

        const file =
            elements.blockFile.files?.[0];

        if (
            type === 'text' &&
            !getEditorPlainText()
        ) {
            return 'Isi teks wajib diisi.';
        }

        if (
            type === 'youtube' &&
            !elements.blockTitle.value.trim()
        ) {
            return 'Judul video YouTube wajib diisi.';
        }

        if (
            type === 'youtube' &&
            !youtubeId(
                elements.blockUrl.value
            )
        ) {
            return 'Masukkan tautan YouTube yang valid.';
        }

        if (
            [
                'image',
                'pdf',
            ].includes(type) &&
            !state.editingBlock &&
            !file
        ) {
            return 'Pilih file terlebih dahulu.';
        }

        if (
            file &&
            file.size >
                20 * 1024 * 1024
        ) {
            return 'Ukuran file maksimal 20 MB.';
        }

        if (
            file &&
            type === 'image' &&
            ![
                'image/jpeg',
                'image/png',
                'image/webp',
            ].includes(file.type)
        ) {
            return 'Gambar harus berupa JPG, PNG, atau WEBP.';
        }

        if (
            file &&
            type === 'pdf' &&
            file.type !==
                'application/pdf'
        ) {
            return 'Dokumen harus berupa PDF.';
        }

        return null;
    }

    async function deleteBlock(id) {
        if (
            state.reordering ||
            state.deletingBlockId
        ) {
            return;
        }

        const block =
            state.blocks.find((item) => {
                return (
                    Number(item.id) ===
                    Number(id)
                );
            });

        if (!block) {
            showToast(
                'Blok tidak ditemukan.',
                'error'
            );

            return;
        }

        const confirmed =
            window.confirm(
                [
                    `Hapus blok ${blockTypeLabel(
                        block.block_type
                    )} ini?`,
                    '',
                    'Blok akan dihapus dari materi dan urutan blok lain akan dirapikan.',
                    '',
                    'Tindakan ini tidak dapat dibatalkan.',
                ].join('\n')
            );

        if (!confirmed) {
            return;
        }

        state.deletingBlockId =
            Number(id);

        renderBlocks();

        try {
            const response = await fetch(
                `${API_BASE_URL}/tutorial-content-blocks/${id}`,
                {
                    method: 'DELETE',

                    headers: {
                        Accept:
                            'application/json',
                    },
                }
            );

            const result =
                await parseResponse(
                    response
                );

            await loadContent();

            showToast(
                result.message ||
                'Blok berhasil dihapus.',
                'success'
            );
        } catch (error) {
            showToast(
                error.message,
                'error'
            );
        } finally {
            state.deletingBlockId =
                null;

            if (
                state.blocks.length > 0
            ) {
                renderBlocks();
            }
        }
    }

    async function moveBlock(
        id,
        direction
    ) {
        if (
            state.reordering ||
            state.loadingContent ||
            state.deletingBlockId
        ) {
            return;
        }

        const currentIndex =
            state.blocks.findIndex(
                (block) => {
                    return (
                        Number(block.id) ===
                        Number(id)
                    );
                }
            );

        const targetIndex =
            currentIndex + direction;

        if (
            currentIndex < 0 ||
            targetIndex < 0 ||
            targetIndex >=
                state.blocks.length
        ) {
            return;
        }

        const previousBlocks =
            [...state.blocks];

        const reorderedBlocks =
            [...state.blocks];

        [
            reorderedBlocks[currentIndex],
            reorderedBlocks[targetIndex],
        ] = [
            reorderedBlocks[targetIndex],
            reorderedBlocks[currentIndex],
        ];

        state.blocks =
            reorderedBlocks;

        state.reordering =
            true;

        setReorderingState(true);
        renderBlocks();

        try {
            const response = await fetch(
                `${API_BASE_URL}/tutorial-nodes/${nodeId}/content-blocks/reorder`,
                {
                    method: 'PUT',

                    headers: {
                        Accept:
                            'application/json',

                        'Content-Type':
                            'application/json',
                    },

                    body:
                        JSON.stringify({
                            blocks:
                                reorderedBlocks.map(
                                    (
                                        block,
                                        index
                                    ) => {
                                        return {
                                            id:
                                                Number(
                                                    block.id
                                                ),

                                            sort_order:
                                                index,
                                        };
                                    }
                                ),
                        }),
                }
            );

            const result =
                await parseResponse(
                    response
                );

            state.blocks =
                reorderedBlocks.map(
                    (
                        block,
                        index
                    ) => {
                        return {
                            ...block,
                            sort_order:
                                index,
                        };
                    }
                );

            renderBlocks();

            showToast(
                result.message ||
                'Urutan blok berhasil disimpan.',
                'success'
            );
        } catch (error) {
            state.blocks =
                previousBlocks;

            renderBlocks();

            showToast(
                `Urutan gagal disimpan: ${error.message}`,
                'error'
            );
        } finally {
            state.reordering =
                false;

            setReorderingState(false);
            updateReorderButtonAvailability();
        }
    }

    function setReorderingState(
        isReordering
    ) {
        elements.reorderStatus.classList.toggle(
            'hidden',
            !isReordering
        );

        elements.reorderStatus.classList.toggle(
            'inline-flex',
            isReordering
        );

        elements.addBlockButtons.forEach(
            (button) => {
                button.disabled =
                    isReordering;
            }
        );

        elements.refreshButton.disabled =
            isReordering;
    }

    function updateReorderButtonAvailability() {
        document
            .querySelectorAll(
                '.move-block-up, .move-block-down'
            )
            .forEach((button) => {
                const boundaryDisabled =
                    button.dataset
                        .boundaryDisabled ===
                    'true';

                button.disabled =
                    state.reordering ||
                    boundaryDisabled;
            });
    }

    function previewSelectedFile() {
        revokeObjectUrl();

        const file =
            elements.blockFile.files?.[0];

        if (
            !file ||
            elements.blockType.value !==
                'image'
        ) {
            if (
                !state.editingBlock
                    ?.file_path
            ) {
                elements.imagePreview.classList.add(
                    'hidden'
                );

                elements.imagePreviewElement.src =
                    '';
            }

            return;
        }

        state.objectUrl =
            URL.createObjectURL(file);

        elements.imagePreviewElement.src =
            state.objectUrl;

        elements.imagePreview.classList.remove(
            'hidden'
        );
    }

    function previewYoutubeInForm() {
        const title =
            elements.blockTitle.value.trim();

        const videoId =
            youtubeId(
                elements.blockUrl.value
            );

        if (!title) {
            showToast(
                'Isi judul video terlebih dahulu.',
                'error'
            );

            return;
        }

        if (!videoId) {
            showToast(
                'Tautan YouTube tidak valid.',
                'error'
            );

            return;
        }

        elements.youtubeIframe.src =
            `https://www.youtube.com/embed/${videoId}`;

        elements.youtubeIframe.title =
            title;

        elements.youtubePreview.classList.remove(
            'hidden'
        );
    }

    function requestCloseBlockModal() {
        if (
            state.dirty &&
            !window.confirm(
                'Perubahan belum disimpan. Tutup form dan buang perubahan?'
            )
        ) {
            return;
        }

        closeBlockModal();
    }

    function openBlockModal() {
        elements.blockModal.classList.remove(
            'hidden'
        );

        elements.blockModal.classList.add(
            'flex'
        );

        elements.blockModal.setAttribute(
            'aria-hidden',
            'false'
        );

        document.body.classList.add(
            'overflow-hidden'
        );

        window.setTimeout(() => {
            if (
                elements.blockType.value ===
                'text'
            ) {
                state.textEditor?.focus();

                return;
            }

            if (
                elements.blockType.value ===
                'youtube'
            ) {
                elements.blockTitle.focus();

                return;
            }

            elements.blockFile.focus();
        }, 100);
    }

    function closeBlockModal() {
        state.dirty =
            false;

        elements.blockModal.classList.add(
            'hidden'
        );

        elements.blockModal.classList.remove(
            'flex'
        );

        elements.blockModal.setAttribute(
            'aria-hidden',
            'true'
        );

        document.body.classList.remove(
            'overflow-hidden'
        );

        resetBlockForm();
    }

    function resetBlockForm() {
        elements.blockForm.reset();

        elements.blockId.value =
            '';

        elements.blockType.value =
            '';

        elements.blockTitle.value =
            '';

        elements.blockContent.value =
            '';

        elements.blockUrl.value =
            '';

        elements.blockCaption.value =
            '';

        elements.blockAlt.value =
            '';

        elements.textFields.classList.add(
            'hidden'
        );

        elements.youtubeFields.classList.add(
            'hidden'
        );

        elements.fileFields.classList.add(
            'hidden'
        );

        elements.captionFields.classList.add(
            'hidden'
        );

        elements.altWrapper.classList.remove(
            'hidden'
        );

        elements.existingFile.classList.add(
            'hidden'
        );

        elements.existingFile.textContent =
            '';

        elements.imagePreview.classList.add(
            'hidden'
        );

        elements.imagePreviewElement.src =
            '';

        elements.youtubePreview.classList.add(
            'hidden'
        );

        elements.youtubeIframe.src =
            '';

        elements.youtubeIframe.title =
            'Preview YouTube';

        setEditorContent('');

        state.editingBlock =
            null;

        state.dirty =
            false;

        state.textEditor?.setDirty(
            false
        );

        revokeObjectUrl();

        setSavingState(false);
    }

    function setEditorContent(content) {
        if (state.textEditor) {
            state.textEditor.setContent(
                content || ''
            );

            state.textEditor.save();

            return;
        }

        elements.blockContent.value =
            content || '';
    }

    function getEditorContent() {
        if (state.textEditor) {
            return state.textEditor.getContent();
        }

        return elements.blockContent.value.trim();
    }

    function getEditorPlainText() {
        if (state.textEditor) {
            return state.textEditor
                .getContent({
                    format:
                        'text',
                })
                .trim();
        }

        return elements.blockContent.value.trim();
    }

    function syncTinyMceToTextarea() {
        state.textEditor?.save();
    }

    function showPageState(name) {
        elements.loadingState.classList.toggle(
            'hidden',
            name !== 'loading'
        );

        elements.errorState.classList.toggle(
            'hidden',
            name !== 'error'
        );

        elements.emptyState.classList.toggle(
            'hidden',
            name !== 'empty'
        );

        elements.blocksContainer.classList.toggle(
            'hidden',
            name !== 'blocks'
        );
    }

    function setPageLoading(isLoading) {
        elements.refreshButton.disabled =
            isLoading ||
            state.reordering;

        elements.addBlockButtons.forEach(
            (button) => {
                button.disabled =
                    isLoading ||
                    state.reordering;
            }
        );

        elements.refreshButton.innerHTML =
            isLoading
                ? `
                    <i class="bi bi-arrow-repeat animate-spin"></i>
                    Memuat...
                `
                : `
                    <i class="bi bi-arrow-clockwise"></i>
                    Muat Ulang
                `;
    }

    function setSavingState(isSaving) {
        elements.cancelButton.disabled =
            isSaving;

        elements.modalClose.disabled =
            isSaving;

        if (!isSaving) {
            elements.submitButton.dataset.originalHtml =
                state.editingBlock
                    ? `
                        <i class="bi bi-pencil-square"></i>
                        <span>Perbarui Blok</span>
                    `
                    : `
                        <i class="bi bi-floppy"></i>
                        <span>Simpan Blok</span>
                    `;
        }

        setButtonLoading(elements.submitButton, isSaving);
    }

    async function parseResponse(response) {
        const result =
            await response
                .json()
                .catch(() => ({}));

        if (response.ok) {
            return result;
        }

        if (result.errors) {
            const error = new Error(
                result.message || 'Terdapat kesalahan pada data yang dimasukkan.'
            );
            error.validationErrors = result.errors;
            throw error;
        }

        throw new Error(
            result.message ||
            `Permintaan gagal (${response.status}).`
        );
    }



    function youtubeId(value) {
        const input =
            String(
                value || ''
            ).trim();

        if (!input) {
            return null;
        }

        try {
            const url =
                new URL(input);

            const hostname =
                url.hostname
                    .replace(
                        /^www\./i,
                        ''
                    )
                    .toLowerCase();

            if (
                hostname ===
                'youtu.be'
            ) {
                return (
                    url.pathname
                        .split('/')
                        .filter(Boolean)[0] ??
                    null
                );
            }

            if (
                hostname ===
                    'youtube.com' ||
                hostname ===
                    'm.youtube.com'
            ) {
                if (
                    url.pathname ===
                    '/watch'
                ) {
                    return url.searchParams.get(
                        'v'
                    );
                }

                const segments =
                    url.pathname
                        .split('/')
                        .filter(Boolean);

                if (
                    [
                        'embed',
                        'shorts',
                        'live',
                    ].includes(
                        segments[0]
                    )
                ) {
                    return (
                        segments[1] ??
                        null
                    );
                }
            }
        } catch {
            return null;
        }

        return null;
    }

    function storageUrl(path) {
        if (!path) {
            return null;
        }

        if (
            /^https?:\/\//i.test(path)
        ) {
            return path;
        }

        return `/storage/${String(path).replace(
            /^\/+/,
            ''
        )}`;
    }

    function formatFileSize(bytes) {
        const size =
            Number(bytes || 0);

        if (
            !Number.isFinite(size) ||
            size <= 0
        ) {
            return 'Ukuran tidak diketahui';
        }

        if (size < 1024) {
            return `${size} B`;
        }

        if (
            size <
            1024 * 1024
        ) {
            return `${(
                size / 1024
            ).toFixed(1)} KB`;
        }

        return `${(
            size /
            (1024 * 1024)
        ).toFixed(1)} MB`;
    }

    function htmlToPlainText(html) {
        const container =
            document.createElement('div');

        container.innerHTML =
            DOMPurify.sanitize(
                String(html || '')
            );

        return String(
            container.textContent ||
            container.innerText ||
            ''
        )
            .replace(/\s+/g, ' ')
            .trim();
    }

    function blockTypeLabel(type) {
        return {
            text:
                'Teks',

            image:
                'Gambar',

            youtube:
                'YouTube',

            pdf:
                'PDF',
        }[type] || type;
    }

    function nodeTypeLabel(type) {
        return {
            kategori:
                'Kategori',

            bagian:
                'Bagian',

            materi:
                'Materi',
        }[type] ||
            type ||
            'Node';
    }

    function blockTypeClass(type) {
        return {
            text:
                'border-blue-200 bg-blue-50 text-blue-900',

            image:
                'border-emerald-200 bg-emerald-50 text-emerald-700',

            youtube:
                'border-red-200 bg-red-50 text-red-700',

            pdf:
                'border-amber-200 bg-amber-50 text-amber-700',
        }[type] ||
            'border-slate-200 bg-slate-100 text-slate-600';
    }

    function revokeObjectUrl() {
        if (!state.objectUrl) {
            return;
        }

        URL.revokeObjectURL(
            state.objectUrl
        );

        state.objectUrl =
            null;
    }

    function escapeHtml(value) {
        return String(value ?? '')
            .replaceAll(
                '&',
                '&amp;'
            )
            .replaceAll(
                '<',
                '&lt;'
            )
            .replaceAll(
                '>',
                '&gt;'
            )
            .replaceAll(
                '"',
                '&quot;'
            )
            .replaceAll(
                "'",
                '&#039;'
            );
    }
});