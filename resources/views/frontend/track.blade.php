@extends('frontend.layout')

@section('title', 'تتبع طلبك - ' . site('store_name'))
@section('description', 'تتبع حالة طلبك بسهولة')

@section('content')

<section class="bg-gradient-to-l from-blue-600 via-indigo-500 to-purple-500 text-white py-10 md:py-14 relative overflow-hidden">
    <div class="absolute -top-20 -right-20 w-72 h-72 bg-white/10 rounded-full blur-3xl"></div>
    <div class="container-app relative z-10">
        <nav class="flex items-center gap-2 text-sm text-white/80 mb-4">
            <a href="{{ route('home') }}" class="hover:text-white transition flex items-center gap-1">
                <span class="material-symbols-outlined text-xs">home</span>
                الرئيسية
            </a>
            <span class="material-symbols-outlined text-[10px] text-white/50">chevron_right</span>
            <span class="text-white font-medium">تتبع طلبك</span>
        </nav>
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-3xl border border-white/30">
                <span class="material-symbols-outlined">location_on</span>
            </div>
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold mb-1">تتبع طلبك</h1>
                <p class="text-white/90">أدخل رقم الطلب وبريدك أو هاتفك لمعرفة حالة طلبك</p>
            </div>
        </div>
    </div>
</section>

