# 🛒 متجر أمار (Amar Store) - التوثيق الكامل للمشروع

متجر إلكتروني متكامل واحترافي مبني باستخدام **Laravel 13** و **PHP 8.3** و **Tailwind CSS 4**.  
مصمم لخدمة دول شمال إفريقيا مع دفع عند الاستلام (COD)، شحن ديناميكي، شراء فوري، و API متكامل.

---

## 📋 جدول المحتويات

1. [الميزات الرئيسية](#الميزات-الرئيسية)
2. [التقنيات المستخدمة](#التقنيات-المستخدمة)
3. [بيئة Docker](#بيئة-docker)
4. [التثبيت والتشغيل](#التثبيت-والتشغيل)
5. [التغطية الإقليمية والشحن](#التغطية-الإقليمية-والشحن)
6. [نظام الأدوار والصلاحيات](#نظام-الأدوار-والصلاحيات)
7. [لوحة التحكم (Admin)](#لوحة-التحكم-admin)
8. [المتجر (Frontend)](#المتجر-frontend)
9. [API](#api)
10. [الموديلز (Models)](#الموديلز-models)
11. [الخدمات (Services)](#الخدمات-services)
12. [Alpine.js (Stores & Components)](#alpinejs-stores--components)
13. [الإعدادات (Settings & Customize)](#الإعدادات-settings--customize)
14. [السكريبتات والاختبارات](#السكريبتات-والاختبارات)
15. [ملفات التصميم (UI/UX)](#ملفات-التصميم-uiux)
16. [أوامر Artisan](#أوامر-artisan)
17. [الأمان](#الأمان)
18. [بنية المجلدات](#بنية-المجلدات)

---

## 🚀 الميزات الرئيسية

### 1. 🛒 الشراء الفوري (Instant Buy)
نموذج شراء فوري داخل صفحة المنتج بدون سلة تسوق:
- **حساب سعر مباشر (AJAX):** الكمية، المقاس/اللون، الكوبون، الدولة/الولاية، الشحن
- **دعم الضيوف (Guest Checkout):** شراء بدون تسجيل
- **تخصيص الطلب:** إدخال نصوص، رفع صور/ملفات مع المنتج

### 2. 📊 لوحة تحكم (KPIs & Charts)
- بطاقات أداء (إجمالي المبيعات، الطلبات، العملاء، المنتجات)
- مخطط مبيعات آخر 7 أيام
- توزيع حالات الطلبات (Pending, Processing, Shipped, Delivered, Cancelled)
- إشعارات المخزون المنخفض (< 10 قطع)
- إعدادات سريعة (عملة، شحن، دفع) + تصفير الكاش

### 3. 🗺️ شحن إقليمي
- 6 دول، ولايات/محافظات، شركات شحن، أسعار ديناميكية
- مناطق شحن (Capital / باقي المدن) لكل دولة
- شحن عادي، سريع، فوري
- تتبع الشحنات (Tracking)
- بوليصة شحن (Label) مع PDF

### 4. 🛍️ سلة تسوق + كوبونات
- إضافة/تعديل/حذف items
- تطبيق كوبونات خصم (نسبة مئوية أو قيمة ثابتة)
- دمج مع حساب الشحن

### 5. ❤️ قائمة المفضلة (Wishlist)
- إضافة/إزالة منتجات
- خاصة بالمستخدمين المسجلين

### 6. 📋 إدارة الطلبات
- إنشاء طلب (سلة أو شراء فوري)
- حالات متعددة مع حفظ التاريخ (Order Status History)
- ملاحظات على الطلب (Order Notes)
- إلغاء الطلب

### 7. ⭐ تقييمات المنتجات (Reviews)
- نشر/إخفاء التقييمات من الأدمن

### 8. 🏷️ العلامات (Tags)
- تصنيف المنتجات بعلامات

### 9. 📄 الصفحات الثابتة (Pages)
- صفحات مخصصة (عن المتجر، اتصل بنا، الأسئلة الشائعة، سياسة الإرجاع)
- نظام SLUG مع محتوى HTML

### 10. 🔔 الإشعارات (Notifications)
- إشعار عند تغيير حالة الطلب (عنوان + رسالة)
- Event + Listener

### 11. 💵 العملات
- كل دولة بعملتها الخاصة
- إعدادات العملة الافتراضية من لوحة التحكم

### 12. 📈 التقارير (Reports)
- تقارير مبيعات إجمالية

### 13. 🎨 التخصيص (Customize)
- ألوان الموقع، الخطوط، الشعار، التذييل

---

## 💻 التقنيات المستخدمة

| المجال | التقنية |
|:---|:---|
| **Backend** | Laravel 13.x, PHP 8.3 |
| **Frontend** | Tailwind CSS v4, Alpine.js 3.x |
| **JavaScript Build** | Vite 6 |
| **Icons** | Font Awesome 6.5 Pro |
| **الخطوط** | Cairo, Tajawal, Inter (RTL support) |
| **Database** | SQLite (dev) / MariaDB 11.4 (production via Docker) |
| **Cache & Session & Queue** | Redis 7.4 (via Docker) |
| **Auth API** | Laravel Sanctum |
| **Charts** | Chart.js عبر Alpine.js |

---

## 🐳 بيئة Docker

### الخدمات

| الخدمة | الحاوية | صورة Docker | المنفذ |
|:---:|:---|:---|:---:|
| MariaDB | `amar-mariadb` | `mariadb:11.4` | 3306 |
| Redis | `amar-redis` | `redis:7.4-alpine` | 6379 |

### التشغيل

```bash
docker compose up -d          # تشغيل الخدمات
docker compose ps             # حالة الخدمات
docker compose down           # إيقاف
docker compose down -v        # إيقاف + حذف البيانات
docker exec -it amar-redis redis-cli    # Redis CLI
```

### الإعدادات في `.env`

```env
# MariaDB
DB_CONNECTION=mariadb
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=amar_store
DB_USERNAME=amar
DB_PASSWORD=amar_pass_2026

# Redis
REDIS_CLIENT=predis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
SESSION_DRIVER=redis
CACHE_STORE=redis
QUEUE_CONNECTION=redis
```

---

## 🛠️ التثبيت والتشغيل

### المتطلبات
- PHP 8.3+ (pdo_sqlite, pdo_mysql, mbstring, openssl, intl)
- Composer 2.x
- Node.js
- Docker (اختياري لتشغيل MariaDB + Redis)

### الخطوات

```bash
# 1. تثبيت حزم PHP
composer install

# 2. إعداد البيئة
copy .env.example .env
php artisan key:generate

# 3. تشغيل MariaDB + Redis (اختياري)
docker compose up -d

# 4. إنشاء قاعدة البيانات والجداول
php artisan migrate --force
php artisan db:seed --force
php artisan db:seed --class=ShippingZonesSeeder --force

# 5. تجميع الأصول
npm install
npm run build

# 6. تشغيل الخادم
php artisan serve
```

---

## 🌍 التغطية الإقليمية والشحن

### الدول المدعومة

| الدولة | العملة | رمز الاتصال | عدد الولايات | شركات الشحن |
|:---:|:---:|:---:|:---:|:---|
| الجزائر (DZ) | د.ج (DZD) | +213 | 48 | Yalidin, Noest, Aramex |
| المغرب (MA) | د.م. (MAD) | +212 | 32 | Aramex, Local |
| تونس (TN) | د.ت (TND) | +216 | 24 | Aramex, Local |
| ليبيا (LY) | د.ل (LYD) | +218 | 20 | SMSA, Aramex |
| مصر (EG) | ج.م (EGP) | +20 | 27 | SMSA, Aramex |
| السودان (SD) | ج.س (SDG) | +249 | 16 | Sudan Post, Local |

### مناطق الشحن
لكل دولة منطقتان: **العاصمة والمدن الكبرى** و **باقي المدن**، لكل منها:
- سعر الشحن العادي
- سعر الشحن السريع (Express)
- حد الطلب المجاني (Free Threshold)

### شركات الشحن المدعومة
Aramex, SMSA, Yalidin, Noest Express, Maystro Delivery  
(مع روابط تتبع مدمجة)

### خيارات الشحن
| الخيار | المدة |
|:---|---:|
| عادي (Standard) | 3-5 أيام |
| سريع (Express) | 1-2 أيام |
| فوري (Same Day) | 0 يوم (مدن محددة) |

### الدفع عند الاستلام (COD)
- حد أدنى: 50 (حسب العملة المحلية)
- حد أقصى: 5000
- تأكيد هاتفي تلقائي
- إشعار المسؤول عند الطلب
- إشعار العميل عند التأكيد

---

## 🔑 نظام الأدوار والصلاحيات

### الأدوار (Roles)
| الدور | الصلاحيات |
|:---|---|
| **Admin** | تحكم كامل بكل شيء |
| **Manager** | إدارة المنتجات، الطلبات، الكوبونات، التصنيفات |
| **Customer** | شراء، تتبع طلباته، مفضلة |

### الصلاحيات (Permissions)
| الصلاحية | الوظيفة |
|:---|---|
| `manage_products` | إضافة/تعديل/حذف المنتجات |
| `manage_orders` | إدارة الطلبات وتغيير حالتها |
| `manage_users` | إدارة حسابات المستخدمين |
| `manage_categories` | تعديل التصنيفات |
| `manage_coupons` | إنشاء كوبونات الخصم |
| `manage_settings` | إعدادات النظام والعملات والشحن |

---

## 📊 لوحة التحكم (Admin)

`/admin` — يتطلب صلاحية `admin` أو `manager`

### المسارات

| المسار | الوظيفة |
|:---|---|
| `GET /admin` | لوحة الإحصائيات (Dashboard) |
| `POST /admin/quick-setting` | تحديث سريع للإعدادات |

### المنتجات (Products)
| المسار | الوظيفة |
|:---|---|
| `GET /admin/products` | قائمة المنتجات |
| `GET /admin/products/create` | إضافة منتج |
| `POST /admin/products` | حفظ منتج جديد |
| `GET /admin/products/{id}/edit` | تعديل منتج |
| `PUT /admin/products/{id}` | تحديث منتج |
| `DELETE /admin/products/{id}` | حذف منتج |
| `POST /admin/products/bulk-action` | إجراء جماعي |
| `GET /admin/products/export/csv` | تصدير CSV |
| `GET /admin/products/{id}/gallery` | معرض الصور |
| `POST /admin/products/{id}/images` | رفع صور |
| `PATCH /admin/products/{id}/images/{img}` | تحديث صورة |
| `DELETE /admin/products/{id}/images/{img}` | حذف صورة |
| `POST /admin/…/images/{img}/primary` | تعيين صورة رئيسية |

منتج يحتوي على: أبعاد (طول/عرض/ارتفاع/وزن)، خيارات (مقاس/لون)، variants، حقول مخصصة، tags.

### التصنيفات (Categories)
| المسار | الوظيفة |
|:---|---|
| `GET /admin/categories` | قائمة التصنيفات |
| `GET /admin/categories/create` | إضافة تصنيف |
| `POST /admin/categories` | حفظ |
| `GET /admin/categories/{id}/edit` | تعديل |
| `PUT /admin/categories/{id}` | تحديث |
| `DELETE /admin/categories/{id}` | حذف |

### الطلبات (Orders)
| المسار | الوظيفة |
|:---|---|
| `GET /admin/orders` | قائمة الطلبات |
| `GET /admin/orders/{id}` | عرض الطلب |
| `DELETE /admin/orders/{id}` | حذف |
| `POST /admin/orders/{id}/status` | تغيير الحالة |
| `POST /admin/orders/{id}/notes` | إضافة ملاحظة |
| `DELETE /admin/orders/notes/{note}` | حذف ملاحظة |
| `POST /admin/orders/bulk-action` | إجراء جماعي |

حالات الطلب: Pending → Processing → Shipped → Delivered / Cancelled

### الكوبونات (Coupons)
| المسار | الوظيفة |
|:---|---|
| `GET /admin/coupons` | قائمة |
| `POST /admin/coupons` | إنشاء |
| `GET /admin/coupons/{id}/edit` | تعديل |
| `PUT /admin/coupons/{id}` | تحديث |
| `DELETE /admin/coupons/{id}` | حذف |

أنواع الخصم: percentage, fixed

### المستخدمون (Users)
| المسار | الوظيفة |
|:---|---|
| `GET /admin/users` | قائمة |
| `GET /admin/users/{id}` | عرض |
| `GET /admin/users/{id}/edit` | تعديل |
| `PUT /admin/users/{id}` | تحديث |

### التقييمات (Reviews)
| المسار | الوظيفة |
|:---|---|
| `GET /admin/reviews` | قائمة |
| `PATCH /admin/reviews/{id}/status` | نشر/إخفاء |
| `DELETE /admin/reviews/{id}` | حذف |

### العلامات (Tags)
| المسار | الوظيفة |
|:---|---|
| `GET /admin/tags` | قائمة |
| `POST /admin/tags` | إنشاء |
| `PUT /admin/tags/{id}` | تحديث |
| `DELETE /admin/tags/{id}` | حذف |

### الصفحات (Pages)
| المسار | الوظيفة |
|:---|---|
| `GET /admin/pages` | قائمة |
| `POST /admin/pages` | إنشاء |
| `GET /admin/pages/{id}/edit` | تعديل |
| `PUT /admin/pages/{id}` | تحديث |
| `DELETE /admin/pages/{id}` | حذف |

### الشحن (Shipping)
#### الشركات (Companies)
| المسار | الوظيفة |
|:---|---|
| `GET /admin/shipping` | صفحة الشحن الرئيسية |
| `GET /admin/shipping/companies/create` | إضافة شركة |
| `POST /admin/shipping/companies` | حفظ |
| `GET /admin/shipping/companies/{id}/edit` | تعديل |
| `PUT /admin/shipping/companies/{id}` | تحديث |
| `DELETE /admin/shipping/companies/{id}` | حذف |

#### المناطق (Zones)
| المسار | الوظيفة |
|:---|---|
| `GET /admin/shipping/zones/create` | إضافة منطقة |
| `POST /admin/shipping/zones` | حفظ |
| `GET /admin/shipping/zones/{id}/edit` | تعديل |
| `PUT /admin/shipping/zones/{id}` | تحديث |
| `DELETE /admin/shipping/zones/{id}` | حذف |

#### وسائل الشحن (Methods)
| المسار | الوظيفة |
|:---|---|
| `GET /admin/shipping/methods/create` | إضافة وسيلة |
| `POST /admin/shipping/methods` | حفظ |
| `GET /admin/shipping/methods/{id}/edit` | تعديل |
| `PUT /admin/shipping/methods/{id}` | تحديث |
| `DELETE /admin/shipping/methods/{id}` | حذف |
| `POST /admin/shipping/zones/{zone}/methods` | إضافة وسيلة لمنطقة |

#### البوالص (Labels/Waybills)
| المسار | الوظيفة |
|:---|---|
| `GET /admin/shipping/labels/create` | إنشاء بوليصة |
| `POST /admin/shipping/labels` | حفظ |
| `GET /admin/shipping/labels/{id}` | عرض |
| `POST /admin/shipping/labels/{id}/status` | تحديث الحالة |
| `POST /admin/shipping/labels/{id}/tracking` | إضافة تتبع |
| `GET /admin/shipping/labels/{id}/pdf` | طباعة PDF |
| `POST /admin/shipping/bulk-ship` | شحن جماعي |

### المدفوعات (Payments)
| المسار | الوظيفة |
|:---|---|
| `GET /admin/payments` | قائمة المدفوعات |

### العملات (Currencies)
| المسار | الوظيفة |
|:---|---|
| `GET /admin/currencies` | صفحة العملات |
| `POST /admin/currencies` | تحديث سعر الصرف |

### التقارير (Reports)
| المسار | الوظيفة |
|:---|---|
| `GET /admin/reports` | تقارير المبيعات |

### الإعدادات (Settings)
| المسار | الوظيفة |
|:---|---|
| `GET /admin/settings` | صفحة الإعدادات |
| `POST /admin/settings` | تحديث الإعدادات |
| `POST /admin/settings/remove-image` | حذف الصورة |

الإعدادات تشمل: اسم المتجر، الوصف، الشعار، الأيقونة، كود التحليل (Google Analytics, Facebook Pixel, TikTok Pixel, إلخ)

### التخصيص (Customize)
| المسار | الوظيفة |
|:---|---|
| `GET /admin/customize` | صفحة التخصيص |
| `POST /admin/customize` | حفظ التخصيص |
| `POST /admin/customize/reset` | إعادة التعيين |
| `POST /admin/customize/remove-image` | حذف صورة |

التخصيص يشمل: الألوان الأساسية/الثانوية، الخط، الشعار في التذييل، النصوص المخصصة.

---

## 🛍️ المتجر (Frontend)

### المسارات العامة

| المسار | الوظيفة |
|:---|---|
| `GET /` | الصفحة الرئيسية |
| `GET /shop` | قائمة المنتجات |
| `GET /shop/{slug}` | صفحة المنتج |
| `GET /category/{slug}` | تصنيف معين |
| `GET /page/{slug}` | صفحة ثابتة (عن المتجر، اتصال بنا، FAQ، سياسة الإرجاع) |
| `GET /track` | تتبع الطلب |
| `POST /track` | إرسال رقم التتبع |
| `GET /currency/{code}` | تبديل العملة |

### Auth (غير مسجل)

| المسار | الوظيفة |
|:---|---|
| `GET /login` | تسجيل الدخول |
| `POST /login` | تنفيذ تسجيل الدخول |
| `GET /register` | إنشاء حساب |
| `POST /register` | تنفيذ التسجيل |
| `POST /logout` | تسجيل الخروج |

### سلة التسوق (Cart)

| المسار | الوظيفة |
|:---|---|
| `GET /cart` | عرض السلة |
| `POST /cart` | إضافة منتج |
| `PATCH /cart/{item}` | تحديث الكمية |
| `DELETE /cart/{item}` | حذف منتج |
| `DELETE /cart` | تفريغ السلة |
| `POST /cart/coupon` | تطبيق كوبون |
| `DELETE /cart/coupon` | إزالة كوبون |

### الشراء (Checkout) — يتطلب تسجيل دخول

| المسار | الوظيفة |
|:---|---|
| `GET /checkout` | صفحة إتمام الشراء |
| `POST /checkout/calculate-shipping` | حساب الشحن |
| `POST /checkout/place-order` | تقديم الطلب |

### الطلبات (Orders)

| المسار | الوظيفة |
|:---|---|
| `GET /orders` | قائمة طلباتي |
| `GET /orders/{id}` | تفاصيل الطلب |
| `POST /orders/{id}/cancel` | إلغاء الطلب |

### المفضلة (Wishlist)

| المسار | الوظيفة |
|:---|---|
| `GET /wishlist` | قائمة المفضلة |
| `POST /wishlist` | إضافة منتج |
| `DELETE /wishlist/{product}` | إزالة منتج |

### الحساب الشخصي (Account)

| المسار | الوظيفة |
|:---|---|
| `GET /account` | صفحة الحساب |
| `PUT /account` | تحديث الملف الشخصي |
| `PUT /account/password` | تغيير كلمة المرور |
| `POST /account/address` | إضافة عنوان |
| `POST /account/address/{id}/default` | تعيين عنوان افتراضي |
| `DELETE /account/address/{id}` | حذف عنوان |

### الشراء الفوري (Instant Buy)

| المسار | الوظيفة |
|:---|---|
| `GET /instant` | صفحة الشراء الفوري |
| `GET /instant/{slug}` | شراء فوري لمنتج |
| `POST /instant/calculate` | حساب السعر (AJAX) |
| `POST /instant/coupon` | التحقق من الكوبون (AJAX) |
| `POST /instant/submit` | تقديم طلب الشراء الفوري |
| `GET /order/{number}/thanks` | صفحة الشكر بعد الطلب |

---

## 🌐 API

`/api/` — Laravel Sanctum (token-based auth)

### عامة (Public)

| الطريقة | المسار | الوظيفة |
|:---:|:---|---|
| POST | `/api/auth/register` | تسجيل مستخدم جديد |
| POST | `/api/auth/login` | تسجيل الدخول |
| GET | `/api/products` | قائمة المنتجات |
| GET | `/api/products/{slug}` | تفاصيل منتج |
| GET | `/api/shipping/zones` | مناطق الشحن |
| POST | `/api/shipping/calculate` | حساب تكلفة الشحن |
| GET | `/api/shipping/tracking/{number}` | تتبع شحنة |
| POST | `/api/coupons/validate` | التحقق من كوبون |

### محمية (Auth Required — Bearer Token)

| الطريقة | المسار | الوظيفة |
|:---:|:---|---|
| GET | `/api/user` | بيانات المستخدم الحالي |
| POST | `/api/auth/logout` | تسجيل الخروج |
| GET | `/api/cart` | عرض السلة |
| POST | `/api/cart` | إضافة للسلة |
| PATCH | `/api/cart/{item}` | تحديث عنصر |
| DELETE | `/api/cart/{item}` | حذف عنصر |
| POST | `/api/cart/coupon` | تطبيق كوبون |
| POST | `/api/cart/calculate-shipping` | حساب الشحن |
| GET | `/api/orders` | قائمة الطلبات |
| POST | `/api/orders` | إنشاء طلب |
| GET | `/api/orders/{id}` | تفاصيل طلب |
| POST | `/api/orders/{id}/cancel` | إلغاء طلب |
| GET | `/api/wishlist` | المفضلة |
| POST | `/api/wishlist` | إضافة |
| DELETE | `/api/wishlist/{product}` | إزالة |

---

## 📦 الموديلز (Models)

| الموديل | الجدول | الوظيفة |
|:---|---|:---|
| `User` | `users` | مستخدم (admin, manager, customer) |
| `Category` | `categories` | تصنيفات المنتجات |
| `Product` | `products` | المنتجات (اسم، وصف، سعر، مخزون، أبعاد، خيارات) |
| `ProductImage` | `product_images` | صور المنتج |
| `ProductOption` | `product_options` | خيارات (مقاس، لون) |
| `ProductOptionValue` | `product_option_values` | قيم الخيارات |
| `ProductVariant` | `product_variants` | تحويلات المنتج (سعر/مخزون مختلف) |
| `ProductCustomField` | `product_custom_fields` | حقول مخصصة للمنتج |
| `Tag` | `tags` | العلامات |
| `Cart` | `carts` | سلة التسوق (حسب الجلسة) |
| `CartItem` | `cart_items` | عناصر السلة |
| `Coupon` | `coupons` | كوبونات الخصم |
| `Order` | `orders` | الطلبات (رقم، حالة، إجمالي، مصدر) |
| `OrderItem` | `order_items` | عناصر الطلب |
| `OrderNote` | `order_notes` | ملاحظات الطلب |
| `OrderStatusHistory` | `order_status_history` | تاريخ حالات الطلب |
| `Payment` | `payments` | المدفوعات |
| `ShippingAddress` | `shipping_addresses` | عناوين الشحن |
| `ShippingZone` | `shipping_zones` | مناطق الشحن |
| `ShippingMethod` | `shipping_methods` | وسائل الشحن |
| `ShippingCompany` | `shipping_companies` | شركات الشحن |
| `ShippingLabel` | `shipping_labels` | بوالص الشحن |
| `ShippingTracking` | `shipping_tracking` | تتبع الشحنات |
| `Review` | `reviews` | تقييمات المنتجات |
| `Wishlist` | `wishlists` | المفضلة |
| `Page` | `pages` | الصفحات الثابتة |
| `Notification` | `notifications` | الإشعارات |
| `Role` | `roles` | الأدوار (RBAC) |
| `Permission` | `permissions` | الصلاحيات |
| `Setting` | `settings` | إعدادات المتجر (key-value) |

### العلاقات الرئيسية

- `Product` ← `Category` (Many-to-One)
- `Product` ← `ProductImage` (One-to-Many)
- `Product` ← `ProductOption` → `ProductOptionValue` (One-to-Many through)
- `Product` ← `ProductVariant` (One-to-Many)
- `Product` ← `ProductCustomField` (One-to-Many)
- `Product` ↔ `Tag` (Many-to-Many)
- `Cart` → `CartItem` → `Product` (Has-Many-Through)
- `Order` → `OrderItem` → `Product`
- `Order` → `OrderStatusHistory` (One-to-Many)
- `Order` → `OrderNote` (One-to-Many)
- `Order` → `ShippingLabel` → `ShippingTracking` (One-to-Many through)
- `User` ↔ `Role` ↔ `Permission` (Many-to-Many through)
- `User` → `Review` → `Product`

---

## ⚙️ الخدمات (Services)

| الخدمة | الملف | الوظيفة |
|:---|---|:---|
| `CartService` | `app/Services/CartService.php` | إدارة سلة التسوق، إضافة، تحديث، كوبون |
| `OrderService` | `app/Services/OrderService.php` | إنشاء الطلبات، إدارة الحالات، الإشعارات |
| `ProductService` | `app/Services/ProductService.php` | منطق المنتجات، الفلترة، البحث |
| `ShippingCalculator` | `app/Services/ShippingCalculator.php` | حساب تكاليف الشحن حسب المنطقة والوزن |

### Events / Listeners

| الحدث | المستمع |
|:---|---|
| `OrderStatusChanged` | `SendOrderStatusNotification` |

يقوم بإنشاء `Notification` مع title و message عند تغيير حالة الطلب.

---

## 🏔️ Alpine.js (Stores & Components)

### Stores (حالات عامة)

| الـ Store | الملف | الوظيفة |
|:---|---|:---|
| `cartStore` | `stores/cart.js` | حالة السلة (عدد العناصر، الإجمالي) |
| `wishlistStore` | `stores/wishlist.js` | حالة المفضلة (عدد العناصر) |
| `themeStore` | `stores/theme.js` | حالة الثيم (الوضع الليلي) |
| `toastStore` | `stores/toast.js` | إشعارات Toast |
| `quickViewStore` | `stores/quickView.js` | نافذة المعاينة السريعة |

### Components (مكونات)

| المكون | الوظيفة |
|:---|---|
| `alg-product-card` | بطاقة منتج مع إضافة للسلة/المفضلة |
| `alg-product-gallery` | معرض صور المنتج |
| `alg-quantity-input` | مدخل الكمية (+/-) |
| `alg-add-to-cart` | زر إضافة للسلة مع خيارات |
| `alg-cart-item` | عنصر سلة مع تعديل/حذف |
| `alg-cart-summary` | ملخص السلة (الإجمالي، الكوبون) |
| `alg-checkout-form` | نموذج إتمام الشراء مع حساب الشحن |
| `alg-instant-buy` | نموذج الشراء الفوري الكامل |
| `alg-live-calculator` | حاسبة السعر المباشر (AJAX) |
| `alg-coupon-input` | مدخل كوبون مع تحقق |
| `alg-wishlist-button` | زر المفضلة (قلب) |

---

## ⚙️ الإعدادات (Settings & Customize)

### الإعدادات العامة (`/admin/settings`)
- اسم المتجر والوصف
- الشعار (Logo) والأيقونة (Favicon)
- أكواد التحليل (Google Analytics, Facebook Pixel, TikTok Pixel, Snapchat Pixel, Twitter Pixel)
- أكواد CSS/JS مخصصة (رأس وتذييل)

### التخصيص (`/admin/customize`)
- اللون الأساسي (Primary)
- اللون الثانوي (Secondary)
- الخط (Font Family)
- شعار التذييل (Footer Logo)
- النصوص: التذييل، الحقوق محفوظة

### الإعدادات السريعة (Dashboard Quick Settings)
- العملة الأساسية
- شركة الشحن الافتراضية
- نظام الدفع (COD On/Off)

### ملف `config/ecommerce.php`
جميع الإعدادات المركزية: الدول، الولايات، أسعار الشحن، شركات الشحن، COD، إلخ.

---

## 📜 السكريبتات والاختبارات

| الملف | النوع | الوظيفة |
|:---|---|:---|
| `test_instant.py` | Python E2E | اختبار آلي لعملية الشراء الفوري وحساب الأسعار عبر المتصفح |
| `test_instant.sh` | Bash | سكريبت اختبار سريع curl للـ API |
| `test_site.php` | PHP | اختبار بسيط للصفحات والاستجابات |
| `tests/Feature/ExampleTest.php` | PHPUnit | اختبار Feature |
| `tests/Unit/ExampleTest.php` | PHPUnit | اختبار Unit |

### تشغيل الاختبارات

```bash
# PHPUnit
php artisan test

# Python E2E
python test_instant.py

# Bash API test
bash test_instant.sh

# PHP site check
php test_site.php
```

---

## 🎨 ملفات التصميم (UI/UX)

### المجلدات الرئيسية

| المجلد | المحتوى |
|:---|---|
| `uiux/_1` إلى `uiux/_11` | نماذج واجهات (HTML + صور) |
| `uiux/khaled_store_admin/DESIGN.md` | مواصفات تصميم لوحة الإدارة |
| `_1` إلى `_6` | نماذج إضافية (HTML + صور) |
| `_7/` | نموذج منتج |
| `khaled_store_admin/DESIGN.md` | مواصفات تصميم لوحة الإدارة |
| `stitch_/` | مشروع مستقل (git submodule سابق) |
| `category_listing_with_filters/screen.png` | نموذج عرض تصنيفات مع فلتر |

---

## 💻 أوامر Artisan

| الأمر | الوصف |
|:---|---|
| `php artisan ecommerce:create-admin` | إنشاء حساب مدير/مشرف تفاعلي |
| `php artisan db:seed --class=ShippingZonesSeeder` | شحن مناطق الشحن |
| `php artisan db:seed --force` | شحن البيانات الأساسية |
| `php artisan config:clear` | مسح كاش الإعدادات |
| `php artisan route:clear` | مسح كاش المسارات |
| `php artisan migrate:fresh --seed --force` | إعادة بناء قاعدة البيانات مع البيانات |

---

## 🛡️ الأمان

- **CSRF Protection** مع استثناء مسارات الشراء الفوري
- **Sanctum** لمصادقة API (Bearer Token)
- **RBAC** (Role-Based Access Control) عبر Middleware `role:admin,manager`
- **تشفير** كلمات المرور عبر bcrypt
- **حماية** من SQL Injection عبر Eloquent ORM
- **تشفير** المعرفات في المسارات
- **صلاحيات** دقيقة لكل عملية في لوحة التحكم

### حسابات تجريبية

| الدور | البريد الإلكتروني | كلمة المرور |
|:---|---|:---:|
| Admin | `admin@amarstore.com` | `password` |
| Manager | `manager@amarstore.com` | `password` |
| Customer | `customer@test.com` | `password` |

---

## 🗂️ بنية المجلدات (المجلدات المخصصة)

```
app/
├── Console/Commands/
│   └── CreateAdminCommand.php       # أمر إنشاء مدير
├── Events/
│   └── OrderStatusChanged.php       # حدث تغيير حالة الطلب
├── Http/
│   ├── Controllers/
│   │   ├── Admin/                   # 14 كونترولر للوحة التحكم
│   │   ├── Api/                     # 7 كونترولر API
│   │   ├── AccountController.php    # الحساب الشخصي
│   │   ├── AuthController.php       # المصادقة
│   │   ├── CartController.php       # سلة التسوق
│   │   ├── CheckoutController.php   # إتمام الشراء
│   │   ├── HomeController.php       # الصفحة الرئيسية
│   │   ├── InstantBuyController.php # الشراء الفوري (AJAX)
│   │   ├── OrderController.php      # الطلبات
│   │   ├── PageController.php       # الصفحات الثابتة + التتبع
│   │   ├── ShopController.php       # المتجر والتصنيفات
│   │   └── WishlistController.php   # المفضلة
│   └── Middleware/
│       └── EnsureUserHasRole.php    # Middleware: role check
├── Listeners/
│   └── SendOrderStatusNotification.php  # إرسال إشعار تغيير الحالة
├── Models/                          # 28 موديل
├── Providers/
│   ├── EventServiceProvider.php
│   ├── SiteSettingsServiceProvider.php  # مزود إعدادات الموقع
│   └── AppServiceProvider.php
├── Services/
│   ├── CartService.php
│   ├── OrderService.php
│   ├── ProductService.php
│   └── ShippingCalculator.php
└── Support/
    └── SiteSettings.php             # كلاس دعم الإعدادات

config/
└── ecommerce.php                    # إعدادات المتجر المركزي

database/
├── migrations/                      # 24 ملف تهجير
└── seeders/
    ├── DatabaseSeeder.php           # البيانات الأساسية
    └── ShippingZonesSeeder.php      # مناطق الشحن

resources/
├── css/app.css                      # ملفات Tailwind + مخصص
├── js/
│   ├── alpine/
│   │   ├── components.js            # 11 مكون Alpine
│   │   ├── index.js                 # تهيئة Alpine
│   │   └── stores/                  # 5 stores
│   └── app.js, bootstrap.js
└── views/
    ├── admin/                       # 22 قالب بليد للوحة التحكم
    ├── frontend/                    # 20 قالب بليد للواجهة
    └── welcome.blade.php

routes/
├── web.php                          # 70+ مسار ويب
├── api.php                          # 20+ مسار API
└── console.php                      # أوامر Artisan

uiux/                                # ملفات تصميم واجهات (HTML + صور)
_1/ إلى _7/                           # نماذج إضافية

test_instant.py                      # اختبار E2E للشراء الفوري
test_instant.sh                      # اختبار API
test_site.php                        # فحص الموقع
```

---

**تطوير وإعداد:** عمار ناص (@amar_naas)  
**الإصدار الحالي:** v2.0.0 (يونيو 2026)
