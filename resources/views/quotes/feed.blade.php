@extends('Layout.app')

@section('title', 'متابعاتي')

@section('content')
    <div class="container-sada">
        <x-page-header eyebrow="متابعاتي" title="صدى من تتابعهم" text="أحدث ما شاركه الأشخاص الذين تتابعهم." />

        @if ($quotes->isEmpty())
            <x-empty-state title="لا يوجد صدى من متابعاتك بعد" text="تابع أشخاصاً تحب ذائقتهم من صفحاتهم الشخصية، وستظهر اقتباساتهم هنا.">
                <a href="{{ route('quotes.index') }}" class="btn btn-primary">اكتشف أشخاصاً جدداً</a>
            </x-empty-state>
        @else
            <x-quote-grid :quotes="$quotes" />
        @endif
    </div>
@endsection
