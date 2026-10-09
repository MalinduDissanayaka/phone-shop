// Grid / list view preference for collection pages, remembered per page.
// Usage: <div x-data="viewMode('inventory.products')"> ... <x-view-toggle /> ... </div>

const MODES = ['grid', 'list'];

export default function registerViewMode(Alpine) {
    Alpine.data('viewMode', (key, fallback = 'grid') => ({
        view: fallback,

        init() {
            const storageKey = `view-mode:${key}`;

            try {
                const saved = localStorage.getItem(storageKey);
                if (MODES.includes(saved)) {
                    this.view = saved;
                }
            } catch {
                // Storage unavailable; keep the fallback.
            }

            this.$watch('view', (value) => {
                try {
                    localStorage.setItem(storageKey, value);
                } catch {
                    // Ignore; the choice lasts for this page only.
                }
            });
        },
    }));
}
