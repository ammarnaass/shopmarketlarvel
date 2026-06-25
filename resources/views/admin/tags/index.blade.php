@extends('admin.layout')

@section('title', 'الوسوم')

@section('content')
<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <div>
        <h1 class="text-3xl font-bold">الوسوم</h1>
        <p class="text-on-surface-variant text-sm mt-1">إدارة وسوم المنتجات</p>
    </div>
</div>

{{-- Add Tag Form --}}
<div class="bg-surface-container-lowest rounded-xl shadow-sm p-6 mb-6">
    <h2 class="font-bold text-lg mb-4">إضافة وسم جديد</h2>
    <form method="POST" action="{{ route('admin.tags.store') }}" class="flex gap-3">
        @csrf
        <input type="text" name="name" required placeholder="اسم الوسم"
               class="flex-1 border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 text-sm">
        <button type="submit" class="bg-primary hover:bg-primary text-white px-6 py-2.5 rounded-lg font-semibold text-sm">
            <span class="material-symbols-outlined ml-1">add</span> إضافة
        </button>
    </form>
    @error('name')<p class="text-red-500 text-sm mt-2">{{ $message }}</p>@enderror
</div>

{{-- Tags List --}}
<div class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden">
    <div class="p-4 border-b">
        <form method="GET" class="flex gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="بحث..."
                   class="flex-1 border rounded-lg px-4 py-2 text-sm">
            <button type="submit" class="bg-surface-container-low hover:bg-surface-container-low px-4 py-2 rounded-lg text-sm">
                <span class="material-symbols-outlined ml-1">search</span> بحث
            </button>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-surface-container-low text-on-surface-variant text-xs">
                <tr>
                    <th class="px-4 py-3 text-right">الاسم</th>
                    <th class="px-4 py-3 text-right">الرابط المختصر</th>
                    <th class="px-4 py-3 text-right">عدد المنتجات</th>
                    <th class="px-4 py-3 text-right">إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tags as $tag)
                    <tr class="border-t hover:bg-surface-container-low" x-data="{ editing: false }">
                        <td class="px-4 py-3">
                            <template x-if="!editing">
                                <span class="font-semibold">{{ $tag->name }}</span>
                            </template>
                            <template x-if="editing">
                                <form method="POST" action="{{ route('admin.tags.update', $tag) }}" class="flex gap-2">
                                    @csrf
                                    @method('PUT')
                                    <input type="text" name="name" value="{{ $tag->name }}" class="border rounded px-3 py-1 text-sm">
                                    <button type="submit" class="text-green-600"><span class="material-symbols-outlined">check</span></button>
                                    <button type="button" @click="editing = false" class="text-on-surface-variant"><span class="material-symbols-outlined">close</span></button>
                                </form>
                            </template>
                        </td>
                        <td class="px-4 py-3 text-on-surface-variant font-mono text-xs">{{ $tag->slug }}</td>
                        <td class="px-4 py-3">
                            <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded text-xs">{{ $tag->products_count }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <button @click="editing = !editing" class="text-primary hover:text-primary">
                                    <span class="material-symbols-outlined">edit</span>
                                </button>
                                <form action="{{ route('admin.tags.destroy', $tag) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد؟')">
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
                        <td colspan="4" class="px-4 py-12 text-center text-on-surface-variant">
                            <span class="material-symbols-outlined text-4xl text-gray-300 mb-3">label</span>
                            <p>لا توجد وسوم</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($tags->hasPages())
        <div class="p-4 border-t">{{ $tags->links() }}</div>
    @endif
</div>
@endsection
