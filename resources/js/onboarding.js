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

let currentStep = 1;
let createdBusinessId = null;

function showStep(step) {
    currentStep = step;
    document.querySelectorAll('.onboarding-panel').forEach((el) => el.classList.add('hidden'));
    const panel = document.getElementById(`step-${step}`);
    if (panel) panel.classList.remove('hidden');

    document.querySelectorAll('.onboarding-step').forEach((el) => {
        const s = Number(el.dataset.step);
        el.classList.remove('border-indigo-600', 'bg-indigo-600', 'text-white');
        el.classList.add('border-zinc-300', 'dark:border-[#3E3E3A]', 'text-zinc-500', 'dark:text-zinc-400');
        if (s < step) {
            el.classList.remove('border-zinc-300', 'dark:border-[#3E3E3A]', 'text-zinc-500', 'dark:text-zinc-400');
            el.classList.add('border-green-500', 'bg-green-500', 'text-white');
        } else if (s === step) {
            el.classList.remove('border-zinc-300', 'dark:border-[#3E3E3A]', 'text-zinc-500', 'dark:text-zinc-400');
            el.classList.add('border-indigo-600', 'bg-indigo-600', 'text-white');
        }
    });
}

async function createBusiness(payload) {
    const res = await apiFetch(`${API_BASE}/businesses`, {
        method: 'POST',
        body: JSON.stringify(payload),
    });
    const data = await res.json().catch(() => ({}));
    if (!res.ok) {
        throw new Error(data.message || 'Failed to create business.');
    }
    return data.data;
}

function init() {
    document.getElementById('btn-step-1-next')?.addEventListener('click', () => showStep(2));

    document.getElementById('btn-step-2-skip')?.addEventListener('click', () => showStep(5));

    document.getElementById('onboarding-business-form')?.addEventListener('submit', async (e) => {
        e.preventDefault();
        const form = e.target;
        const msgEl = document.getElementById('onboarding-create-message');
        msgEl.classList.add('hidden');
        try {
            const payload = {
                name: form.name.value.trim(),
                niche: form.niche.value.trim() || null,
                website_url: null,
                tone: null,
                language: null,
                posts_per_day: parseInt(form.posts_per_day.value, 10) || 1,
                timezone: form.timezone.value.trim() || 'UTC',
                autopilot_enabled: form.autopilot_enabled.checked,
            };
            const business = await createBusiness(payload);
            createdBusinessId = business?.id;
            if (createdBusinessId) {
                sessionStorage.setItem('meta_connector_business_id', String(createdBusinessId));
            }
            showStep(3);
        } catch (err) {
            msgEl.textContent = err.message || 'Failed to create business.';
            msgEl.classList.remove('hidden');
        }
    });

    document.getElementById('btn-step-3-skip')?.addEventListener('click', () => showStep(4));

    const connectMetaBtn = document.getElementById('btn-connect-meta');
    if (connectMetaBtn && createdBusinessId) {
        connectMetaBtn.href = `${connectMetaBtn.href}?business=${createdBusinessId}`;
    }
    connectMetaBtn?.addEventListener('click', (e) => {
        if (!createdBusinessId) {
            e.preventDefault();
            showStep(4);
        }
    });

    document.getElementById('btn-step-4-skip')?.addEventListener('click', () => showStep(5));

    const addAiBtn = document.getElementById('btn-add-ai');
    if (addAiBtn && createdBusinessId) {
        addAiBtn.href = `${addAiBtn.href}?business=${createdBusinessId}`;
    }
    addAiBtn?.addEventListener('click', (e) => {
        if (!createdBusinessId) {
            e.preventDefault();
            showStep(5);
        }
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
} else {
    init();
}
