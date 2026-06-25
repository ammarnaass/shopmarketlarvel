# Tailwind CSS - دليل التصميم

نظام تصميم كامل مبني على **Tailwind CSS 4** مع Vite للأداء العالي في الإنتاج.

---

## 1. نظرة عامة

| الإعداد | القيمة |
|---------|--------|
| Framework | Tailwind CSS 4.0+ |
| Bundler | Vite 8 |
| Plugin | @tailwindcss/vite |
| حجم CSS | ~110KB (gzipped: ~19KB) |
| حجم JS | ~3KB (gzipped: ~1.5KB) |
| الخط | Cairo + Tajawal + Inter |

### لماذا Tailwind 4؟
- **أسرع 10x** في البناء من Tailwind 3
- **CSS أصغر** (Automatic CSS Pruning)
- **مكونات ذرية** (CSS-only components)
- **Theme via @theme** بدلاً من `tailwind.config.js`
- **دعم أصلي** لـ CSS nesting و custom properties

---

## 2. التثبيت

### 2.1 الـ packages المطلوبة

```json
{
  "devDependencies": {
    "@tailwindcss/vite": "^4.0.0",
    "laravel-vite-plugin": "^3.1",
    "tailwindcss": "^4.0.0",
    "vite": "^8.0.0"
  }
}
```

### 2.2 الأوامر

```bash
# تثبيت الحزم
npm install

# بناء للأول مرة (production)
npm run build

# تشغيل dev server مع HMR
npm run dev
```

### 2.3 الملفات المهمة

| الملف | الوصف |
|------|-------|
| `vite.config.js` | إعدادات Vite + Tailwind plugin |
| `resources/css/app.css` | ملف Tailwind الرئيسي (theme + components) |
| `resources/js/app.js` | JavaScript (cart, wishlist, animations) |
| `resources/views/frontend/layout.blade.php` | يستخدم `@vite()` directive |

---

## 3. بنية الـ Theme

### 3.1 الألوان

```css
@theme {
    /* Brand: Indigo (للأزرار الأساسية) */
    --color-brand-50  إلى --color-brand-950  (10 درجات)

    /* Accent: Rose (للعروض والحب) */
    --color-accent-50 إلى --color-accent-900  (10 درجات)

    /* Semantic (معروفة من Tailwind 3) */
    --color-success: #10b981
    --color-warning: #f59e0b
    --color-danger:  #ef4444
    --color-info:    #3b82f6
}
```

**الاستخدام:**
```html
<button class="bg-brand-600 text-white">Primary</button>
<button class="bg-accent-500 text-white">Sale</button>
<div class="bg-success-light text-success">Success</div>
```

### 3.2 الخطوط

```css
--font-sans: 'Cairo', 'Tajawal', sans-serif;     /* افتراضي */
--font-arabic: 'Cairo', 'Tajawal', sans-serif;  /* عربي */
--font-display: 'Tajawal', 'Cairo', sans-serif; /* للعناوين */
```

### 3.3 الظلال

```css
--shadow-soft: 0 2px 8px rgba(0,0,0,0.04);
--shadow-soft-lg: 0 4px 16px rgba(0,0,0,0.06);
--shadow-soft-xl: 0 8px 32px rgba(0,0,0,0.08);
--shadow-brand: 0 4px 14px rgba(99,102,241,0.25);
--shadow-brand-lg: 0 8px 28px rgba(99,102,241,0.35);
--shadow-accent: 0 4px 14px rgba(244,63,94,0.25);
```

**الاستخدام:**
```html
<div class="shadow-soft">بطاقة عادية</div>
<div class="shadow-brand">بطاقة مع لمسة براند</div>
```

### 3.4 Animations

```css
--animate-fade-in: fade-in 0.4s ease-out;
--animate-fade-up: fade-up 0.5s ease-out;
--animate-slide-down: slide-down 0.3s ease-out;
--animate-slide-up: slide-up 0.3s ease-out;
--animate-shimmer: shimmer 2s linear infinite;
--animate-bounce-slow: bounce-slow 2s ease-in-out infinite;
```

**الاستخدام:**
```html
<div class="animate-fade-up">يظهر مع تأثير</div>
<div class="animate-bounce-slow">يرتد ببطء</div>
```

