const API_BASE = '/api';

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

let selectedBusinessId = null;
let businesses = [];

function renderBusinessList() {
    const container = document.getElementById('business-list');
    if (!container) return;
    if (!businesses.length) {
        container.innerHTML = '<p class="text-sm text-zinc-500 dark:text-zinc-400">No businesses yet. Create one below.</p>';
        return;
    }
    container.innerHTML = businesses.map((b) => `
        <div class="flex items-center gap-2">
            <span class="text-sm">${escapeHtml(b.name)}</span>
            <button type="button" data-business-id="${b.id}" class="select-business rounded border border-[#19140035] dark:border-[#3E3E3A] px-2 py-1 text-xs hover:bg-gray-100 dark:hover:bg-[#3E3E3A]">Select</button>
            <button type="button" data-business-id="${b.id}" class="delete-business rounded border border-red-200 dark:border-red-800 px-2 py-1 text-xs text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20">Delete</button>
        </div>
    `).join('');
    container.querySelectorAll('.select-business').forEach((btn) => {
        btn.addEventListener('click', () => selectBusiness(Number(btn.dataset.businessId)));
    });
    container.querySelectorAll('.delete-business').forEach((btn) => {
        btn.addEventListener('click', () => deleteBusiness(Number(btn.dataset.businessId)));
    });
}

function getBusinesses() {
    window.ajaxRequest({
        method: 'GET',
        url: `${API_BASE}/businesses`,
        onSuccess: (res) => {
            businesses = res.data || [];
            renderBusinessList();
        },
        onError: () => {
            const el = document.getElementById('business-list');
            if (el) el.innerHTML = '<p class="text-sm text-red-600">Failed to load businesses.</p>';
        },
    });
}

function createBusiness(form, submitBtn) {
    window.clearFieldErrors(form);
    const payload = {
        name: form.name.value.trim(),
        niche: form.niche.value.trim() || null,
        website_url: form.website_url.value.trim() || null,
        tone: form.tone.value.trim() || null,
        language: form.language.value.trim() || null,
        posts_per_day: parseInt(form.posts_per_day.value, 10) || 1,
        timezone: form.timezone.value.trim() || 'UTC',
        autopilot_enabled: form.autopilot_enabled.checked,
    };

    window.ajaxRequest({
        method: 'POST',
        url: `${API_BASE}/businesses`,
        data: payload,
        onSuccess: (res) => {
            window.showToast('success', res.message || 'Business created.');
            form.reset();
            getBusinesses();
        },
        onError: (err) => {
            window.renderFieldErrors(form, err?.errors);
        },
        onFinally: () => {
            window.setLoading(submitBtn, false);
        },
    });
}

function deleteBusiness(businessId) {
    if (!window.confirm('Delete this business? This cannot be undone.')) return;

    window.ajaxRequest({
        method: 'DELETE',
        url: `${API_BASE}/businesses/${businessId}`,
        onSuccess: (res) => {
            window.showToast('success', res.message || 'Business deleted.');
            businesses = businesses.filter((b) => b.id !== businessId);
            if (selectedBusinessId === businessId) {
                selectedBusinessId = null;
                document.getElementById('selected-business-section')?.classList.add('hidden');
            }
            renderBusinessList();
        },
        onError: () => {},
    });
}

function selectBusiness(id) {
    selectedBusinessId = id;
    const business = businesses.find((b) => b.id === id);
    const section = document.getElementById('selected-business-section');
    const nameEl = document.getElementById('selected-business-name');
    if (business) {
        nameEl.textContent = business.name;
        section.classList.remove('hidden');
        loadPosts(id);
        loadCalendar(id);
    }
}

function generateToday(businessId, btn) {
    const msgEl = document.getElementById('selected-message');
    if (msgEl) msgEl.textContent = 'Generating…';

    window.ajaxRequest({
        method: 'POST',
        url: `${API_BASE}/businesses/${businessId}/generate-today`,
        onSuccess: (res) => {
            window.showToast('success', res.message || 'Job dispatched.');
            if (msgEl) msgEl.textContent = '';
            if (selectedBusinessId === businessId) {
                loadPosts(businessId);
                loadCalendar(businessId);
            }
        },
        onError: () => {
            if (msgEl) msgEl.textContent = 'Failed.';
        },
        onFinally: () => {
            window.setLoading(btn, false);
        },
    });
}

