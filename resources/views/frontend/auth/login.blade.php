@extends('frontend.layout')

@section('title', 'تسجيل الدخول - ' . site('store_name'))
@section('description', 'سجل دخولك إلى حسابك في ' . site('store_name'))

@section('content')

<section class="min-h-[80vh] flex items-center py-12 bg-gradient-to-bl from-gray-50 via-white to-brand-50/30">
    <div class="container-app">
        <div class="max-w-md mx-auto">
            {{-- Card --}}
            <div class="card animate-fade-up overflow-hidden">
                <div class="bg-gradient-to-l from-brand-600 via-brand-500 to-accent-500 text-white p-6 text-center">
                    <div class="w-16 h-16 mx-auto mb-3 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center border border-white/30">
                        <span class="material-symbols-outlined text-2xl">login</span>
                    </div>
                    <h1 class="text-2xl font-extrabold mb-1">مرحباً بعودتك</h1>
                    <p class="text-white/90 text-sm">سجل دخولك للمتابعة</p>
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

                    <form method="POST" action="{{ route('login') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="form-label">البريد الإلكتروني</label>
                            <div class="relative">
                                <input type="email" name="email" value="{{ old('email') }}" required
                                       placeholder="example@email.com"
                                       autocomplete="email"
                                       class="form-input pl-11 @error('email') form-input-error @enderror">
                                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">mail</span>
                            </div>
                            @error('email')<p class="form-error">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="form-label">كلمة المرور</label>
                            <div class="relative">
                                <input type="password" name="password" required
                                       placeholder="••••••••"
                                       autocomplete="current-password"
                                       class="form-input pl-11 @error('password') form-input-error @enderror">
                                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">lock</span>
                            </div>
                            @error('password')<p class="form-error">{{ $message }}</p>@enderror
                        </div>

                        <div class="flex items-center justify-between text-sm">
                            <label class="flex items-center gap-2 cursor-pointer text-gray-600">
                                <input type="checkbox" name="remember" class="form-checkbox">
                                <span>تذكرني</span>
                            </label>
                            <a href="#" class="text-brand-600 font-semibold hover:underline">
                                نسيت كلمة المرور؟
                            </a>
                        </div>

                        <button type="submit" class="btn-primary btn-block btn-lg mt-2 bg-gradient-to-l from-brand-600 to-accent-500 hover:from-brand-700 hover:to-accent-600">
                            <span class="material-symbols-outlined">login</span>
                            تسجيل الدخول
                        </button>
                    </form>

                    <div class="relative my-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-200"></div>
                        </div>
                        <div class="relative flex justify-center text-xs">
                            <span class="bg-white px-3 text-gray-500">أو</span>
                        </div>
                    </div>

                    <p class="text-center text-sm text-gray-600">
                        ليس لديك حساب؟
                        <a href="{{ route('register') }}" class="text-brand-600 font-bold hover:underline">
                            سجل الآن
                        </a>
                    </p>
                </div>
            </div>

            <p class="text-center text-xs text-gray-500 mt-6">
                بتسجيل دخولك، أنت توافق على
                <a href="{{ route('page.show', 'terms') }}" class="text-brand-600 hover:underline">الشروط والأحكام</a>
                و
                <a href="{{ route('page.show', 'privacy') }}" class="text-brand-600 hover:underline">سياسة الخصوصية</a>
            </p>
        </div>
    </div>
</section>
@endsection
