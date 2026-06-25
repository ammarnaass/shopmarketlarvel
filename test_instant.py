"""End-to-end test for the instant-buy flow."""
import re
import json
import urllib.parse
import urllib.request
import http.cookiejar
import sys

BASE = "http://127.0.0.1:8000"

cj = http.cookiejar.CookieJar()
opener = urllib.request.build_opener(urllib.request.HTTPCookieProcessor(cj))

def post(path, payload):
    data = json.dumps(payload).encode('utf-8')
    # Get XSRF token from cookies (already URL-decoded by cookiejar)
    xsrf = None
    for c in cj:
        if c.name == 'XSRF-TOKEN':
            xsrf = urllib.parse.unquote(c.value)
            break
    req = urllib.request.Request(
        BASE + path,
        data=data,
        headers={
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': xsrf or '',
            'X-XSRF-TOKEN': urllib.parse.quote(xsrf) if xsrf else '',
            'Referer': BASE + '/shop/samsung-galaxy-s24',
        },
        method='POST',
    )
    try:
        resp = opener.open(req)
        body = resp.read().decode('utf-8')
        return resp.status, body
    except urllib.error.HTTPError as e:
        return e.code, e.read().decode('utf-8', errors='replace')

def get(path):
    req = urllib.request.Request(BASE + path, headers={'Accept': 'text/html'})
    try:
        resp = opener.open(req)
        return resp.status, resp.read().decode('utf-8')
    except urllib.error.HTTPError as e:
        return e.code, e.read().decode('utf-8', errors='replace')

# 1) Load product page
print("=== 1) Load /shop/samsung-galaxy-s24 ===")
status, body = get('/shop/samsung-galaxy-s24')
print(f"  HTTP {status}, size={len(body)}")
if status != 200:
    print(body[:500])
    sys.exit(1)
print("  OK - product page loaded")

# 2) Calculate
print("\n=== 2) POST /instant/calculate (DZ, 2 qty) ===")
status, body = post('/instant/calculate', {
    'product_id': 1,
    'quantity': 2,
    'country_code': 'DZ',
    'city': 'الجزائر',
    'state_code': '16',
    'shipping_method': 'standard',
    'options': {},
    'custom_text': '',
})
print(f"  HTTP {status}")
print(f"  Body: {body[:300]}")
if status == 200:
    data = json.loads(body)
    assert data['success'], "calculate failed"
    print(f"  Subtotal: {data['subtotal']}, Shipping: {data['shipping_cost']}, Total: {data['total']}")
    assert data['shipping_cost'] == 0, f"Expected 0 DZD (free over 5000) for Alger with 2x, got {data['shipping_cost']}"
    print(f"  ✓ Shipping cost correct (0 DZD - free over 5000 threshold)")

# 3) Validate coupon
print("\n=== 3) POST /instant/coupon WELCOME10 ===")
status, body = post('/instant/coupon', {'code': 'WELCOME10', 'subtotal': 8400})
print(f"  HTTP {status}")
print(f"  Body: {body[:300]}")
if status == 200:
    data = json.loads(body)
    assert data['success'], "coupon failed"
    print(f"  ✓ Coupon applied: {data['coupon']['description']}")

# 4) Submit instant buy (guest)
print("\n=== 4) POST /instant/submit (guest) ===")
status, body = post('/instant/submit', {
    'product_id': 1,
    'first_name': 'كريم',
    'last_name': 'بن علي',
    'email': 'karim.test@example.com',
    'phone': '0555000999',
    'country_code': 'DZ',
    'state_code': '16',
    'city': 'الجزائر',
    'address': 'شارع ديدوش مراد، رقم 25',
    'shipping_method': 'standard',
    'payment_method': 'cod',
    'quantity': 2,
    'options': {},
    'custom_text': 'نقش خاص',
    'coupon_code': 'WELCOME10',
})
print(f"  HTTP {status}")
print(f"  Body: {body[:500]}")
if status == 200:
    data = json.loads(body)
    if data.get('success'):
        print(f"  ✓ Order created: {data['order_number']}")
        print(f"  Redirect: {data['redirect']}")
    else:
        print(f"  ! {data.get('message')}")

print("\n=== All tests complete ===")
