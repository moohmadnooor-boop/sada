@extends('Layout.AuthImg')

@section('title', 'إنشاء حساب')

@section('aside', '«من شارك كلمة طيبة، ترك أثراً لا يزول»')

@section('form')
    <div class="card p-7 sm:p-10 max-w-md w-full mx-auto">
        <h1 class="font-display text-3xl font-bold">انضم لصدى</h1>
        <p class="text-muted mt-2">أنشئ حسابك وشارك أول اقتباس لك خلال أقل من دقيقة.</p>

        <form method="post" action="{{ url('/signup') }}" class="mt-8 flex flex-col gap-5" novalidate>
            @csrf
            <x-field name="name" label="الاسم" :max="50" required autocomplete="name" placeholder="مثال: سارة العتيبي" hint="من 2 إلى 50 حرفاً، حروف فقط بدون أرقام أو رموز" />
            <x-field type="email" name="email" label="البريد الإلكتروني" required autocomplete="email" placeholder="name@example.com" hint="سنستخدمه لتسجيل دخولك" />
            <x-field type="password" name="password" label="كلمة المرور" required autocomplete="new-password" placeholder="••••••••" hint="8 أحرف على الأقل" />
            <button type="submit" class="btn btn-primary btn-lg mt-2">إنشاء الحساب</button>
        </form>

        <p class="text-center text-muted mt-6 text-sm">لديك حساب بالفعل؟ <a href="{{ route('login') }}" class="text-accent font-semibold hover:underline">تسجيل الدخول</a></p>
    </div>
@endsection
