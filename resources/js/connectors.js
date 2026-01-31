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
    return fetch(url, {
        ...options,
        headers,
        credentials: 'include',
    });
}

function escapeHtml(text) {
    if (text == null) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

let selectedBusinessId = null;
let businesses = [];
let aiConnections = [];
let metaAssets = [];

function renderBusinessSelector() {
    const container = document.getElementById('business-selector');
    if (!businesses.length) {
        container.innerHTML = '<p class="text-sm text-[#706f6c]">No businesses. Create one from the Dashboard.</p>';
        return;
    }
    container.innerHTML = `
        <select id="business-select" class="rounded border border-gray-300 dark:border-[#3E3E3A] dark:bg-[#161615] px-3 py-2 text-sm">
            <option value="">Choose a business…</option>
            ${businesses.map((b) => `<option value="${b.id}">${escapeHtml(b.name)}</option>`).join('')}
        </select>
    `;
    document.getElementById('business-select').addEventListener('change', (e) => {
        const id = e.target.value ? Number(e.target.value) : null;
        selectedBusinessId = id;
        if (id) {
            document.getElementById('connectors-content').classList.remove('hidden');
            loadAiConnections(id);
            loadMetaStatus(id);
            loadMetaAssets(id);
        } else {
            document.getElementById('connectors-content').classList.add('hidden');
        }
    });

    const returnBusinessId = sessionStorage.getItem('meta_connector_business_id');
    if (returnBusinessId && businesses.some((b) => String(b.id) === returnBusinessId)) {
        document.getElementById('business-select').value = returnBusinessId;
        selectedBusinessId = Number(returnBusinessId);
        document.getElementById('connectors-content').classList.remove('hidden');
        loadAiConnections(selectedBusinessId);
        loadMetaStatus(selectedBusinessId);
        loadMetaAssets(selectedBusinessId);
        if (new URLSearchParams(window.location.search).get('connected') === '1') {
            sessionStorage.removeItem('meta_connector_business_id');
        }
    }
}

async function getBusinesses() {
    const res = await apiFetch(`${API_BASE}/businesses`);
    if (!res.ok) {
        document.getElementById('business-selector').innerHTML = '<p class="text-sm text-red-600">Failed to load businesses.</p>';
        return;
    }
    const json = await res.json();
    businesses = json.data || [];
    renderBusinessSelector();
}

async function loadAiConnections(businessId) {
    const listEl = document.getElementById('ai-connections-list');
    const res = await apiFetch(`${API_BASE}/businesses/${businessId}/ai-connections`);
    if (!res.ok) {
        listEl.innerHTML = '<p class="text-sm text-red-600">Failed to load AI connections.</p>';
        return;
    }
    const json = await res.json();
    aiConnections = json.data || [];
    renderAiConnections(businessId);
}

function renderAiConnections(businessId) {
    const listEl = document.getElementById('ai-connections-list');
    if (!aiConnections.length) {
        listEl.innerHTML = '<p class="text-sm text-[#706f6c]">No AI connections yet. Add one below.</p>';
        return;
    }
    listEl.innerHTML = aiConnections.map((c) => `
        <div class="flex flex-wrap items-center gap-2 p-3 border border-gray-200 dark:border-[#3E3E3A] rounded" data-connection-id="${c.id}">
            <span class="font-medium capitalize">${escapeHtml(c.provider)}</span>
            <span class="text-sm text-[#706f6c]">${escapeHtml(c.api_key_masked || '••••')}</span>
            ${c.default_model ? `<span class="text-sm">${escapeHtml(c.default_model)}</span>` : ''}
            ${c.is_primary ? '<span class="text-xs bg-green-100 dark:bg-green-900/30 px-2 py-0.5 rounded">Primary</span>' : ''}
            ${c.is_enabled ? '' : '<span class="text-xs text-[#706f6c]">Disabled</span>'}
            <div class="flex gap-1 ml-auto">
                ${!c.is_primary ? `<button type="button" class="btn-make-primary rounded border border-gray-300 dark:border-[#3E3E3A] px-2 py-1 text-xs hover:bg-gray-100 dark:hover:bg-[#3E3E3A]" data-id="${c.id}">Set primary</button>` : ''}
                <button type="button" class="btn-test rounded border border-gray-300 dark:border-[#3E3E3A] px-2 py-1 text-xs hover:bg-gray-100 dark:hover:bg-[#3E3E3A]" data-id="${c.id}">Test</button>
                <button type="button" class="btn-delete rounded border border-red-200 dark:border-red-800 px-2 py-1 text-xs text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20" data-id="${c.id}">Delete</button>
            </div>
        </div>
    `).join('');

    listEl.querySelectorAll('.btn-make-primary').forEach((btn) => {
        btn.addEventListener('click', () => makePrimary(businessId, Number(btn.dataset.id)));
    });
    listEl.querySelectorAll('.btn-test').forEach((btn) => {
        btn.addEventListener('click', () => testConnection(businessId, Number(btn.dataset.id)));
    });
    listEl.querySelectorAll('.btn-delete').forEach((btn) => {
        btn.addEventListener('click', () => deleteConnection(businessId, Number(btn.dataset.id)));
    });
}

async function addAiConnection(businessId, payload) {
    const msgEl = document.getElementById('ai-add-message');
    msgEl.classList.add('hidden');
    const res = await apiFetch(`${API_BASE}/businesses/${businessId}/ai-connections`, {
        method: 'POST',
        body: JSON.stringify(payload),
    });
    const data = await res.json().catch(() => ({}));
    if (!res.ok) {
        msgEl.textContent = data.message || 'Failed to add connection.';
        msgEl.classList.remove('hidden');
        msgEl.classList.add('text-red-600');
        return;
    }
    msgEl.textContent = 'Connection added.';
    msgEl.classList.remove('hidden', 'text-red-600');
    document.getElementById('add-ai-connection-form').reset();
    await loadAiConnections(businessId);
}

async function makePrimary(businessId, connectionId) {
    const res = await apiFetch(`${API_BASE}/businesses/${businessId}/ai-connections/${connectionId}/make-primary`, { method: 'POST' });
    if (res.ok) await loadAiConnections(businessId);
}

async function testConnection(businessId, connectionId) {
    const res = await apiFetch(`${API_BASE}/businesses/${businessId}/ai-connections/${connectionId}/test`, { method: 'POST' });
    const data = await res.json().catch(() => ({}));
    if (res.ok) {
        await loadAiConnections(businessId);
        alert(data.message || 'Connection validated.');
    } else {
        alert(data.message || 'Test failed.');
    }
}

async function deleteConnection(businessId, connectionId) {
    if (!confirm('Delete this AI connection?')) return;
    const res = await apiFetch(`${API_BASE}/businesses/${businessId}/ai-connections/${connectionId}`, { method: 'DELETE' });
    if (res.ok) await loadAiConnections(businessId);
}

async function loadMetaStatus(businessId) {
    const res = await apiFetch(`${API_BASE}/businesses/${businessId}/connectors/meta/status`);
    const statusEl = document.getElementById('meta-status');
    const msgEl = document.getElementById('meta-message');
    msgEl.classList.add('hidden');
    if (!res.ok) {
        statusEl.textContent = 'Failed to load Meta status.';
        document.getElementById('meta-assets-block').classList.add('hidden');
        return;
    }
    const data = await res.json();
    if (data.connected) {
        statusEl.textContent = 'Connected' + (data.connected_at ? ' at ' + data.connected_at : '') + (data.token_masked ? ' (token ' + data.token_masked + ')' : '') + '.';
        document.getElementById('meta-assets-block').classList.remove('hidden');
    } else {
        statusEl.textContent = 'Not connected.';
        document.getElementById('meta-assets-block').classList.add('hidden');
    }
}

async function metaConnect(businessId) {
    const res = await apiFetch(`${API_BASE}/businesses/${businessId}/connectors/meta/auth-url`, { method: 'POST' });
    const data = await res.json().catch(() => ({}));
    const msgEl = document.getElementById('meta-message');
    msgEl.classList.remove('hidden');
    if (res.ok && data.url) {
        sessionStorage.setItem('meta_connector_business_id', String(businessId));
        window.location.href = data.url;
        return;
    }
    msgEl.textContent = data.message || 'Failed to get auth URL.';
    msgEl.classList.add('text-red-600');
}

async function loadMetaAssets(businessId) {
    const listEl = document.getElementById('meta-assets-list');
    const res = await apiFetch(`${API_BASE}/businesses/${businessId}/connectors/meta/assets`);
    if (!res.ok) {
        listEl.innerHTML = '<p class="text-sm text-red-600">Failed to load assets.</p>';
        return;
    }
    const json = await res.json();
    metaAssets = json.data || [];
    renderMetaAssets();
}

function renderMetaAssets() {
    const listEl = document.getElementById('meta-assets-list');
    if (!metaAssets.length) {
        listEl.innerHTML = '<p class="text-sm text-[#706f6c]">No pages yet. Connect Meta to discover.</p>';
        return;
    }
    listEl.innerHTML = metaAssets.map((a) => `
        <label class="flex items-center gap-2 p-2 border border-gray-200 dark:border-[#3E3E3A] rounded cursor-pointer hover:bg-gray-50 dark:hover:bg-[#1a1a1a]">
            <input type="checkbox" class="meta-asset-checkbox rounded" data-page-id="${escapeHtml(a.page_id || '')}" ${a.selected ? 'checked' : ''}>
            <span class="font-medium">${escapeHtml(a.page_name || 'Page')}</span>
            ${a.ig_username ? `<span class="text-sm text-[#706f6c]">@${escapeHtml(a.ig_username)}</span>` : ''}
        </label>
    `).join('');
}

async function saveMetaSelection(businessId) {
    const checkboxes = document.querySelectorAll('.meta-asset-checkbox:checked');
    const pageIds = Array.from(checkboxes).map((cb) => cb.dataset.pageId).filter(Boolean);
    const res = await apiFetch(`${API_BASE}/businesses/${businessId}/connectors/meta/assets/select`, {
        method: 'POST',
        body: JSON.stringify({ page_ids: pageIds }),
    });
    const msgEl = document.getElementById('meta-assets-message');
    msgEl.classList.remove('hidden');
    if (res.ok) {
        msgEl.textContent = 'Selection saved.';
        msgEl.classList.remove('text-red-600');
        await loadMetaAssets(businessId);
    } else {
        const data = await res.json().catch(() => ({}));
        msgEl.textContent = data.message || 'Failed to save selection.';
        msgEl.classList.add('text-red-600');
    }
}

async function metaDisconnect(businessId) {
    const res = await apiFetch(`${API_BASE}/businesses/${businessId}/connectors/meta/disconnect`, { method: 'POST' });
    const msgEl = document.getElementById('meta-message');
    msgEl.classList.remove('hidden');
    if (res.ok) {
        msgEl.textContent = 'Disconnected.';
        msgEl.classList.remove('text-red-600');
        await loadMetaStatus(businessId);
        metaAssets = [];
        renderMetaAssets();
        document.getElementById('meta-assets-block').classList.add('hidden');
    } else {
        const data = await res.json().catch(() => ({}));
        msgEl.textContent = data.message || 'Disconnect failed.';
        msgEl.classList.add('text-red-600');
    }
}

function init() {
    getBusinesses();

    document.getElementById('add-ai-connection-form').addEventListener('submit', (e) => {
        e.preventDefault();
        if (!selectedBusinessId) return;
        const form = e.target;
        const payload = {
            provider: form.provider.value,
            api_key: form.api_key.value,
            default_model: form.default_model.value.trim() || null,
            is_primary: form.is_primary.checked,
        };
        addAiConnection(selectedBusinessId, payload);
    });

    document.getElementById('btn-meta-connect').addEventListener('click', () => {
        if (selectedBusinessId) metaConnect(selectedBusinessId);
    });

    document.getElementById('btn-meta-disconnect').addEventListener('click', () => {
        if (selectedBusinessId) metaDisconnect(selectedBusinessId);
    });

    document.getElementById('btn-meta-save-selection').addEventListener('click', () => {
        if (selectedBusinessId) saveMetaSelection(selectedBusinessId);
    });

    setTimeout(checkUrlParams, 100);
}

function checkUrlParams() {
    const params = new URLSearchParams(window.location.search);
    if (params.get('connected') === '1' && selectedBusinessId) {
        loadMetaStatus(selectedBusinessId);
        loadMetaAssets(selectedBusinessId);
        window.history.replaceState({}, document.title, window.location.pathname);
    }
    if (params.get('error')) {
        document.getElementById('meta-message').textContent = params.get('error') === 'invalid_state' ? 'Invalid or expired link. Try connecting again.' : 'Connection failed. Try again.';
        document.getElementById('meta-message').classList.remove('hidden');
        document.getElementById('meta-message').classList.add('text-red-600');
        window.history.replaceState({}, document.title, window.location.pathname);
    }
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
} else {
    init();
}
