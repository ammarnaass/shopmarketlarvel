@extends('admin.layout')

@section('title', 'الصفحات الثابتة')

@section('content')
<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <div>
        <h1 class="text-3xl font-bold">الصفحات الثابتة</h1>
        <p class="text-on-surface-variant text-sm mt-1">إدارة صفحات الموقع الثابتة</p>
    </div>
    <a href="{{ route('admin.pages.create') }}" class="bg-primary hover:bg-primary text-white px-4 py-2 rounded-lg text-sm font-semibold">
        <span class="material-symbols-outlined ml-1">add</span> إضافة صفحة
    </a>
</div>

<div class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-surface-container-low text-on-surface-variant text-xs">
                <tr>
                    <th class="px-4 py-3 text-right">العنوان</th>
                    <th class="px-4 py-3 text-right">الرابط</th>
                    <th class="px-4 py-3 text-right">الحالة</th>
                    <th class="px-4 py-3 text-right">الترتيب</th>
                    <th class="px-4 py-3 text-right">إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pages as $page)
                    <tr class="border-t hover:bg-surface-container-low">
                        <td class="px-4 py-3 font-semibold">{{ $page->title }}</td>
                        <td class="px-4 py-3 text-on-surface-variant font-mono text-xs">/page/{{ $page->slug }}</td>
                        <td class="px-4 py-3">
                            @if($page->is_active)
                                <span class="bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded text-xs">نشط</span>
                            @else
                                <span class="bg-error-container text-on-error-container px-2 py-0.5 rounded text-xs">معطل</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-on-surface-variant">{{ $page->sort_order }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.pages.edit', $page) }}" class="text-primary hover:text-primary">
                                    <span class="material-symbols-outlined">edit</span>
                                </a>
                                <form action="{{ route('admin.pages.destroy', $page) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذه الصفحة؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">
                                        <span class="material-symbols-outlined">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-12 text-center text-on-surface-variant">
                            <span class="material-symbols-outlined text-4xl text-gray-300 mb-3">description</span>
                            <p>لا توجد صفحات</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($pages->hasPages())
        <div class="p-4 border-t">{{ $pages->links() }}</div>
    @endif
</div>
@endsection
