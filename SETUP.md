# Amar Store - دليل الإعداد والتشغيل

متجر إلكتروني متكامل مبني بـ Laravel 13، يدعم 6 دول في شمال أفريقيا، 3 أدوار، 6 صلاحيات، ولوحة إدارة كاملة.

## ⭐ ميزات جديدة (KHALED STORE v2)

- **🛒 نظام الشراء الفوري (Instant Buy)** - نموذج شراء مدمج بالكامل في صفحة المنتج
  - حساب السعر المباشر (Live Calculator) - AJAX
  - دعم الكوبونات الفورية
  - دعم الضيوف (Guest checkout) + العملاء المسجلين
  - تخصيص نصي/ملفي
  - رفع ملفات مخصصة
- **📊 لوحة تحكم محسّنة** - تصميم احترافي مع KPIs، رسوم بيانية، إحصائيات
  - بطاقة 4 KPIs ملوّنة
  - مخطط مبيعات أسبوعية (آخر 7 أيام)
  - توزيع حالات الطلبات
  - أفضل المنتجات مبيعاً
  - كوبونات نشطة + مخزون منخفض
  - إعدادات سريعة (Quick Settings)

---

## 1. المتطلبات الأساسية

- PHP 8.3 أو أعلى (الإضافات: pdo_sqlite أو pdo_mysql، mbstring، openssl، intl)
- Composer 2.x
- SQLite (افتراضي) أو MySQL 8
- Node.js 18+ (اختياري - لتجميع أصول Front-end)

> في هذا المشروع، PHP 8.3.31 في `C:\php83\` (محمول، ليس XAMPP)

---

## 2. خطوات التثبيت

### 2.1 نسخ المشروع وتثبيت الحزم

```bash
cd C:\Users\amarn
# (المشروع موجود في C:\Users\amarn\ecommerce)
cd ecommerce
composer install
```

### 2.2 إعداد ملف البيئة

```bash
copy .env.example .env
php artisan key:generate
```

عدّل ملف `.env` إن لزم الأمر:

```env
APP_NAME="Amar Store"
APP_URL=http://localhost:8000

# قاعدة البيانات
DB_CONNECTION=sqlite
# أو MySQL:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=amar_store
# DB_USERNAME=root
# DB_PASSWORD=

# المتجر
STORE_CURRENCY=SDG
STORE_CURRENCY_SYMBOL=ج.س
STORE_DEFAULT_COUNTRY=SD
STORE_FREE_SHIPPING_THRESHOLD=200

# COD
COD_ENABLED=true
COD_MIN_ORDER=50
COD_MAX_ORDER=5000
COD_EXTRA_FEE=0
```

### 2.3 إنشاء قاعدة البيانات SQLite

```bash
# في Windows PowerShell
New-Item -ItemType File -Path database\database.sqlite -Force

# أو في Git Bash
touch database/database.sqlite
```

### 2.4 تشغيل الـ Migrations و Seeders

```bash
# إنشاء الجداول
php artisan migrate --force

# إدخال البيانات الأولية (الأدوار، المنتجات، التصنيفات، الكوبونات، الشحن)
php artisan db:seed --force

# إدخال مناطق الشحن لجميع الدول الـ 6
php artisan db:seed --class=ShippingZonesSeeder --force
```

ما تحتويه البيانات الأولية:

| المورد | العدد |
|--------|-------|
| أدوار المستخدمين (Roles) | 3 (admin, manager, customer) |
| صلاحيات (Permissions) | 6 |
| مستخدمين تجريبيين | 3 |
| تصنيفات | 8 |
| منتجات | 16 (بعضها variable بمقاسات وألوان) |
| كوبونات خصم | 4 |
| شركات شحن | 6 (Aramex, SMSA, Yalidin, Noest, Sudan Post, Local) |
| مناطق شحن في الـ Config | 12 (SD, DZ, MA, TN, LY, EG) |

---

## 3. إنشاء حساب مدير النظام

### 3.1 الطريقة التفاعلية (موصى بها)

```bash
php artisan ecommerce:create-admin
```

سيُطلب منك إدخال:

- الاسم الكامل
- البريد الإلكتروني
- رقم الهاتف
- كلمة المرور
- رمز الدولة (SD, DZ, MA, TN, LY, EG)
- الدور (admin أو manager)

### 3.2 الطريقة بالأعلام (Flags)

```bash
php artisan ecommerce:create-admin \
  --name="مدير النظام" \
  --email=admin@amarstore.com \
  --phone=0912345678 \
  --password=admin1234 \
  --country=SD \
  --role=admin
