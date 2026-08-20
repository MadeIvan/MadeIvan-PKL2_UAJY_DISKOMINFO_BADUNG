export function showToast(message, type = 'success') {
    let container = document.getElementById('toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'fixed bottom-4 right-4 z-[100] flex flex-col gap-3 pointer-events-none';
        document.body.appendChild(container);
    }

    const toast = document.createElement('div');
    const isError = type === 'error';
    
    toast.className = `pointer-events-auto flex items-start gap-3 w-80 max-w-full p-4 border shadow-lg transition-all duration-300 translate-y-4 opacity-0 ${
        isError ? 'border-red-200 bg-red-50 text-red-900' : 'border-green-200 bg-green-50 text-green-900'
    }`;
    
    const icon = isError ? 'bi-exclamation-circle text-red-500' : 'bi-check-circle text-green-500';

    toast.innerHTML = `
        <i class="bi ${icon} text-lg mt-0.5 shrink-0"></i>
        <div class="flex-1 text-sm font-medium">
            ${message}
        </div>
        <button type="button" class="shrink-0 text-slate-400 hover:text-slate-600 transition" aria-label="Tutup">
            <i class="bi bi-x-lg"></i>
        </button>
    `;

    container.appendChild(toast);

    requestAnimationFrame(() => {
        toast.classList.remove('translate-y-4', 'opacity-0');
    });

    const closeButton = toast.querySelector('button');
    let timeoutId;

    const closeToast = () => {
        toast.classList.add('opacity-0', 'scale-95');
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 300);
    };

    closeButton.addEventListener('click', () => {
        clearTimeout(timeoutId);
        closeToast();
    });

    timeoutId = setTimeout(closeToast, 5000);
}

export function displayValidationErrors(errors, formElement, idPrefix = '') {
    clearValidationErrors(formElement);

    Object.entries(errors).forEach(([field, messages]) => {
        // field could be "name" -> categoryName or "category_id" -> applicationCategoryId
        const camelCaseField = field.replace(/_([a-z])/g, g => g[1].toUpperCase());
        const dashField = field.replace(/_/g, '-');
        
        const possibleIds = idPrefix 
            ? [
                `#${idPrefix}${dashField}`,
                `#${idPrefix}${camelCaseField}`,
                `#${idPrefix.charAt(0).toLowerCase() + idPrefix.slice(1).replace(/-$/, '')}${field.charAt(0).toUpperCase() + camelCaseField.slice(1)}` // applicationCategory -> applicationCategoryName
              ]
            : [
                `#${dashField}`, 
                `#${camelCaseField}`
              ];

        const selector = [...possibleIds, `[name="${field}"]`].join(', ');
            
        const input = formElement.querySelector(selector);
        
        if (input) {
            input.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-500/20');
            
            const errorText = document.createElement('p');
            errorText.className = 'mt-1 text-xs text-red-500 validation-error-message font-medium';
            errorText.textContent = Array.isArray(messages) ? messages[0] : messages;
            
            if (input.parentNode.classList.contains('relative')) {
                input.parentNode.parentNode.appendChild(errorText);
            } else {
                input.parentNode.appendChild(errorText);
            }
        }
    });
}

export function clearValidationErrors(formElement) {
    if (!formElement) return;
    
    const errorMessages = formElement.querySelectorAll('.validation-error-message');
    errorMessages.forEach(el => el.remove());
    
    const invalidInputs = formElement.querySelectorAll('.border-red-500');
    invalidInputs.forEach(input => {
        input.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500/20');
    });
}

export function setButtonLoading(button, isLoading, loadingText = 'Menyimpan...') {
    if (!button) return;

    if (isLoading) {
        button.disabled = true;
        button.dataset.originalHtml = button.innerHTML;
        button.classList.add('opacity-75', 'cursor-not-allowed');
        button.innerHTML = `
            <div class="flex items-center justify-center gap-2">
                <i class="bi bi-arrow-repeat animate-spin"></i>
                <span>${loadingText}</span>
            </div>
        `;
    } else {
        button.disabled = false;
        button.classList.remove('opacity-75', 'cursor-not-allowed');
        if (button.dataset.originalHtml) {
            button.innerHTML = button.dataset.originalHtml;
        }
    }
}