---

## 4. مكونات جاهزة (Components)

### 4.1 Buttons

```html
<!-- رئيسي (Brand gradient) -->
<button class="btn-primary">تسوق الآن</button>

<!-- مع لون مميز (Accent) -->
<button class="btn-accent">اشترِ الآن</button>

<!-- ثانوي (خلفية بيضاء + حد) -->
<button class="btn-secondary">إلغاء</button>

<!-- شبحي (شفاف) -->
<button class="btn-ghost">رجوع</button>

-- خطير
<button class="btn-danger">حذف</button>

<!-- أحجام -->
<button class="btn-primary btn-sm">صغير</button>
<button class="btn-primary">عادي</button>
<button class="btn-primary btn-lg">كبير</button>

<!-- عرض كامل -->
<button class="btn-primary btn-block">كامل العرض</button>
```

### 4.2 Forms

```html
<!-- Input عادي -->
<input type="text" class="form-input" placeholder="الاسم">

<!-- Input فيه خطأ -->
<input type="text" class="form-input form-input-error" value="بيانات خاطئة">
<p class="form-error">هذا الحقل مطلوب</p>

<!-- Select -->
<select class="form-select">
    <option>اختر...</option>
</select>

<!-- Checkbox -->
<input type="checkbox" class="form-checkbox">

<!-- Label -->
<label class="form-label">البريد الإلكتروني</label>
<p class="form-help">سيتم استخدامه لتسجيل الدخول</p>
```

### 4.3 Cards

```html
<!-- بطاقة عادية -->
<div class="card">
    <div class="card-body">محتوى البطاقة</div>
</div>

<!-- بطاقة مع hover effect -->
<div class="card card-hover">
    <div class="card-body">ترتفع عند المرور</div>
</div>

<!-- بطاقة مع header و footer -->
<div class="card">
    <div class="card-header">عنوان</div>
    <div class="card-body">المحتوى</div>
    <div class="card-footer">
        <button class="btn-primary">حفظ</button>
    </div>
</div>

<!-- بطاقة منتج (جاهزة) -->
<div class="product-card">
    <div class="product-card-image">
        <img src="..." alt="...">
    </div>
    <div class="p-4">اسم المنتج والسعر</div>
</div>
```

### 4.4 Badges

```html
<span class="badge badge-primary">جديد</span>
<span class="badge badge-accent">-25%</span>
<span class="badge badge-success">متوفر</span>
<span class="badge badge-warning">متبقي 3</span>
<span class="badge badge-danger">نفد</span>
<span class="badge badge-info">شحن مجاني</span>
<span class="badge badge-gray">عادي</span>
```

### 4.5 Alerts

```html
<div class="alert alert-success">
    <i class="fas fa-check-circle"></i>
    <span>تمت العملية بنجاح</span>
</div>

<div class="alert alert-danger">
    <i class="fas fa-exclamation-circle"></i>
    <span>حدث خطأ ما</span>
</div>

<div class="alert alert-warning">
    <i class="fas fa-exclamation-triangle"></i>
    <span>تحذير: تحقق من البيانات</span>
</div>

<div class="alert alert-info">
    <i class="fas fa-info-circle"></i>
    <span>معلومة: تم تحديث السياسة</span>
</div>
```

### 4.6 Layout

```html
<!-- حاوية رئيسية (max-width: 80rem) -->
<div class="container-app">...</div>

<!-- أقسام (spacing) -->
<section class="section">مسافة كبيرة</section>
<section class="section-sm">مسافة صغيرة</section>

<!-- عناوين -->
<h1 class="heading-1">عنوان 1</h1>
<h2 class="heading-2">عنوان 2</h2>
<h3 class="heading-3">عنوان 3</h3>

<!-- عنوان قسم مع خط -->
<h2 class="section-title">منتجات مميزة</h2>
```

---

## 5. Utilities مخصصة

