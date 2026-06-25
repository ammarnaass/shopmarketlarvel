# Redis (Cache + Queue + Sessions) - دليل الإعداد

دليل كامل لتحويل الـ Cache, Queue, Sessions من قاعدة البيانات إلى Redis (الأنسب للأداء العالي).

---

## 1. التثبيت

### الخيار A: Redis عبر Docker (موصى به)

```bash
docker run -d \
  --name amar-redis \
  -p 6379:6379 \
  -v amar_redis_data:/data \
  --restart unless-stopped \
  redis:7-alpine
```

### الخيار B: Redis native على Windows

حمّل من https://github.com/microsoftarchive/redis/releases (Redis 3.0)
أو استخدم Memurai: https://www.memurai.com/

### الخيار C: Redis في WSL

```bash
wsl -d Ubuntu-24.04 -- bash -c "sudo apt install -y redis-server && sudo service redis-server start"
```

### التحقق

```bash
docker exec amar-redis redis-cli ping
# PONG
```

---

## 2. إعدادات Laravel

### 2.1 تثبيت Predis (pure-PHP client)

**لماذا Predis وليس phpredis؟**
- لا يحتاج extension C مثبت
- يعمل على أي نظام
- سرعة كافية لمعظم المشاريع

```bash
composer require predis/predis
```

> **اختياري**: إذا أردت phpredis (أسرع 2x):
> - حمّل `php_redis.dll` من https://pecl.php.net/package/redis
> - ضعها في `C:\php83\ext\`
> - أضف `extension=php_redis.dll` في `php.ini`
> - غيّر `REDIS_CLIENT=phpredis` في `.env`

### 2.2 ملف `.env`

```env
# ===== Cache =====
CACHE_STORE=redis
CACHE_PREFIX=amar_store

# ===== Queue =====
QUEUE_CONNECTION=redis
REDIS_QUEUE_CONNECTION=queue
REDIS_QUEUE=default

# ===== Sessions =====
SESSION_DRIVER=redis
SESSION_CONNECTION=session
SESSION_LIFETIME=120

# ===== Redis client =====
REDIS_CLIENT=predis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
REDIS_PASSWORD=null

# ===== Redis DBs (لفصل البيانات) =====
REDIS_DB=0           # default
REDIS_CACHE_DB=1     # cache
REDIS_SESSION_DB=2   # sessions
REDIS_QUEUE_DB=3     # queues
```

### 2.3 إضافة `session` و `queue` connections

في `config/database.php`، أضف بعد connection `cache`:

```php
'session' => [
    'url' => env('REDIS_URL'),
    'host' => env('REDIS_HOST', '127.0.0.1'),
    'password' => env('REDIS_PASSWORD'),
    'port' => env('REDIS_PORT', '6379'),
    'database' => env('REDIS_SESSION_DB', '2'),
],

'queue' => [
    'url' => env('REDIS_URL'),
    'host' => env('REDIS_HOST', '127.0.0.1'),
    'password' => env('REDIS_PASSWORD'),
    'port' => env('REDIS_PORT', '6379'),
    'database' => env('REDIS_QUEUE_DB', '3'),
],
```

---

## 3. بنية Redis DBs

| DB | الاستخدام | المدة | Key prefix |
|----|-----------|--------|-----------|
| **DB 0** | default (متعدد الاستخدامات) | بدون TTL افتراضي | `amar-store-database-` |
| **DB 1** | Cache (config, queries, html fragments) | TTL مخصص لكل key | `amar-store-database-` |
| **DB 2** | Sessions (CSRF, flash data, user session) | 120 دقيقة (SESSION_LIFETIME) | `amar-store-database-` |
| **DB 3** | Queue jobs (pending, delayed, reserved) | حتى المعالجة | `amar-store-database-` |

> **Key prefix** = `amar-store-database-` (مشتق من `Str::slug(env('APP_NAME'))`)

---

## 4. التحقق

### 4.1 التحقق من الاتصالات

```bash
php artisan tinker --execute='
foreach (["default", "cache", "session", "queue"] as $c) {
    try {
        \Illuminate\Support\Facades\Redis::connection($c)->ping();
        echo "✓ $c: OK" . PHP_EOL;
    } catch (\Throwable $e) {
        echo "✗ $c: " . $e->getMessage() . PHP_EOL;
    }
}
'
```

### 4.2 التحقق من Cache

```bash
php artisan tinker --execute='
\Illuminate\Support\Facades\Cache::put("test", "hello", 60);
echo "Cache: " . \Illuminate\Support\Facades\Cache::get("test") . PHP_EOL;
\Illuminate\Support\Facades\Cache::forget("test");
'
# يفترض: hello
```

### 4.3 التحقق من Session

```bash
php artisan tinker --execute='
echo "Driver: " . config("session.driver") . PHP_EOL;
echo "Connection: " . config("session.connection") . PHP_EOL;
'
# يفترض:
# Driver: redis
# Connection: session
```

### 4.4 التحقق من Queue

```bash
# Dispatch a test job
php artisan tinker --execute='
\App\Jobs\YourJob::dispatch();
echo "Job dispatched" . PHP_EOL;
'

