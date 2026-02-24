import $ from 'jquery';

document.addEventListener('DOMContentLoaded', () => {
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    function tabClick(tab) {
        const target = tab.getAttribute('data-settings-tab');
        document.querySelectorAll('.settings-tab').forEach((t) => {
            t.classList.remove('border-indigo-500', 'text-indigo-600', 'dark:text-indigo-400');
            t.classList.add('border-transparent', 'text-zinc-600', 'dark:text-zinc-400');
        });
        tab.classList.remove('border-transparent', 'text-zinc-600', 'dark:text-zinc-400');
        tab.classList.add('border-indigo-500', 'text-indigo-600', 'dark:text-indigo-400');
        document.querySelectorAll('[data-settings-panel]').forEach((p) => p.classList.add('hidden'));
        document.querySelector(`[data-settings-panel="${target}"]`)?.classList.remove('hidden');
    }

    document.querySelectorAll('[data-settings-tab]').forEach((tab) => {
        tab.addEventListener('click', () => tabClick(tab));
    });

    const handleHash = () => {
        const hash = window.location.hash.replace('#', '');
        if (hash && document.querySelector(`[data-settings-tab="${hash}"]`)) {
            tabClick(document.querySelector(`[data-settings-tab="${hash}"]`));
        }
    };
    window.addEventListener('hashchange', handleHash);
    handleHash();

    function ajaxSubmit(form, url, data, btnId) {
        const btn = document.getElementById(btnId);
        if (typeof setLoading === 'function') setLoading(btn, true);
        if (typeof clearFieldErrors === 'function') clearFieldErrors(form);

        $.ajax({
            method: 'POST',
            url,
            data,
            processData: !(data instanceof FormData),
            contentType: data instanceof FormData ? false : 'application/json',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrf,
            },
            xhrFields: { withCredentials: true },
        })
            .done((res) => {
                if (res?.ok !== false && typeof showToast === 'function') {
                    showToast({ type: 'success', message: res?.message || 'Saved.' });
                }
                if (form.id !== 'form-password' && form.id !== 'form-logout-sessions') form.reset?.();
            })
            .fail((jqXHR) => {
                const data = jqXHR.responseJSON || {};
                if (typeof renderFieldErrors === 'function' && data.errors) {
                    renderFieldErrors(form, data.errors);
                }
                if (typeof showToast === 'function') {
                    showToast({ type: 'error', message: data.message || 'Something went wrong.' });
                }
            })
            .always(() => {
                if (typeof setLoading === 'function') setLoading(btn, false);
            });
    }

    document.getElementById('form-profile')?.addEventListener('submit', (e) => {
        e.preventDefault();
        const form = e.target;
        const fd = new FormData(form);
        fd.append('_method', 'POST');
        ajaxSubmit(form, form.action, fd, 'btn-profile');
    });

    document.getElementById('form-email')?.addEventListener('submit', (e) => {
        e.preventDefault();
        const form = e.target;
        const data = { email: form.querySelector('[name="email"]')?.value, _token: csrf };
        ajaxSubmit(form, form.action, JSON.stringify(data), 'btn-email');
    });

    document.getElementById('form-password')?.addEventListener('submit', (e) => {
        e.preventDefault();
        const form = e.target;
        const data = {
            current_password: form.current_password.value,
            password: form.password.value,
            password_confirmation: form.password_confirmation.value,
            _token: csrf,
        };
        ajaxSubmit(form, form.action, JSON.stringify(data), 'btn-password');
    });

    document.getElementById('form-preferences')?.addEventListener('submit', (e) => {
        e.preventDefault();
        const form = e.target;
        const data = {
            timezone: form.timezone?.value,
            language: form.language?.value,
            theme: form.theme?.value,
            _token: csrf,
        };
        ajaxSubmit(form, form.action, JSON.stringify(data), 'btn-preferences');
    });

    document.getElementById('form-notifications')?.addEventListener('submit', (e) => {
        e.preventDefault();
        const form = e.target;
        const data = {
            notify_posts: form.querySelector('[name="notify_posts"]')?.checked ? 1 : 0,
            notify_weekly: form.querySelector('[name="notify_weekly"]')?.checked ? 1 : 0,
            _token: csrf,
        };
        ajaxSubmit(form, form.action, JSON.stringify(data), 'btn-notifications');
    });

    document.getElementById('form-logout-sessions')?.addEventListener('submit', (e) => {
        e.preventDefault();
        const form = e.target;
        const data = { password: form.password.value, _token: csrf };
        ajaxSubmit(form, form.action, JSON.stringify(data), 'btn-logout-sessions');
    });
});