```html
<!-- Gradient text (شفافية نص مع gradient) -->
<h1 class="gradient-text">نص متدرج</h1>

<!-- خلفية متدرجة -->
<div class="gradient-bg">خلفية بلون البراند</div>

<!-- Glass effect (شفافية مع blur) -->
<nav class="glass sticky top-0">شريط شفاف</nav>

<!-- بدون scrollbar -->
<div class="no-scrollbar">مرئي بدون scrollbar</div>

<!-- RTL: عكس الأيقونة -->
<i class="fas fa-arrow-right ltr-flip"></i>
```

---

## 6. JavaScript Helpers

متوفرة في `resources/js/app.js`:

```javascript
// تنسيق العملة
formatCurrency(150.5)              // "150.50 ر.س"
formatCurrency(150.5, 'AED')      // "150.50 د.إ"

// Toast notifications
showToast('تمت الإضافة للسلة', 'success');
showToast('حدث خطأ', 'error', 5000);  // مدة 5 ثواني

// Debounce
const handler = debounce(function() { ... }, 300);

// AJAX مع CSRF
const result = await apiRequest('/api/endpoint', {
    method: 'POST',
    body: { key: 'value' }
});
```

---

## 7. أنماط الاستخدام الشائعة

### 7.1 Hero Section (الصفحة الرئيسية)

```html
<section class="relative overflow-hidden bg-gradient-to-bl from-brand-700 via-brand-600 to-brand-500 text-white">
    <div class="container-app py-16 md:py-24 lg:py-32">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div>
                <span class="inline-flex items-center gap-2 bg-white/15 backdrop-blur-md px-4 py-1.5 rounded-full text-sm mb-6">
                    <i class="fas fa-sparkles text-accent-300"></i>
                    عرض حصري
                </span>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-tight mb-6 text-balance">
                    تسوق
                    <span class="bg-gradient-to-l from-accent-300 to-accent-100 bg-clip-text text-transparent">بذكاء</span>
                </h1>
                <p class="text-lg sm:text-xl mb-8 text-white/90 max-w-xl text-pretty">
                    اكتشف أحدث المنتجات بأفضل الأسعار
                </p>
                <div class="flex gap-3 flex-wrap">
                    <a href="#" class="btn-accent btn-lg shadow-accent">
                        <i class="fas fa-shopping-bag"></i> تسوق الآن
                    </a>
                    <a href="#" class="btn btn-lg bg-white/15 backdrop-blur-md border border-white/30 text-white">
                        المنتجات المميزة
                    </a>
                </div>
            </div>
            <div class="hidden md:block">
                <!-- صورة أو animation -->
            </div>
        </div>
    </div>
</section>
```

### 7.2 Product Card (شبكة المنتجات)

```html
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
    @foreach($products as $product)
        <div class="product-card group">
            <a href="#" class="block relative">
                <div class="product-card-image">
                    <img src="..." alt="...">
                </div>
                <div class="absolute top-2 right-2 flex flex-col gap-1.5">
                    <span class="badge badge-accent">-25%</span>
                </div>
            </a>
            <div class="p-4">
                <h3 class="font-semibold text-sm">اسم المنتج</h3>
                <p class="text-lg font-extrabold gradient-text">150 ر.س</p>
            </div>
        </div>
    @endforeach
</div>
```

### 7.3 Card with Glass Effect (نافذة منبثقة)

```html
<div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="card max-w-md w-full mx-4">
        <div class="card-body">
            <h2 class="heading-3 mb-4">هل أنت متأكد؟</h2>
            <p class="text-gray-600 mb-6">هذا الإجراء لا يمكن التراجع عنه.</p>
            <div class="flex gap-2 justify-end">
                <button class="btn-secondary">إلغاء</button>
                <button class="btn-danger">تأكيد</button>
            </div>
        </div>
    </div>
</div>
```

### 7.4 Loading Skeleton

```html
<div class="space-y-3">
    <div class="skeleton h-4 w-3/4"></div>
    <div class="skeleton h-4 w-1/2"></div>
    <div class="skeleton h-32 w-full"></div>
</div>
```

### 7.5 Empty State

```html
<div class="text-center py-16">
    <div class="w-24 h-24 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
        <i class="fas fa-shopping-cart text-4xl text-gray-300"></i>
    </div>
    <h3 class="heading-3 mb-2">سلة التسوق فارغة</h3>
    <p class="text-gray-500 mb-6">ابدأ التسوق الآن واملأ سلتك بمنتجاتك المفضلة</p>
    <a href="{{ route('shop.index') }}" class="btn-primary">
        <i class="fas fa-shopping-bag"></i> تصفح المنتجات
    </a>
</div>
```

