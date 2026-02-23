const API_BASE = '/api';

function escapeHtml(text) {
    if (text == null) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

let selectedBusinessId = null;
let businesses = [];
let adAccounts = [];

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
            document.getElementById('ads-content').classList.remove('hidden');
            loadAdAccounts(id);
        } else {
            document.getElementById('ads-content').classList.add('hidden');
        }
    });
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

function loadAdAccounts(businessId) {
    const listEl = document.getElementById('ad-accounts-list');
    const loadingEl = document.getElementById('ad-accounts-loading');
    const placeholderEl = document.getElementById('ad-accounts-placeholder');
    if (!listEl) return;

    loadingEl?.classList.remove('hidden');
    placeholderEl?.classList.add('hidden');
    listEl.innerHTML = '';

    window.ajaxRequest({
        method: 'GET',
        url: `${API_BASE}/businesses/${businessId}/ads/accounts`,
        onSuccess: (res) => {
            adAccounts = res.data || [];
            renderAdAccounts(businessId);
        },
        onError: (err) => {
            const msg = err?.message || err?.data?.message || 'Failed to load ad accounts.';
            listEl.innerHTML = `<p class="text-sm text-red-600 dark:text-red-400">${escapeHtml(msg)}</p>`;
        },
        onFinally: () => {
            loadingEl?.classList.add('hidden');
        },
    });
}

function renderAdAccounts(businessId) {
    const listEl = document.getElementById('ad-accounts-list');
    const placeholderEl = document.getElementById('ad-accounts-placeholder');
    if (!listEl) return;

    if (!adAccounts.length) {
        listEl.innerHTML = '<p class="text-sm text-gray-500 dark:text-gray-400">No ad accounts found. Connect Meta in Connectors and ensure you have Admin or Advertiser role on an ad account.</p>';
        return;
    }

    listEl.innerHTML = adAccounts.map((acc) => `
        <div class="flex items-center justify-between p-3 rounded-lg border border-gray-200 dark:border-[#252523] bg-white dark:bg-[#161615]">
            <div>
                <span class="font-medium text-gray-900 dark:text-white">${escapeHtml(acc.name || acc.id)}</span>
                <span class="text-sm text-gray-500 dark:text-gray-400 ml-2">${escapeHtml(acc.id)}</span>
                ${acc.currency ? `<span class="text-xs text-gray-400 ml-2">${escapeHtml(acc.currency)}</span>` : ''}
            </div>
            <button type="button" class="btn-select-account px-3 py-1.5 text-sm rounded-lg border border-indigo-600 dark:border-indigo-500 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-950/50 transition-colors ${acc.selected ? 'opacity-50 cursor-default' : ''}" data-id="${escapeHtml(acc.id)}" data-name="${escapeHtml(acc.name || '')}" data-currency="${escapeHtml(acc.currency || '')}" data-status="${acc.account_status ?? ''}" ${acc.selected ? 'disabled' : ''}>
                ${acc.selected ? 'Selected' : 'Select'}
            </button>
        </div>
    `).join('');

    listEl.querySelectorAll('.btn-select-account:not([disabled])').forEach((btn) => {
        btn.addEventListener('click', () => {
            window.setLoading(btn, true);
            selectAdAccount(businessId, {
                ad_account_id: btn.dataset.id,
                name: btn.dataset.name || undefined,
                currency: btn.dataset.currency || undefined,
                account_status: btn.dataset.status ? parseInt(btn.dataset.status, 10) : undefined,
            }, btn);
        });
    });
}

function selectAdAccount(businessId, data, btn) {
    window.ajaxRequest({
        method: 'POST',
        url: `${API_BASE}/businesses/${businessId}/ads/accounts/select`,
        data,
        onSuccess: (res) => {
            window.showToast('success', res.message || 'Ad account selected.');
            loadAdAccounts(businessId);
        },
        onError: (err) => {
            window.showToast('error', err?.message || err?.data?.message || 'Failed to select ad account.');
        },
        onFinally: () => {
            window.setLoading(btn, false);
        },
    });
}

function init() {
    getBusinesses();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
} else {
    init();
}
