# MySQL 8 - دليل التحويل والإعداد

دليل كامل لتحويل المشروع من SQLite إلى MySQL 8 (أو MariaDB 10.5+ المتوافق).

---

## 1. التثبيت - 3 خيارات

### الخيار A: MySQL 8 عبر Docker (موصى به)

```bash
# تشغيل MySQL 8 مع volume للبيانات
docker run -d \
  --name amar-mysql \
  -e MYSQL_ROOT_PASSWORD=*** \
  -e MYSQL_DATABASE=amar_store \
  -e MYSQL_USER=amar \
  -e MYSQL_PASSWORD=*** \
  -p 3306:3306 \
  -v amar_mysql_data:/var/lib/mysql \
  --restart unless-stopped \
  mysql:8.0 \
  --default-authentication-plugin=mysql_native_password \
  --character-set-server=utf8mb4 \
  --collation-server=utf8mb4_unicode_ci
```

### الخيار B: MariaDB عبر Docker (أخف وأسرع)

```bash
docker run -d \
  --name amar-mysql \
  -e MARIADB_ROOT_PASSWORD=*** \
  -e MARIADB_DATABASE=amar_store \
  -e MARIADB_USER=amar \
  -e MARIADB_PASSWORD=*** \
  -p 3306:3306 \
  -v amar_mysql_data:/var/lib/mysql \
  --restart unless-stopped \
  mariadb:11
```

### الخيار C: MySQL Community Server على Windows مباشرة

1. حمّل MySQL Installer من: https://dev.mysql.com/downloads/installer/
2. اختر "Custom" ثم اختر: MySQL Server 8.0 + MySQL Workbench
3. في خطوة "Accounts and Roles": اضبط root password
4. أضف مستخدم: اسم `amar`، كلمة مرور `***`، صلاحية كاملة على `amar_store`
5. شغّل MySQL كـ Windows Service

---

## 2. إنشاء قاعدة البيانات

### عبر MySQL CLI

```bash
mysql -u root -p
```

```sql
CREATE DATABASE amar_store CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'amar'@'localhost' IDENTIFIED BY '***';
GRANT ALL PRIVILEGES ON amar_store.* TO 'amar'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### عبر Docker مباشرة

يتم إنشاء القاعدة والمستخدم تلقائياً عند تشغيل `docker run` بالأعلام أعلاه.

### عبر phpMyAdmin / MySQL Workbench

1. افتح phpMyAdmin على http://localhost/phpmyadmin
2. New → اسم: `amar_store` → Collation: `utf8mb4_unicode_ci` → Create
3. Privileges → Add user → `amar`@`localhost` → كلمة مرور → Grant all

---

## 3. إعداد Laravel

### 3.1 ملف `.env`

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=amar_store
DB_USERNAME=amar
DB_PASSWORD=***
```

### 3.2 التحقق من الاتصال

```bash
cd C:\Users\amarn\ecommerce
C:\php83\php.exe artisan db:show
```

الناتج المتوقع:
```
Database
  Database name:  amar_store
  Host:           127.0.0.1
  Port:           3306
  ...

Tables
  (فارغ - أول تشغيل)
```

---

## 4. نقل البيانات من SQLite (اختياري)

إذا كان لديك بيانات في SQLite وتريد نقلها إلى MySQL:

### 4.1 تثبيت أدوات إضافية

```bash
# في المشروع (مرة واحدة)
composer require --dev wnx/dump-to-mysql
# أو
composer require --dev cyrildewit/eloquent-incremental
```

### 4.2 التصدير من SQLite

```bash
# إنشاء dump من SQLite
php artisan db:dump --database=sqlite
# ينشئ storage/app/db-dumps/sqlite-{timestamp}.sql.gz
```

### 4.3 الاستيراد إلى MySQL

```bash
# تحويل الـ SQL من SQLite إلى MySQL syntax
# ثم استيراده
mysql -u amar -p amar_store < converted_dump.sql
```

