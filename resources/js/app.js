import './bootstrap';

/**
 * Toast notifications: call window.showToast('Message', 'success'|'error'|'warning'|'info')
 */
function showToast(message, type = 'info') {
    const container = document.getElementById('toast-container');
    if (!container) return;
    const toast = document.createElement('div');
    toast.setAttribute('role', 'alert');
    const bg = { success: 'bg-green-600', error: 'bg-red-600', warning: 'bg-amber-600', info: 'bg-indigo-600' }[type] || 'bg-indigo-600';
    toast.className = `pointer-events-auto rounded-lg px-4 py-3 text-sm text-white shadow-lg ${bg}`;
    toast.textContent = message;
    container.appendChild(toast);
    setTimeout(() => toast.remove(), 4000);
}

/**
 * Modal: call window.openModal('modal-id') and window.closeModal('modal-id')
 */
function openModal(id) {
    const el = document.getElementById(id);
    if (el) el.classList.remove('hidden');
}

function closeModal(id) {
    const el = document.getElementById(id);
    if (el) el.classList.add('hidden');
}

document.addEventListener('DOMContentLoaded', () => {
    window.showToast = showToast;
    window.openModal = openModal;
    window.closeModal = closeModal;
    document.querySelectorAll('[data-modal-backdrop]').forEach((el) => {
        el.addEventListener('click', () => {
            const modal = el.closest('[id]');
            if (modal) modal.classList.add('hidden');
        });
    });
});
