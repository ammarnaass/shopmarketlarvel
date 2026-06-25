#!/bin/bash
set -e
cd /c/Users/amarn/ecommerce
BASE="http://127.0.0.1:8000"
COOKIES=/tmp/test_cookies.txt
rm -f $COOKIES

echo "=== 1) Load product page ==="
curl -s -c $COOKIES $BASE/shop/samsung-galaxy-s24 > /tmp/show.html
echo "Loaded. Size: $(wc -c < /tmp/show.html) bytes"

# Extract XSRF-TOKEN from cookies and decode
XSRF=$(grep XSRF-TOKEN $COOKIES | awk '{print $7}')
# Need to URL-decode
DECODED=$(python3 -c "import urllib.parse; print(urllib.parse.unquote('$XSRF'))" 2>/dev/null || echo "$XSRF")
echo "XSRF: ${DECODED:0:50}..."

echo ""
echo "=== 2) Calculate (DZ, 2 qty) ==="
curl -s -b $COOKIES -X POST $BASE/instant/calculate \
  -H "Content-Type: application/json" -H "Accept: application/json" -H "X-Requested-With: XMLHttpRequest" \
  -H "X-XSRF-TOKEN: $DECODED" \
  --data-raw '{"product_id":1,"quantity":2,"country_code":"DZ","city":"الجزائر","shipping_method":"standard"}'
echo ""

echo "=== 3) Validate coupon WELCOME10 ==="
curl -s -b $COOKIES -X POST $BASE/instant/coupon \
  -H "Content-Type: application/json" -H "Accept: application/json" -H "X-Requested-With: XMLHttpRequest" \
  -H "X-XSRF-TOKEN: $DECODED" \
  --data-raw '{"code":"WELCOME10","subtotal":8400}'
echo ""

echo "=== 4) Submit instant-buy (guest) ==="
curl -s -b $COOKIES -X POST $BASE/instant/submit \
  -H "Accept: application/json" -H "X-Requested-With: XMLHttpRequest" \
  -H "X-XSRF-TOKEN: $DECODED" \
  --data-raw '{
    "product_id":1,
    "first_name":"كريم",
    "last_name":"بن علي",
    "email":"karim.test@example.com",
    "phone":"0555000999",
    "country_code":"DZ",
    "state_code":"16",
    "city":"الجزائر",
    "address":"شارع ديدوش مراد، رقم 25",
    "shipping_method":"standard",
    "payment_method":"cod",
    "quantity":2,
    "options":{},
    "custom_text":"نقش خاص على المنتج",
    "coupon_code":"WELCOME10"
  }' | head -c 500
echo ""
