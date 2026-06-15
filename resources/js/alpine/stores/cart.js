/* ====================================================================
   CART STORE — Reactive global cart state with AJAX
   Usage: <span x-text="$store.cart.count"></span>
   Add: $store.cart.add(productId, qty, options)
   ==================================================================== */

document.addEventListener('alpine:init', () => {
    Alpine.store('cart', {
        items: window.__CART_ITEMS__ || [],
        count: window.__CART_COUNT__ || 0,
        subtotal: window.__CART_SUBTOTAL__ || 0,
        discount: 0,
        coupon: null,
        loading: false,
        updating: null, // id of item being updated

        get total() {
            return this.subtotal - this.discount;
        },

        get formattedSubtotal() {
            return this._money(this.subtotal);
        },

        get formattedTotal() {
            return this._money(this.total);
        },

        _money(value) {
            const symbol = window.__CURRENCY_SYMBOL__ || 'ر.س';
            return new Intl.NumberFormat('ar-SA', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 2,
            }).format(value) + ' ' + symbol;
        },

        async add(productId, quantity = 1, options = {}) {
            this.loading = true;
            try {
                const res = await this._fetch('/cart', {
                    method: 'POST',
                    body: { product_id: productId, quantity, ...options },
                });
                if (res.ok) {
                    this._updateFromResponse(res.data);
                    this._toast('success', res.data.message || 'تمت الإضافة للسلة');
                } else {
                    this._toast('error', res.data.message || 'حدث خطأ');
                }
                return res;
            } catch (e) {
                this._toast('error', 'خطأ في الاتصال');
                return { ok: false, data: { message: 'خطأ في الاتصال' } };
            } finally {
                this.loading = false;
            }
        },

        async updateQty(itemId, quantity) {
            if (quantity < 1) return this.remove(itemId);
            this.updating = itemId;
            try {
                const res = await this._fetch(`/cart/${itemId}`, {
                    method: 'PATCH',
                    body: { quantity },
                });
                if (res.ok) {
                    this._updateFromResponse(res.data);
                } else {
                    this._toast('error', res.data.message || 'حدث خطأ');
                }
            } finally {
                this.updating = null;
            }
        },

        async remove(itemId) {
            this.updating = itemId;
            // Optimistic UI update
            const idx = this.items.findIndex(i => i.id === itemId);
            if (idx > -1) this.items.splice(idx, 1);
            try {
                const res = await this._fetch(`/cart/${itemId}`, { method: 'DELETE' });
                if (res.ok) {
                    this._updateFromResponse(res.data);
                    this._toast('success', res.data.message || 'تم الحذف من السلة');
                }
            } catch (e) {
                this._toast('error', 'خطأ في الاتصال');
            } finally {
                this.updating = null;
            }
        },

        async clear() {
            if (!confirm('هل تريد إفراغ السلة بالكامل؟')) return;
            this.loading = true;
            try {
                const res = await this._fetch('/cart', { method: 'DELETE' });
                if (res.ok) {
                    this.items = [];
                    this.subtotal = 0;
                    this.discount = 0;
                    this.coupon = null;
                    this.count = 0;
                    this._toast('success', res.data.message || 'تم إفراغ السلة');
                }
            } finally {
                this.loading = false;
            }
        },

        async applyCoupon(code) {
            if (!code || !code.trim()) {
                this._toast('warning', 'أدخل كود الخصم');
                return;
            }
            this.loading = true;
            try {
                const res = await this._fetch('/cart/coupon', {
                    method: 'POST',
                    body: { code: code.trim() },
                });
                if (res.ok) {
                    this._updateFromResponse(res.data);
                    this._toast('success', res.data.message || 'تم تطبيق الكوبون');
                } else {
                    this._toast('error', res.data.message || 'كود غير صحيح');
                }
            } finally {
                this.loading = false;
            }
        },

        async removeCoupon() {
            try {
                const res = await this._fetch('/cart/coupon', { method: 'DELETE' });
                if (res.ok) {
                    this.coupon = null;
                    this.discount = 0;
                    this._updateFromResponse(res.data);
                    this._toast('success', res.data.message || 'تم إزالة الكوبون');
                }
            } catch (e) {
                this._toast('error', 'خطأ في الاتصال');
            }
        },

        _updateFromResponse(data) {
            if (data.cart) {
                this.items = data.cart.items || [];
                this.subtotal = data.cart.subtotal || 0;
                this.discount = data.cart.discount || 0;
                this.coupon = data.cart.coupon || null;
                this.count = data.cart.count || this.items.reduce((s, i) => s + i.quantity, 0);
            }
        },

        async _fetch(url, options = {}) {
            const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
            const headers = {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrf,
                ...(options.headers || {}),
            };
            const config = {
                method: options.method || 'GET',
                headers,
                credentials: 'same-origin',
            };
            if (options.body && !(options.body instanceof FormData)) {
                config.body = JSON.stringify(options.body);
            } else if (options.body) {
                config.body = options.body;
            }
            try {
                const response = await fetch(url, config);
                const data = await response.json();
                return { ok: response.ok, status: response.status, data };
            } catch (e) {
                return { ok: false, status: 0, data: { message: 'خطأ في الاتصال' } };
            }
        },

        _toast(type, message) {
            if (window.Alpine?.store('toast')) {
                Alpine.store('toast').show(message, type);
            }
        },
    });
});
