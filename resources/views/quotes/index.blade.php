@extends('Layout.app')

@section('title', 'الاقتباسات')

@section('content')
    <div class="container-sada">
        <x-page-header eyebrow="اكتشف" title="كل الاقتباسات" text="ابحث، صنّف، ورتّب كما تحب. كل اقتباس هنا شاركه شخص أحبّ أن يُسمع صداه." />

        <form method="get" action="{{ route('quotes.index') }}" class="card p-4 sm:p-5 flex flex-col gap-4 animate-fade-up" style="--i: 3" data-filter-form>
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <x-icon name="search" class="w-5 h-5 absolute top-1/2 -translate-y-1/2 start-4 text-muted" />
                    <input type="search" id="search" name="q" value="{{ $search }}" placeholder="ابحث في نص الاقتباس أو اسم القائل أو الناشر..." class="input !ps-12" aria-label="بحث">
                </div>
                <select name="sort" class="input sm:!w-52" aria-label="الترتيب" data-auto-submit>
                    @foreach (\App\Models\Quote::SORTS as $value => $label)
                        <option value="{{ $value }}" @selected($sort === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @if ($activeCategory)
                    <input type="hidden" name="category" value="{{ $activeCategory->slug }}">
                @endif
                <button type="submit" class="btn btn-primary">بحث</button>
            </div>

            <div class="flex gap-2 overflow-x-auto pb-1 -mx-1 px-1">
                <a href="{{ route('quotes.index', array_filter(['q' => $search, 'sort' => $sort !== 'latest' ? $sort : null])) }}" class="chip {{ $activeCategory ? '' : 'is-selected' }}">الكل</a>
                @foreach ($categories as $category)
                    <a href="{{ route('quotes.index', array_filter(['category' => $category->slug, 'q' => $search, 'sort' => $sort !== 'latest' ? $sort : null])) }}"
                        class="chip {{ $activeCategory?->is($category) ? 'is-selected' : '' }}" style="--chip: {{ $category->color }}">
                        {{ $category->icon }} {{ $category->name }}
                    </a>
                @endforeach
            </div>
        </form>

        <div class="mt-8 flex items-center justify-between text-sm text-muted">
            <p>
                @if ($search !== '' || $activeCategory)
                    {{ $quotes->total() }} نتيجة
                    @if ($search !== '') لـ «{{ $search }}» @endif
                    @if ($activeCategory) في {{ $activeCategory->name }} @endif
                @else
                    {{ $quotes->total() }} اقتباس
                @endif
            </p>
            @guest
                <a href="{{ route('login') }}" class="text-accent hover:underline">سجّل الدخول لتشارك اقتباساً</a>
            @endguest
        </div>

        <div class="mt-5">
            @if ($quotes->isEmpty())
                <x-empty-state title="لا يوجد صدى هنا بعد" text="لم نجد اقتباسات تطابق بحثك. جرّب كلمة أخرى أو تصنيفاً مختلفاً.">
                    <a href="{{ route('quotes.index') }}" class="btn btn-ghost">عرض كل الاقتباسات</a>
                </x-empty-state>
            @else
                <x-quote-grid :quotes="$quotes" />
            @endif
        </div>
    </div>
@endsection
