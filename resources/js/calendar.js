const API_BASE = '/api';

function escapeHtml(text) {
    if (text == null) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

let selectedBusinessId = null;
let businesses = [];
let openPanelDate = null;
let currentYear = new Date().getFullYear();
let currentMonth = new Date().getMonth();
let viewMode = 'month';
let calendarData = { days: {}, posts_per_day: 1 };

function getMonthStart(year, month) {
    return new Date(year, month, 1);
}

function getMonthEnd(year, month) {
    return new Date(year, month + 1, 0);
}

function getWeekStart(date) {
    const d = new Date(date);
    const day = d.getDay();
    d.setDate(d.getDate() - day);
    return d;
}

function formatMonthYear(year, month) {
    return new Date(year, month).toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
}

function toYMD(d) {
    const y = d.getFullYear();
    const m = String(d.getMonth() + 1).padStart(2, '0');
    const day = String(d.getDate()).padStart(2, '0');
    return `${y}-${m}-${day}`;
}

function renderBusinessSelector() {
    const container = document.getElementById('business-selector');
    if (!container) return;
    if (!businesses.length) {
        container.innerHTML = '<p class="text-sm text-zinc-500 dark:text-zinc-400">No businesses. Create one from the Dashboard.</p>';
        return;
    }
    container.innerHTML = `
        <select id="business-select" class="rounded-xl border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 px-3 py-2 text-sm">
            <option value="">Choose a business…</option>
            ${businesses.map((b) => `<option value="${b.id}">${escapeHtml(b.name)}</option>`).join('')}
        </select>
    `;
    document.getElementById('business-select').addEventListener('change', (e) => {
        const id = e.target.value ? Number(e.target.value) : null;
        selectedBusinessId = id;
        if (id) {
            document.getElementById('calendar-content').classList.remove('hidden');
            document.getElementById('calendar-empty').classList.add('hidden');
            loadCalendar(id);
        } else {
            document.getElementById('calendar-content').classList.add('hidden');
            document.getElementById('calendar-empty').classList.remove('hidden');
        }
    });
}

function loadBusinesses() {
    window.ajaxRequest({
        method: 'GET',
        url: `${API_BASE}/businesses`,
        onSuccess: (res) => {
            businesses = res.data || [];
            renderBusinessSelector();
            const firstId = businesses[0]?.id;
            const contentEl = document.getElementById('calendar-content');
            const emptyEl = document.getElementById('calendar-empty');
            if (firstId) {
                selectedBusinessId = firstId;
                document.getElementById('business-select').value = firstId;
                if (contentEl) contentEl.classList.remove('hidden');
                if (emptyEl) emptyEl.classList.add('hidden');
                loadCalendar(firstId);
            } else {
                if (contentEl) contentEl.classList.add('hidden');
                if (emptyEl) emptyEl.classList.remove('hidden');
            }
        },
        onError: () => {
            const el = document.getElementById('business-selector');
            if (el) el.innerHTML = '<p class="text-sm text-red-600">Failed to load businesses.</p>';
        },
    });
}

function loadCalendar(businessId) {
    const loading = document.getElementById('calendar-loading');
    const grid = document.getElementById('calendar-grid');
    if (loading) loading.classList.remove('hidden');
    if (grid) grid.classList.add('hidden');

    const monthParam = `${currentYear}-${String(currentMonth + 1).padStart(2, '0')}`;
    const url = `${API_BASE}/businesses/${businessId}/calendar?month=${monthParam}`;

    window.ajaxRequest({
        method: 'GET',
        url,
        onSuccess: (res) => {
            calendarData = res.data || { days: {}, posts_per_day: 1 };
            renderCalendar();
            if (loading) loading.classList.add('hidden');
            if (grid) grid.classList.remove('hidden');
        },
        onError: () => {
            if (loading) loading.classList.add('hidden');
            window.showToast('error', 'Failed to load calendar.');
        },
    });
}

function renderCalendar() {
    const cellsEl = document.getElementById('calendar-cells');
    const titleEl = document.getElementById('calendar-title');
    if (!cellsEl || !titleEl) return;

    titleEl.textContent = formatMonthYear(currentYear, currentMonth);

    let startDate;
    let endDate;
    if (viewMode === 'week') {
        const firstOfMonth = getMonthStart(currentYear, currentMonth);
        startDate = getWeekStart(firstOfMonth);
        endDate = new Date(startDate);
        endDate.setDate(endDate.getDate() + 6);
    } else {
        startDate = getMonthStart(currentYear, currentMonth);
        endDate = getMonthEnd(currentYear, currentMonth);
        const startDay = startDate.getDay();
        startDate.setDate(startDate.getDate() - startDay);
        const endDay = endDate.getDay();
        endDate.setDate(endDate.getDate() + (6 - endDay));
    }

    const cells = [];
    const d = new Date(startDate);

    while (d <= endDate) {
        const ymd = toYMD(d);
        const dayData = calendarData.days[ymd] || { total: 0, scheduled: 0, published: 0, failed: 0 };
        const isCurrentMonth = d.getMonth() === currentMonth;
        const isToday = ymd === toYMD(new Date());

        let dotHtml = '';
        if (dayData.total > 0) {
            const dots = [];
            if (dayData.scheduled) dots.push('<span class="inline-block w-1.5 h-1.5 rounded-full bg-blue-500" title="Scheduled"></span>');
            if (dayData.published) dots.push('<span class="inline-block w-1.5 h-1.5 rounded-full bg-green-500" title="Published"></span>');
            if (dayData.failed) dots.push('<span class="inline-block w-1.5 h-1.5 rounded-full bg-red-500" title="Failed"></span>');
            dotHtml = `<div class="flex justify-center gap-0.5 mt-1">${dots.join('')}</div>`;
        }

        cells.push(`
            <button type="button" data-date="${ymd}" class="calendar-cell min-h-[80px] sm:min-h-[100px] p-2 text-left bg-white dark:bg-zinc-900 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition ${!isCurrentMonth ? 'text-zinc-400 dark:text-zinc-500' : 'text-zinc-900 dark:text-white'} ${isToday ? 'ring-2 ring-indigo-500 ring-inset' : ''}">
                <span class="text-sm font-medium">${d.getDate()}</span>
                ${dayData.total > 0 ? `<span class="ml-1 text-xs text-zinc-500 dark:text-zinc-400">(${dayData.total})</span>` : ''}
                ${dotHtml}
            </button>
        `);
        d.setDate(d.getDate() + 1);
    }

    cellsEl.innerHTML = cells.join('');
    cellsEl.querySelectorAll('.calendar-cell').forEach((btn) => {
        btn.addEventListener('click', () => openDayPanel(btn.dataset.date));
    });
}

function openDayPanel(dateStr) {
    openPanelDate = dateStr;
    const panel = document.getElementById('day-panel');
    const backdrop = document.getElementById('day-panel-backdrop');
    if (panel) panel.classList.remove('translate-x-full');
    if (backdrop) backdrop.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
    loadDayDetail(dateStr);
}

function closeDayPanel() {
    openPanelDate = null;
    const panel = document.getElementById('day-panel');
    const backdrop = document.getElementById('day-panel-backdrop');
    if (panel) panel.classList.add('translate-x-full');
    if (backdrop) backdrop.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

function loadDayDetail(dateStr) {
    if (!selectedBusinessId) return;

    const titleEl = document.getElementById('day-panel-title');
    const loadingEl = document.getElementById('day-panel-loading');
    const bodyEl = document.getElementById('day-panel-body');

    if (titleEl) titleEl.textContent = new Date(dateStr + 'T12:00:00').toLocaleDateString('en-US', { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' });
    if (loadingEl) loadingEl.classList.remove('hidden');
    if (bodyEl) bodyEl.classList.add('hidden');

    const url = `${API_BASE}/businesses/${selectedBusinessId}/calendar/day?date=${dateStr}`;

    window.ajaxRequest({
        method: 'GET',
        url,
        onSuccess: (res) => {
            renderDayPanel(res.data, dateStr);
            if (loadingEl) loadingEl.classList.add('hidden');
            if (bodyEl) bodyEl.classList.remove('hidden');
        },
        onError: () => {
            if (loadingEl) loadingEl.classList.add('hidden');
            window.showToast('error', 'Failed to load day details.');
        },
    });
}

function qualityScoreClass(score) {
    if (score == null) return 'text-zinc-500';
    if (score >= 70) return 'text-green-600 dark:text-green-400';
    if (score >= 40) return 'text-amber-600 dark:text-amber-400';
    return 'text-red-600 dark:text-red-400';
}

function renderDayPanel(data, dateStr) {
    const bodyEl = document.getElementById('day-panel-body');
    if (!bodyEl) return;

    const s = data.summary || {};
    const eng = data.engagement || {};
    const posts = data.posts || [];

    const fmt = (n) => (n != null && n > 0 ? n.toLocaleString() : '—');

    let html = `
        <div class="flex items-center justify-between">
            <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Status</span>
            <div class="flex gap-2 text-sm">
                <span>Total: ${s.total ?? 0}</span>
                <span class="text-blue-600 dark:text-blue-400">Scheduled: ${s.scheduled ?? 0}</span>
                <span class="text-green-600 dark:text-green-400">Published: ${s.published ?? 0}</span>
                <span class="text-red-600 dark:text-red-400">Failed: ${s.failed ?? 0}</span>
            </div>
        </div>

        <div class="rounded-xl border border-zinc-200 dark:border-zinc-800 p-4 space-y-2">
            <h4 class="text-sm font-semibold text-zinc-900 dark:text-white">Engagement</h4>
            <div class="grid grid-cols-2 lg:grid-cols-3 gap-2 text-sm">
                <div><span class="text-zinc-500">Reach</span> <span class="font-medium">${fmt(eng.reach)}</span></div>
                <div><span class="text-zinc-500">Impressions</span> <span class="font-medium">${fmt(eng.impressions)}</span></div>
                <div><span class="text-zinc-500">Likes</span> <span class="font-medium">${fmt(eng.likes)}</span></div>
                <div><span class="text-zinc-500">Comments</span> <span class="font-medium">${fmt(eng.comments)}</span></div>
                <div><span class="text-zinc-500">Shares</span> <span class="font-medium">${fmt(eng.shares)}</span></div>
                <div><span class="text-zinc-500">Saves</span> <span class="font-medium">${fmt(eng.saves)}</span></div>
                <div><span class="text-zinc-500">Eng. rate</span> <span class="font-medium">${eng.engagement_rate != null ? eng.engagement_rate + '%' : '—'}</span></div>
            </div>
        </div>

        <div class="flex items-center justify-between">
            <h4 class="text-sm font-semibold text-zinc-900 dark:text-white">Generate AI Posts</h4>
            <span class="text-xs text-zinc-500">${calendarData.posts_per_day || 1} per day configured</span>
        </div>
        <div class="flex flex-wrap gap-2">
            <input type="number" id="generate-count" min="1" max="20" value="${calendarData.posts_per_day || 1}" class="w-16 rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 px-2 py-1.5 text-sm">
            <button type="button" id="btn-generate-day" data-date="${dateStr}" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Generate</button>
        </div>

        <div>
            <h4 class="text-sm font-semibold text-zinc-900 dark:text-white mb-2">Posts</h4>
            <div id="day-panel-posts" class="space-y-3">
    `;

    if (!posts.length) {
        html += '<p class="text-sm text-zinc-500 dark:text-zinc-400">No posts for this day.</p>';
    } else {
        posts.forEach((p) => {
            const timeStr = p.scheduled_at ? new Date(p.scheduled_at).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' }) : '—';
            const statusBadge = p.status === 'published' ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200' : p.status === 'scheduled' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200' : p.status === 'failed' ? 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-200' : 'bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300';
            const reachStr = p.metrics?.reach != null ? p.metrics.reach.toLocaleString() : '—';
            const scoreClass = qualityScoreClass(p.quality_score);
            const scoreVal = p.quality_score != null ? p.quality_score : '—';

            html += `
                <div class="rounded-xl border border-zinc-200 dark:border-zinc-800 p-4 space-y-2" data-post-id="${p.id}">
                    <p class="text-sm text-zinc-700 dark:text-zinc-300 line-clamp-2">${escapeHtml(p.caption || '')}</p>
                    <div class="flex flex-wrap items-center gap-2 text-xs">
                        <span class="rounded-full px-2 py-0.5 font-medium ${statusBadge}">${escapeHtml(p.status)}</span>
                        <span>${timeStr}</span>
                        <span>Reach: ${reachStr}</span>
                        <span class="${scoreClass}">Score: ${scoreVal}</span>
                    </div>
                    <div class="flex flex-wrap gap-2 pt-2">
                        <button type="button" class="edit-post-btn rounded-lg border border-zinc-300 dark:border-zinc-600 px-2 py-1 text-xs hover:bg-zinc-50 dark:hover:bg-zinc-800" data-post-id="${p.id}">Edit</button>
                        <button type="button" class="regenerate-post-btn rounded-lg border border-zinc-300 dark:border-zinc-600 px-2 py-1 text-xs hover:bg-zinc-50 dark:hover:bg-zinc-800" data-post-id="${p.id}">Regenerate AI</button>
                        <button type="button" class="delete-post-btn rounded-lg border border-red-200 dark:border-red-800 px-2 py-1 text-xs text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20" data-post-id="${p.id}">Delete</button>
                    </div>
                </div>
            `;
        });
    }

    html += '</div></div>';
    bodyEl.innerHTML = html;

    document.getElementById('btn-generate-day')?.addEventListener('click', (e) => {
        const date = e.target.dataset.date;
        const countInput = document.getElementById('generate-count');
        const count = countInput ? parseInt(countInput.value, 10) : undefined;
        generateDay(date, count, e.target);
    });

    bodyEl.querySelectorAll('.edit-post-btn').forEach((btn) => {
        btn.addEventListener('click', () => fetchPostAndOpenEditModal(btn.dataset.postId));
    });
    bodyEl.querySelectorAll('.regenerate-post-btn').forEach((btn) => {
        btn.addEventListener('click', () => regeneratePost(btn.dataset.postId, btn));
    });
    bodyEl.querySelectorAll('.delete-post-btn').forEach((btn) => {
        btn.addEventListener('click', () => deletePost(btn.dataset.postId));
    });
}

function generateDay(dateStr, count, btn) {
    if (!selectedBusinessId) return;

    window.setLoading(btn, true);

    window.ajaxRequest({
        method: 'POST',
        url: `${API_BASE}/businesses/${selectedBusinessId}/calendar/generate-day`,
        data: { date: dateStr, count: count || undefined },
        onSuccess: (res) => {
            window.showToast('success', res.message || 'Generation started.');
            loadCalendar(selectedBusinessId);
            loadDayDetail(dateStr);
        },
        onError: () => {
            window.showToast('error', 'Failed to generate.');
        },
        onFinally: () => {
            window.setLoading(btn, false);
        },
    });
}

function deletePost(postId) {
    if (!selectedBusinessId || !window.confirm('Delete this post? This cannot be undone.')) return;

    window.ajaxRequest({
        method: 'DELETE',
        url: `${API_BASE}/businesses/${selectedBusinessId}/posts/${postId}`,
        onSuccess: (res) => {
            window.showToast('success', res.message || 'Post deleted.');
            const dateStr = getOpenPanelDate();
            if (dateStr) loadDayDetail(dateStr);
            loadCalendar(selectedBusinessId);
        },
        onError: () => {
            window.showToast('error', 'Failed to delete.');
        },
    });
}

function getOpenPanelDate() {
    return openPanelDate;
}

function regeneratePost(postId, btn) {
    if (!selectedBusinessId) return;

    window.setLoading(btn, true);

    window.ajaxRequest({
        method: 'POST',
        url: `${API_BASE}/businesses/${selectedBusinessId}/posts/${postId}/regenerate`,
        onSuccess: (res) => {
            window.showToast('success', res.message || 'Caption regenerated.');
            const dateStr = getOpenPanelDate();
            if (dateStr) loadDayDetail(dateStr);
        },
        onError: () => {
            window.showToast('error', 'Failed to regenerate.');
        },
        onFinally: () => {
            window.setLoading(btn, false);
        },
    });
}

function fetchPostAndOpenEditModal(postId) {
    if (!selectedBusinessId) return;

    window.ajaxRequest({
        method: 'GET',
        url: `${API_BASE}/businesses/${selectedBusinessId}/posts/${postId}`,
        onSuccess: (res) => {
            const post = res.data;
            openEditModal(postId, post?.caption || '');
        },
        onError: () => {
            window.showToast('error', 'Failed to load post.');
        },
    });
}

function openEditModal(postId, caption) {
    const modal = document.getElementById('edit-post-modal');
    const idInput = document.getElementById('edit-post-id');
    const captionInput = document.getElementById('edit-post-caption');
    if (modal && idInput && captionInput) {
        idInput.value = postId;
        captionInput.value = caption || '';
        modal.classList.remove('hidden');
    }
}

function closeEditModal() {
    const modal = document.getElementById('edit-post-modal');
    if (modal) modal.classList.add('hidden');
}

function saveEditPost(e) {
    e.preventDefault();
    const idInput = document.getElementById('edit-post-id');
    const captionInput = document.getElementById('edit-post-caption');
    const submitBtn = document.getElementById('edit-post-submit');
    if (!selectedBusinessId || !idInput?.value) return;

    window.setLoading(submitBtn, true);

    window.ajaxRequest({
        method: 'PUT',
        url: `${API_BASE}/businesses/${selectedBusinessId}/posts/${idInput.value}`,
        data: { caption: captionInput?.value || '' },
        onSuccess: (res) => {
            window.showToast('success', res.message || 'Post updated.');
            closeEditModal();
            const dateStr = getOpenPanelDate();
            if (dateStr) loadDayDetail(dateStr);
            loadCalendar(selectedBusinessId);
        },
        onError: () => {
            window.showToast('error', 'Failed to update.');
        },
        onFinally: () => {
            window.setLoading(submitBtn, false);
        },
    });
}

function init() {
    loadBusinesses();

    document.getElementById('btn-prev-month')?.addEventListener('click', () => {
        currentMonth--;
        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear--;
        }
        if (selectedBusinessId) loadCalendar(selectedBusinessId);
    });

    document.getElementById('btn-next-month')?.addEventListener('click', () => {
        currentMonth++;
        if (currentMonth > 11) {
            currentMonth = 0;
            currentYear++;
        }
        if (selectedBusinessId) loadCalendar(selectedBusinessId);
    });

    document.getElementById('btn-view-month')?.addEventListener('click', () => {
        viewMode = 'month';
        document.getElementById('btn-view-month').classList.add('border-indigo-300', 'bg-indigo-50', 'dark:bg-indigo-950/50', 'text-indigo-700', 'dark:text-indigo-300');
        document.getElementById('btn-view-month').classList.remove('border-zinc-300', 'dark:border-zinc-600', 'bg-white', 'dark:bg-zinc-900');
        document.getElementById('btn-view-week').classList.remove('border-indigo-300', 'bg-indigo-50', 'dark:bg-indigo-950/50', 'text-indigo-700', 'dark:text-indigo-300');
        document.getElementById('btn-view-week').classList.add('border-zinc-300', 'dark:border-zinc-600', 'bg-white', 'dark:bg-zinc-900');
        if (selectedBusinessId) loadCalendar(selectedBusinessId);
    });

    document.getElementById('btn-view-week')?.addEventListener('click', () => {
        viewMode = 'week';
        document.getElementById('btn-view-week').classList.add('border-indigo-300', 'bg-indigo-50', 'dark:bg-indigo-950/50', 'text-indigo-700', 'dark:text-indigo-300');
        document.getElementById('btn-view-week').classList.remove('border-zinc-300', 'dark:border-zinc-600', 'bg-white', 'dark:bg-zinc-900');
        document.getElementById('btn-view-month').classList.remove('border-indigo-300', 'bg-indigo-50', 'dark:bg-indigo-950/50', 'text-indigo-700', 'dark:text-indigo-300');
        document.getElementById('btn-view-month').classList.add('border-zinc-300', 'dark:border-zinc-600', 'bg-white', 'dark:bg-zinc-900');
        if (selectedBusinessId) loadCalendar(selectedBusinessId);
    });

    document.getElementById('day-panel-close')?.addEventListener('click', closeDayPanel);
    document.getElementById('day-panel-backdrop')?.addEventListener('click', closeDayPanel);

    document.getElementById('edit-post-cancel')?.addEventListener('click', closeEditModal);
    document.getElementById('edit-post-modal')?.querySelector('[data-modal-backdrop]')?.addEventListener('click', closeEditModal);
    document.getElementById('edit-post-form')?.addEventListener('submit', saveEditPost);
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
} else {
    init();
}
