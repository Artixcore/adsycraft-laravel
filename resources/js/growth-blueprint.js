const API_BASE = '/api';

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text ?? '';
    return div.innerHTML;
}

let businesses = [];

function loadBusinesses() {
    window.ajaxRequest({
        method: 'GET',
        url: `${API_BASE}/businesses`,
        onSuccess: (res) => {
            businesses = res.data || [];
            const select = document.getElementById('blueprint-business-select');
            if (!select) return;
            select.innerHTML = '<option value="">Select a business</option>' +
                businesses.map((b) => `<option value="${b.id}">${escapeHtml(b.name)}</option>`).join('');
        },
    });
}

function loadBlueprints(businessId) {
    const list = document.getElementById('blueprint-list');
    if (!list) return;
    list.innerHTML = '<p class="text-sm text-zinc-500 dark:text-zinc-400">Loading…</p>';

    window.ajaxRequest({
        method: 'GET',
        url: `${API_BASE}/businesses/${businessId}/growth-blueprints`,
        onSuccess: (res) => {
            const items = res.data || [];
            if (!items.length) {
                list.innerHTML = '<p class="text-sm text-zinc-500 dark:text-zinc-400">No blueprints yet. Generate one above.</p>';
                return;
            }
            list.innerHTML = items.map((b) => `
                <div class="flex items-center justify-between rounded-xl border border-zinc-200 dark:border-zinc-800 p-3">
                    <div>
                        <span class="font-medium">Blueprint #${b.id}</span>
                        <span class="ml-2 text-xs px-2 py-0.5 rounded ${b.status === 'completed' ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400' : b.status === 'failed' ? 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400' : 'bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400'}">${escapeHtml(b.status)}</span>
                    </div>
                    <div class="text-sm text-zinc-500 dark:text-zinc-400">${new Date(b.created_at).toLocaleString()}</div>
                    <button type="button" data-blueprint-id="${b.id}" class="view-blueprint rounded border border-zinc-300 dark:border-zinc-600 px-2 py-1 text-xs hover:bg-zinc-50 dark:hover:bg-zinc-800">View</button>
                </div>
            `).join('');
            list.querySelectorAll('.view-blueprint').forEach((btn) => {
                btn.addEventListener('click', () => viewBlueprint(businessId, Number(btn.dataset.blueprintId)));
            });
        },
        onError: () => {
            list.innerHTML = '<p class="text-sm text-red-600">Failed to load blueprints.</p>';
        },
    });
}

function viewBlueprint(businessId, blueprintId) {
    window.ajaxRequest({
        method: 'GET',
        url: `${API_BASE}/businesses/${businessId}/growth-blueprints/${blueprintId}`,
        onSuccess: (res) => {
            const data = res.data;
            const card = document.getElementById('blueprint-detail-card');
            const content = document.getElementById('blueprint-detail-content');
            if (!card || !content) return;

            if (data.status !== 'completed' || !data.payload) {
                content.innerHTML = `<p class="text-zinc-500 dark:text-zinc-400">${data.error_message || 'Blueprint not yet completed.'}</p>`;
            } else {
                const p = data.payload;
                content.innerHTML = `
                    <h3 class="text-lg font-semibold mb-2">Executive Summary</h3>
                    <p class="mb-4">${escapeHtml(p.executive_summary || '—')}</p>
                    <h3 class="text-lg font-semibold mb-2">Disruptor Moves</h3>
                    <ul class="list-disc list-inside mb-4">${(p.disruptor_moves || []).map((m) => `<li>${escapeHtml(m)}</li>`).join('')}</ul>
                    <h3 class="text-lg font-semibold mb-2">Market Map</h3>
                    <pre class="text-sm bg-zinc-100 dark:bg-zinc-800 p-4 rounded overflow-x-auto">${escapeHtml(JSON.stringify(p.market_map || {}, null, 2))}</pre>
                    <h3 class="text-lg font-semibold mt-4 mb-2">Positioning & Offer</h3>
                    <pre class="text-sm bg-zinc-100 dark:bg-zinc-800 p-4 rounded overflow-x-auto">${escapeHtml(JSON.stringify(p.positioning_offer || {}, null, 2))}</pre>
                `;
            }
            card.classList.remove('hidden');
        },
    });
}

function generateBlueprint() {
    const select = document.getElementById('blueprint-business-select');
    const msg = document.getElementById('blueprint-message');
    const btn = document.getElementById('btn-generate-blueprint');
    if (!select || !select.value) {
        if (msg) {
            msg.textContent = 'Select a business first.';
            msg.classList.remove('hidden');
        }
        return;
    }

    const businessId = select.value;
    if (btn) btn.disabled = true;
    if (msg) {
        msg.textContent = 'Generating… This may take a few minutes.';
        msg.classList.remove('hidden');
    }

    window.ajaxRequest({
        method: 'POST',
        url: `${API_BASE}/businesses/${businessId}/growth-blueprints`,
        onSuccess: () => {
            if (msg) {
                msg.textContent = 'Blueprint generation started. Refresh the list in a few minutes.';
                msg.classList.add('text-green-600', 'dark:text-green-400');
            }
            loadBlueprints(businessId);
        },
        onError: (err) => {
            if (msg) {
                msg.textContent = err?.message || 'Failed to start generation.';
                msg.classList.add('text-red-600', 'dark:text-red-400');
            }
        },
        onFinally: () => {
            if (btn) btn.disabled = false;
        },
    });
}

document.addEventListener('DOMContentLoaded', () => {
    loadBusinesses();

    const select = document.getElementById('blueprint-business-select');
    if (select) {
        select.addEventListener('change', () => {
            const id = select.value;
            if (id) loadBlueprints(id);
            else {
                const list = document.getElementById('blueprint-list');
                if (list) list.innerHTML = '<p class="text-sm text-zinc-500 dark:text-zinc-400">Select a business to view blueprints.</p>';
            }
        });
    }

    const btn = document.getElementById('btn-generate-blueprint');
    if (btn) btn.addEventListener('click', generateBlueprint);
});
