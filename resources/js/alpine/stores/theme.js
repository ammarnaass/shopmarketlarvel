/* ====================================================================
   THEME STORE — Dark / Light mode with persistence
   Usage: <html :class="$store.theme.dark && 'dark'">
   ==================================================================== */

document.addEventListener('alpine:init', () => {
    Alpine.store('theme', {
        dark: Alpine.$persist(false).as('amar:theme').using(localStorage),

        init() {
            // Apply persisted theme on load
            this._apply();
            // Watch for system preference changes
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
                if (this.dark === null) {
                    this.setDark(e.matches);
                }
            });
        },

        toggle() {
            this.dark = !this.dark;
            this._apply();
        },

        setDark(value) {
            this.dark = value;
            this._apply();
        },

        _apply() {
            const html = document.documentElement;
            if (this.dark) {
                html.classList.add('dark');
            } else {
                html.classList.remove('dark');
            }
        },
    });
});
