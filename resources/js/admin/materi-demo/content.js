import 'bootstrap-icons/font/bootstrap-icons.css';

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
    const API = '/api/admin';
    const page = document.getElementById('content-page');

    if (!page) {
        return;
    }

    const nodeId = Number(page.dataset.nodeId);

    const state = {
        node: null,
        blocks: [],
        editing: null,
        dirty: false,
        objectUrl: null,
        textEditor: null,
    };

    let notificationTimer = null;
    let reordering = false;

    const el = {
        notification: document.getElementById(
            'notification'
        ),

        nodeTitle: document.getElementById(
            'node-title'
        ),

        nodeMeta: document.getElementById(
            'node-meta'
        ),

        refresh: document.getElementById(
            'refresh-button'
        ),

        count: document.getElementById(
            'block-count'
        ),

        loading: document.getElementById(
            'loading-state'
        ),

        error: document.getElementById(
            'error-state'
        ),

        errorMessage: document.getElementById(
            'error-message'
        ),

        retry: document.getElementById(
            'retry-button'
        ),

        empty: document.getElementById(
            'empty-state'
        ),

        container: document.getElementById(
            'blocks-container'
        ),

        addButtons: document.querySelectorAll(
            '.add-block-button'
        ),

        modal: document.getElementById(
            'block-modal'
        ),

        form: document.getElementById(
            'block-form'
        ),

        formTitle: document.getElementById(
            'form-title'
        ),

        formDescription: document.getElementById(
            'form-description'
        ),

        close: document.getElementById(
            'modal-close'
        ),

        cancel: document.getElementById(
            'cancel-button'
        ),

        submit: document.getElementById(
            'submit-button'
        ),

        id: document.getElementById(
            'block-id'
        ),

        type: document.getElementById(
            'block-type'
        ),

        title: document.getElementById(
            'block-title'
        ),

        content: document.getElementById(
            'block-content'
        ),

        url: document.getElementById(
            'block-url'
        ),

        file: document.getElementById(
            'block-file'
        ),

        fileHelp: document.getElementById(
            'file-help'
        ),

        caption: document.getElementById(
            'block-caption'
        ),

        alt: document.getElementById(
            'block-alt'
        ),

        textFields: document.getElementById(
            'text-fields'
        ),

        youtubeFields: document.getElementById(
            'youtube-fields'
        ),

        fileFields: document.getElementById(
            'file-fields'
        ),

        captionFields: document.getElementById(
            'caption-fields'
        ),

        altWrapper: document.getElementById(
            'alt-wrapper'
        ),

        existingFile: document.getElementById(
            'existing-file'
        ),

        imagePreview: document.getElementById(
            'image-preview'
        ),

        imagePreviewElement: document.getElementById(
            'image-preview-element'
        ),

        youtubePreview: document.getElementById(
            'youtube-preview'
        ),

        youtubeIframe: document.getElementById(
            'youtube-iframe'
        ),

        youtubePreviewButton: document.getElementById(
            'youtube-preview-button'
        ),
    };

    const missingElements = Object.entries(el)
        .filter(([key, value]) => {
            return key !== 'addButtons' && !value;
        })
        .map(([key]) => key);

    if (missingElements.length > 0) {
        console.error(
            'Elemen Blade tidak ditemukan:',
            missingElements
        );

        return;
    }

    if (
        !Number.isInteger(nodeId) ||
        nodeId <= 0
    ) {
        console.error(
            'Tutorial node ID tidak valid.'
        );

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
                    }

                    pre {
                        background: #0f172a;
                        color: #f8fafc;
                        padding: 16px;
                        overflow-x: auto;
                    }

                    a {
                        color: #1d4ed8;
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

            state.textEditor =
                editors?.[0] ?? null;
        } catch (error) {
            console.error(
                'TinyMCE gagal dimuat:',
                error
            );

            notify(
                'Editor teks gagal dimuat. Textarea biasa tetap dapat digunakan.',
                'error'
            );
        }
    }

    function bindEvents() {
        el.refresh.addEventListener(
            'click',
            loadContent
        );

        el.retry.addEventListener(
            'click',
            loadContent
        );

        el.addButtons.forEach((button) => {
            button.addEventListener(
                'click',
                () => {
                    openCreate(
                        button.dataset.addType
                    );
                }
            );
        });

        el.container.addEventListener(
            'click',
            handleAction
        );

        el.form.addEventListener(
            'submit',
            saveBlock
        );

        el.form.addEventListener(
            'input',
            () => {
                state.dirty = true;
            }
        );

        el.form.addEventListener(
            'change',
            () => {
                state.dirty = true;
            }
        );

        el.close.addEventListener(
            'click',
            requestClose
        );

        el.cancel.addEventListener(
            'click',
            requestClose
        );

        el.file.addEventListener(
            'change',
            previewFile
        );

        el.youtubePreviewButton.addEventListener(
            'click',
            previewYoutube
        );

        el.modal.addEventListener(
            'click',
            (event) => {
                if (event.target === el.modal) {
                    requestClose();
                }
            }
        );

        document.addEventListener(
            'keydown',
            (event) => {
                if (
                    event.key === 'Escape' &&
                    !el.modal.classList.contains(
                        'hidden'
                    )
                ) {
                    requestClose();
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
        showState('loading');

        try {
            const response = await fetch(
                `${API}/tutorial-nodes/${nodeId}/content-blocks`,
                {
                    headers: {
                        Accept: 'application/json',
                    },
                }
            );

            const result =
                await parseResponse(response);

            state.node =
                result?.data?.tutorial_node ??
                null;

            state.blocks = Array.isArray(
                result?.data?.blocks
            )
                ? result.data.blocks
                : [];

            renderHeader();
            renderBlocks();
        } catch (error) {
            el.errorMessage.textContent =
                error.message;

            showState('error');
        }
    }

    function renderHeader() {
        el.nodeTitle.textContent =
            state.node?.title ??
            'Materi tidak ditemukan';

        const applicationName =
            state.node?.application?.name ??
            'Aplikasi tidak diketahui';

        const parentTitle =
            state.node?.parent?.title ??
            'Tanpa parent';

        el.nodeMeta.textContent =
            `${applicationName} • ${parentTitle} • ${nodeTypeLabel(
                state.node?.node_type
            )}`;
    }

    function renderBlocks() {
        el.count.textContent =
            `${state.blocks.length} blok`;

        if (!state.blocks.length) {
            el.container.innerHTML = '';

            showState('empty');

            return;
        }

        el.container.innerHTML =
            state.blocks
                .map((block, index) => {
                    return blockHtml(
                        block,
                        index
                    );
                })
                .join('');

        showState('blocks');
    }

    function blockHtml(block, index) {
        const isFirst = index === 0;

        const isLast =
            index ===
            state.blocks.length - 1;

        return `
            <article
                class="border border-slate-200 bg-white p-5"
                data-id="${block.id}"
            >
                <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="border px-2 py-1 text-xs font-semibold ${typeClass(
                                block.block_type
                            )}">
                                ${escapeHtml(
                                    typeLabel(
                                        block.block_type
                                    )
                                )}
                            </span>

                            <span class="text-xs text-slate-400">
                                Urutan ${index + 1}
                            </span>
                        </div>

                        <div class="mt-4">
                            ${previewHtml(block)}
                        </div>
                    </div>

                    <div class="flex shrink-0 flex-wrap gap-2">
                        <button
                            type="button"
                            class="move-up flex h-9 w-9 items-center justify-center border border-slate-300 hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-40"
                            data-id="${block.id}"
                            ${isFirst ? 'disabled' : ''}
                            aria-label="Pindahkan ke atas"
                        >
                            <i class="bi bi-arrow-up"></i>
                        </button>

                        <button
                            type="button"
                            class="move-down flex h-9 w-9 items-center justify-center border border-slate-300 hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-40"
                            data-id="${block.id}"
                            ${isLast ? 'disabled' : ''}
                            aria-label="Pindahkan ke bawah"
                        >
                            <i class="bi bi-arrow-down"></i>
                        </button>

                        <button
                            type="button"
                            class="edit-block flex h-9 w-9 items-center justify-center border border-blue-200 text-blue-900 hover:bg-blue-50"
                            data-id="${block.id}"
                            aria-label="Ubah blok"
                        >
                            <i class="bi bi-pencil-square"></i>
                        </button>

                        <button
                            type="button"
                            class="delete-block flex h-9 w-9 items-center justify-center border border-red-200 text-red-600 hover:bg-red-50"
                            data-id="${block.id}"
                            aria-label="Hapus blok"
                        >
                            <i class="bi bi-trash3"></i>
                        </button>
                    </div>
                </div>
            </article>
        `;
    }

    function previewHtml(block) {
        if (block.block_type === 'text') {
            const safeContent =
                DOMPurify.sanitize(
                    block.content ||
                    '<p>Teks kosong.</p>'
                );

            return `
                <div class="tutorial-rich-content text-sm leading-7 text-slate-700">
                    ${safeContent}
                </div>
            `;
        }

        if (block.block_type === 'image') {
            const url = storageUrl(
                block.file_path
            );

            return `
                <div class="space-y-3">
                    ${
                        url
                            ? `
                                <img
                                    src="${escapeHtml(url)}"
                                    alt="${escapeHtml(
                                        block.alt_text ||
                                        block.caption ||
                                        'Gambar materi'
                                    )}"
                                    class="max-h-[420px] w-full border border-slate-200 bg-slate-50 object-contain"
                                >
                            `
                            : `
                                <p class="text-sm text-red-600">
                                    File gambar tidak tersedia.
                                </p>
                            `
                    }

                    ${
                        block.caption
                            ? `
                                <p class="text-sm text-slate-500">
                                    ${escapeHtml(
                                        block.caption
                                    )}
                                </p>
                            `
                            : ''
                    }
                </div>
            `;
        }

        if (block.block_type === 'youtube') {
            const videoId = youtubeId(
                block.external_url
            );

            if (!videoId) {
                return `
                    <p class="text-sm text-red-600">
                        Tautan YouTube tidak valid.
                    </p>
                `;
            }

            return `
                <div class="space-y-3">
                    <h4 class="text-base font-bold text-slate-950">
                        ${escapeHtml(
                            block.title ||
                            'Video YouTube'
                        )}
                    </h4>

                    <div class="aspect-video max-w-3xl overflow-hidden bg-slate-950">
                        <iframe
                            class="h-full w-full"
                            src="https://www.youtube.com/embed/${escapeHtml(
                                videoId
                            )}"
                            title="${escapeHtml(
                                block.title ||
                                'Video YouTube'
                            )}"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen
                        ></iframe>
                    </div>
                </div>
            `;
        }

        if (block.block_type === 'pdf') {
            const url = storageUrl(
                block.file_path
            );

            return `
                <div class="flex flex-col gap-3 border border-slate-200 bg-slate-50 p-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="min-w-0">
                        <p class="truncate font-semibold text-slate-800">
                            ${escapeHtml(
                                block.original_file_name ||
                                'Dokumen PDF'
                            )}
                        </p>

                        <p class="mt-1 text-xs text-slate-500">
                            ${fileSize(
                                block.file_size
                            )}
                        </p>

                        ${
                            block.caption
                                ? `
                                    <p class="mt-2 text-sm text-slate-600">
                                        ${escapeHtml(
                                            block.caption
                                        )}
                                    </p>
                                `
                                : ''
                        }
                    </div>

                    ${
                        url
                            ? `
                                <a
                                    href="${escapeHtml(url)}"
                                    target="_blank"
                                    rel="noopener"
                                    class="inline-flex items-center justify-center gap-2 border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100"
                                >
                                    <i class="bi bi-box-arrow-up-right"></i>
                                    Buka PDF
                                </a>
                            `
                            : ''
                    }
                </div>
            `;
        }

        return `
            <p class="text-sm text-slate-500">
                Jenis blok tidak dikenali.
            </p>
        `;
    }

    function handleAction(event) {
        const editButton =
            event.target.closest(
                '.edit-block'
            );

        const deleteButton =
            event.target.closest(
                '.delete-block'
            );

        const moveUpButton =
            event.target.closest(
                '.move-up'
            );

        const moveDownButton =
            event.target.closest(
                '.move-down'
            );

        if (editButton) {
            openEdit(
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

    function openCreate(type) {
        resetForm();

        el.type.value = type;

        el.formTitle.textContent =
            `Tambah Blok ${typeLabel(type)}`;

        el.formDescription.textContent =
            'Isi informasi blok konten baru.';

        configureFields(type);

        if (type === 'text') {
            setEditorContent('');
        }

        openModal();
    }

    function openEdit(id) {
        const block = state.blocks.find(
            (item) =>
                Number(item.id) === id
        );

        if (!block) {
            notify(
                'Blok tidak ditemukan.',
                'error'
            );

            return;
        }

        resetForm();

        state.editing = block;

        el.id.value =
            String(block.id);

        el.type.value =
            block.block_type;

        el.title.value =
            block.title ?? '';

        el.content.value =
            block.content ?? '';

        el.url.value =
            block.external_url ?? '';

        el.caption.value =
            block.caption ?? '';

        el.alt.value =
            block.alt_text ?? '';

        if (block.block_type === 'text') {
            setEditorContent(
                block.content ?? ''
            );
        }

        if (block.file_path) {
            el.existingFile.textContent =
                `File saat ini: ${
                    block.original_file_name ||
                    block.file_path
                }`;

            el.existingFile.classList.remove(
                'hidden'
            );
        }

        el.formTitle.textContent =
            `Ubah Blok ${typeLabel(
                block.block_type
            )}`;

        el.formDescription.textContent =
            'Perbarui informasi blok konten.';

        configureFields(
            block.block_type
        );

        state.dirty = false;

        state.textEditor?.setDirty(false);

        openModal();
    }

    function configureFields(type) {
        el.textFields.classList.toggle(
            'hidden',
            type !== 'text'
        );

        el.youtubeFields.classList.toggle(
            'hidden',
            type !== 'youtube'
        );

        el.fileFields.classList.toggle(
            'hidden',
            !['image', 'pdf'].includes(type)
        );

        el.captionFields.classList.toggle(
            'hidden',
            !['image', 'pdf'].includes(type)
        );

        el.altWrapper.classList.toggle(
            'hidden',
            type !== 'image'
        );

        el.content.required =
            type === 'text';

        el.url.required =
            type === 'youtube';

        el.title.required =
            type === 'youtube';

        el.file.required =
            !state.editing &&
            ['image', 'pdf'].includes(type);

        if (type === 'youtube') {
            el.title.placeholder =
                'Contoh: Cara Menginstal Laravel';
        }

        if (type === 'image') {
            el.file.accept =
                'image/jpeg,image/png,image/webp';

            el.fileHelp.textContent =
                'JPG, PNG, atau WEBP. Maksimal 20 MB.';
        } else if (type === 'pdf') {
            el.file.accept =
                'application/pdf';

            el.fileHelp.textContent =
                'Hanya PDF. Maksimal 20 MB.';
        } else {
            el.file.accept = '';
            el.fileHelp.textContent = '';
        }
    }

    async function saveBlock(event) {
        event.preventDefault();

        syncTinyMceToTextarea();

        const validationError =
            validateForm();

        if (validationError) {
            notify(
                validationError,
                'error'
            );

            return;
        }

        const data = new FormData();

        data.append(
            'block_type',
            el.type.value
        );

        data.append(
            'title',
            el.title.value.trim()
        );

        data.append(
            'content',
            getEditorContent()
        );

        data.append(
            'external_url',
            el.url.value.trim()
        );

        data.append(
            'caption',
            el.caption.value.trim()
        );

        data.append(
            'alt_text',
            el.alt.value.trim()
        );

        const file =
            el.file.files?.[0];

        if (file) {
            data.append(
                'file',
                file
            );
        }

        let url =
            `${API}/tutorial-nodes/${nodeId}/content-blocks`;

        if (state.editing) {
            url =
                `${API}/tutorial-content-blocks/${state.editing.id}`;

            data.append(
                '_method',
                'PUT'
            );
        }

        setSaving(true);

        try {
            const response = await fetch(
                url,
                {
                    method: 'POST',

                    headers: {
                        Accept: 'application/json',
                    },

                    body: data,
                }
            );

            const result =
                await parseResponse(response);

            state.dirty = false;

            state.textEditor?.setDirty(false);

            closeModal();

            await loadContent();

            notify(
                result.message ||
                'Blok berhasil disimpan.',
                'success'
            );
        } catch (error) {
            notify(
                error.message,
                'error'
            );
        } finally {
            setSaving(false);
        }
    }

    function validateForm() {
        const type =
            el.type.value;

        const file =
            el.file.files?.[0];

        if (
            type === 'text' &&
            !getEditorPlainText()
        ) {
            return 'Isi teks wajib diisi.';
        }

        if (
            type === 'youtube' &&
            !el.title.value.trim()
        ) {
            return 'Judul video YouTube wajib diisi.';
        }

        if (
            type === 'youtube' &&
            !youtubeId(el.url.value)
        ) {
            return 'Masukkan tautan YouTube yang valid.';
        }

        if (
            ['image', 'pdf'].includes(type) &&
            !state.editing &&
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
            file.type !== 'application/pdf'
        ) {
            return 'Dokumen harus berupa PDF.';
        }

        return null;
    }

    async function deleteBlock(id) {
        const block = state.blocks.find(
            (item) =>
                Number(item.id) === id
        );

        if (!block) {
            return;
        }

        const confirmed =
            window.confirm(
                `Hapus blok ${typeLabel(
                    block.block_type
                )} ini?\n\nTindakan ini tidak dapat dibatalkan.`
            );

        if (!confirmed) {
            return;
        }

        try {
            const response = await fetch(
                `${API}/tutorial-content-blocks/${id}`,
                {
                    method: 'DELETE',

                    headers: {
                        Accept: 'application/json',
                    },
                }
            );

            const result =
                await parseResponse(response);

            await loadContent();

            notify(
                result.message ||
                'Blok berhasil dihapus.',
                'success'
            );
        } catch (error) {
            notify(
                error.message,
                'error'
            );
        }
    }

    async function moveBlock(
        id,
        direction
    ) {
        if (reordering) {
            return;
        }

        const currentIndex =
            state.blocks.findIndex(
                (block) =>
                    Number(block.id) === id
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

        const previousBlocks = [
            ...state.blocks,
        ];

        const nextBlocks = [
            ...state.blocks,
        ];

        [
            nextBlocks[currentIndex],
            nextBlocks[targetIndex],
        ] = [
            nextBlocks[targetIndex],
            nextBlocks[currentIndex],
        ];

        state.blocks = nextBlocks;

        renderBlocks();

        reordering = true;

        try {
            const response = await fetch(
                `${API}/tutorial-nodes/${nodeId}/content-blocks/reorder`,
                {
                    method: 'PUT',

                    headers: {
                        Accept:
                            'application/json',

                        'Content-Type':
                            'application/json',
                    },

                    body: JSON.stringify({
                        blocks:
                            nextBlocks.map(
                                (
                                    block,
                                    index
                                ) => ({
                                    id: Number(
                                        block.id
                                    ),

                                    sort_order:
                                        index + 1,
                                })
                            ),
                    }),
                }
            );

            const result =
                await parseResponse(response);

            notify(
                result.message ||
                'Urutan berhasil disimpan.',
                'success'
            );
        } catch (error) {
            state.blocks =
                previousBlocks;

            renderBlocks();

            notify(
                `Urutan gagal disimpan: ${error.message}`,
                'error'
            );
        } finally {
            reordering = false;
        }
    }

    function previewFile() {
        revokeObjectUrl();

        const file =
            el.file.files?.[0];

        if (
            !file ||
            el.type.value !== 'image'
        ) {
            el.imagePreview.classList.add(
                'hidden'
            );

            return;
        }

        state.objectUrl =
            URL.createObjectURL(file);

        el.imagePreviewElement.src =
            state.objectUrl;

        el.imagePreview.classList.remove(
            'hidden'
        );
    }

    function previewYoutube() {
        const videoId = youtubeId(
            el.url.value
        );

        if (!el.title.value.trim()) {
            notify(
                'Isi judul video terlebih dahulu.',
                'error'
            );

            return;
        }

        if (!videoId) {
            notify(
                'Tautan YouTube tidak valid.',
                'error'
            );

            return;
        }

        el.youtubeIframe.src =
            `https://www.youtube.com/embed/${videoId}`;

        el.youtubeIframe.title =
            el.title.value.trim();

        el.youtubePreview.classList.remove(
            'hidden'
        );
    }

    function requestClose() {
        if (
            state.dirty &&
            !window.confirm(
                'Perubahan belum disimpan. Tutup form dan buang perubahan?'
            )
        ) {
            return;
        }

        closeModal();
    }

    function openModal() {
        el.modal.classList.remove(
            'hidden'
        );

        el.modal.classList.add(
            'flex'
        );

        el.modal.setAttribute(
            'aria-hidden',
            'false'
        );

        document.body.classList.add(
            'overflow-hidden'
        );

        window.setTimeout(() => {
            if (
                el.type.value === 'text'
            ) {
                state.textEditor?.focus();
            }
        }, 100);
    }

    function closeModal() {
        state.dirty = false;

        el.modal.classList.add(
            'hidden'
        );

        el.modal.classList.remove(
            'flex'
        );

        el.modal.setAttribute(
            'aria-hidden',
            'true'
        );

        document.body.classList.remove(
            'overflow-hidden'
        );

        resetForm();
    }

    function resetForm() {
        el.form.reset();

        el.id.value = '';
        el.type.value = '';
        el.title.value = '';
        el.content.value = '';
        el.url.value = '';
        el.caption.value = '';
        el.alt.value = '';

        el.textFields.classList.add(
            'hidden'
        );

        el.youtubeFields.classList.add(
            'hidden'
        );

        el.fileFields.classList.add(
            'hidden'
        );

        el.captionFields.classList.add(
            'hidden'
        );

        el.existingFile.classList.add(
            'hidden'
        );

        el.existingFile.textContent = '';

        el.imagePreview.classList.add(
            'hidden'
        );

        el.youtubePreview.classList.add(
            'hidden'
        );

        el.imagePreviewElement.src = '';

        el.youtubeIframe.src = '';

        el.youtubeIframe.title =
            'Preview YouTube';

        setEditorContent('');

        state.editing = null;
        state.dirty = false;

        state.textEditor?.setDirty(false);

        revokeObjectUrl();
        setSaving(false);
    }

    function setEditorContent(content) {
        if (state.textEditor) {
            state.textEditor.setContent(
                content || ''
            );

            state.textEditor.save();

            return;
        }

        el.content.value =
            content || '';
    }

    function getEditorContent() {
        if (state.textEditor) {
            return state.textEditor.getContent();
        }

        return el.content.value.trim();
    }

    function getEditorPlainText() {
        if (state.textEditor) {
            return state.textEditor
                .getContent({
                    format: 'text',
                })
                .trim();
        }

        return el.content.value.trim();
    }

    function syncTinyMceToTextarea() {
        state.textEditor?.save();
    }

    function showState(name) {
        el.loading.classList.toggle(
            'hidden',
            name !== 'loading'
        );

        el.error.classList.toggle(
            'hidden',
            name !== 'error'
        );

        el.empty.classList.toggle(
            'hidden',
            name !== 'empty'
        );

        el.container.classList.toggle(
            'hidden',
            name !== 'blocks'
        );
    }

    function setSaving(value) {
        el.submit.disabled = value;

        el.submit.innerHTML =
            value
                ? `
                    <i class="bi bi-arrow-repeat animate-spin"></i>
                    <span>Menyimpan...</span>
                `
                : `
                    <i class="bi bi-floppy"></i>
                    <span>Simpan Blok</span>
                `;
    }

    async function parseResponse(
        response
    ) {
        const result = await response
            .json()
            .catch(() => ({}));

        if (response.ok) {
            return result;
        }

        if (result.errors) {
            throw new Error(
                Object.values(
                    result.errors
                )
                    .flat()
                    .join(' ')
            );
        }

        throw new Error(
            result.message ||
            `Permintaan gagal (${response.status}).`
        );
    }

    function notify(
        message,
        type = 'success'
    ) {
        const styles = {
            success:
                'border-emerald-200 bg-emerald-50 text-emerald-700',

            error:
                'border-red-200 bg-red-50 text-red-700',
        };

        window.clearTimeout(
            notificationTimer
        );

        el.notification.className =
            `border px-4 py-3 text-sm ${styles[type]}`;

        el.notification.textContent =
            message;

        el.notification.classList.remove(
            'hidden'
        );

        notificationTimer =
            window.setTimeout(() => {
                el.notification.classList.add(
                    'hidden'
                );
            }, 5000);
    }

    function youtubeId(value) {
        const input = String(
            value || ''
        ).trim();

        const patterns = [
            /youtube\.com\/watch\?v=([^&]+)/i,
            /youtu\.be\/([^?&/]+)/i,
            /youtube\.com\/embed\/([^?&/]+)/i,
            /youtube\.com\/shorts\/([^?&/]+)/i,
        ];

        for (const pattern of patterns) {
            const match =
                input.match(pattern);

            if (match?.[1]) {
                return match[1];
            }
        }

        return null;
    }

    function storageUrl(path) {
        if (!path) {
            return null;
        }

        if (/^https?:\/\//i.test(path)) {
            return path;
        }

        return `/storage/${String(path)
            .replace(/^\/+/, '')}`;
    }

    function fileSize(bytes) {
        const size = Number(
            bytes || 0
        );

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

    function typeLabel(type) {
        return {
            text: 'Teks',
            image: 'Gambar',
            youtube: 'YouTube',
            pdf: 'PDF',
        }[type] || type;
    }

    function nodeTypeLabel(type) {
        return {
            category: 'Kategori',
            section: 'Bagian',
            tutorial: 'Tutorial',
            step: 'Langkah',
        }[type] ||
            type ||
            'Node';
    }

    function typeClass(type) {
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

        state.objectUrl = null;
    }

    function escapeHtml(value) {
        return String(value ?? '')
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }
});