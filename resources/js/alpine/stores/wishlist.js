/* ====================================================================
   WISHLIST STORE — Reactive wishlist with localStorage persistence
   Usage: <button @click="$store.wishlist.toggle(productId)">
   ==================================================================== */

document.addEventListener('alpine:init', () => {
    Alpine.store('wishlist', {
        items: Alpine.$persist([]).as('amar:wishlist'),
        loading: false,

        get count() {
            return this.items.length;
        },

        has(productId) {
            return this.items.includes(productId);
        },

        async toggle(productId) {
            const wasInWishlist = this.has(productId);
            // Optimistic UI update
            if (wasInWishlist) {
                this.items = this.items.filter(id => id !== productId);
            } else {
                this.items = [...this.items, productId];
            }

            try {
                const res = await fetch('/wishlist', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({ product_id: productId }),
                });
                const data = await res.json();
                if (res.ok) {
                    if (window.Alpine?.store('toast')) {
                        Alpine.store('toast').success(data.message || (wasInWishlist ? 'أزيل من المفضلة' : 'أضيف للمفضلة'));
                    }
                } else {
                    // Revert
                    if (wasInWishlist) {
                        this.items = [...this.items, productId];
                    } else {
                        this.items = this.items.filter(id => id !== productId);
                    }
                    if (window.Alpine?.store('toast')) {
                        Alpine.store('toast').error(data.message || 'الرجاء تسجيل الدخول');
                    }
                }
            } catch (e) {
                // Revert
                if (wasInWishlist) {
                    this.items = [...this.items, productId];
                } else {
                    this.items = this.items.filter(id => id !== productId);
                }
                if (window.Alpine?.store('toast')) {
                    Alpine.store('toast').error('خطأ في الاتصال');
                }
            }
        },

        clear() {
            this.items = [];
        },
    });
});