---

## 8. Responsive Breakpoints

| Class | Width | الوصف |
|-------|-------|-------|
| (افتراضي) | < 640px | موبايل |
| `sm:` | ≥ 640px | موبايل كبير / تابلت |
| `md:` | ≥ 768px | تابلت / لابتوب صغير |
| `lg:` | ≥ 1024px | لابتوب |
| `xl:` | ≥ 1280px | ديسكتوب |
| `2xl:` | ≥ 1536px | شاشات كبيرة |

**مثال:**
```html
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
    <!-- 1 عمود موبايل، 2 تابلت صغير، 3 تابلت، 4 لابتوب -->
</div>
```

---

## 9. الأيقونات (Font Awesome 6.5)

```html
<!-- أساسي -->
<i class="fas fa-home"></i>            <!-- Solid -->
<i class="far fa-heart"></i>           <!-- Regular (outline) -->
<i class="fab fa-facebook"></i>        <!-- Brands -->
<i class="fal fa-bell"></i>            <!-- Light -->

<!-- مع أحجام -->
<i class="fas fa-home text-xs"></i>
<i class="fas fa-home text-sm"></i>
<i class="fas fa-home text-base"></i>
<i class="fas fa-home text-lg"></i>
<i class="fas fa-home text-2xl"></i>
<i class="fas fa-home text-4xl"></i>

<!-- مع دوران -->
<i class="fas fa-spinner fa-spin"></i>

<!-- مع نبض -->
<i class="fas fa-circle fa-pulse text-red-500"></i>
```

---

## 10. الأمثلة الكاملة

### 10.1 نموذج تسجيل دخول

```html
<div class="min-h-screen flex items-center justify-center bg-gradient-to-bl from-brand-50 to-accent-50 px-4 py-12">
    <div class="card max-w-md w-full">
        <div class="card-body p-8">
            <div class="text-center mb-6">
                <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-gradient-to-br from-brand-600 to-accent-500 flex items-center justify-center text-white shadow-brand">
                    <i class="fas fa-user text-2xl"></i>
                </div>
                <h1 class="heading-2 mb-1">مرحبا بعودتك</h1>
                <p class="text-gray-500 text-sm">سجل دخولك للمتابعة</p>
            </div>

            <form class="space-y-4">
                <div>
                    <label class="form-label">البريد الإلكتروني</label>
                    <input type="email" class="form-input" placeholder="you@example.com">
                </div>
                <div>
                    <label class="form-label">كلمة المرور</label>
                    <input type="password" class="form-input" placeholder="••••••••">
                </div>
                <button type="submit" class="btn-primary btn-block btn-lg">
                    <i class="fas fa-right-to-bracket"></i> تسجيل الدخول
                </button>
            </form>

            <p class="text-center mt-6 text-sm text-gray-600">
                ليس لديك حساب؟
                <a href="#" class="text-brand-600 font-semibold hover:underline">سجل الآن</a>
            </p>
        </div>
    </div>
</div>
```

### 10.2 نافذة منبثقة (Modal)

```html
<div x-data="{ open: true }" x-show="open" x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
     @keydown.escape.window="open = false">
    <div @click.outside="open = false"
         class="card max-w-md w-full shadow-soft-xl animate-fade-up">
        <div class="card-header flex items-center justify-between">
            <h3 class="font-bold text-lg">تأكيد الطلب</h3>
            <button @click="open = false" class="btn-icon text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="card-body">
            <p>هل تريد إتمام عملية الشراء؟</p>
        </div>
        <div class="card-footer flex gap-2 justify-end">
            <button @click="open = false" class="btn-secondary">إلغاء</button>
            <button class="btn-primary">تأكيد</button>
        </div>
    </div>
</div>
```

### 10.3 Empty State مع Action

