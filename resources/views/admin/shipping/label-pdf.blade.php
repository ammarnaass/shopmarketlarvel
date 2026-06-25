<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>بوليصة شحن - {{ $label->tracking_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 11px; color: #333; }
        .label { width: 100%; padding: 15px; border: 2px solid #333; }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 10px; }
        .store-name { font-size: 18px; font-weight: bold; }
        .tracking-box { background: #f0f0f0; padding: 8px 15px; border: 1px solid #999; text-align: center; }
        .tracking-number { font-size: 16px; font-weight: bold; font-family: monospace; }
        .section { margin-bottom: 10px; }
        .section-title { font-weight: bold; font-size: 10px; color: #666; border-bottom: 1px solid #ddd; padding-bottom: 3px; margin-bottom: 5px; }
        .row { display: flex; margin-bottom: 3px; }
        .label-col { width: 80px; color: #666; font-size: 10px; }
        .value-col { flex: 1; font-weight: bold; }
        .barcode { text-align: center; margin-top: 10px; padding-top: 10px; border-top: 1px dashed #999; }
        .barcode-text { font-family: monospace; font-size: 14px; letter-spacing: 3px; }
        .footer { text-align: center; font-size: 9px; color: #999; margin-top: 10px; border-top: 1px solid #ddd; padding-top: 5px; }
        .items-table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        .items-table th, .items-table td { border: 1px solid #ddd; padding: 3px 6px; font-size: 10px; text-align: right; }
        .items-table th { background: #f5f5f5; }
    </style>
</head>
<body>
    <div class="label">
        <div class="header">
            <div class="store-name">{{ config('app.name') }}</div>
            <div class="tracking-box">
                <div style="font-size: 9px; color: #666;">رقم التتبع</div>
                <div class="tracking-number">{{ $label->tracking_number }}</div>
            </div>
        </div>

        <div style="display: flex; gap: 15px;">
            <div class="section" style="flex: 1;">
                <div class="section-title">بيانات المرسل</div>
                <div class="row"><span class="label-col">المتجر:</span><span class="value-col">{{ config('app.name') }}</span></div>
                <div class="row"><span class="label-col">الهاتف:</span><span class="value-col">{{ config('ecommerce.store.phone', '-') }}</span></div>
                <div class="row"><span class="label-col">العنوان:</span><span class="value-col">{{ config('ecommerce.store.address', '-') }}</span></div>
            </div>

            <div class="section" style="flex: 1;">
                <div class="section-title">بيانات المستلم</div>
                @if($label->order?->shippingAddress)
                    <div class="row"><span class="label-col">الاسم:</span><span class="value-col">{{ $label->order->shippingAddress->name }}</span></div>
                    <div class="row"><span class="label-col">الهاتف:</span><span class="value-col">{{ $label->order->shippingAddress->phone }}</span></div>
                    <div class="row"><span class="label-col">العنوان:</span><span class="value-col">{{ $label->order->shippingAddress->address }}</span></div>
                    <div class="row"><span class="label-col">المدينة:</span><span class="value-col">{{ $label->order->shippingAddress->city }}</span></div>
                @else
                    <div class="row"><span class="value-col">-</span></div>
                @endif
            </div>
        </div>

        <div class="section">
            <div class="section-title">تفاصيل الشحنة</div>
            <div style="display: flex; gap: 20px;">
                <div class="row"><span class="label-col">الشركة:</span><span class="value-col">{{ $label->carrier?->name ?? '-' }}</span></div>
                <div class="row"><span class="label-col">الوزن:</span><span class="value-col">{{ $label->weight ? $label->weight . ' كغ' : '-' }}</span></div>
                <div class="row"><span class="label-col">التكلفة:</span><span class="value-col">{{ number_format($label->cost, 2) }} {{ currentCurrencySymbol() }}</span></div>
                <div class="row"><span class="label-col">رقم الطلب:</span><span class="value-col">#{{ $label->order?->order_number ?? '-' }}</span></div>
            </div>
        </div>

        @if($label->order?->items?->count())
            <div class="section">
                <div class="section-title">المنتجات</div>
                <table class="items-table">
                    <thead>
                        <tr>
                            <th>المنتج</th>
                            <th>الكمية</th>
                            <th>السعر</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($label->order->items as $item)
                            <tr>
                                <td>{{ $item->product_name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <div class="barcode">
            <div class="barcode-text">||||| {{ $label->tracking_number }} |||||</div>
        </div>

        <div class="footer">
            تم طباعة هذه البوليصة في {{ now()->format('Y-m-d H:i') }} | {{ config('app.name') }}
        </div>
    </div>
</body>
</html>
