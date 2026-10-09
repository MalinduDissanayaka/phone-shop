// Light / dark theme handling.
// The initial class is applied by the inline script in layouts/partials/theme-init
// (before first paint); this module keeps it in sync afterwards.

const STORAGE_KEY = 'theme';
const root = document.documentElement;
const systemDark = window.matchMedia('(prefers-color-scheme: dark)');

function storedTheme() {
    try {
        return localStorage.getItem(STORAGE_KEY);
    } catch {
        return null;
    }
}

function applyTheme(dark) {
    root.classList.add('theme-switching');
    root.classList.toggle('dark', dark);
    // Re-enable transitions once the new colors have painted.
    requestAnimationFrame(() => requestAnimationFrame(() => root.classList.remove('theme-switching')));
}

export default function registerTheme(Alpine) {
    Alpine.store('theme', {
        dark: root.classList.contains('dark'),

        toggle() {
            this.dark = !this.dark;
            try {
                localStorage.setItem(STORAGE_KEY, this.dark ? 'dark' : 'light');
            } catch {
                // Storage unavailable (private mode); the choice lasts for this page only.
            }
            applyTheme(this.dark);
        },
    });

    // Pages that force a theme (e.g. the auth screens) opt out of syncing.
    if (root.hasAttribute('data-theme-locked')) {
        return;
    }

    // Follow the OS setting until the user makes an explicit choice.
    systemDark.addEventListener('change', (event) => {
        if (storedTheme()) {
            return;
        }
        Alpine.store('theme').dark = event.matches;
        applyTheme(event.matches);
    });
}
