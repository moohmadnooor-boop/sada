@extends('Layout.app')

@section('title', 'محفوظاتي')

@section('content')
    <div class="container-sada">
        <x-page-header eyebrow="محفوظاتي" title="اقتباسات احتفظت بها" text="كل اقتباس ضغطت على أيقونة الحفظ فيه تجده هنا." />

        @if ($quotes->isEmpty())
            <x-empty-state title="لم تحفظ أي اقتباس بعد" text="اضغط على أيقونة الحفظ في أي اقتباس يعجبك ليبقى صداه معك.">
                <a href="{{ route('quotes.index') }}" class="btn btn-primary">تصفح الاقتباسات</a>
            </x-empty-state>
        @else
            <x-quote-grid :quotes="$quotes" />
        @endif
    </div>
@endsection
