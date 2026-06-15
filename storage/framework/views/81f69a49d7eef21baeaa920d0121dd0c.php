<?php $__env->startSection('title', 'سلة التسوق'); ?>

<?php $__env->startSection('content'); ?>
<?php
    // Prepare cart data for Alpine.js
    $cartItems = $cart->items->load('product.primaryImage', 'product.images')->map(function($item) {
        return [
            'id' => $item->id,
            'product_id' => $item->product_id,
            'product' => [
                'id' => $item->product->id,
                'slug' => $item->product->slug,
                'name' => $item->product->name,
                'price' => (float) $item->price,
                'stock' => $item->product->stock,
                'image' => $item->product->primaryImage?->path,
            ],
            'quantity' => $item->quantity,
            'price' => (float) $item->price,
            'subtotal' => (float) $item->subtotal,
            'options' => (object) ($item->options ?? []),
            'custom_text' => $item->custom_text,
        ];
    })->values();
    $cartDataJson = json_encode([
        'items' => $cartItems,
        'count' => $cartItems->sum('quantity'),
        'subtotal' => (float) $cart->subtotal,
        'discount' => (float) $cart->discount,
        'coupon' => $cart->coupon ? [
            'id' => $cart->coupon->id,
            'code' => $cart->coupon->code,
            'description' => $cart->coupon->description,
        ] : null,
    ], JSON_UNESCAPED_UNICODE);
?>