# Check queue size
docker exec amar-redis redis-cli -n 3 LLEN 'amar-store-database-queues:default'

# Process one job
php artisan queue:work --once --queue=default
```

---

## 5. تشغيل الـ Queue Worker

### 5.1 يدوي (للتطوير)

```bash
php artisan queue:work --queue=default --tries=3 --max-time=3600
```

### 5.2 كـ Windows Service (Supervisor-like)

أنشئ ملف `run-queue-worker.bat`:

```bat
@echo off
:loop
C:\php83\php.exe C:\Users\amarn\ecommerce\artisan queue:work --queue=default --tries=3 --sleep=3 --max-time=3600
timeout /t 5 /nobreak
goto loop
```

ثم شغّله عبر Task Scheduler:
1. افتح Task Scheduler
2. Create Task → اسم "Laravel Queue Worker"
3. Trigger: At system startup
4. Action: Start a program → `C:\Users\amarn\ecommerce\run-queue-worker.bat`
5. ✅ Run whether user is logged in or not

### 5.3 كـ Background Process (WSL)

```bash
wsl -d Ubuntu-24.04 -- bash -c "cd /mnt/c/Users/amarn/ecommerce && php artisan queue:work --daemon"
```

### 5.4 Supervisor (في Linux/Production)

```ini
; /etc/supervisor/conf.d/amar-worker.conf
[program:amar-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/amar/artisan queue:work --queue=default --tries=3 --sleep=3
autostart=true
autorestart=true
numprocs=2
user=www-data
redirect_stderr=true
stdout_logfile=/var/log/amar-worker.log
stopwaitsecs=3600
```

---

## 6. أوامر مفيدة

### 6.1 إدارة الـ Queue

```bash
# عرض جميع الـ queues
php artisan queue:monitor

# عدد jobs في الانتظار
docker exec amar-redis redis-cli -n 3 LLEN 'amar-store-database-queues:default'

# إعادة job فاشلة
php artisan queue:retry all

# مسح جميع jobs فاشلة
php artisan queue:flush

# مسح جميع jobs من queue معين
php artisan queue:clear --queue=default
```

### 6.2 إدارة الـ Cache

```bash
# مسح كل الـ cache
php artisan cache:clear

# مسح مفتاح محدد
docker exec amar-redis redis-cli -n 1 DEL "amar-store-database-key_name"

# عرض جميع المفاتيح في DB 1
docker exec amar-redis redis-cli -n 1 KEYS '*' | head -20
```

### 6.3 إدارة الـ Sessions

```bash
# مسح كل الجلسات
docker exec amar-redis redis-cli -n 2 FLUSHDB

# عرض عدد الجلسات النشطة
docker exec amar-redis redis-cli -n 2 DBSIZE

# تفاصيل جلسة محددة
docker exec amar-redis redis-cli -n 2 GET 'amar-store-database-amar_storeXXXXX' | head -200
```

---

## 7. الأداء

### 7.1 مقارنة مع Database

| العملية | Database | Redis | الفرق |
|---------|----------|-------|-------|
| Session read | 5-20ms (SQL query) | 0.1-0.5ms | **10-50x** أسرع |
| Session write | 10-30ms | 0.2-1ms | **15-50x** أسرع |
| Cache get | 2-10ms | 0.1-0.3ms | **10-30x** أسرع |
| Queue push | 5-15ms (INSERT) | 0.1-0.3ms | **20-50x** أسرع |
| Queue pop | 5-15ms (SELECT) | 0.1-0.3ms | **20-50x** أسرع |

### 7.2 نصائح الأداء

```env
# في .env
REDIS_MAX_RETRIES=3
REDIS_BACKOFF_ALGORITHM=decorrelated_jitter
REDIS_BACKOFF_BASE=100
REDIS_BACKOFF_CAP=1000

# Connection pool (في production)
REDIS_PERSISTENT=true
```

### 7.3 Memory limit

```bash
# Redis max memory
docker exec amar-redis redis-cli CONFIG SET maxmemory 256mb
docker exec amar-redis redis-cli CONFIG SET maxmemory-policy allkeys-lru
```

---

## 8. المراقبة

### 8.1 Redis CLI

```bash
docker exec -it amar-redis redis-cli
> MONITOR         # مشاهدة كل الأوامر
> INFO stats      # إحصائيات
> INFO keyspace   # عدد المفاتيح في كل DB
> SLOWLOG GET 10  # أبطأ 10 أوامر
```

### 8.2 Laravel Telescope (اختياري)

```bash
composer require laravel/telescope --dev
php artisan telescope:install
php artisan migrate
# ثم: http://localhost:8000/telescope
```

### 8.3 RedisInsight (UI)

حمّل من https://redis.com/redis-enterprise/redis-insight/

---

## 9. الأمان

### 9.1 تفعيل كلمة المرور

```bash
# في Redis CLI
CONFIG SET requirepass "your_strong_password"
CONFIG REWRITE
```

```env
# في .env
REDIS_PASSWORD=your_strong_password
```

### 9.2 تقييد الوصول

```bash
# bind إلى localhost فقط
docker exec -it amar-redis redis-cli CONFIG SET bind 127.0.0.1
# أو في docker-compose:
ports:
  - "127.0.0.1:6379:6379"  # لا تعرضه على الشبكة
```

### 9.3 TLS (Redis 6+)

```bash
docker run -d --name amar-redis \
  -p 6380:6380 \
  -v /path/to/certs:/certs \
  redis:7-alpine \
  --tls-port 6380 \
  --tls-cert-file /certs/redis.crt \
  --tls-key-file /certs/redis.key \
  --tls-ca-cert-file /certs/ca.crt
```

---

## 10. استكشاف الأخطاء

### 10.1 خطأ "Connection refused"

- Redis لا يعمل:
  ```bash
  docker ps | grep amar-redis
  docker start amar-redis
  ```

### 10.2 خطأ "AUTH required"

- أعدت تعيين كلمة مرور في Redis لكن لم تحدّث `.env`:
  ```env
  REDIS_PASSWORD=your_actual_password
  ```

### 10.3 خطأ "READONLY"

- Redis في وضع replica فقط
- تأكد من أنك تتصل بـ master

### 10.4 الجلسات لا تنتهي

- الـ TTL لم يضبط
- تحقق: `docker exec amar-redis redis-cli -n 2 TTL <key>`

### 10.5 الـ Queue بطيء جداً

- استخدم `php artisan queue:work --queue=high,default` للترتيب
- ضع الـ `high` queue على workers أسرع

### 10.6 امتلاء الذاكرة

```bash
# عرض أكبر 10 مفاتيح
docker exec amar-redis redis-cli --bigkeys

# مسح مفاتيح منتهية
docker exec amar-redis redis-cli --scan --pattern 'amar-store*' | xargs -L 1000 docker exec amar-redis redis-cli DEL
```

---

## 11. Migration من Database إلى Redis

### 11.1 Cache

```bash
# ليس هناك بيانات cache يجب نقلها - الـ cache مؤقت
php artisan cache:clear
# غيّر CACHE_STORE=database → CACHE_STORE=redis
```

### 11.2 Sessions

```bash
# مسح الجلسات القديمة (المستخدمون سيُسجلون دخولهم من جديد)
php artisan tinker --execute='
\DB::table("sessions")->truncate();
'
# غيّر SESSION_DRIVER=database → SESSION_DRIVER=redis
```

### 11.3 Queue

```bash
# إكمال أي jobs في database queue أولاً
php artisan queue:work --once  # or drain

# ثم غيّر QUEUE_CONNECTION=database → QUEUE_CONNECTION=redis
```

---

## 12. Production checklist

- [x] Redis container مع `restart: unless-stopped`
- [x] Volume للبيانات (`-v amar_redis_data:/data`)
- [x] Password مفعّل
- [x] Bind إلى 127.0.0.1 فقط
- [x] maxmemory محدد + LRU policy
- [x] Queue worker كـ service (Task Scheduler / Supervisor)
- [x] `php artisan config:cache` لأداء أفضل
- [x] `php artisan route:cache` لأداء أفضل
- [x] `php artisan view:cache` لأداء أفضل
- [x] Redis monitoring (RedisInsight أو Grafana)

---

**تم إعداده:** يونيو 2026
**النسخة:** Redis 7.x (latest stable)
**العميل:** Predis 3.x (pure-PHP)
**يدعم:** Laravel 11 / 12 / 13
