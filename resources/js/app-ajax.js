import $ from 'jquery';

const LOGIN_URL = '/login';

function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
}

/**
 * Show toast - delegates to global showToast (from toast.js) when available.
 * Use: showToast({ type: 'error', message: '...' })
 */
function showToast(opts) {
    const { type = 'info', message = '' } = typeof opts === 'object' ? opts : { type: opts, message: '' };
    if (typeof window.showToast === 'function') {
        window.showToast({ type, message });
        return;
    }
    const container = document.getElementById('toast-container');
    if (!container) return;
    const variantClasses = {
        success: 'border-green-200 dark:border-green-800 bg-green-50 dark:bg-green-950/40 text-green-800 dark:text-green-200',
        error: 'border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-950/40 text-red-800 dark:text-red-200',
        warning: 'border-amber-200 dark:border-amber-800 bg-amber-50 dark:bg-amber-950/40 text-amber-800 dark:text-amber-200',
        info: 'border-blue-200 dark:border-blue-800 bg-blue-50 dark:bg-blue-950/40 text-blue-800 dark:text-blue-200',
    };
    const variant = variantClasses[type] || variantClasses.info;
    const toast = document.createElement('div');
    toast.setAttribute('role', 'alert');
    toast.className = `pointer-events-auto flex items-start gap-3 rounded-xl border px-4 py-3 shadow-md ${variant}`;
    toast.innerHTML = `
        <span class="flex-1 text-sm font-medium">${escapeHtml(String(message))}</span>
        <button type="button" data-toast-dismiss aria-label="Dismiss" class="shrink-0 rounded-lg p-1 opacity-70 hover:opacity-100">×</button>
    `;
    container.appendChild(toast);
    toast.querySelector('[data-toast-dismiss]')?.addEventListener('click', () => toast.remove());
    setTimeout(() => toast.remove(), 5000);
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

/**
 * Set loading state on a button (disable + optional spinner)
 */
function setLoading(button, loading) {
    if (!button) return;
    const $btn = $(button);
    if (loading) {
        $btn.prop('disabled', true);
        $btn.data('original-html', $btn.html());
        $btn.html('<span class="inline-block animate-spin mr-1">⟳</span> Loading...');
    } else {
        $btn.prop('disabled', false);
        if ($btn.data('original-html')) {
            $btn.html($btn.data('original-html'));
            $btn.removeData('original-html');
        }
    }
}

/**
 * Render field errors under form inputs. errors: { fieldName: ['msg1', 'msg2'] }
 */
function renderFieldErrors(form, errors) {
    if (!form || !errors || typeof errors !== 'object') return;
    clearFieldErrors(form);
    for (const [field, messages] of Object.entries(errors)) {
        const input = form.querySelector(`[name="${field}"]`);
        if (!input) continue;
        const msg = Array.isArray(messages) ? messages[0] : String(messages);
        const errEl = document.createElement('p');
        errEl.className = 'mt-1 text-sm text-red-600 dark:text-red-400';
        errEl.setAttribute('data-field-error', field);
        errEl.textContent = msg;
        input.closest('div')?.appendChild(errEl);
        input.classList.add('border-red-500');
    }
}

/**
 * Clear all field error messages and error styling
 */
function clearFieldErrors(form) {
    if (!form) return;
    form.querySelectorAll('[data-field-error]').forEach((el) => el.remove());
    form.querySelectorAll('.border-red-500').forEach((el) => el.classList.remove('border-red-500'));
}

/**
 * Handle HTTP error from jQuery AJAX. Returns { message, errors } for 422 so caller can render field errors.
 * For 401/419: shows toast and redirects to login.
 */
function handleHttpError(jqXHR) {
    const status = jqXHR.status;
    const data = jqXHR.responseJSON || {};
    const message = data.message || getDefaultErrorMessage(status);

    if (status === 401 || status === 419) {
        showToast({ type: 'error', message: 'Session expired. Redirecting to login.' });
        setTimeout(() => {
            window.location.href = LOGIN_URL;
        }, 1500);
        return { message, errors: data.errors };
    }

    if (status === 403) {
        showToast({ type: 'error', message });
        return { message, errors: data.errors };
    }

    if (status === 422) {
        showToast({ type: 'error', message });
        return { message, errors: data.errors || null };
    }

    if (status >= 500 || status === 0) {
        showToast({ type: 'error', message: 'Something went wrong. Please try again.' });
        return { message, errors: null };
    }

    showToast({ type: 'error', message });
    return { message, errors: data.errors };
}

function getDefaultErrorMessage(status) {
    if (status === 0) return 'Network error. Please check your connection and try again.';
    if (status === 404) return 'Not found.';
    if (status >= 500) return 'Server error. Please try again.';
    return 'Request failed.';
}

/**
 * Make an AJAX request. Options: { method, url, data, onSuccess, onError, onFinally }
 * Success callback receives (data, textStatus, jqXHR). Data is the parsed JSON (with our envelope: ok, message, data, meta).
 */
function ajaxRequest(options) {
    const { method = 'GET', url, data, onSuccess, onError, onFinally } = options;
    const isJson = method !== 'GET' && data !== undefined;

    $.ajax({
        method,
        url,
        data: isJson ? JSON.stringify(data) : data,
        contentType: isJson ? 'application/json' : undefined,
        dataType: 'json',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': getCsrfToken(),
        },
        xhrFields: { withCredentials: true },
    })
        .done((response, textStatus, jqXHR) => {
            if (response && response.ok === false) {
                const err = handleHttpError({
                    status: jqXHR.status,
                    responseJSON: response,
                });
                onError?.(err, jqXHR);
                return;
            }
            onSuccess?.(response, textStatus, jqXHR);
        })
        .fail((jqXHR) => {
            const err = handleHttpError(jqXHR);
            onError?.(err, jqXHR);
        })
        .always(() => {
            onFinally?.();
        });
}

// Export for global use (showToast is provided by toast.js)
window.getCsrfToken = getCsrfToken;
window.setLoading = setLoading;
window.renderFieldErrors = renderFieldErrors;
window.clearFieldErrors = clearFieldErrors;
window.handleHttpError = handleHttpError;
window.ajaxRequest = ajaxRequest;