```

### 3.3 حساب المدير الافتراضي من Seeder

| الحقل | القيمة |
|-------|--------|
| البريد | `admin@amarstore.com` |
| كلمة المرور | `password` |
| الهاتف | `0911111111` |
| الدور | admin |
| الدولة | SD |

> ⚠️ **مهم:** غيّر كلمة المرور فور تسجيل الدخول في الإنتاج.

### 3.4 حساب مدير متجر (Manager)

```bash
php artisan ecommerce:create-admin \
  --name="مدير المبيعات" \
  --email=manager@amarstore.com \
  --phone=0922222222 \
  --password=manager1234 \
  --country=DZ \
  --role=manager
```

---

## 4. الأدوار والصلاحيات

### 4.1 الفرق بين الأدوار

| الدور | الوصف | الصلاحيات |
|-------|-------|-----------|
| `admin` | مدير النظام | جميع الصلاحيات |
| `manager` | مدير متجر | المنتجات، الطلبات، التصنيفات، الكوبونات |
| `customer` | عميل عادي | لا صلاحيات إدارية (افتراضي عند التسجيل) |

### 4.2 الصلاحيات المتاحة

- `manage_products` - إدارة المنتجات
- `manage_orders` - إدارة الطلبات
- `manage_users` - إدارة المستخدمين
- `manage_categories` - إدارة التصنيفات
- `manage_coupons` - إدارة الكوبونات
- `manage_settings` - إدارة الإعدادات

### 4.3 إضافة دور جديد (مثال)

```php
use App\Models\Role;
use App\Models\Permission;

$editorRole = Role::create([
    'name' => 'editor',
    'display_name' => 'محرر',
    'description' => 'يدير المحتوى والمنتجات فقط',
]);

$perms = Permission::whereIn('name', ['manage_products', 'manage_categories'])
    ->pluck('id')
    ->toArray();
$editorRole->permissions()->sync($perms);
```

### 4.4 التحقق من الصلاحيات في الكود

```php
if (auth()->user()->isAdmin()) { /* ... */ }
if (auth()->user()->isManager()) { /* ... */ }
if (auth()->user()->hasRole('editor')) { /* ... */ }
if (auth()->user()->hasPermission('manage_orders')) { /* ... */ }
```

---

## 5. الدول والولايات المدعومة

المتجر يدعم 6 دول في شمال أفريقيا مع ولاياتها/محافظاتها ورموز الاتصال والعملات المحلية:

| الرمز | الدولة | رمز الاتصال | العملة | عدد الولايات |
|-------|--------|-------------|--------|---------------|
| `DZ` | الجزائر | +213 | DZD (د.ج) | 48 |
| `MA` | المغرب | +212 | MAD (د.م.) | 32 |
| `TN` | تونس | +216 | TND (د.ت) | 24 |
| `LY` | ليبيا | +218 | LYD (د.ل) | 20 |
| `EG` | مصر | +20 | EGP (ج.م) | 27 |
| `SD` | السودان | +249 | SDG (ج.س) | 16 |

عند تسجيل حساب جديد، يختار العميل دولته من القائمة، ويُحفظ:
- `country_code` (مثل `DZ`)
- `state_code` (مثل `16` للجزائر العاصمة)
- يُحفظ الهاتف كاملاً بالصيغة الدولية (`+213555000111`)

### إضافة دولة جديدة

في `config/ecommerce.php`، أضف مفتاح جديد في `countries`:

```php
'SS' => [
    'name' => 'جنوب السودان',
    'name_en' => 'South Sudan',
    'dial_code' => '+211',
    'currency' => 'SSP',
    'currency_symbol' => 'ج.س.ج',
    'states' => [
        '01' => 'جوبا',
        '02' => 'واو',
        // ... إلخ
    ],
],
```

---

## 6. منطق حساب الشحن

### 6.1 كيف يعمل

1. العميل يختار الدولة والمدينة في صفحة الـ Checkout
2. JavaScript يستدعي `POST /checkout/calculate-shipping` مع `city`, `country_code`, `method`
3. الـ Controller يبحث عن منطقة شحن مطابقة في:
   - قاعدة البيانات (الجدول `shipping_zones`)
   - ثم في `config/ecommerce.php` كـ fallback
4. يعرض التكلفة مع رمز العملة المحلية

### 6.2 بنية منطقة الشحن

```php
[
    'name' => 'الجزائر - العاصمة',
    'countries' => ['DZ'],         // مصفوفة الدول (يمكن استخدام '*' للكل)
    'cities' => ['الجزائر', 'البليدة', ...],  // مصفوفة المدن، أو '*' للكل
    'cost' => 400,                  // تكلفة الشحن العادي
    'express_cost' => 700,          // تكلفة الشحن السريع
    'free_threshold' => 5000,       // شحن مجاني إذا تجاوز الطلب هذا المبلغ
],
```

### 6.3 إضافة منطقة شحن جديدة في الـ Database

```bash
php artisan tinker
```

```php
\App\Models\ShippingZone::create([
    'name' => 'تونس - صفاقس',
    'countries' => ['TN'],
    'cities' => ['صفاقس', 'سيدي بوزيد'],
    'regions' => ['*'],   // للتوافق مع القديم
    'cost' => 8,
    'express_cost' => 15,
    'free_threshold' => 120,
    'status' => 'active',
]);
```

### 6.4 تعديل أسعار الشحن

عدّل في `config/ecommerce.php` تحت `shipping.zones` ثم شغّل:

```bash
php artisan config:clear
php artisan db:seed --class=ShippingZonesSeeder --force
```

> 💡 المناطق المعرّفة في الـ Config تُستخدم كـ fallback. المناطق المعرّفة في الـ Database لها الأولوية.

---

## 7. تسجيل حساب عميل

### 7.1 عبر الواجهة

- افتح `/register`
- املأ النموذج:
  - الاسم
  - البريد
  - **الدولة** (dropdown)
  - **الولاية/المحافظة** (dropdown ديناميكي حسب الدولة)
  - **رقم الهاتف** (مع رمز الاتصال يظهر تلقائياً)
  - كلمة المرور
- اضغط "إنشاء حساب"

### 7.2 عبر API

```http
POST /api/auth/register
Content-Type: application/json