export function showDoubleConfirmModal(title, itemName) {
    let modal = document.getElementById('double-confirm-modal');
    if (!modal) {
        modal = document.createElement('div');
        modal.id = 'double-confirm-modal';
        modal.className = 'fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/50 hidden';
        modal.innerHTML = `
            <div class="w-full max-w-md rounded-md bg-white shadow-lg">
                <div class="flex items-center justify-between border-b px-5 py-4">
                    <h3 class="text-base font-semibold text-slate-800" id="double-confirm-title">Konfirmasi Hapus</h3>
                    <button type="button" class="text-slate-400 hover:text-slate-600 focus:outline-none" id="double-confirm-close">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <div class="p-5">
                    <p class="mb-4 text-sm text-slate-600">
                        Tindakan ini tidak dapat dibatalkan. Untuk melanjutkan, silakan ketik <strong class="text-slate-800 select-all" id="double-confirm-item-name"></strong> di bawah ini:
                    </p>
                    <input type="text" id="double-confirm-input" class="w-full rounded-sm border border-slate-300 px-3 py-2 text-sm focus:border-red-500 focus:outline-none focus:ring-1 focus:ring-red-500" placeholder="Ketik nama di sini..." autocomplete="off">
                </div>
                <div class="flex items-center justify-end gap-3 border-t bg-slate-50 px-5 py-4">
                    <button type="button" id="double-confirm-cancel" class="rounded-sm border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-200">Batal</button>
                    <button type="button" id="double-confirm-submit" class="rounded-sm bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 disabled:opacity-50 disabled:cursor-not-allowed" disabled>Hapus</button>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
    }

    document.getElementById('double-confirm-title').textContent = title;
    document.getElementById('double-confirm-item-name').textContent = itemName;
    const input = document.getElementById('double-confirm-input');
    const submitBtn = document.getElementById('double-confirm-submit');
    const closeBtn = document.getElementById('double-confirm-close');
    const cancelBtn = document.getElementById('double-confirm-cancel');

    input.value = '';
    submitBtn.disabled = true;
    modal.classList.remove('hidden');
    input.focus();

    return new Promise((resolve) => {
        const checkInput = () => {
            submitBtn.disabled = input.value !== itemName;
        };
        
        const enterHandler = (e) => {
            if (e.key === 'Enter' && !submitBtn.disabled) {
                e.preventDefault();
                confirmHandler();
            }
        };

        const cleanup = () => {
            modal.classList.add('hidden');
            submitBtn.removeEventListener('click', confirmHandler);
            closeBtn.removeEventListener('click', cancelHandler);
            cancelBtn.removeEventListener('click', cancelHandler);
            input.removeEventListener('input', checkInput);
            input.removeEventListener('keydown', enterHandler);
        };

        const confirmHandler = () => {
            cleanup();
            resolve(true);
        };

        const cancelHandler = () => {
            cleanup();
            resolve(false);
        };

        input.addEventListener('input', checkInput);
        input.addEventListener('keydown', enterHandler);
        submitBtn.addEventListener('click', confirmHandler);
        closeBtn.addEventListener('click', cancelHandler);
        cancelBtn.addEventListener('click', cancelHandler);
    });
}

export function showConfirmModal(title, message, options = {}) {
    const config = {
        actionText: 'Konfirmasi',
        actionTheme: 'primary', // 'primary' (blue) or 'danger' (red)
        ...options
    };

    let modal = document.getElementById('simple-confirm-modal');
    if (!modal) {
        modal = document.createElement('div');
        modal.id = 'simple-confirm-modal';
        modal.className = 'fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/50 hidden';
        modal.innerHTML = `
            <div class="w-full max-w-md rounded-md bg-white shadow-lg">
                <div class="flex items-center justify-between border-b px-5 py-4">
                    <h3 class="text-base font-semibold text-slate-800" id="simple-confirm-title">Konfirmasi</h3>
                    <button type="button" class="text-slate-400 hover:text-slate-600 focus:outline-none" id="simple-confirm-close">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <div class="p-5">
                    <p class="text-sm text-slate-600" id="simple-confirm-message"></p>
                </div>
                <div class="flex items-center justify-end gap-3 border-t bg-slate-50 px-5 py-4">
                    <button type="button" id="simple-confirm-cancel" class="rounded-sm border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-200">Batal</button>
                    <button type="button" id="simple-confirm-submit" class="rounded-sm px-4 py-2 text-sm font-medium text-white focus:outline-none focus:ring-2"></button>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
    }

    document.getElementById('simple-confirm-title').textContent = title;
    document.getElementById('simple-confirm-message').textContent = message;
    
    const submitBtn = document.getElementById('simple-confirm-submit');
    const closeBtn = document.getElementById('simple-confirm-close');
    const cancelBtn = document.getElementById('simple-confirm-cancel');

    submitBtn.textContent = config.actionText;
    
    // Apply theme
    if (config.actionTheme === 'danger') {
        submitBtn.className = 'rounded-sm px-4 py-2 text-sm font-medium text-white focus:outline-none focus:ring-2 bg-red-600 hover:bg-red-700 focus:ring-red-500';
    } else {
        submitBtn.className = 'rounded-sm px-4 py-2 text-sm font-medium text-white focus:outline-none focus:ring-2 bg-blue-900 hover:bg-blue-800 focus:ring-blue-900';
    }

    modal.classList.remove('hidden');

    return new Promise((resolve) => {
        const confirmHandler = () => {
            cleanup();
            modal.classList.add('hidden');
            resolve(true);
        };

        const cancelHandler = () => {
            cleanup();
            modal.classList.add('hidden');
            resolve(false);
        };

        const cleanup = () => {
            submitBtn.removeEventListener('click', confirmHandler);
            closeBtn.removeEventListener('click', cancelHandler);
            cancelBtn.removeEventListener('click', cancelHandler);
        };

        submitBtn.addEventListener('click', confirmHandler);
        closeBtn.addEventListener('click', cancelHandler);
        cancelBtn.addEventListener('click', cancelHandler);
    });
}
