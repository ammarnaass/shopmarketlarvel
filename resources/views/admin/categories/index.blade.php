@extends('admin.layout')

@section('title', 'التصنيفات')

@section('content')
<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <div>
        <h1 class="text-3xl font-bold">التصنيفات</h1>
        <p class="text-on-surface-variant text-sm mt-1">إدارة تصنيفات المنتجات</p>
    </div>
    <a href="{{ route('admin.categories.create') }}" class="bg-primary hover:bg-primary text-white px-4 py-2 rounded-lg flex items-center gap-2">
        <span class="material-symbols-outlined">add</span>
        إضافة تصنيف
    </a>
</div>

<div class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-surface-container-low text-on-surface-variant text-xs">
                <tr>
                    <th class="px-4 py-3 text-right">#</th>
                    <th class="px-4 py-3 text-right">الاسم</th>
                    <th class="px-4 py-3 text-right">التصنيف الأب</th>
                    <th class="px-4 py-3 text-right">الترتيب</th>
                    <th class="px-4 py-3 text-right">المنتجات</th>
                    <th class="px-4 py-3 text-right">الحالة</th>
                    <th class="px-4 py-3 text-right">إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $cat)
                    <tr class="border-t hover:bg-surface-container-low">
                        <td class="px-4 py-3 text-on-surface-variant">{{ $cat->id }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                @if($cat->image)
                                    <img src="{{ asset('storage/' . $cat->image) }}" class="w-10 h-10 rounded object-cover" alt="">
                                @else
                                    <div class="w-10 h-10 rounded bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white">
                                        @if($cat->icon)
                                            @categoryIcon($cat->icon, 'text-xl text-white')
                                        @else
                                            <span class="material-symbols-outlined">label</span>
                                        @endif
                                    </div>
                                @endif
                                <div>
                                    <div class="font-semibold">{{ $cat->name }}</div>
                                    <div class="text-xs text-on-surface-variant font-mono">{{ $cat->slug }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-on-surface-variant">
                            @if($cat->parent)
                                <span class="bg-purple-100 text-purple-700 px-2 py-0.5 rounded text-xs">{{ $cat->parent->name }}</span>
                            @else
                                <span class="text-gray-400">— رئيسي —</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">{{ $cat->order ?? 0 }}</td>
                        <td class="px-4 py-3">
                            <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded text-xs font-semibold">
                                {{ $cat->products()->count() ?? 0 }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded text-xs {{ $cat->status === 'active' ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-100 text-gray-700' }}">
                                {{ $cat->status === 'active' ? 'نشط' : 'غير نشط' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.categories.edit', $cat) }}" class="text-green-600 hover:text-green-800" title="تعديل">
                                    <span class="material-symbols-outlined">edit</span>
                                </a>
                                <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا التصنيف؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800" title="حذف">
                                        <span class="material-symbols-outlined">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-12 text-center text-on-surface-variant">
                            <span class="material-symbols-outlined text-4xl text-gray-300 mb-3">label</span>
                            <p>لا توجد تصنيفات</p>
                            <a href="{{ route('admin.categories.create') }}" class="text-primary hover:underline text-sm mt-2 inline-block">إضافة أول تصنيف</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($categories->hasPages())
        <div class="p-4 border-t">
            {{ $categories->links() }}
        </div>
    @endif
</div>
@endsection
