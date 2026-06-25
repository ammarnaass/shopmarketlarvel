@extends('frontend.layout')

@section('title', 'حسابي - ' . site('store_name'))
@section('description', 'إدارة حسابك الشخصي في ' . site('store_name'))

@section('content')
@php
    $countries = config('ecommerce.countries', []);
@endphp

<section class="bg-gradient-to-l from-brand-600 via-brand-500 to-accent-500 text-white py-10 md:py-14">
    <div class="container-app">
        <nav class="flex items-center gap-2 text-sm text-white/80 mb-4">
            <a href="{{ route('home') }}" class="hover:text-white transition flex items-center gap-1">
                <span class="material-symbols-outlined text-xs">home</span>
                الرئيسية
            </a>
            <span class="material-symbols-outlined text-[10px] text-white/50">chevron_right</span>
            <span class="text-white font-medium">حسابي</span>
        </nav>
        <h1 class="text-3xl md:text-4xl font-extrabold mb-2">حسابي</h1>
        <p class="text-white/90">إدارة بياناتك الشخصية، عناوينك، وكلمات المرور</p>
    </div>
</section>

<div class="container-app py-8 md:py-10" x-data="{ tab: 'profile' }">
    @if(session('success'))
        <div class="alert alert-success mb-5 animate-slide-down">
            <span class="material-symbols-outlined text-lg">check_circle</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger mb-5">
            <span class="material-symbols-outlined text-lg">warning</span>
            <div class="flex-1">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        </div>
    @endif

    <div class="grid lg:grid-cols-4 gap-6">
        {{-- ============ SIDEBAR ============ --}}
        <aside class="lg:col-span-1">
            <div class="card sticky top-24 animate-fade-up">
                {{-- Profile --}}
                <div class="p-5 text-center border-b border-gray-100">
                    <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-brand-500 to-accent-500 mx-auto flex items-center justify-center text-white text-3xl font-extrabold shadow-lg">
                        {{ mb_substr($user->name, 0, 1) }}
                    </div>
                    <div class="font-bold mt-3 text-gray-800">{{ $user->name }}</div>
                    <div class="text-xs text-gray-500 mt-0.5">{{ $user->email }}</div>
                    @if($user->roles->count() > 0)
                        <div class="mt-2 flex justify-center gap-1 flex-wrap">
                            @foreach($user->roles as $r)
                                <span class="badge badge-primary text-[10px]">{{ $r->display_name ?? $r->name }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
                {{-- Nav --}}
                <nav class="p-3 space-y-1">
                    <button @click="tab='profile'" :class="tab==='profile' ? 'bg-brand-50 text-brand-700 font-semibold' : 'text-gray-700 hover:bg-gray-50'" class="w-full text-right px-3 py-2.5 rounded-lg text-sm transition flex items-center gap-2">
                        <span class="material-symbols-outlined text-xs w-4">person</span>
                        البيانات الشخصية
                    </button>
                    <button @click="tab='addresses'" :class="tab==='addresses' ? 'bg-brand-50 text-brand-700 font-semibold' : 'text-gray-700 hover:bg-gray-50'" class="w-full text-right px-3 py-2.5 rounded-lg text-sm transition flex items-center gap-2">
                        <span class="material-symbols-outlined text-xs w-4">location_on</span>
                        العناوين
                    </button>
                    <button @click="tab='password'" :class="tab==='password' ? 'bg-brand-50 text-brand-700 font-semibold' : 'text-gray-700 hover:bg-gray-50'" class="w-full text-right px-3 py-2.5 rounded-lg text-sm transition flex items-center gap-2">
                        <span class="material-symbols-outlined text-xs w-4">lock</span>
                        كلمة المرور
                    </button>
                    <a href="{{ route('orders.index') }}" class="w-full text-right px-3 py-2.5 rounded-lg text-sm transition flex items-center gap-2 text-gray-700 hover:bg-gray-50">
                        <span class="material-symbols-outlined text-xs w-4">inventory_2</span>
                        طلباتي ({{ $user->orders->count() }})
                    </a>
                    <a href="{{ route('wishlist.index') }}" class="w-full text-right px-3 py-2.5 rounded-lg text-sm transition flex items-center gap-2 text-gray-700 hover:bg-gray-50">
                        <span class="material-symbols-outlined text-xs w-4">favorite</span>
                        المفضلة
                    </a>
                </nav>
            </div>
        </aside>

        {{-- ============ CONTENT ============ --}}
        <div class="lg:col-span-3 space-y-5">

            {{-- Profile --}}
            <div x-show="tab==='profile'" x-cloak class="card animate-fade-up">
                <div class="card-header">
                    <h2 class="font-bold text-lg flex items-center gap-2">
                        <span class="material-symbols-outlined text-brand-600">person</span>
                        البيانات الشخصية
                    </h2>
                </div>
                <div class="card-body p-5">
                    <form method="POST" action="{{ route('account.update') }}">
                        @csrf @method('PUT')
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label">الاسم الكامل <span class="text-rose-500">*</span></label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                       class="form-input @error('name') form-input-error @enderror">
                                @error('name')<p class="form-error">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="form-label">البريد الإلكتروني <span class="text-rose-500">*</span></label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                       class="form-input @error('email') form-input-error @enderror">
                                @error('email')<p class="form-error">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="form-label">الدولة <span class="text-rose-500">*</span></label>
                                <select name="country_code" class="form-input appearance-none">
                                    @foreach($countries as $code => $info)
                                        <option value="{{ $code }}" {{ $user->country_code == $code ? 'selected' : '' }}>
                                            {{ $info['name'] }} - {{ $info['name_en'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="form-label">الولاية / المحافظة</label>
                                <input type="text" name="state_code" value="{{ old('state_code', $user->state_code) }}"
                                       class="form-input">
                            </div>
                            <div class="md:col-span-2">
                                <label class="form-label">رقم الهاتف <span class="text-rose-500">*</span></label>
                                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" required
                                       class="form-input @error('phone') form-input-error @enderror">
                                @error('phone')<p class="form-error">{{ $message }}</p>@enderror
                            </div>
                        </div>
                        <div class="mt-5 flex justify-end">
                            <button type="submit" class="btn-primary">
                                <span class="material-symbols-outlined">save</span>
                                حفظ التغييرات
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Addresses --}}
            <div x-show="tab==='addresses'" x-cloak class="card animate-fade-up">
                <div class="card-header flex items-center justify-between">
                    <h2 class="font-bold text-lg flex items-center gap-2">
                        <span class="material-symbols-outlined text-brand-600">location_on</span>
                        عناويني
                    </h2>
                    <span class="badge badge-gray">{{ $user->addresses->count() }}</span>
                </div>
                <div class="card-body p-5">
                    @if($user->addresses->isEmpty())
                        <div class="bg-gray-50 border-2 border-dashed border-gray-200 rounded-xl p-8 text-center mb-5">
                            <span class="material-symbols-outlined text-4xl text-gray-300 mb-2">map</span>
                            <p class="text-gray-500">لا توجد عناوين محفوظة</p>
                        </div>
                    @else
                        <div class="space-y-3 mb-5">
                            @foreach($user->addresses as $addr)
                                <div class="rounded-xl p-4 border-2 transition
                                            {{ $addr->is_default ? 'border-brand-500 bg-brand-50' : 'border-gray-200 hover:border-gray-300' }}">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="flex items-start gap-3 flex-1 min-w-0">
                                            <div class="w-10 h-10 rounded-lg {{ $addr->is_default ? 'bg-brand-500 text-white' : 'bg-gray-100 text-gray-500' }} flex items-center justify-center flex-shrink-0">
                                                <span class="material-symbols-outlined">location_on</span>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="font-bold text-gray-800 flex items-center gap-2 flex-wrap">
                                                    {{ $addr->name }}
                                                    <span class="text-xs text-gray-500 font-normal">({{ $addr->phone }})</span>
                                                    @if($addr->is_default)
                                                        <span class="badge badge-primary text-[10px]"><span class="material-symbols-outlined text-[8px]">check</span>افتراضي</span>
                                                    @endif
                                                </div>
                                                <div class="text-sm text-gray-600 mt-1">{{ $addr->full_address }}</div>
                                            </div>
                                        </div>
                                        <div class="flex flex-col gap-1">
                                            @if(!$addr->is_default)
                                                <form method="POST" action="{{ route('account.address.default', $addr) }}">
                                                    @csrf
                                                    <button class="text-xs text-brand-600 hover:underline font-semibold">اجعله افتراضي</button>
                                                </form>
                                            @endif
                                            <form method="POST" action="{{ route('account.address.destroy', $addr) }}" onsubmit="return confirm('حذف هذا العنوان؟')">
                                                @csrf @method('DELETE')
                                                <button class="text-xs text-rose-600 hover:underline font-semibold">حذف</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <details class="group rounded-xl border-2 border-dashed border-gray-200 hover:border-brand-300 transition">
                        <summary class="cursor-pointer px-4 py-3 font-semibold text-brand-600 flex items-center gap-2 list-none">
                            <span class="material-symbols-outlined">add_circle</span>
                            إضافة عنوان جديد
                        </summary>
                        <form method="POST" action="{{ route('account.address.store') }}" class="mt-3 p-4 border-t border-gray-100 grid md:grid-cols-2 gap-3">
                            @csrf
                            <input type="text" name="name" placeholder="الاسم" required class="form-input text-sm">
                            <input type="text" name="phone" placeholder="الهاتف" required class="form-input text-sm">
                            <select name="country_code" class="form-input text-sm appearance-none">
                                @foreach($countries as $code => $info)
                                    <option value="{{ $code }}" {{ ($user->country_code ?? 'SD') == $code ? 'selected' : '' }}>{{ $info['name'] }}</option>
                                @endforeach
                            </select>
                            <input type="text" name="state_code" placeholder="الولاية (اختياري)" class="form-input text-sm">
                            <input type="text" name="city" placeholder="المدينة" required class="form-input text-sm">
                            <input type="text" name="district" placeholder="الحي (اختياري)" class="form-input text-sm">
                            <input type="text" name="zip" placeholder="الرمز البريدي" class="form-input text-sm md:col-span-2">
                            <textarea name="address" placeholder="العنوان التفصيلي" required class="form-input text-sm md:col-span-2" rows="2"></textarea>
                            <label class="md:col-span-2 flex items-center gap-2 text-sm cursor-pointer">
                                <input type="checkbox" name="is_default" value="1" class="form-checkbox">
                                <span>اجعله افتراضي</span>
                            </label>
                            <div class="md:col-span-2">
                                <button type="submit" class="btn-primary btn-block">
                                <span class="material-symbols-outlined">save</span>
                                    حفظ العنوان
                                </button>
                            </div>
                        </form>
                    </details>
                </div>
            </div>

            {{-- Password --}}
            <div x-show="tab==='password'" x-cloak class="card animate-fade-up">
                <div class="card-header">
                    <h2 class="font-bold text-lg flex items-center gap-2">
                        <span class="material-symbols-outlined text-brand-600">lock</span>
                        تغيير كلمة المرور
                    </h2>
                </div>
                <div class="card-body p-5">
                    <form method="POST" action="{{ route('account.password') }}" class="space-y-4 max-w-md">
                        @csrf @method('PUT')
                        <div>
                            <label class="form-label">كلمة المرور الحالية <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <input type="password" name="current_password" required
                                       class="form-input pl-11 @error('current_password') form-input-error @enderror">
                                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">key</span>
                            </div>
                            @error('current_password')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="form-label">كلمة المرور الجديدة <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <input type="password" name="password" required minlength="6"
                                       class="form-input pl-11 @error('password') form-input-error @enderror">
                                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">lock</span>
                            </div>
                            @error('password')<p class="form-error">{{ $message }}</p>@enderror
                            <p class="form-help"><span class="material-symbols-outlined text-xs ml-1">info</span>يجب أن تكون 6 أحرف على الأقل</p>
                        </div>
                        <div>
                            <label class="form-label">تأكيد كلمة المرور الجديدة <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <input type="password" name="password_confirmation" required minlength="6"
                                       class="form-input pl-11">
                                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">shield</span>
                            </div>
                        </div>
                        <div class="pt-2">
                            <button type="submit" class="btn-primary">
                                <span class="material-symbols-outlined">shield</span>
                                تحديث كلمة المرور
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
