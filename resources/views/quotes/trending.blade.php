@extends('Layout.app')

@section('title', 'الأكثر صدى')

@section('content')
    <div class="container-sada max-w-3xl">
        <x-page-header eyebrow="🔥 هذا الأسبوع" title="الأكثر صدى" text="أكثر عشرة اقتباسات حصدت إعجاب الناس خلال آخر سبعة أيام." />

        @if ($quotes->isEmpty())
            <x-empty-state title="الهدوء يعمّ صدى" text="لم تُنشر اقتباسات بعد، كن أول من يكسر الصمت." />
        @else
            <div class="flex flex-col gap-5">
                @foreach ($quotes as $quote)
                    <x-quote-card :quote="$quote" :index="$loop->index" :rank="$loop->iteration" />
                @endforeach
            </div>
        @endif
    </div>
@endsection
