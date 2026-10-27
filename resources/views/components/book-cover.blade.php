@props(['book', 'size' => 'md'])

@php
    $coverUrl = $book->coverUrl();
@endphp

{{-- The real cover when one exists, otherwise a generated cover in the Sada style. Both get a spine on the right (RTL). --}}
<div {{ $attributes->merge(['class' => 'book-cover book-cover--'.$size.($coverUrl ? ' book-cover--image' : '')]) }} style="--cover: {{ $book->cover_color }}">
    @if ($coverUrl)
        <img src="{{ $coverUrl }}" alt="غلاف كتاب {{ $book->title }}" title="{{ $book->coverCredit() }}" loading="lazy">
    @else
        <div class="book-cover__rings" aria-hidden="true"></div>
        <div class="book-cover__body" aria-hidden="true">
            <span class="book-cover__mark">”</span>
            <span class="book-cover__title">{{ $book->title }}</span>
            <span class="book-cover__rule"></span>
            <span class="book-cover__author">{{ $book->author }}</span>
        </div>
        <span class="book-cover__brand" aria-hidden="true">صدى</span>
    @endif
</div>
