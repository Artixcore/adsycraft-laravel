const API_BASE = '/api';
const AD_LIBRARY_BASE = `${API_BASE}/ad-library`;

function escapeHtml(text) {
    if (text == null) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

let config = null;
let collections = [];
let selectedCollectionId = null;
let nextCursor = null;
let currentSearchParams = null;

function loadConfig() {
    window.ajaxRequest({
        method: 'GET',
        url: `${AD_LIBRARY_BASE}/config`,
        onSuccess: (res) => {
            config = res.data || res;
            const disclaimerEl = document.getElementById('ad-library-disclaimer');
            if (config.disclaimer && disclaimerEl) {
                disclaimerEl.textContent = config.disclaimer;
            }
            if (config.default_country) {
                const countryEl = document.getElementById('search-country');
                if (countryEl) countryEl.value = config.default_country;
            }
        },
        onError: (err) => {
            const disclaimerEl = document.getElementById('ad-library-disclaimer');
            if (disclaimerEl) disclaimerEl.textContent = err?.message || 'Ad Library is not enabled.';
        },
    });
}

function getSearchParams() {
    const form = document.getElementById('ad-library-search-form');
    const query = form?.querySelector('#search-query')?.value?.trim() || '';
    const countryEl = form?.querySelector('#search-country');
    const countries = countryEl?.multiple
        ? Array.from(countryEl.selectedOptions || []).map((o) => o.value)
        : countryEl?.value
            ? [countryEl.value]
            : [];
    const adActiveStatus = form?.querySelector('#search-status')?.value || 'ACTIVE';
    const startedAfter = form?.querySelector('#search-started-after')?.value || null;
    const startedBefore = form?.querySelector('#search-started-before')?.value || null;
    const mediaType = form?.querySelector('#search-media-type')?.value || null;

    return {
        query,
        countries,
        ad_active_status: adActiveStatus,
        started_after: startedAfter || undefined,
        started_before: startedBefore || undefined,
        media_type: mediaType || undefined,
    };
}

function runSearch(after = null) {
    const params = getSearchParams();
    if (!params.countries?.length) {
        showResultsError('Please select a country.');
        return;
    }
    if (!params.query && !params.search_page_ids?.length) {
        showResultsError('Enter keywords or page IDs to search.');
        return;
    }

    const body = { ...params, after };
    currentSearchParams = params;

    const resultsPlaceholder = document.getElementById('results-placeholder');
    const resultsGrid = document.getElementById('results-grid');
    const resultsLoading = document.getElementById('results-loading');
    const loadMore = document.getElementById('results-load-more');

    if (!after) {
        resultsPlaceholder?.classList.add('hidden');
        resultsGrid?.classList.add('hidden');
        document.getElementById('results-error')?.classList.add('hidden');
        resultsLoading?.classList.remove('hidden');
        if (resultsGrid) resultsGrid.innerHTML = '';
    }

    window.ajaxRequest({
        method: 'POST',
        url: `${AD_LIBRARY_BASE}/search`,
        data: body,
        onSuccess: (res) => {
            resultsLoading?.classList.add('hidden');
            const data = res.data || res;
            nextCursor = data.paging?.next_cursor || null;
            const ads = data.data || [];
            const template = document.getElementById('ad-card-template');
            if (!template || !resultsGrid) return;

            ads.forEach((ad) => {
                const clone = template.content.cloneNode(true);
                const card = clone.querySelector('[data-ad-id]');
                card.dataset.adId = ad.id || ad.ad_archive_id || '';
                card.querySelector('h4').textContent = ad.page_name || 'Unknown page';
                card.querySelector('h4').nextElementSibling.textContent = ad.ad_delivery_start_time
                    ? new Date(ad.ad_delivery_start_time).toLocaleDateString()
                    : '';
                const bodyEl = card.querySelector('.line-clamp-2');
                bodyEl.textContent = ad.ad_creative_body || (Array.isArray(ad.ad_creative_bodies) ? ad.ad_creative_bodies[0] : '') || '';
                const platforms = ad.publisher_platforms || [];
                card.querySelector('.platforms').textContent = platforms.length ? platforms.join(', ') : '—';
                const link = card.querySelector('.ad-snapshot-link');
                link.href = ad.ad_snapshot_url || '#';
                link.style.display = ad.ad_snapshot_url ? '' : 'none';
                const saveBtn = card.querySelector('.btn-save-to-collection');
                saveBtn.dataset.ad = JSON.stringify(ad);
                saveBtn.addEventListener('click', () => openSaveToCollectionModal(ad));
                resultsGrid.appendChild(clone);
            });

            resultsGrid.classList.remove('hidden');
            if (nextCursor) {
                loadMore?.classList.remove('hidden');
            } else {
                loadMore?.classList.add('hidden');
            }
        },
        onError: (err) => {
            resultsLoading?.classList.add('hidden');
            showResultsError(err?.message || 'Search failed.');
        },
    });
}

function showResultsError(msg) {
    const el = document.getElementById('results-error');
    if (el) {
        el.textContent = msg;
        el.classList.remove('hidden');
    }
    document.getElementById('results-grid')?.classList.add('hidden');
    document.getElementById('results-placeholder')?.classList.add('hidden');
}

function loadCollections() {
    window.ajaxRequest({
        method: 'GET',
        url: `${AD_LIBRARY_BASE}/collections`,
        onSuccess: (res) => {
            collections = res.data || [];
            renderCollections();
        },
    });
}

function renderCollections() {
    const listEl = document.getElementById('collections-list');
    if (!listEl) return;
    if (!collections.length) {
        listEl.innerHTML = '<p class="text-sm text-gray-500 dark:text-gray-400">No collections yet.</p>';
        return;
    }
    listEl.innerHTML = collections
        .map(
            (c) =>
                `<button type="button" class="collection-item w-full text-left px-3 py-2 rounded-lg text-sm hover:bg-gray-100 dark:hover:bg-[#1c1c1a] ${selectedCollectionId === c.id ? 'bg-indigo-50 dark:bg-indigo-950/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-700 dark:text-gray-300'}" data-id="${c.id}">
                    ${escapeHtml(c.name)} (${c.items_count ?? 0})
                </button>`
        )
        .join('');
    listEl.querySelectorAll('.collection-item').forEach((btn) => {
        btn.addEventListener('click', () => selectCollection(Number(btn.dataset.id)));
    });
}

function selectCollection(id) {
    selectedCollectionId = id;
    renderCollections();
    loadCollectionItems(id);
}

function loadCollectionItems(collectionId) {
    const listEl = document.getElementById('collection-items-list');
    if (!listEl) return;

    window.ajaxRequest({
        method: 'GET',
        url: `${AD_LIBRARY_BASE}/collections/${collectionId}/items`,
        onSuccess: (res) => {
            const items = res.data || [];
            if (!items.length) {
                listEl.innerHTML = '<p class="text-sm text-gray-500 dark:text-gray-400">No items in this collection.</p>';
                return;
            }
            listEl.innerHTML = items
                .map(
                    (i) => `
                    <div class="flex items-center justify-between gap-2 py-2 border-b border-gray-100 dark:border-[#252523] last:border-0">
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">${escapeHtml(i.page_name || 'Ad')}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">${escapeHtml((i.ad_creative_body || '').slice(0, 60))}…</p>
                        </div>
                        <div class="flex gap-1 shrink-0">
                            ${i.snapshot_url ? `<a href="${escapeHtml(i.snapshot_url)}" target="_blank" rel="noopener" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline">Open</a>` : ''}
                            <button type="button" class="btn-remove-item text-xs text-red-600 dark:text-red-400 hover:underline" data-item-id="${i.id}">Remove</button>
                        </div>
                    </div>
                `
                )
                .join('');
            listEl.querySelectorAll('.btn-remove-item').forEach((btn) => {
                btn.addEventListener('click', () => removeCollectionItem(collectionId, Number(btn.dataset.itemId)));
            });
        },
        onError: () => {
            listEl.innerHTML = '<p class="text-sm text-red-600 dark:text-red-400">Failed to load items.</p>';
        },
    });
}

function removeCollectionItem(collectionId, itemId) {
    window.ajaxRequest({
        method: 'DELETE',
        url: `${AD_LIBRARY_BASE}/collections/${collectionId}/items/${itemId}`,
        onSuccess: (res) => {
            window.showToast('success', res.message || 'Item removed.');
            if (selectedCollectionId === collectionId) {
                loadCollectionItems(collectionId);
            }
            loadCollections();
        },
    });
}

function openSaveToCollectionModal(ad) {
    if (!collections.length) {
        window.showToast('warning', 'Create a collection first.');
        return;
    }
    const name = window.prompt('Select collection (enter name or number):', collections[0]?.name);
    if (!name) return;
    const collection = collections.find((c) => c.name.toLowerCase() === name.toLowerCase() || String(c.id) === name);
    if (!collection) {
        window.showToast('error', 'Collection not found.');
        return;
    }
    saveAdToCollection(collection.id, ad);
}

function saveAdToCollection(collectionId, ad) {
    window.ajaxRequest({
        method: 'POST',
        url: `${AD_LIBRARY_BASE}/collections/${collectionId}/items`,
        data: {
            ad_archive_id: ad.id || ad.ad_archive_id,
            snapshot_url: ad.ad_snapshot_url,
            page_name: ad.page_name,
            ad_creative_body: ad.ad_creative_body || (Array.isArray(ad.ad_creative_bodies) ? ad.ad_creative_bodies[0] : null),
            page_id: ad.page_id,
            publisher_platforms: ad.publisher_platforms,
            ad_delivery_start_time: ad.ad_delivery_start_time,
        },
        onSuccess: (res) => {
            window.showToast('success', res.message || 'Ad saved to collection.');
            loadCollections();
            if (selectedCollectionId === collectionId) {
                loadCollectionItems(collectionId);
            }
            const btn = document.querySelector(`[data-ad-id="${ad.id || ad.ad_archive_id}"] .btn-save-to-collection`);
            if (btn) btn.textContent = 'Saved';
        },
    });
}

function createCollection(form, submitBtn) {
    const input = document.getElementById('new-collection-name');
    const name = input?.value?.trim();
    if (!name) return;

    window.ajaxRequest({
        method: 'POST',
        url: `${AD_LIBRARY_BASE}/collections`,
        data: { name },
        onSuccess: (res) => {
            window.showToast('success', res.message || 'Collection created.');
            if (input) input.value = '';
            loadCollections();
        },
        onError: () => {},
        onFinally: () => {
            window.setLoading(submitBtn, false);
        },
    });
}

function saveSearch() {
    const params = getSearchParams();
    if (!params.query || !params.countries?.length) {
        window.showToast('warning', 'Enter keywords and select a country to save the search.');
        return;
    }

    window.ajaxRequest({
        method: 'POST',
        url: `${AD_LIBRARY_BASE}/searches`,
        data: {
            query: params.query,
            countries: params.countries,
            ad_active_status: params.ad_active_status,
        },
        onSuccess: (res) => {
            window.showToast('success', res.message || 'Search saved.');
        },
    });
}

document.addEventListener('DOMContentLoaded', () => {
    loadConfig();
    loadCollections();

    document.getElementById('ad-library-search-form')?.addEventListener('submit', (e) => {
        e.preventDefault();
        runSearch();
    });

    document.getElementById('btn-load-more')?.addEventListener('click', () => {
        if (nextCursor) runSearch(nextCursor);
    });

    document.getElementById('btn-save-search')?.addEventListener('click', (e) => {
        e.preventDefault();
        saveSearch();
    });

    const createForm = document.getElementById('create-collection-form');
    if (createForm) {
        createForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const submitBtn = createForm.querySelector('button[type="submit"]');
            window.setLoading(submitBtn, true);
            createCollection(createForm, submitBtn);
        });
    }
});