{
  "name": "أحمد",
  "email": "ahmed@example.com",
  "phone": "0555000111",
  "country_code": "DZ",
  "state_code": "16",
  "password": "secret123",
  "password_confirmation": "secret123"
}
```

الاستجابة:

```json
{
  "success": true,
  "user": {
    "id": 8,
    "name": "أحمد",
    "email": "ahmed@example.com",
    "phone": "+213555000111",
    "country_code": "DZ",
    "state_code": "16"
  },
  "token": "1|xxxxxxxxxxxxxx"
}
```

### 7.3 الحساب التجريبي

| الدور | البريد | كلمة المرور |
|-------|--------|-------------|
| Admin | `admin@amarstore.com` | `password` |
| Manager | `manager@amarstore.com` | `password` |
| Customer | `customer@test.com` | `password` |

---

## 8. تشغيل خادم التطوير

```bash
cd C:\Users\amarn\ecommerce
C:\php83\php.exe artisan serve --host=127.0.0.1 --port=8000
```

ثم افتح المتصفح على:

- **الواجهة الأمامية (المتجر):** http://127.0.0.1:8000
- **لوحة الإدارة:** http://127.0.0.1:8000/admin
- **حسابي:** http://127.0.0.1:8000/account
- **تسجيل:** http://127.0.0.1:8000/register
- **دخول:** http://127.0.0.1:8000/login

---

## 9. بنية المشروع

```
ecommerce/
├── app/
│   ├── Console/Commands/
│   │   └── CreateAdminCommand.php       # artisan ecommerce:create-admin
│   ├── Http/Controllers/
│   │   ├── AccountController.php        # /account
│   │   ├── AuthController.php           # login, register, logout
│   │   ├── CartController.php
│   │   ├── CheckoutController.php
│   │   ├── HomeController.php
│   │   ├── InstantBuyController.php     # ⭐ /instant/* (Instant Buy)
│   │   ├── OrderController.php
│   │   ├── ShopController.php
│   │   ├── WishlistController.php
│   │   ├── Admin/                       # 6 controllers للوحة الإدارة
│   │   │   └── DashboardController.php  # ⭐ محسّن مع KPIs + charts
│   │   └── Api/                         # API endpoints
│   ├── Models/                          # 22 model
│   │   ├── User.php
│   │   ├── Role.php
│   │   ├── Permission.php
│   │   ├── ShippingAddress.php          # ⭐ + first_name, last_name, email
│   │   ├── ShippingZone.php
│   │   ├── ShippingCompany.php
│   │   ├── Product.php
│   │   ├── Order.php                    # ⭐ + is_instant_buy, guest_email, guest_phone
│   │   ├── OrderItem.php                # ⭐ + options_summary
│   │   └── ...
│   └── Services/
│       ├── CartService.php
│       ├── OrderService.php             # منطق إنشاء الطلب والشحن
│       └── ProductService.php
├── bootstrap/
│   └── app.php                          # ⭐ exclude instant/* من CSRF
├── config/
│   └── ecommerce.php                    # إعدادات الدول، الشحن، COD
├── database/
│   ├── migrations/                      # 15 migration
│   │   └── 2026_06_12_*.php             # ⭐ 4 migrations جديدة
│   ├── seeders/
│   │   ├── DatabaseSeeder.php           # البيانات الأساسية
│   │   └── ShippingZonesSeeder.php      # 12 منطقة شحن
│   └── database.sqlite
├── resources/views/
│   ├── frontend/                        # واجهة المتجر
│   │   ├── auth/                        # login, register
│   │   ├── account/                     # صفحة حسابي
│   │   ├── cart/
│   │   ├── checkout/
│   │   ├── instant/                     # ⭐ صفحة شكر Instant Buy
│   │   ├── orders/
│   │   ├── shop/
│   │   │   └── show.blade.php           # ⭐ نموذج Instant Buy الكامل
│   │   └── wishlist/
│   └── admin/                           # لوحة الإدارة
│       ├── dashboard.blade.php          # ⭐ لوحة محسّنة
│       └── layout.blade.php             # ⭐ Sidebar مُنظَّم
├── routes/
│   ├── web.php
│   └── api.php
├── test_instant.py                      # ⭐ اختبار E2E للشراء الفوري
└── SETUP.md                             # هذا الملف
```

---

## 10. سير العمل الكامل للعميل (Instant Buy)

### 10.1 صفحة المنتج الواحدة

العميل يفتح `/shop/{slug}` ويجد كل شيء في نفس الصفحة:

1. صور المنتج (gallery)
2. تفاصيل + تقييمات
3. **خيارات المنتج** (لون، مقاس، إلخ) - اختيار واحد لكل خيار
4. **تخصيص نصي** (إن وُجد) - حقول نصية/textarea
5. **تخصيص ملف** (إن وُجد) - رفع صورة/مستند
6. **الكمية**
7. **حساب السعر المباشر (Live Calculator)** - يحدّث فوراً عند:
   - تغيير الكمية
   - اختيار خيار
   - كتابة نص مخصص
   - تغيير الدولة/المدينة
   - تغيير طريقة الشحن
   - تطبيق كوبون
8. **بيانات الشحن** (الاسم، اللقب، الهاتف، الدولة، الولاية، المدينة، العنوان)
9. **طريقة الدفع** (COD / تحويل بنكي)
10. **ملاحظات**
11. **كوبون خصم** - تطبيق AJAX
12. **زر "تأكيد الطلب"** - ينشئ الطلب فوراً

### 10.2 حساب السعر المباشر

عند تغيير أي حقل، JavaScript يستدعي `POST /instant/calculate`:

```json
{
  "success": true,
  "base_price": 4200,
  "options_adjustment": 100,
  "options_summary": [
    {"option": "المقاس", "value": "XL", "adjustment": 100}
  ],
  "custom_field_price": 5,
  "quantity": 2,
  "subtotal": 8610,
  "shipping_cost": 400,
  "shipping_free": false,
  "discount": 861,
  "coupon": {"code": "WELCOME10", "type": "percent", "value": 10},
  "total": 8149,
  "currency_symbol": "د.ج"
}
```

### 10.3 إنشاء الطلب

- `POST /instant/submit` (يدعم الضيوف والعملاء)
- ينشئ:
  - `Order` بـ `is_instant_buy=true`
  - `ShippingAddress` (مع `user_id=null` للضيوف)
  - `OrderItem` مع `options_summary` (لون، مقاس، إلخ)
  - `Payment` (COD أو bank_transfer)
  - يخصم المخزون
  - يزيد `Coupon.used_count` إن وُجد كوبون
- يحوّل إلى `/order/{order_number}/thanks` (صفحة شكر)

### 10.4 نقاط نهاية API للشراء الفوري

| المسار | الطريقة | الوصف |
|--------|---------|-------|
| `POST /instant/calculate` | POST | حساب السعر المباشر (AJAX) |
| `POST /instant/coupon` | POST | التحقق من كوبون (AJAX) |
| `POST /instant/submit` | POST | إنشاء الطلب (يدعم AJAX + form submit) |
| `GET /order/{orderNumber}/thanks` | GET | صفحة الشكر للضيوف |

### 10.5 سير العمل التقليدي (للتوافق)

لا يزال `/cart` و `/checkout` يعملان للعملاء الذين يفضلون السلة أولاً.

---

## 11. سير العمل للمدير

### 11.1 لوحة التحكم الجديدة (`/admin`)

- **4 KPIs كبيرة ملوّنة**: المبيعات، الطلبات، العملاء، المنتجات
- **مخطط مبيعات أسبوعية**: أعمدة ملوّنة (آخر 7 أيام)
- **توزيع حالات الطلبات**: 5 ألوان (pending, processing, shipped, delivered, cancelled)
- **جدول الطلبات الحديثة** (آخر 8): رقم، عميل، منتج، مبلغ، حالة، **نوع** (فوري/عادي)
- **مخزون منخفض**: المنتجات التي `stock < 10` (مرتّبة تصاعدياً)
- **أفضل المنتجات مبيعاً**: أكثر 5 منتجات من حيث `quantity * total`
- **كوبونات نشطة**: مع الاستخدام الحالي/الحد
- **إعدادات سريعة**: روابط لتغيير العملة، شركة الشحن، طريقة الدفع، الثيم

### 11.2 الشريط الجانبي (Sidebar)

مُنظَّم في 6 مجموعات:
- (الرئيسية)
- المبيعات: الطلبات، طلبات فورية، الكوبونات
- الكتالوج: المنتجات، التصنيفات، التقييمات
- العملاء: قائمة العملاء
- العمليات: الشحن، المدفوعات
- النظام: العملات، التقارير، الإعدادات، التخصيص

### 11.3 API الإعدادات السريعة

```http
POST /admin/quick-setting
Content-Type: application/json