<div class="container-app py-8"
     x-data="{
         ...$store.cart,
         couponCode: '',
     }"
     x-init="
         const data = <?php echo e($cartDataJson); ?>;
         $store.cart.items = data.items;
         $store.cart.count = data.count;
         $store.cart.subtotal = data.subtotal;
         $store.cart.discount = data.discount;
         $store.cart.coupon = data.coupon;
     ">

    <h1 class="heading-2 mb-2 flex items-center gap-3">
        <i class="fas fa-shopping-bag text-brand-600"></i>
        سلة التسوق
        <span class="text-base font-normal text-gray-500" x-text="'(' + $store.cart.count + ' منتج)'"></span>
    </h1>

    
    <template x-if="$store.cart.items.length === 0 && !$store.cart.loading">
        <div class="card max-w-2xl mx-auto mt-8 animate-fade-up">
            <div class="card-body p-12 text-center">
                <div class="w-24 h-24 mx-auto mb-6 rounded-full bg-gradient-to-br from-brand-100 to-accent-100 flex items-center justify-center animate-bounce-slow">
                    <i class="fas fa-shopping-bag text-5xl text-brand-500"></i>
                </div>
                <h2 class="heading-3 mb-2">سلة التسوق فارغة</h2>
                <p class="text-gray-500 mb-6">لم تقم بإضافة أي منتجات بعد</p>
                <a href="<?php echo e(route('shop.index')); ?>" class="btn-primary btn-lg inline-flex">
                    <i class="fas fa-shopping-bag"></i> تسوق الآن
                </a>
            </div>
        </div>
    </template>

    
    <template x-if="$store.cart.items.length > 0">
        <div class="grid lg:grid-cols-3 gap-6 mt-6">
            
            <div class="lg:col-span-2 space-y-3">
                <template x-for="item in $store.cart.items" :key="item.id">
                    <div class="card animate-fade-up"
                         :class="$store.cart.updating === item.id ? 'opacity-50 pointer-events-none' : ''">
                        <div class="card-body p-4 flex gap-4">
                            
                            <a :href="'/shop/' + item.product.slug" class="w-24 h-24 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0 group">
                                <template x-if="item.product.image">
                                    <img :src="'/storage/' + item.product.image"
                                         :alt="item.product.name"
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                </template>
                                <template x-if="!item.product.image">
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                        <i class="fas fa-image text-2xl"></i>
                                    </div>
                                </template>
                            </a>

                            
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-sm sm:text-base mb-1">
                                    <a :href="'/shop/' + item.product.slug"
                                       class="hover:text-brand-600 transition"
                                       x-text="item.product.name"></a>
                                </h3>

                                
                                <template x-if="item.options && Object.keys(item.options).length > 0">
                                    <div class="flex flex-wrap gap-1 mb-2">
                                        <template x-for="(value, key) in item.options" :key="key">
                                            <span class="badge badge-gray text-[10px]" x-text="key + ': ' + value"></span>
                                        </template>
                                    </div>
                                </template>

                                
                                <template x-if="item.custom_text">
                                    <p class="text-xs text-gray-500 mb-2">
                                        <i class="fas fa-pen ml-1"></i>
                                        <span x-text="item.custom_text"></span>
                                    </p>
                                </template>

                                
                                <div class="flex items-center justify-between mt-3 gap-2 flex-wrap">
                                    
                                    <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden">
                                        <button @click="$store.cart.updateQty(item.id, item.quantity - 1)"
                                                :disabled="item.quantity <= 1"
                                                class="px-2.5 py-1.5 text-gray-600 hover:bg-brand-50 hover:text-brand-600 disabled:opacity-30 disabled:cursor-not-allowed transition">
                                            <i class="fas fa-minus text-xs"></i>
                                        </button>
                                        <span class="px-3 py-1.5 min-w-[2.5rem] text-center text-sm font-semibold" x-text="item.quantity"></span>
                                        <button @click="$store.cart.updateQty(item.id, item.quantity + 1)"
                                                :disabled="item.quantity >= item.product.stock"
                                                class="px-2.5 py-1.5 text-gray-600 hover:bg-brand-50 hover:text-brand-600 disabled:opacity-30 disabled:cursor-not-allowed transition">
                                            <i class="fas fa-plus text-xs"></i>
                                        </button>
                                    </div>

                                    
                                    <div class="font-bold gradient-text" x-text="$store.cart._money(item.subtotal)"></div>

                                    
                                    <button @click="$store.cart.remove(item.id)"
                                            class="btn-icon text-red-500 hover:bg-red-50"
                                            title="حذف">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>

                
                <div class="flex justify-between items-center pt-2">
                    <a href="<?php echo e(route('shop.index')); ?>" class="btn btn-ghost text-sm">
                        <i class="fas fa-arrow-right"></i> متابعة التسوق
                    </a>
                    <button @click="$store.cart.clear()"
                            class="text-sm text-red-500 hover:text-red-700 hover:underline flex items-center gap-1.5">
                        <i class="fas fa-trash"></i> إفراغ السلة
                    </button>
                </div>
            </div>

            
            <div class="lg:sticky lg:top-28 lg:self-start">
                <div class="card animate-fade-up">
                    <div class="card-header">
                        <h3 class="font-bold text-lg flex items-center gap-2">
                            <i class="fas fa-receipt text-brand-600"></i>
                            ملخص الطلب
                        </h3>
                    </div>

                    <div class="card-body space-y-4">
                        
                        <template x-if="$store.cart.coupon">
                            <div class="alert-success">
                                <i class="fas fa-tag text-green-500"></i>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold" x-text="$store.cart.coupon.code"></p>
                                    <p class="text-xs" x-text="$store.cart.coupon.description || ('خصم ' + $store.cart.discount)"></p>
                                </div>
                                <button @click="$store.cart.removeCoupon()"
                                        class="btn-icon text-red-500 hover:bg-red-50"
                                        title="إزالة الكوبون">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </template>

                        
                        <template x-if="!$store.cart.coupon">
                            <form @submit.prevent="$store.cart.applyCoupon(couponCode); couponCode = ''" class="space-y-2">
                                <label class="form-label">
                                    <i class="fas fa-tag ml-1 text-brand-600"></i> كود الخصم
                                </label>
                                <div class="flex gap-2">
                                    <input type="text"
                                           x-model="couponCode"
                                           placeholder="أدخل الكود"
                                           class="form-input flex-1"
                                           :disabled="$store.cart.loading">
                                    <button type="submit"
                                            class="btn-primary"
                                            :disabled="$store.cart.loading || !couponCode">
                                        <i x-show="$store.cart.loading" class="fas fa-spinner fa-spin"></i>
                                        <span x-show="!$store.cart.loading">تطبيق</span>
                                    </button>
                                </div>
                            </form>
                        </template>

                        <hr class="border-gray-200">

                        
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between text-gray-600">
                                <span>المجموع الفرعي</span>
                                <span class="font-semibold" x-text="$store.cart.formattedSubtotal"></span>
                            </div>
                            <template x-if="$store.cart.discount > 0">
                                <div class="flex justify-between text-green-600">
                                    <span>الخصم</span>
                                    <span class="font-semibold" x-text="'- ' + $store.cart._money($store.cart.discount)"></span>
                                </div>
                            </template>
                            <div class="flex justify-between text-gray-600">
                                <span>الشحن</span>
                                <span class="text-xs text-gray-400">يُحسب عند الطلب</span>
                            </div>
                        </div>

                        <hr class="border-gray-200">

                        
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-lg">الإجمالي</span>
                            <span class="font-extrabold text-2xl gradient-text" x-text="$store.cart.formattedTotal"></span>
                        </div>

                        <a href="<?php echo e(route('checkout.index')); ?>" class="btn-primary btn-block btn-lg">
                            <i class="fas fa-credit-card"></i>
                            إتمام الطلب
                        </a>

                        
                        <div class="grid grid-cols-2 gap-2 pt-3 text-xs">
                            <div class="flex items-center gap-1.5 text-gray-500">
                                <i class="fas fa-shield-halved text-green-500"></i>
                                <span>دفع آمن</span>
                            </div>
                            <div class="flex items-center gap-1.5 text-gray-500">
                                <i class="fas fa-truck-fast text-brand-500"></i>
                                <span>شحن سريع</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\amarn\ecommerce\resources\views/frontend/cart/index.blade.php ENDPATH**/ ?>