### 4.4 طريقة يدوية (أبسط)

```bash
# 1) شغّل migrations على MySQL
php artisan migrate --force

# 2) شغّل seeders
php artisan db:seed --force
php artisan db:seed --class=ShippingZonesSeeder --force
```

> 💡 يفضل إعادة الـ seed في MySQL من الصفر للحصول على charset موحد.

---

## 5. تنفيذ الـ Migrations

```bash
# مسح الـ cache
php artisan config:clear
php artisan cache:clear

# تشغيل كل الـ migrations
php artisan migrate --force
```

### الجداول المتوقعة (15 جدول):

| الجدول | الوصف |
|--------|-------|
| `users` | المستخدمين (admin, manager, customer) |
| `password_reset_tokens` | استعادة كلمة المرور |
| `sessions` | جلسات Laravel |
| `cache` | كاش Laravel |
| `cache_locks` | قفل الكاش |
| `jobs` | Queue Jobs |
| `job_batches` | دفعات الـ Queue |
| `failed_jobs` | الوظائف الفاشلة |
| `categories` | تصنيفات المنتجات |
| `products` | المنتجات |
| `product_images` | صور المنتج |
| `product_options` | خيارات (لون، مقاس) |
| `product_option_values` | قيم الخيارات |
| `product_variants` | متغيرات المنتج |
| `product_custom_fields` | حقول مخصصة |
| `shipping_addresses` | عناوين الشحن |
| `shipping_zones` | مناطق الشحن |
| `shipping_companies` | شركات الشحن |
| `carts` | سلات التسوق |
| `cart_items` | عناصر السلة |
| `coupons` | كوبونات الخصم |
| `orders` | الطلبات |
| `order_items` | عناصر الطلب |
| `payments` | المدفوعات |
| `reviews` | التقييمات |
| `wishlists` | المفضلة |
| `notifications` | الإشعارات |
| `roles` | أدوار المستخدمين |
| `permissions` | الصلاحيات |
| `role_user` | ربط المستخدمين بالأدوار |
| `permission_role` | ربط الأدوار بالصلاحيات |
| `personal_access_tokens` | توكنات Sanctum |

---

## 6. تشغيل الـ Seeders

```bash
# البيانات الأساسية (3 مستخدمين، 16 منتج، 8 تصنيفات، 4 كوبونات)
php artisan db:seed --force

# 12 منطقة شحن في 6 دول
php artisan db:seed --class=ShippingZonesSeeder --force
```

---

## 7. إنشاء حساب مدير

```bash
# تفاعلي
php artisan ecommerce:create-admin

# بالأعلام
php artisan ecommerce:create-admin \
  --name="مدير النظام" \
  --email=admin@amarstore.com \
  --phone=0912345678 \
  --password=admin1234 \
  --country=SD \
  --role=admin
```

---

## 8. اختبار MySQL

### 8.1 اختبار الاتصال

```bash
php artisan tinker --execute='echo DB::connection()->getDatabaseName();'
# يجب أن يطبع: amar_store
```

### 8.2 اختبار الـ charset

```bash
php artisan tinker --execute='
$tables = DB::select("SHOW TABLE STATUS");
foreach ($tables as $t) {
    echo $t->Name . " => " . $t->Collation . PHP_EOL;
}
'
```

### 8.3 اختبار E2E

```bash
python test_instant.py
```

---

## 9. ملاحظات هامة حول MySQL

### 9.1 Engine

Laravel يستخدم `InnoDB` بشكل افتراضي (يدعم foreign keys + transactions).

### 9.2 Charset

كل الجداول تُنشأ بـ `utf8mb4_unicode_ci`:
- يدعم كل الحروف العربية
- يدعم الإيموجي (4 bytes)

### 9.3 Strict Mode

`config/database.php` يفعّل `'strict' => true` مما يعني:
- لا يحفظ بيانات مقطوعة (truncated)
- يرفض القيم الخاطئة بدلاً من تجاهلها
- يفرض NOT NULL على كل الحقول