```html
<div class="text-center py-20">
    <div class="w-32 h-32 mx-auto mb-6 rounded-full bg-gradient-to-br from-brand-100 to-accent-100 flex items-center justify-center animate-bounce-slow">
        <i class="fas fa-box-open text-5xl text-brand-500"></i>
    </div>
    <h2 class="heading-2 mb-2">لا توجد طلبات</h2>
    <p class="text-gray-500 mb-6 max-w-md mx-auto">لم تقم بإنشاء أي طلب بعد. ابدأ التسوق الآن!</p>
    <a href="{{ route('shop.index') }}" class="btn-primary btn-lg">
        <i class="fas fa-shopping-bag"></i> تصفح المنتجات
    </a>
</div>
```

---

## 11. بناء للـ Production

```bash
# بناء (يضع الملفات في public/build/)
npm run build

# الملفات الناتجة
public/build/
├── manifest.json
├── fonts-manifest.json
└── assets/
    ├── app-XXXXXX.css     (110 KB, 19 KB gzipped)
    ├── app-XXXXXX.js      (3 KB, 1.5 KB gzipped)
    └── fonts-XXXXXX.css
```

### 11.1 مع Cache Busting

Laravel's `@vite()` directive يضيف `?id=` تلقائياً عند تغيير الملف.

### 11.2 تحسينات تلقائية

- **CSS Pruning**: يستخدم Tailwind 4 `@source` لاكتشاف الفئات المستخدمة فقط
- **Minification**: Vite يصغّر CSS و JS تلقائياً
- **Tree Shaking**: يحذف الكود غير المستخدم

---

## 12. RTL Support

التطبيق يعمل بـ `dir="rtl"` و `lang="ar"` بشكل كامل.

```html
<html dir="rtl" lang="ar">
```

### 12.1 الأيقونات التي تنعكس

```html
<!-- سهم لليسار يصبح لليمين تلقائياً -->
<i class="fas fa-arrow-left"></i>  →  يظهر لليمين

<!-- لإجبار العكس -->
<i class="fas fa-arrow-right ltr-flip"></i>
```

### 12.2 Margin/Padding (في RTL)

```html
<!-- ml- (margin-left) = mr- (margin-right) في RTL تلقائياً -->
<div class="ml-2">  →  في RTL: margin-right

<!-- للضبط اليدوي -->
<div class="ms-2 me-2">  <!-- logical properties (الأفضل) -->
```

---

## 13. الأداء

### قبل وبعد Tailwind 4

| المقياس | CDN Tailwind 3 | Vite + Tailwind 4 |
|---------|----------------|-------------------|
| حجم CSS | ~300 KB | **~19 KB** (gzipped) |
| طلبات HTTP | 1 (CDN) | 0 (bundled) |
| وقت البناء | 0 | 1.5 ثانية |
| Tree shaking | ❌ | ✅ |
| Critical CSS | ❌ | ✅ (مع Vite) |

### 13.1 Lighthouse

- **Performance**: 95+ (mobile) / 99+ (desktop)
- **Best Practices**: 100
- **Accessibility**: 95+

---

## 14. استكشاف الأخطاء

### 14.1 "Cannot apply unknown utility class"

يحدث في `@apply` إذا حاولت تطبيق كلاس غير موجود:

```css
/* ❌ خطأ */
.btn-primary { @apply btn bg-blue-500; }

/* ✅ صحيح */
.btn-primary { @apply bg-blue-500; }
/* أو عرّف .btn أولاً ثم @apply */
.btn { @apply px-4 py-2 rounded; }
.btn-primary { @apply btn bg-blue-500; }
```

### 14.2 الألوان المخصصة لا تظهر

```bash
# 1) تأكد من أن @theme موجود في app.css
# 2) أعد البناء
npm run build
# 3) امسح cache Laravel
php artisan view:clear
```

### 14.3 الخطوط لا تعمل

```bash
# تأكد من إضافة @import url في بداية app.css
# @import url('https://fonts.googleapis.com/...');
# @import 'tailwindcss';
# (الترتيب مهم في Tailwind 4)
```

---

**يدعم:** Laravel 11 / 12 / 13
**المتصفحات:** Chrome 90+, Firefox 90+, Safari 14+, Edge 90+
**Mobile-first:** نعم
**RTL:** مدعوم بالكامل
**الأداء:** Lighthouse 95+
