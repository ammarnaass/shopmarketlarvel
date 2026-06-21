@extends('admin.layout')

@section('title', 'تعديل ' . $category->name)

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold">تعديل التصنيف</h1>
    <p class="text-on-surface-variant text-sm mt-1">
        <a href="{{ route('admin.categories.index') }}" class="text-primary hover:underline">التصنيفات</a>
        <span class="mx-1">/</span>
        <span>{{ $category->name }}</span>
    </p>
</div>

<form method="POST" action="{{ route('admin.categories.update', $category) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-surface-container-lowest rounded-xl shadow-sm p-5">
                <h2 class="font-bold text-lg mb-4"><span class="material-symbols-outlined text-primary ml-2">info</span>المعلومات الأساسية</h2>

                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-1">اسم التصنيف <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $category->name) }}" required class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-1">أيقونة التصنيف</label>
                    <input type="text" name="icon" value="{{ old('icon', $category->icon) }}" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('icon') border-red-500 @enderror" placeholder="مثال: fa-laptop أو storefront">
                    <p class="text-xs text-on-surface-variant mt-1">يمكنك استخدام أيقونات FontAwesome (تبدأ بـ fa-) أو Google Material Symbols.</p>
                    @error('icon')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-1">التصنيف الأب</label>
                    <select name="parent_id" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('parent_id') border-red-500 @enderror">
                        <option value="">— بدون أب (رئيسي) —</option>
                        @foreach($parents as $p)
                            <option value="{{ $p->id }}" {{ old('parent_id', $category->parent_id) == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                        @endforeach
                    </select>
                    @error('parent_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-1">الوصف</label>
                    <textarea name="description" rows="4" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description', $category->description) }}</textarea>
                    @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-surface-container-lowest rounded-xl shadow-sm p-5">
                <h2 class="font-bold text-lg mb-4"><span class="material-symbols-outlined text-primary ml-2">settings</span>الإعدادات</h2>
                <div class="mb-3">
                    <label class="block text-sm font-semibold mb-1">الترتيب</label>
                    <input type="number" name="order" value="{{ old('order', $category->order) }}" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('order') border-red-500 @enderror">
                    @error('order')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">الحالة <span class="text-red-500">*</span></label>
                    <select name="status" required class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('status') border-red-500 @enderror">
                        <option value="active" {{ old('status', $category->status) === 'active' ? 'selected' : '' }}>نشط</option>
                        <option value="inactive" {{ old('status', $category->status) === 'inactive' ? 'selected' : '' }}>غير نشط</option>
                    </select>
                    @error('status')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="bg-surface-container-lowest rounded-xl shadow-sm p-5">
                <h2 class="font-bold text-lg mb-4"><span class="material-symbols-outlined text-primary ml-2">image</span>الصورة</h2>
                @if($category->image)
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $category->image) }}" class="w-32 h-32 object-cover rounded-lg border" alt="">
                    </div>
                @endif
                <input type="file" name="image" accept="image/*" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('image') border-red-500 @enderror">
                @error('image')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>
    </div>

    <div class="mt-6 flex items-center gap-3">
        <button type="submit" class="bg-primary hover:bg-primary text-white px-6 py-2.5 rounded-lg font-semibold flex items-center gap-2">
            <span class="material-symbols-outlined">save</span>تحديث التصنيف
        </button>
        <a href="{{ route('admin.categories.index') }}" class="bg-gray-200 hover:bg-gray-300 text-on-surface px-6 py-2.5 rounded-lg font-semibold">إلغاء</a>
    </div>
</form>
@endsection