{
  "key": "shipping_company",
  "value": "smsa"
}
```

الـ `key` المسموح به: `shipping_company`، `store_name`
يقوم بتعديل `.env` تلقائياً ويستدعي `config:clear`.

---

## 12. استكشاف الأخطاء

### 12.1 خطأ "Class 'Redis' not found"
عادي إذا لم تستخدم Redis. الـ Cache الحالي يستخدم `database` driver افتراضياً.

### 12.2 خطأ "SQLSTATE[HY000] [1049] Unknown database"
أنشئ قاعدة البيانات MySQL يدوياً:
```sql
CREATE DATABASE amar_store CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 12.3 خطأ "Vite manifest not found"
شغّل `npm install && npm run build`، أو تجاهل (التطبيق يعمل بدونه).

### 12.4 خطأ "Route [checkout.shipping] not defined"
امسح الكاش:
```bash
php artisan route:clear
php artisan config:clear
php artisan cache:clear
```

### 12.5 نسيت كلمة مرور المدير

```bash
php artisan tinker
$u = \App\Models\User::where('email', 'admin@amarstore.com')->first();
$u->password = \Illuminate\Support\Facades\Hash::make('newpassword');
$u->save();
echo "تم التحديث";
```

---

## 13. أوامر Artisan المفيدة