<div class="container-app py-10 max-w-3xl">
    {{-- Search by tracking number --}}
    <div class="card mb-6 animate-fade-up" x-data="{ showTracking: false }">
        <div class="card-body p-4">
            <button @click="showTracking = !showTracking" class="w-full flex items-center justify-between text-sm font-semibold text-gray-700">
                <span><span class="material-symbols-outlined ml-2 text-indigo-600">local_shipping</span>تتبع بالرقم المباشر (رقم التتبع)</span>
                <span class="material-symbols-outlined" x-text="showTracking ? 'expand_less' : 'expand_more'"></span>
            </button>
            <div x-show="showTracking" x-transition class="mt-4">
                <form method="GET" action="{{ route('track') }}" class="flex gap-2">
                    <input type="text" name="tracking" placeholder="أدخل رقم التتبع (مثل: SH1234567890)"
                           class="flex-1 border rounded-lg px-4 py-2.5 text-sm font-mono">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2.5 rounded-lg text-sm">
                        <span class="material-symbols-outlined ml-1">search</span> تتبع
                    </button>
                </form>
            </div>
        </div>
    </div>

    @if(!$order)
        <div class="card animate-fade-up">
            <div class="card-body p-6 md:p-8">
                @if($error)
                    <div class="alert alert-danger mb-5 animate-slide-down">
                        <span class="material-symbols-outlined text-lg">warning</span>
                        <span>{{ $error }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('track.submit') }}" class="space-y-5">
                    @csrf
                    <div>
                        <label class="form-label">رقم الطلب <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <input type="text" name="order_number" value="{{ old('order_number', $orderNumber ?? '') }}" required
                                   placeholder="ORD-XXXXXXXXXXXX"
                                   class="form-input pl-11 font-mono @error('order_number') form-input-error @enderror">
                            <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">tag</span>
                        </div>
                        <p class="form-help"><span class="material-symbols-outlined text-xs ml-1">info</span>يبدأ عادة بـ ORD-</p>
                        @error('order_number')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="form-label">البريد الإلكتروني أو رقم الهاتف <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <input type="text" name="contact" value="{{ old('contact', $contact ?? '') }}" required
                                   placeholder="example@email.com أو 0912345678"
                                   class="form-input pl-11 @error('contact') form-input-error @enderror">
                            <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">mail</span>
                        </div>
                        @error('contact')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <button type="submit" class="btn-primary btn-lg btn-block bg-gradient-to-l from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700">
                        <span class="material-symbols-outlined">search</span>
                        تتبع الطلب
                    </button>
                </form>
            </div>
        </div>

        <div class="alert alert-info mt-5 animate-fade-up">
            <span class="material-symbols-outlined text-lg">info</span>
            <div>
                <strong>نصيحة:</strong> رقم الطلب تجده في رسالة التأكيد المرسلة لبريدك عند إتمام الطلب، أو في صفحة "طلباتي" بعد تسجيل الدخول.
            </div>
        </div>
    @else
        @php
            $statusColors = [
                'pending' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'border' => 'border-amber-200', 'icon' => 'schedule', 'label' => 'قيد الانتظار'],
                'confirmed' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'border' => 'border-blue-200', 'icon' => 'check', 'label' => 'مؤكد'],
                'processing' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'border' => 'border-blue-200', 'icon' => 'settings', 'label' => 'قيد التجهيز'],
                'shipped' => ['bg' => 'bg-indigo-50', 'text' => 'text-indigo-700', 'border' => 'border-indigo-200', 'icon' => 'local_shipping', 'label' => 'تم الشحن'],
                'delivered' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200', 'icon' => 'house', 'label' => 'تم التسليم'],
                'cancelled' => ['bg' => 'bg-rose-50', 'text' => 'text-rose-700', 'border' => 'border-rose-200', 'icon' => 'cancel', 'label' => 'ملغي'],
            ];
            $st = $statusColors[$order->status] ?? $statusColors['pending'];
        @endphp

        <div class="card animate-fade-up">
            <div class="card-body p-6 md:p-8">
                {{-- Header --}}
                <div class="flex flex-wrap items-start justify-between gap-3 pb-5 border-b border-gray-100 mb-6">
                    <div>
                        <div class="text-xs text-gray-500 mb-1">رقم الطلب</div>
                        <div class="font-mono font-bold text-xl text-gray-800">{{ $order->order_number }}</div>
                    </div>
                    <span class="badge {{ $st['bg'] }} {{ $st['text'] }} border {{ $st['border'] }} text-sm py-1.5 px-3">
                        <span class="material-symbols-outlined">{{ $st['icon'] }}</span>
                        {{ $st['label'] }}
                    </span>
                </div>

                {{-- Status timeline --}}
                <div class="mb-6">
                    <h3 class="font-bold text-base mb-5 flex items-center gap-2 text-gray-800">
                        <span class="material-symbols-outlined text-brand-600">route</span>
                        مراحل الطلب
                    </h3>
                    @php
                        $stages = [
                            'pending' => ['label' => 'قيد الانتظار', 'icon' => 'inbox'],
                            'confirmed' => ['label' => 'مؤكد', 'icon' => 'check'],
                            'processing' => ['label' => 'قيد التجهيز', 'icon' => 'inventory_2'],
                            'shipped' => ['label' => 'تم الشحن', 'icon' => 'local_shipping'],
                            'delivered' => ['label' => 'تم التسليم', 'icon' => 'house'],
                        ];
                        $currentIdx = array_search($order->status, array_keys($stages));
                        if ($currentIdx === false) $currentIdx = 0;
                        if ($order->status === 'cancelled') $currentIdx = -1;
                    @endphp
                    <div class="flex items-center justify-between overflow-x-auto pb-2">
                        @foreach($stages as $key => $stage)
                            @php
                                $idx = $loop->index;
                                $isReached = $order->status !== 'cancelled' && $idx <= $currentIdx;
                                $isCurrent = $order->status === $key;
                            @endphp
                            <div class="flex flex-col items-center min-w-[80px] relative z-10">
                                <div class="w-12 h-12 rounded-full flex items-center justify-center mb-2 transition
                                    {{ $isCurrent ? 'bg-gradient-to-br from-brand-500 to-accent-500 text-white shadow-lg ring-4 ring-brand-100' :
                                       ($isReached ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-200' :
                                       'bg-gray-100 text-gray-400') }}">
                                    <span class="material-symbols-outlined">{{ $isReached && !$isCurrent ? 'check' : $stage['icon'] }}</span>
                                </div>
                                <div class="text-xs text-center font-medium
                                    {{ $isCurrent ? 'font-bold text-brand-600' :
                                       ($isReached ? 'text-gray-800' : 'text-gray-400') }}">
                                    {{ $stage['label'] }}
                                </div>
                            </div>
                            @if(!$loop->last)
                                <div class="flex-1 h-1 -mt-7 mx-2 rounded-full
                                    {{ $idx < $currentIdx && $order->status !== 'cancelled' ? 'bg-emerald-500' : 'bg-gray-200' }}"></div>
                            @endif
                        @endforeach
                    </div>

                    @if($order->status === 'cancelled')
                        <div class="alert alert-danger mt-4">
                            <span class="material-symbols-outlined text-lg">cancel</span>
                            <span>هذا الطلب ملغي{{ $order->cancel_reason ? ' — السبب: ' . $order->cancel_reason : '' }}</span>
                        </div>
                    @endif
                </div>

                {{-- Shipping Tracking Details --}}
                @php
                    $label = $order->shippingLabel ?? null;
                @endphp
                @if($label)
                    <div class="mb-6 p-4 bg-gradient-to-l from-indigo-50 to-blue-50 border border-indigo-200 rounded-xl">
                        <h3 class="font-bold text-sm mb-3 flex items-center gap-2 text-indigo-800">
                            <span class="material-symbols-outlined">local_shipping</span>
                            معلومات الشحن
                        </h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-sm">
                            <div>
                                <span class="text-xs text-gray-500">الشركة:</span>
                                <div class="font-semibold">{{ $label->carrier?->name ?? '-' }}</div>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500">رقم التتبع:</span>
                                <div class="font-mono font-semibold text-indigo-700">{{ $label->tracking_number }}</div>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500">حالة الشحن:</span>
                                <div class="font-semibold">{{ $label->getStatusLabel() }}</div>
                            </div>
                            @if($label->carrier?->tracking_url)
                                <div>
                                    <a href="{{ str_replace('{TRACKING}', $label->tracking_number, $label->carrier->tracking_url) }}"
                                       target="_blank" class="inline-flex items-center gap-1 text-indigo-600 hover:text-indigo-800 font-semibold text-sm mt-4">
                                        <span class="material-symbols-outlined">open_in_new</span>
                                        تتبع على موقع الشركة
                                    </a>
                                </div>
                            @endif
                        </div>

                        {{-- Tracking Updates Timeline --}}
                        @if($label->trackingUpdates->count())
                            <div class="mt-4 pt-4 border-t border-indigo-200">
                                <h4 class="text-xs font-semibold text-indigo-700 mb-3">آخر تحديثات التتبع</h4>
                                <div class="space-y-2">
                                    @foreach($label->trackingUpdates->sortByDesc('tracked_at')->take(5) as $update)
                                        <div class="flex items-start gap-2 text-xs">
                                            <div class="w-2 h-2 rounded-full bg-indigo-400 mt-1.5 flex-shrink-0"></div>
                                            <div>
                                                <span class="font-semibold">{{ $update->getStatusLabel() }}</span>
                                                @if($update->location)
                                                    <span class="text-gray-500">— {{ $update->location }}</span>
                                                @endif
                                                <span class="text-gray-400 mr-2">{{ $update->tracked_at->format('Y-m-d H:i') }}</span>
                                                @if($update->description)
                                                    <div class="text-gray-600 mt-0.5">{{ $update->description }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                {{-- Info grid --}}
                <div class="grid md:grid-cols-2 gap-3 mb-6">
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <div class="text-xs text-gray-500 mb-1 flex items-center gap-1">
                            <span class="material-symbols-outlined text-xs">calendar_month</span>
                            تاريخ الطلب
                        </div>
                        <div class="font-semibold text-gray-800">{{ $order->created_at->format('Y-m-d H:i') }}</div>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <div class="text-xs text-gray-500 mb-1 flex items-center gap-1">
                            <span class="material-symbols-outlined text-xs">credit_card</span>
                            طريقة الدفع
                        </div>
                        <div class="font-semibold text-gray-800">{{ $order->payment?->method ?? 'الدفع عند الاستلام' }}</div>
                    </div>
                    @if($order->tracking_number)
                        <div class="bg-gray-50 p-4 rounded-xl">
                            <div class="text-xs text-gray-500 mb-1 flex items-center gap-1">
                                <span class="material-symbols-outlined text-xs">my_location</span>
                                رقم التتبع
                            </div>
                            <div class="font-mono font-semibold text-gray-800">{{ $order->tracking_number }}</div>
                        </div>
                    @endif
                    <div class="bg-gradient-to-l from-brand-50 to-accent-50 p-4 rounded-xl border border-brand-100">
                        <div class="text-xs text-gray-600 mb-1 flex items-center gap-1">
                            <span class="material-symbols-outlined text-xs">payments</span>
                            المبلغ الإجمالي
                        </div>
                        <div class="font-extrabold text-xl bg-gradient-to-l from-brand-600 to-accent-500 bg-clip-text text-transparent">
                            {{ number_format($order->grand_total, 0) }} {{ currentCurrencySymbol() }}
                        </div>
                    </div>
                </div>

                {{-- Items --}}
                <h3 class="font-bold text-base mb-3 flex items-center gap-2 text-gray-800">
                    <span class="material-symbols-outlined text-brand-600">category</span>
                    المنتجات ({{ $order->items->count() }})
                </h3>
                <div class="space-y-2 mb-6">
                    @foreach($order->items as $item)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition">
                            <div>
                                <div class="font-semibold text-sm text-gray-800">{{ $item->product_name }}</div>
                                <div class="text-xs text-gray-500">{{ $item->quantity }} × {{ number_format($item->price, 0) }} {{ currentCurrencySymbol() }}</div>
                            </div>
                            <div class="font-bold text-gray-800">{{ number_format($item->total, 0) }} {{ currentCurrencySymbol() }}</div>
                        </div>
                    @endforeach
                </div>

                {{-- Shipping address --}}
                @if($order->shippingAddress)
                    <h3 class="font-bold text-base mb-3 flex items-center gap-2 text-gray-800">
                        <span class="material-symbols-outlined text-brand-600">location_on</span>
                        عنوان التوصيل
                    </h3>
                    <div class="p-4 bg-gradient-to-l from-blue-50 to-indigo-50 border border-blue-200 rounded-xl text-sm">
                        <div class="font-bold text-gray-800 mb-1">{{ $order->shippingAddress->name }} — {{ $order->shippingAddress->phone }}</div>
                        <div class="text-gray-600">{{ $order->shippingAddress->address }}</div>
                        <div class="text-gray-600">{{ $order->shippingAddress->city }} - {{ $order->shippingAddress->state_name ?? '' }} - {{ $order->shippingAddress->country_name ?? '' }}</div>
                    </div>
                @endif

                <div class="mt-6 text-center">
                    <a href="{{ route('track') }}" class="btn btn-secondary inline-flex">
                        <span class="material-symbols-outlined">search</span>
                        تتبع طلب آخر
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