function toggleAutopilot(businessId) {
    window.ajaxRequest({
        method: 'POST',
        url: `${API_BASE}/businesses/${businessId}/toggle-autopilot`,
        onSuccess: (res) => {
            const data = res.data;
            if (data) {
                const b = businesses.find((x) => x.id === businessId);
                if (b) b.autopilot_enabled = data.autopilot_enabled;
                const msgEl = document.getElementById('selected-message');
                if (msgEl) msgEl.textContent = 'Autopilot ' + (data.autopilot_enabled ? 'on' : 'off');
                const statusEl = document.getElementById('status-autopilot');
                if (statusEl) statusEl.textContent = data.autopilot_enabled ? 'On' : 'Off';
            }
            window.showToast('success', res.message || 'Autopilot updated.');
            getBusinesses();
        },
    });
}

function loadPosts(businessId, status = '') {
    const url = status ? `${API_BASE}/businesses/${businessId}/posts?status=${status}` : `${API_BASE}/businesses/${businessId}/posts`;
    const tbody = document.getElementById('posts-tbody');
    if (!tbody) return;

    window.ajaxRequest({
        method: 'GET',
        url,
        onSuccess: (res) => {
            const posts = res.data || [];
            if (!posts.length) {
                tbody.innerHTML = '<tr><td colspan="4" class="p-2 text-[#706f6c]">No posts.</td></tr>';
                return;
            }
            tbody.innerHTML = posts.map((p) => `
                <tr class="border-t border-gray-200 dark:border-[#3E3E3A]">
                    <td class="p-2">${p.id}</td>
                    <td class="p-2">${escapeHtml(p.status)}</td>
                    <td class="p-2">${p.scheduled_at ? new Date(p.scheduled_at).toLocaleString() : '—'}</td>
                    <td class="p-2 max-w-xs truncate">${escapeHtml(p.caption || '')}</td>
                </tr>
            `).join('');
        },
        onError: () => {
            tbody.innerHTML = '<tr><td colspan="4" class="p-2 text-red-600">Failed to load posts.</td></tr>';
        },
    });
}

function loadCalendar(businessId, from = '', to = '') {
    if (!from || !to) {
        const start = new Date();
        start.setDate(start.getDate() - 7);
        const end = new Date();
        end.setDate(end.getDate() + 30);
        from = start.toISOString().slice(0, 10);
        to = end.toISOString().slice(0, 10);
    }
    const params = new URLSearchParams();
    params.set('from', from);
    params.set('to', to);
    const url = `${API_BASE}/businesses/${businessId}/posts?${params}`;
    const listEl = document.getElementById('calendar-list');
    if (!listEl) return;

    window.ajaxRequest({
        method: 'GET',
        url,
        onSuccess: (res) => {
            const events = res.data || [];
            if (!events.length) {
                listEl.innerHTML = '<li class="text-[#706f6c]">No events.</li>';
                return;
            }
            listEl.innerHTML = events.map((p) => {
                const at = p.scheduled_at ? new Date(p.scheduled_at).toLocaleString() : '—';
                const cap = p.caption ? escapeHtml(p.caption).slice(0, 50) + (p.caption.length > 50 ? '…' : '') : '';
                return `<li>${at} – ${escapeHtml(p.status)}${cap ? ': ' + cap : ''}</li>`;
            }).join('');
        },
        onError: () => {
            listEl.innerHTML = '<li class="text-red-600">Failed to load calendar.</li>';
        },
    });
}

function init() {
    getBusinesses();

    const form = document.getElementById('create-business-form');
    if (form) {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const submitBtn = form.querySelector('button[type="submit"]');
            window.setLoading(submitBtn, true);
            createBusiness(form, submitBtn);
        });
    }

    const btnGenerate = document.getElementById('btn-generate-today');
    if (btnGenerate) {
        btnGenerate.addEventListener('click', () => {
            if (selectedBusinessId) {
                window.setLoading(btnGenerate, true);
                generateToday(selectedBusinessId, btnGenerate);
            }
        });
    }

    const btnAutopilot = document.getElementById('btn-toggle-autopilot');
    if (btnAutopilot) {
        btnAutopilot.addEventListener('click', () => {
            if (selectedBusinessId) toggleAutopilot(selectedBusinessId);
        });
    }
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
} else {
    init();
}
