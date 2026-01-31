const API_BASE = '/api';

function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
}

function apiFetch(url, options = {}) {
    const headers = {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': getCsrfToken(),
        ...options.headers,
    };
    const opts = {
        ...options,
        headers,
        credentials: 'include',
    };
    return fetch(url, opts);
}

let selectedBusinessId = null;
let businesses = [];

function renderBusinessList() {
    const container = document.getElementById('business-list');
    if (!businesses.length) {
        container.innerHTML = '<p class="text-sm text-[#706f6c]">No businesses yet. Create one below.</p>';
        return;
    }
    container.innerHTML = businesses.map((b) => `
        <div class="flex items-center gap-2">
            <span class="text-sm">${escapeHtml(b.name)}</span>
            <button type="button" data-business-id="${b.id}" class="select-business rounded border border-[#19140035] dark:border-[#3E3E3A] px-2 py-1 text-xs hover:bg-gray-100 dark:hover:bg-[#3E3E3A]">Select</button>
        </div>
    `).join('');
    container.querySelectorAll('.select-business').forEach((btn) => {
        btn.addEventListener('click', () => selectBusiness(Number(btn.dataset.businessId)));
    });
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

async function getBusinesses() {
    const res = await apiFetch(`${API_BASE}/businesses`);
    if (!res.ok) {
        document.getElementById('business-list').innerHTML = '<p class="text-sm text-red-600">Failed to load businesses.</p>';
        return;
    }
    const json = await res.json();
    businesses = json.data || [];
    renderBusinessList();
}

async function createBusiness(payload) {
    const msgEl = document.getElementById('create-message');
    msgEl.classList.add('hidden');
    const res = await apiFetch(`${API_BASE}/businesses`, {
        method: 'POST',
        body: JSON.stringify(payload),
    });
    const data = await res.json().catch(() => ({}));
    if (!res.ok) {
        msgEl.textContent = data.message || 'Failed to create business.';
        msgEl.classList.remove('hidden');
        msgEl.classList.add('text-red-600');
        return;
    }
    msgEl.textContent = 'Business created.';
    msgEl.classList.remove('hidden', 'text-red-600');
    await getBusinesses();
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

async function generateToday(businessId) {
    const msgEl = document.getElementById('selected-message');
    msgEl.textContent = 'Generating…';
    const res = await apiFetch(`${API_BASE}/businesses/${businessId}/generate-today`, { method: 'POST' });
    const data = await res.json().catch(() => ({}));
    msgEl.textContent = res.ok ? (data.message || 'Job dispatched.') : (data.message || 'Failed.');
    if (selectedBusinessId === businessId) {
        loadPosts(businessId);
        loadCalendar(businessId);
    }
}

async function toggleAutopilot(businessId) {
    const res = await apiFetch(`${API_BASE}/businesses/${businessId}/toggle-autopilot`, { method: 'POST' });
    const data = await res.json().catch(() => ({}));
    if (res.ok && data.data) {
        const b = businesses.find((x) => x.id === businessId);
        if (b) b.autopilot_enabled = data.data.autopilot_enabled;
        document.getElementById('selected-message').textContent = 'Autopilot ' + (data.data.autopilot_enabled ? 'on' : 'off');
    }
    await getBusinesses();
    renderBusinessList();
}

async function loadPosts(businessId, status = '') {
    const url = status ? `${API_BASE}/businesses/${businessId}/posts?status=${status}` : `${API_BASE}/businesses/${businessId}/posts`;
    const res = await apiFetch(url);
    const tbody = document.getElementById('posts-tbody');
    if (!res.ok) {
        tbody.innerHTML = '<tr><td colspan="4" class="p-2 text-red-600">Failed to load posts.</td></tr>';
        return;
    }
    const json = await res.json();
    const posts = json.data || [];
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
}

async function loadCalendar(businessId, from = '', to = '') {
    if (!from || !to) {
        const start = new Date();
        start.setDate(start.getDate() - 7);
        const end = new Date();
        end.setDate(end.getDate() + 30);
        from = from || start.toISOString().slice(0, 10);
        to = to || end.toISOString().slice(0, 10);
    }
    const params = new URLSearchParams();
    params.set('from', from);
    params.set('to', to);
    const url = `${API_BASE}/businesses/${businessId}/calendar?${params}`;
    const res = await apiFetch(url);
    const listEl = document.getElementById('calendar-list');
    if (!res.ok) {
        listEl.innerHTML = '<li class="text-red-600">Failed to load calendar.</li>';
        return;
    }
    const json = await res.json();
    const events = json.data || [];
    if (!events.length) {
        listEl.innerHTML = '<li class="text-[#706f6c]">No events.</li>';
        return;
    }
    listEl.innerHTML = events.map((p) => {
        const at = p.scheduled_at ? new Date(p.scheduled_at).toLocaleString() : '—';
        const cap = p.caption ? escapeHtml(p.caption).slice(0, 50) + (p.caption.length > 50 ? '…' : '') : '';
        return `<li>${at} – ${escapeHtml(p.status)}${cap ? ': ' + cap : ''}</li>`;
    }).join('');
}

function init() {
    getBusinesses();

    document.getElementById('create-business-form').addEventListener('submit', (e) => {
        e.preventDefault();
        const form = e.target;
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
        createBusiness(payload);
    });

    document.getElementById('btn-generate-today').addEventListener('click', () => {
        if (selectedBusinessId) generateToday(selectedBusinessId);
    });

    document.getElementById('btn-toggle-autopilot').addEventListener('click', () => {
        if (selectedBusinessId) toggleAutopilot(selectedBusinessId);
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
} else {
    init();
}
