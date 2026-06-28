@extends('admin.layout')

@section('title', __t('admin.newsletter.title'))

@section('content')
<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <div>
        <h1 class="text-3xl font-bold">{{ __t('admin.newsletter.title') }}</h1>
        <p class="text-on-surface-variant text-sm mt-1">{{ __t('admin.newsletter.subtitle') }}</p>
    </div>
    <a href="{{ route('admin.newsletter.export') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary text-white font-medium hover:bg-primary-container shadow-sm transition-all active:scale-95">
        <span class="material-symbols-outlined text-sm">download</span>
        {{ __t('admin.newsletter.export_csv') }}
    </a>
</div>

{{-- Stats --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-surface-container-lowest rounded-xl shadow-sm p-4">
        <p class="text-on-surface-variant text-xs">{{ __t('admin.newsletter.total') }}</p>
        <p class="text-2xl font-bold mt-1">{{ number_format($totalSubscribers) }}</p>
    </div>
    <div class="bg-surface-container-lowest rounded-xl shadow-sm p-4">
        <p class="text-on-surface-variant text-xs">{{ __t('admin.newsletter.active') }}</p>
        <p class="text-2xl font-bold mt-1 text-green-600">{{ number_format($activeCount) }}</p>
    </div>
    <div class="bg-surface-container-lowest rounded-xl shadow-sm p-4">
        <p class="text-on-surface-variant text-xs">{{ __t('admin.newsletter.unsubscribed') }}</p>
        <p class="text-2xl font-bold mt-1 text-red-500">{{ number_format($unsubscribedCount) }}</p>
    </div>
</div>

{{-- Filters --}}
<div class="bg-surface-container-lowest rounded-xl shadow-sm p-4 mb-6">
    <form method="GET" action="{{ route('admin.newsletter.index') }}" class="flex flex-wrap items-center gap-3">
        <div class="relative flex-1 min-w-[200px]">
            <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-outline text-lg">search</span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __t('admin.newsletter.search_placeholder') }}"
                   class="w-full rounded-lg border-outline-variant bg-white p-2.5 pr-10 text-body-md">
        </div>
        <select name="status" class="rounded-lg border-outline-variant bg-white p-2.5 text-body-md">
            <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>{{ __t('admin.newsletter.all') }}</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>{{ __t('admin.newsletter.active') }}</option>
            <option value="unsubscribed" {{ request('status') === 'unsubscribed' ? 'selected' : '' }}>{{ __t('admin.newsletter.unsubscribed') }}</option>
        </select>
        <button type="submit" class="px-5 py-2.5 rounded-xl bg-primary text-white font-medium hover:bg-primary-container shadow-sm transition-all active:scale-95 flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">filter_list</span>
            {{ __t('admin.newsletter.filter') }}
        </button>
    </form>
</div>

{{-- Table --}}
<form method="POST" action="{{ route('admin.newsletter.destroySelected') }}" id="delete-selected-form">
    @csrf
    @method('DELETE')
    <div class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-outline-variant bg-surface-container-low">
                        <th class="p-3 text-right">
                            <input type="checkbox" id="select-all" class="rounded border-outline-variant">
                        </th>
                        <th class="p-3 text-right font-semibold text-on-surface-variant">#</th>
                        <th class="p-3 text-right font-semibold text-on-surface-variant">{{ __t('admin.newsletter.email') }}</th>
                        <th class="p-3 text-right font-semibold text-on-surface-variant">{{ __t('admin.newsletter.status') }}</th>
                        <th class="p-3 text-right font-semibold text-on-surface-variant">{{ __t('admin.newsletter.subscribed_at') }}</th>
                        <th class="p-3 text-right font-semibold text-on-surface-variant">{{ __t('admin.newsletter.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subscribers as $subscriber)
                    <tr class="border-b border-outline-variant hover:bg-surface-container-low transition">
                        <td class="p-3"><input type="checkbox" name="selected[]" value="{{ $subscriber->id }}" class="subscriber-checkbox rounded border-outline-variant"></td>
                        <td class="p-3 text-on-surface-variant">{{ $loop->index + $subscribers->firstItem() }}</td>
                        <td class="p-3 font-medium" dir="ltr">{{ $subscriber->email }}</td>
                        <td class="p-3">
                            @if($subscriber->status === 'active')
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                    <span class="material-symbols-outlined text-xs">check_circle</span> {{ __t('admin.newsletter.active') }}
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                    <span class="material-symbols-outlined text-xs">cancel</span> {{ __t('admin.newsletter.unsubscribed') }}
                                </span>
                            @endif
                        </td>
                        <td class="p-3 text-on-surface-variant">{{ $subscriber->subscribed_at ? $subscriber->subscribed_at->format('Y/m/d H:i') : '—' }}</td>
                        <td class="p-3">
                            <form method="POST" action="{{ route('admin.newsletter.destroy', $subscriber) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('{{ __t('admin.newsletter.delete_confirm') }}')" class="text-error hover:text-error-container transition">
                                    <span class="material-symbols-outlined text-lg">delete</span>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-on-surface-variant">
                            <span class="material-symbols-outlined text-4xl mb-2 block text-outline">mail_off</span>
                            {{ __t('admin.newsletter.empty') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($subscribers->hasPages())
        <div class="p-4 border-t border-outline-variant flex items-center justify-between">
            <button type="button" onclick="document.getElementById('delete-selected-form').submit()" class="px-4 py-2 rounded-lg bg-error-container/20 text-error text-xs font-medium hover:bg-error-container transition flex items-center gap-1">
                <span class="material-symbols-outlined text-sm">delete_sweep</span>
                {{ __t('admin.newsletter.delete_selected') }}
            </button>
            {{ $subscribers->withQueryString()->links() }}
        </div>
        @endif
    </div>
</form>

<script>
    document.getElementById('select-all')?.addEventListener('change', function() {
        document.querySelectorAll('.subscriber-checkbox').forEach(cb => cb.checked = this.checked);
    });
</script>
@endsection
