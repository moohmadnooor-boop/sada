@extends('Layout.app')

@section('title', 'المكتبة')

@section('content')
    <div class="container-sada">
        <x-page-header eyebrow="📚 المكتبة" title="كتب يتردد صداها" text="اختر كتاباً وتصفّح الاقتباسات التي شاركها الناس منه. وعند مشاركة اقتباس يمكنك أن تنسبه لكتابه." />

        <div class="bookshelf grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-x-6 gap-y-12 pt-4">
            @foreach ($books as $book)
                <a href="{{ route('books.show', $book) }}" class="book-link group" data-reveal style="--i: {{ $loop->index }}">
                    <x-book-cover :book="$book" />
                    <div class="mt-4">
                        <h2 class="font-display text-lg font-bold group-hover:text-[var(--accent)] transition">{{ $book->title }}</h2>
                        <p class="text-muted text-sm">{{ $book->author }}</p>
                        <p class="text-accent text-sm font-semibold mt-1">{{ $book->quotes_count }} اقتباس</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
@endsection