```bash
# المستخدمون
php artisan ecommerce:create-admin                    # إنشاء مدير تفاعلياً
php artisan user:list                                 # عرض كل المستخدمين (افتراضي Laravel)

# قاعدة البيانات
php artisan migrate:fresh --seed --force              # حذف كل شيء وإعادة الإنشاء
php artisan db:seed --class=ShippingZonesSeeder       # إعادة زرع مناطق الشحن

# الكاش
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# روابط الـ Storage
php artisan storage:link

# معلومات النظام
php artisan about
```

---

## 14. الإنتاج (Production)

### 14.1 قائمة المهام

```bash
# 1. تحسين أداء Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 2. رابط storage
php artisan storage:link

# 3. صلاحيات المجلدات (في Linux)
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# 4. APP_DEBUG=false في .env
APP_ENV=production
APP_DEBUG=false

# 5. استخدم MySQL بدلاً من SQLite للإنتاج
```

### 14.2 جدار الحماية الموصى به

- Rate limiting على `POST /login` و `POST /register`: Laravel `throttle:5,1`
- CSRF protection مفعّل تلقائياً
- استخدام HTTPS فقط
- كلمات مرور قوية (8+ أحرف، حروف، أرقام، رموز)

---

## 15. الترخيص والمساهمة

هذا المشروع ملكية خاصة لـ **Amar Naas** (@amar_naas).

للإبلاغ عن مشاكل أو طلب ميزات جديدة، تواصل عبر:
- Twitter: @amar_naas
- Telegram: @amar_naas

---

**تم إعداد هذا الدليل في:** يونيو 2026
**إصدار Laravel:** 13.x
**إصدار PHP:** 8.3.31
**قاعدة البيانات:** SQLite (افتراضي) / MySQL 8 (إنتاج)
