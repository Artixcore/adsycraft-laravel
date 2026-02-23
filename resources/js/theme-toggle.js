/**
 * Theme toggle: class-based dark mode using localStorage + system preference.
 * Apply .dark to <html> for dark mode. Run early to avoid FOUC.
 */
function initTheme() {
    const isDark =
        localStorage.theme === 'dark' ||
        (!('theme' in localStorage) &&
            window.matchMedia('(prefers-color-scheme: dark)').matches);

    document.documentElement.classList.toggle('dark', isDark);
}

function setTheme(theme) {
    if (theme === 'dark') {
        localStorage.theme = 'dark';
        document.documentElement.classList.add('dark');
    } else if (theme === 'light') {
        localStorage.theme = 'light';
        document.documentElement.classList.remove('dark');
    } else {
        localStorage.removeItem('theme');
        initTheme();
    }
}

function toggleTheme() {
    const isDark = document.documentElement.classList.contains('dark');
    setTheme(isDark ? 'light' : 'dark');
}

// Run immediately (before DOM ready) to prevent FOUC
initTheme();

// Listen for system preference changes when no explicit choice
window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
    if (!('theme' in localStorage)) {
        document.documentElement.classList.toggle('dark', e.matches);
    }
});

document.addEventListener('DOMContentLoaded', () => {
    window.setTheme = setTheme;
    window.toggleTheme = toggleTheme;
    document.querySelectorAll('[data-theme-toggle]').forEach((btn) => {
        btn.addEventListener('click', toggleTheme);
    });
});
