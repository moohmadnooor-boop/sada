@extends('Layout.app')

@section('title', $book->title)

@section('content')
    <div class="container-sada pt-12">
        <a href="{{ route('books.index') }}" class="inline-flex items-center gap-2 text-muted hover:text-[var(--accent)] text-sm mb-6">
            <x-icon name="arrow" class="w-4 h-4 rotate-180" /> المكتبة
        </a>

        <section class="card p-6 sm:p-10 overflow-hidden grid sm:grid-cols-[auto_1fr] gap-8 items-center">
            <x-echo-art class="absolute -bottom-24 -left-24 w-72 h-72 opacity-30 pointer-events-none" />
            <x-book-cover :book="$book" size="lg" class="mx-auto animate-fade-up" />
            <div class="relative text-center sm:text-start animate-fade-up" style="--i: 1">
                @if ($book->era)
                    <p class="section-eyebrow sm:justify-start justify-center">{{ $book->era }}</p>
                @endif
                <h1 class="font-display text-4xl sm:text-5xl font-bold mt-2">{{ $book->title }}</h1>
                <p class="text-gold font-semibold text-lg mt-2">{{ $book->author }}</p>
                <p class="text-muted leading-loose mt-4 max-w-2xl">{{ $book->description }}</p>

                <dl class="mt-6 flex justify-center sm:justify-start gap-8">
                    <div>
                        <dd class="font-display text-3xl font-bold text-accent tabular-nums">{{ $book->quotes_count }}</dd>
                        <dt class="text-xs text-muted">اقتباس من الكتاب</dt>
                    </div>
                    <div>
                        <dd class="font-display text-3xl font-bold text-accent tabular-nums">{{ $likesCount }}</dd>
                        <dt class="text-xs text-muted">إعجاب</dt>
                    </div>
                </dl>

                @auth
                    <button type="button" class="btn btn-primary mt-6" data-open-modal="share-modal" data-preselect-book="{{ $book->id }}">
                        <x-icon name="sparkle" class="w-4 h-4" /> شارك اقتباساً من هذا الكتاب
                    </button>
                @else
                    <a href="{{ route('login') }}" class="btn btn-ghost mt-6">سجّل الدخول لتشارك اقتباساً منه</a>
                @endauth
            </div>
        </section>

        <div class="mt-12 mb-6 flex flex-wrap items-center justify-between gap-4" data-reveal>
            <h2 class="font-display text-2xl font-bold">اقتباسات من «{{ $book->title }}»</h2>
            <form method="get" action="{{ route('books.show', $book) }}">
                <select name="sort" class="input !py-2 !w-48 text-sm" aria-label="الترتيب" data-auto-submit>
                    @foreach (\App\Models\Quote::SORTS as $value => $label)
                        <option value="{{ $value }}" @selected($sort === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        @if ($quotes->isEmpty())
            <x-empty-state title="لم يُشارك اقتباس من هذا الكتاب بعد" text="كن أول من يترك صدى هذا الكتاب في صدى." />
        @else
            <x-quote-grid :quotes="$quotes" />
        @endif
    </div>
@endsection
