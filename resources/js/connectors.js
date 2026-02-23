const API_BASE = '/api';

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
    if (!container) return;
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

function getBusinesses() {
    window.ajaxRequest({
        method: 'GET',
        url: `${API_BASE}/businesses`,
        onSuccess: (res) => {
            businesses = res.data || [];
            renderBusinessSelector();
        },
        onError: () => {
            const el = document.getElementById('business-selector');
            if (el) el.innerHTML = '<p class="text-sm text-red-600">Failed to load businesses.</p>';
        },
    });
}

function loadAiConnections(businessId) {
    const listEl = document.getElementById('ai-connections-list');
    if (!listEl) return;

    window.ajaxRequest({
        method: 'GET',
        url: `${API_BASE}/businesses/${businessId}/ai-connections`,
        onSuccess: (res) => {
            aiConnections = res.data || [];
            renderAiConnections(businessId);
        },
        onError: () => {
            listEl.innerHTML = '<p class="text-sm text-red-600">Failed to load AI connections.</p>';
        },
    });
}

function renderAiConnections(businessId) {
    const listEl = document.getElementById('ai-connections-list');
    if (!listEl) return;
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

function addAiConnection(businessId, payload, form, submitBtn) {
    window.clearFieldErrors(form);

    window.ajaxRequest({
        method: 'POST',
        url: `${API_BASE}/businesses/${businessId}/ai-connections`,
        data: payload,
        onSuccess: (res) => {
            window.showToast('success', res.message || 'Connection added.');
            form.reset();
            loadAiConnections(businessId);
        },
        onError: (err) => {
            window.renderFieldErrors(form, err?.errors);
        },
        onFinally: () => {
            window.setLoading(submitBtn, false);
        },
    });
}

function makePrimary(businessId, connectionId) {
    window.ajaxRequest({
        method: 'POST',
        url: `${API_BASE}/businesses/${businessId}/ai-connections/${connectionId}/make-primary`,
        onSuccess: () => {
            window.showToast('success', 'Primary connection updated.');
            loadAiConnections(businessId);
        },
    });
}

function testConnection(businessId, connectionId) {
    window.ajaxRequest({
        method: 'POST',
        url: `${API_BASE}/businesses/${businessId}/ai-connections/${connectionId}/test`,
        onSuccess: (res) => {
            window.showToast('success', res.message || 'Connection validated.');
            loadAiConnections(businessId);
        },
        onError: (err) => {
            window.showToast('error', err?.message || 'Test failed.');
        },
    });
}

function deleteConnection(businessId, connectionId) {
    if (!window.confirm('Delete this AI connection?')) return;

    window.ajaxRequest({
        method: 'DELETE',
        url: `${API_BASE}/businesses/${businessId}/ai-connections/${connectionId}`,
        onSuccess: (res) => {
            window.showToast('success', res.message || 'Connection deleted.');
            loadAiConnections(businessId);
        },
    });
}

function loadMetaStatus(businessId) {
    const statusEl = document.getElementById('meta-status');
    const msgEl = document.getElementById('meta-message');
    const assetsBlock = document.getElementById('meta-assets-block');
    if (msgEl) msgEl.classList.add('hidden');

    window.ajaxRequest({
        method: 'GET',
        url: `${API_BASE}/businesses/${businessId}/connectors/meta/status`,
        onSuccess: (res) => {
            const data = res.data || res;
            if (data.connected) {
                statusEl.textContent = 'Connected' + (data.connected_at ? ' at ' + data.connected_at : '') + (data.token_masked ? ' (token ' + data.token_masked + ')' : '') + '.';
                if (assetsBlock) assetsBlock.classList.remove('hidden');
            } else {
                statusEl.textContent = 'Not connected.';
                if (assetsBlock) assetsBlock.classList.add('hidden');
            }
        },
        onError: () => {
            statusEl.textContent = 'Failed to load Meta status.';
            if (assetsBlock) assetsBlock.classList.add('hidden');
        },
    });
}

function metaConnect(businessId) {
    window.ajaxRequest({
        method: 'POST',
        url: `${API_BASE}/businesses/${businessId}/connectors/meta/auth-url`,
        onSuccess: (res) => {
            const url = res.data?.url || res.url;
            if (url) {
                sessionStorage.setItem('meta_connector_business_id', String(businessId));
                window.location.href = url;
            } else {
                window.showToast('error', 'Failed to get auth URL.');
            }
        },
        onError: (err) => {
            window.showToast('error', err?.message || 'Failed to get auth URL.');
        },
    });
}

function loadMetaAssets(businessId) {
    const listEl = document.getElementById('meta-assets-list');
    if (!listEl) return;

    window.ajaxRequest({
        method: 'GET',
        url: `${API_BASE}/businesses/${businessId}/connectors/meta/assets`,
        onSuccess: (res) => {
            metaAssets = res.data || [];
            renderMetaAssets();
        },
        onError: () => {
            listEl.innerHTML = '<p class="text-sm text-red-600">Failed to load assets.</p>';
        },
    });
}

function renderMetaAssets() {
    const listEl = document.getElementById('meta-assets-list');
    if (!listEl) return;
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

function saveMetaSelection(businessId, btn) {
    const checkboxes = document.querySelectorAll('.meta-asset-checkbox:checked');
    const pageIds = Array.from(checkboxes).map((cb) => cb.dataset.pageId).filter(Boolean);

    window.ajaxRequest({
        method: 'POST',
        url: `${API_BASE}/businesses/${businessId}/connectors/meta/assets/select`,
        data: { page_ids: pageIds },
        onSuccess: (res) => {
            window.showToast('success', res.message || 'Selection saved.');
            loadMetaAssets(businessId);
        },
        onError: () => {},
        onFinally: () => {
            window.setLoading(btn, false);
        },
    });
}

function metaDisconnect(businessId) {
    window.ajaxRequest({
        method: 'POST',
        url: `${API_BASE}/businesses/${businessId}/connectors/meta/disconnect`,
        onSuccess: (res) => {
            window.showToast('success', res.message || 'Disconnected.');
            loadMetaStatus(businessId);
            metaAssets = [];
            renderMetaAssets();
            document.getElementById('meta-assets-block')?.classList.add('hidden');
        },
    });
}

function init() {
    getBusinesses();

    const form = document.getElementById('add-ai-connection-form');
    if (form) {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            if (!selectedBusinessId) return;
            const submitBtn = form.querySelector('button[type="submit"]');
            window.setLoading(submitBtn, true);
            const payload = {
                provider: form.provider.value,
                api_key: form.api_key.value,
                default_model: form.default_model.value.trim() || null,
                is_primary: form.is_primary.checked,
            };
            addAiConnection(selectedBusinessId, payload, form, submitBtn);
        });
    }

    const btnConnect = document.getElementById('btn-meta-connect');
    if (btnConnect) {
        btnConnect.addEventListener('click', () => {
            if (selectedBusinessId) metaConnect(selectedBusinessId);
        });
    }

    const btnDisconnect = document.getElementById('btn-meta-disconnect');
    if (btnDisconnect) {
        btnDisconnect.addEventListener('click', () => {
            if (selectedBusinessId) metaDisconnect(selectedBusinessId);
        });
    }

    const btnSaveSelection = document.getElementById('btn-meta-save-selection');
    if (btnSaveSelection) {
        btnSaveSelection.addEventListener('click', () => {
            if (selectedBusinessId) {
                window.setLoading(btnSaveSelection, true);
                saveMetaSelection(selectedBusinessId, btnSaveSelection);
            }
        });
    }

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
        const msgEl = document.getElementById('meta-message');
        if (msgEl) {
            msgEl.textContent = params.get('error') === 'invalid_state' ? 'Invalid or expired link. Try connecting again.' : 'Connection failed. Try again.';
            msgEl.classList.remove('hidden');
            msgEl.classList.add('text-red-600');
        }
        window.history.replaceState({}, document.title, window.location.pathname);
    }
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
} else {
    init();
}
