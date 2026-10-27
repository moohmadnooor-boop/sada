@extends('Layout.AuthImg')

@section('title', 'تسجيل الدخول')

@section('form')
    <div class="card p-7 sm:p-10 max-w-md w-full mx-auto">
        <h1 class="font-display text-3xl font-bold">أهلاً بعودتك</h1>
        <p class="text-muted mt-2">سجّل دخولك لتشارك اقتباساتك وتتفاعل مع الآخرين.</p>

        <form method="post" action="{{ url('/login') }}" class="mt-8 flex flex-col gap-5" novalidate>
            @csrf
            <x-field type="email" name="email" label="البريد الإلكتروني" required autocomplete="email" placeholder="name@example.com" hint="البريد الذي سجلت به" />
            <x-field type="password" name="password" label="كلمة المرور" required autocomplete="current-password" placeholder="••••••••" hint="8 أحرف على الأقل" />
            <label class="flex items-center gap-2 text-sm text-muted cursor-pointer">
                <input type="checkbox" name="remember" value="1" class="accent-[var(--accent)] w-4 h-4"> تذكرني
            </label>
            <button type="submit" class="btn btn-primary btn-lg">تسجيل الدخول</button>
        </form>

        <p class="text-center text-muted mt-6 text-sm">ليس لديك حساب؟ <a href="{{ route('signup') }}" class="text-accent font-semibold hover:underline">أنشئ حساباً</a></p>
    </div>
@endsection
