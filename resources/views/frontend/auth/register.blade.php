@extends('frontend.layout')

@section('title', __t('auth.register.title') . ' - ' . site('store_name'))
@section('description', __t('auth.register.description') . ' ' . site('store_name'))

@section('content')
@php
    $countries = config('ecommerce.countries', []);
    $defaultCountry = old('country_code', config('ecommerce.store.default_country', 'SD'));
@endphp

<section class="min-h-[80vh] flex items-center py-12 bg-gradient-to-bl from-gray-50 via-white to-accent-50/30">
    <div class="container-app">
        <div class="max-w-2xl mx-auto">
            <div class="card animate-fade-up overflow-hidden border border-outline-variant/60 shadow-lg">
                <div class="bg-gradient-to-l from-accent-500 via-rose-500 to-pink-500 text-white p-6 text-center">
                    <div class="w-16 h-16 mx-auto mb-3 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center border border-white/30">
                        <span class="material-symbols-outlined text-2xl">person_add</span>
                    </div>
                    <h1 class="text-2xl font-extrabold mb-1">{{ __t('auth.register.heading') }}</h1>
                    <p class="text-white/90 text-sm">{{ __t('auth.register.subtitle') }}</p>
                </div>

                <div class="card-body p-6 md:p-8">
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

                    <form method="POST" action="{{ route('register') }}" class="space-y-4">
                        @csrf

                        <div>
                            <label class="form-label">{{ __t('auth.register.full_name') }} <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <input type="text" name="name" value="{{ old('name') }}" required
                                       placeholder="{{ __t('auth.register.full_name_placeholder') }}"
                                       class="form-input pl-11 @error('name') form-input-error @enderror">
                                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">person</span>
                            </div>
                            @error('name')<p class="form-error">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="form-label">{{ __t('auth.register.email') }} <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <input type="email" name="email" value="{{ old('email') }}" required
                                       placeholder="example@email.com"
                                       autocomplete="email"
                                       class="form-input pl-11 @error('email') form-input-error @enderror">
                                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">mail</span>
                            </div>
                            @error('email')<p class="form-error">{{ $message }}</p>@enderror
                        </div>

                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label">{{ __t('auth.register.country') }} <span class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <select name="country_code" id="country_code" required
                                            onchange="updateStates(this.value)"
                                            class="form-input pl-11 appearance-none @error('country_code') form-input-error @enderror">
                                        @foreach($countries as $code => $info)
                                            <option value="{{ $code }}" {{ $defaultCountry == $code ? 'selected' : '' }}
                                                    data-dial="{{ $info['dial_code'] }}">
                                                {{ $info['name'] }} ({{ $info['name_en'] }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">public</span>
                                </div>
                                @error('country_code')<p class="form-error">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="form-label">{{ __t('auth.register.state') }}</label>
                                <div class="relative">
                                    <select name="state_code" id="state_code" class="form-input pl-11 appearance-none">
                                        <option value="">{{ __t('auth.register.state_placeholder') }}</option>
                                        @foreach($countries[$defaultCountry]['states'] ?? [] as $code => $name)
                                            <option value="{{ $code }}" {{ old('state_code') == $code ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">location_on</span>
                                </div>
                            </div>
                        </div>

                        <div>
                                <label class="form-label">{{ __t('auth.register.phone') }} <span class="text-rose-500">*</span></label>
                            <div class="flex gap-2" dir="ltr">
                                <input type="text" id="dial_code" value="{{ $countries[$defaultCountry]['dial_code'] ?? '' }}"
                                       readonly
                                       class="w-20 px-3 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-center font-semibold text-gray-600">
                                <input type="text" name="phone" value="{{ old('phone') }}" required
                                       placeholder="5XXXXXXXX"
                                       class="flex-1 form-input text-right @error('phone') form-input-error @enderror">
                            </div>
                            <p class="form-help"><span class="material-symbols-outlined text-xs ml-1">info</span>{{ __t('auth.register.phone_help') }}</p>
                            @error('phone')<p class="form-error">{{ $message }}</p>@enderror
                        </div>

                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label">{{ __t('auth.register.password') }} <span class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <input type="password" name="password" required minlength="6"
                                           placeholder="••••••••"
                                           autocomplete="new-password"
                                           class="form-input pl-11 @error('password') form-input-error @enderror">
                                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">lock</span>
                                </div>
                                @error('password')<p class="form-error">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="form-label">{{ __t('auth.register.password_confirmation') }} <span class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <input type="password" name="password_confirmation" required
                                           placeholder="••••••••"
                                           autocomplete="new-password"
                                           class="form-input pl-11">
                                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">lock</span>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn-primary btn-block btn-lg mt-2 bg-gradient-to-l from-accent-500 to-pink-500 hover:from-accent-600 hover:to-pink-600">
                            <span class="material-symbols-outlined">person_add</span>
                            {{ __t('auth.register.submit') }}
                        </button>
                    </form>

                    <div class="relative my-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-200"></div>
                        </div>
                        <div class="relative flex justify-center text-xs">
                            <span class="bg-white px-3 text-gray-500">{{ __t('auth.register.or') }}</span>
                        </div>
                    </div>

                    <p class="text-center text-sm text-gray-600">
                        {{ __t('auth.register.has_account') }}
                        <a href="{{ route('login') }}" class="text-brand-600 font-bold hover:underline">
                            {{ __t('auth.register.login') }}
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

@php
    $countriesJson = json_encode($countries, JSON_UNESCAPED_UNICODE);
@endphp
<script>
const countriesData = @json($countriesJson);

function updateStates(countryCode) {
    const dialEl = document.getElementById('dial_code');
    const stateEl = document.getElementById('state_code');
    const info = countriesData[countryCode];
    if (!info) return;
    dialEl.value = info.dial_code;
    stateEl.innerHTML = '<option value="">' + __t('auth.register.state_placeholder') + '</option>';
    if (info.states) {
        for (const [code, name] of Object.entries(info.states)) {
            const opt = document.createElement('option');
            opt.value = code;
            opt.textContent = name;
            stateEl.appendChild(opt);
        }
    }
}
</script>
@endsection
