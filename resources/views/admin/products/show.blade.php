@extends('admin.layout')

@section('title', $product->name)

@section('content')
<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <div>
        <h1 class="text-3xl font-bold">{{ $product->name }}</h1>
        <p class="text-gray-600 text-sm mt-1">
            <a href="{{ route('admin.products.index') }}" class="text-blue-600 hover:underline">المنتجات</a>
            <span class="mx-1">/</span>
            <span>{{ $product->name }}</span>
        </p>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.products.gallery', $product) }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm">
            <i class="fas fa-images ml-1"></i>إدارة المعرض ({{ $product->images->count() }})
        </a>
        <a href="{{ route('shop.show', $product->slug) }}" target="_blank" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm">
            <i class="fas fa-external-link-alt ml-1"></i>عرض في المتجر
        </a>
        <a href="{{ route('admin.products.edit', $product) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
            <i class="fas fa-edit ml-1"></i>تعديل
        </a>
        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا المنتج؟')">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm">
                <i class="fas fa-trash ml-1"></i>حذف
            </button>
        </form>
    </div>
</div>

<div class="grid lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        {{-- Main image --}}
        <div class="bg-white rounded-xl shadow-sm p-5">
            <h2 class="font-bold text-lg mb-4"><i class="fas fa-image text-blue-600 ml-2"></i>الصور</h2>
            @if($product->images->count() > 0)
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    @foreach($product->images as $img)
                        <div class="relative">
                            <img src="{{ asset('storage/' . $img->image) }}" alt="" class="w-full h-32 object-cover rounded-lg border {{ $img->is_primary ? 'ring-2 ring-blue-500' : '' }}">
                            @if($img->is_primary)
                                <span class="absolute top-1 right-1 bg-blue-600 text-white text-xs px-2 py-0.5 rounded">رئيسية</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 text-gray-400">
                    <i class="fas fa-image text-4xl mb-2"></i>
                    <p>لا توجد صور</p>
                </div>
            @endif
        </div>

        {{-- Description --}}
        <div class="bg-white rounded-xl shadow-sm p-5">
            <h2 class="font-bold text-lg mb-4"><i class="fas fa-align-right text-blue-600 ml-2"></i>الوصف</h2>
            @if($product->short_description)
                <p class="text-gray-700 mb-3 font-semibold">{{ $product->short_description }}</p>
            @endif
            @if($product->description)
                <p class="text-gray-700 whitespace-pre-line">{{ $product->description }}</p>
            @else
                <p class="text-gray-400 text-center py-6">لا يوجد وصف</p>
            @endif
        </div>

        {{-- Variants --}}
        @if($product->variants->count() > 0)
            <div class="bg-white rounded-xl shadow-sm p-5">
                <h2 class="font-bold text-lg mb-4"><i class="fas fa-layer-group text-blue-600 ml-2"></i>المتغيرات ({{ $product->variants->count() }})</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-gray-600 text-xs">
                            <tr>
                                <th class="px-3 py-2 text-right">الاسم</th>
                                <th class="px-3 py-2 text-right">SKU</th>
                                <th class="px-3 py-2 text-right">السعر</th>
                                <th class="px-3 py-2 text-right">المخزون</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($product->variants as $v)
                                <tr class="border-t">
                                    <td class="px-3 py-2">{{ $v->name }}</td>
                                    <td class="px-3 py-2 font-mono text-xs">{{ $v->sku ?? '—' }}</td>
                                    <td class="px-3 py-2">{{ number_format($v->price ?? 0, 0) }}</td>
                                    <td class="px-3 py-2">{{ $v->stock ?? 0 }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>

    <div class="space-y-6">
        {{-- Info --}}
        <div class="bg-white rounded-xl shadow-sm p-5">
            <h2 class="font-bold text-lg mb-4"><i class="fas fa-info-circle text-blue-600 ml-2"></i>معلومات سريعة</h2>
            <dl class="space-y-2 text-sm">
                <div class="flex justify-between"><dt class="text-gray-500">السعر:</dt><dd class="font-bold">{{ number_format($product->price, 0) }}</dd></div>
                @if($product->sale_price)
                    <div class="flex justify-between"><dt class="text-gray-500">سعر التخفيض:</dt><dd class="font-bold text-red-600">{{ number_format($product->sale_price, 0) }}</dd></div>
                @endif
                <div class="flex justify-between"><dt class="text-gray-500">SKU:</dt><dd class="font-mono text-xs">{{ $product->sku ?? '—' }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">المخزون:</dt><dd><span class="px-2 py-0.5 rounded text-xs {{ $product->stock < 10 ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">{{ $product->stock }} قطعة</span></dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">التصنيف:</dt><dd>{{ $product->category->name ?? '—' }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">النوع:</dt><dd>
                    @switch($product->type)
                        @case('simple') بسيط @break
                        @case('variable') متغير @break
                        @case('digital') رقمي @break
                        @case('bundle') حزمة @break
                    @endswitch
                </dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">الحالة:</dt><dd>
                    <span class="px-2 py-0.5 rounded text-xs
                        @switch($product->status)
                            @case('active') bg-green-100 text-green-700 @break
                            @case('inactive') bg-gray-100 text-gray-700 @break
                            @case('draft') bg-yellow-100 text-yellow-700 @break
                        @endswitch">
                        @switch($product->status)
                            @case('active') نشط @break
                            @case('inactive') غير نشط @break
                            @case('draft') مسودة @break
                        @endswitch
                    </span>
                </dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">مميز:</dt><dd>{!! $product->featured ? '<i class="fas fa-star text-yellow-500"></i> نعم' : 'لا' !!}</dd></div>
            </dl>
        </div>

        {{-- Timestamps --}}
        <div class="bg-white rounded-xl shadow-sm p-5">
            <h2 class="font-bold text-lg mb-4"><i class="fas fa-clock text-blue-600 ml-2"></i>التواريخ</h2>
            <dl class="space-y-2 text-sm">
                <div class="flex justify-between"><dt class="text-gray-500">أُنشئ:</dt><dd>{{ $product->created_at->format('Y-m-d H:i') }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">آخر تحديث:</dt><dd>{{ $product->updated_at->format('Y-m-d H:i') }}</dd></div>
            </dl>
        </div>
    </div>
</div>
@endsection