### 9.4 ENUM columns

MySQL يدعم ENUM لكن Laravel 11+ يحوّلها إلى `varchar` مع CHECK constraint.
إذا ظهر تحذير، شغّل:

```bash
# إجبار ENUM التقليدية (اختياري)
# عدّل migrations لتستخدم ->enum()
```

### 9.5 JSON columns

MySQL 5.7+ و 8.0 يدعمان JSON الأصلي. كل أعمدة `json()` ستُنشأ كـ `JSON` (وليست TEXT).

### 9.6 أداء InnoDB

```sql
-- فحص حالة الجداول
SHOW TABLE STATUS FROM amar_store;

-- إضافة index يدوياً إذا لزم
ALTER TABLE orders ADD INDEX idx_created_status (created_at, status);
```

---

## 10. استكشاف الأخطاء

### 10.1 خطأ "SQLSTATE[HY000] [2002]"

- MySQL لا يعمل. شغّله:
  - Docker: `docker start amar-mysql`
  - Windows: Services → MySQL → Start

### 10.2 خطأ "Access denied for user 'amar'@'localhost'"

- كلمة المرور خاطئة أو المستخدم غير موجود
- تأكد من:
  ```bash
  mysql -u amar -p*** amar_store
  ```

### 10.3 خطأ "Unknown database 'amar_store'"

- أنشئ القاعدة:
  ```sql
  CREATE DATABASE amar_store CHARACTER SET utf8mb4;
  ```

### 10.4 خطأ "Specified key was too long"

- بعض indexes تتجاوز 767 bytes على MySQL القديمة
- الحل: استخدم `DB_UNIQUE_CHECKS=false` و `DB_FOREIGN_KEY_CHECKS=false` مؤقتاً، أو قلل طول الـ index:
  ```php
  // في migration
  $table->string('email', 191)->unique();
  ```

### 10.5 خطأ "Connection refused" على Windows

- تحقق من أن MySQL يستمع على 3306:
  ```bash
  netstat -an | findstr 3306
  ```
- إذا لم يكن، شغّل في `my.ini`:
  ```ini
  [mysqld]
  bind-address = 127.0.0.1
  port = 3306
  ```

### 10.6 بطء شديد

- تحقق من الـ indexes
- فعّل query log:
  ```env
  DB_LOG_QUERIES=true
  ```

---

## 11. النسخ الاحتياطي

### 11.1 backup يدوي

```bash
mysqldump -u amar -p amar_store > backup_$(date +%Y%m%d).sql
```

### 11.2 استعادة

```bash
mysql -u amar -p amar_store < backup_20260612.sql
```

### 11.3 backup تلقائي يومي (Windows Task Scheduler)

```bash
# backup-mysql.bat
@echo off
set TIMESTAMP=%date:~-4%%date:~3,2%%date:~0,2%
"C:\Program Files\MySQL\MySQL Server 8.0\bin\mysqldump.exe" -u amar -p*** amar_store > "D:\backups\amar_%TIMESTAMP%.sql"
```

---

## 12. Migration من MySQL إلى SQLite (عكس)

إذا احتجت للرجوع لـ SQLite:

```bash
# 1) غيّر .env
DB_CONNECTION=sqlite
DB_DATABASE=C:\Users\amarn\ecommerce\database\database.sqlite

# 2) أنشئ ملف قاعدة جديد
touch database/database.sqlite

# 3) شغّل migrations
php artisan migrate --force

# 4) شغّل seeders
php artisan db:seed --force
php artisan db:seed --class=ShippingZonesSeeder --force
```

> البيانات لن تنتقل تلقائياً - تحتاج لتصدير/استيراد يدوي.

---

**تم إعداد هذا الدليل في:** يونيو 2026
**يدعم:** MySQL 5.7+ / 8.0 / MariaDB 10.5+
**UTF8MB4:** مُفعّل افتراضياً
**Engine:** InnoDB
