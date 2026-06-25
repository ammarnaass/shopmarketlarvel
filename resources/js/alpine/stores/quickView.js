/* ====================================================================
   QUICK VIEW STORE — Global modal for product quick preview
   Usage: $store.quickView.open(html) / $store.quickView.close()
   ==================================================================== */

document.addEventListener('alpine:init', () => {
    Alpine.store('quickView', {
        open: false,
        html: '',
        show(html) {
            this.html = html;
            this.open = true;
            document.body.style.overflow = 'hidden';
        },
        close() {
            this.open = false;
            document.body.style.overflow = '';
            // Clear html after animation
            setTimeout(() => { this.html = ''; }, 300);
        },
    });
});
