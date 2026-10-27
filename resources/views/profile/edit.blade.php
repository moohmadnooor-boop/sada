@extends('Layout.app')

@section('title', 'تعديل الملف الشخصي')

@section('content')
    <div class="container-sada max-w-xl">
        <x-page-header eyebrow="ملفي" title="تعديل الملف الشخصي" />

        <form method="post" action="{{ route('profile.update') }}" class="card p-6 sm:p-8 flex flex-col gap-5 animate-fade-up" style="--i: 2">
            @csrf
            @method('PUT')
            <div class="flex items-center gap-4">
                <x-avatar :user="$user" size="12" />
                <p class="text-muted text-sm">صورتك تُولَّد تلقائياً من أول حرف في اسمك.</p>
            </div>
            <x-field name="name" label="الاسم" :value="$user->name" :max="50" required hint="من 2 إلى 50 حرفاً، حروف فقط" />
            <x-field type="textarea" name="bio" label="نبذة عنك (اختياري)" :value="$user->bio" :max="160" rows="3" hint="جملة قصيرة تعرّف بذائقتك" class="[&_textarea]:!font-sans [&_textarea]:!text-base" />
            <div class="flex gap-3">
                <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                <a href="{{ route('profile.show', $user) }}" class="btn btn-ghost">إلغاء</a>
            </div>
        </form>
    </div>
@endsection
