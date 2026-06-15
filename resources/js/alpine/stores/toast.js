/* ====================================================================
   TOAST STORE — Global notification system
   Usage: <div x-data x-text="$store.toast.last()"></div>
   Show: $store.toast.show('Hello', 'success')
   ==================================================================== */

document.addEventListener('alpine:init', () => {
    Alpine.store('toast', {
        items: [],
        history: [],

        show(message, type = 'success', duration = 3500) {
            const id = Date.now() + Math.random();
            this.items.push({ id, message, type, duration });
            this.history.unshift({ id, message, type, time: new Date() });
            if (this.history.length > 20) this.history.pop();

            setTimeout(() => {
                this.dismiss(id);
            }, duration);
        },

        success(message, duration = 3500) {
            this.show(message, 'success', duration);
        },

        error(message, duration = 5000) {
            this.show(message, 'error', duration);
        },

        warning(message, duration = 4500) {
            this.show(message, 'warning', duration);
        },

        info(message, duration = 4500) {
            this.show(message, 'info', duration);
        },

        dismiss(id) {
            this.items = this.items.filter(t => t.id !== id);
        },

        clear() {
            this.items = [];
        },
    });

    // Backward-compat with window.showToast()
    window.showToast = function (message, type = 'success', duration = 3500) {
        Alpine.store('toast').show(message, type, duration);
    };
});
