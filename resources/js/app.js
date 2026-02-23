import './bootstrap';
import './app-ajax';
import './theme-toggle';
import './toast';

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
    window.openModal = openModal;
    window.closeModal = closeModal;
    document.querySelectorAll('[data-modal-backdrop]').forEach((el) => {
        el.addEventListener('click', () => {
            const modal = el.closest('[id]');
            if (modal) modal.classList.add('hidden');
        });
    });
});
