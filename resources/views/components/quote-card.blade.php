@props(['quote', 'index' => 0, 'size' => 'md', 'rank' => null])

@php
    $isOwner = auth()->check() && auth()->id() === $quote->user_id;
    $shareText = '«'.$quote->content.'»'.($quote->author ? ' — '.$quote->author : '');
    $quoteUrl = route('quotes.show', $quote);
@endphp

<article {{ $attributes->merge(['class' => 'card quote-card']) }} data-reveal style="--i: {{ $index % 6 }}"
    data-quote
    data-quote-id="{{ $quote->id }}"
    data-content="{{ $quote->content }}"
    data-author="{{ $quote->author }}"
    data-sharer="{{ $quote->user->name }}"
    data-url="{{ $quoteUrl }}">

    <header class="flex items-center gap-3">
        @if ($rank)
            <span class="rank">{{ $rank }}</span>
        @endif
        <a href="{{ route('profile.show', $quote->user) }}" class="flex items-center gap-3 min-w-0 group">
            <x-avatar :user="$quote->user" />
            <span class="min-w-0">
                <span class="block font-semibold truncate group-hover:text-[var(--accent)] transition">{{ $quote->user->name }}</span>
                <time class="block text-xs text-muted" datetime="{{ $quote->created_at->toIso8601String() }}" title="{{ $quote->created_at->translatedFormat('j F Y، g:i a') }}">
                    {{ $quote->created_at->translatedFormat('j F Y') }} · {{ $quote->created_at->diffForHumans() }}
                </time>
            </span>
        </a>
        @if ($quote->category)
            <a href="{{ route('quotes.index', ['category' => $quote->category->slug]) }}" class="chip ms-auto" style="--chip: {{ $quote->category->color }}">
                {{ $quote->category->icon }} {{ $quote->category->name }}
            </a>
        @endif
    </header>

    <div data-quote-view>
        <div class="quote-mark" aria-hidden="true">”</div>
        <a href="{{ $quoteUrl }}" class="block">
            <blockquote class="quote-text {{ $size === 'xl' ? 'quote-text--xl' : '' }}">{{ $quote->content }}</blockquote>
        </a>
        @if ($quote->author)
            <p class="mt-3 text-gold font-semibold">— {{ $quote->author }}</p>
        @endif
        @if ($quote->book)
            <a href="{{ route('books.show', $quote->book) }}" class="book-tag">📖 من كتاب «{{ $quote->book->title }}»</a>
        @endif
    </div>

    @if ($isOwner)
        <form method="post" action="{{ route('quotes.update', $quote) }}" class="hidden flex-col gap-3" data-quote-edit>
            @csrf
            @method('PUT')
            <x-field type="textarea" name="content" label="نص الاقتباس" :value="$quote->content" :max="155" :min="3" :use-old="false" required
                hint="من 3 إلى 155 حرفاً" id="edit-content-{{ $quote->id }}" />
            <div class="grid sm:grid-cols-2 gap-3">
                <x-field name="author" label="قائل الاقتباس (اختياري)" :value="$quote->author" :max="80" :use-old="false" hint="مثال: المتنبي" id="edit-author-{{ $quote->id }}" />
                <x-field type="select" name="category_id" label="التصنيف" :use-old="false" id="edit-category-{{ $quote->id }}">
                    <option value="">بدون تصنيف</option>
                    @foreach ($allCategories as $category)
                        <option value="{{ $category->id }}" @selected($quote->category_id === $category->id)>{{ $category->icon }} {{ $category->name }}</option>
                    @endforeach
                </x-field>
            </div>
            <x-field type="select" name="book_id" label="من كتاب (اختياري)" :use-old="false" id="edit-book-{{ $quote->id }}">
                <option value="">ليس من كتاب في المكتبة</option>
                @foreach ($allBooks as $book)
                    <option value="{{ $book->id }}" @selected($quote->book_id === $book->id)>{{ $book->title }} — {{ $book->author }}</option>
                @endforeach
            </x-field>
            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm">حفظ</button>
                <button type="button" class="btn btn-ghost btn-sm" data-edit-cancel>إلغاء</button>
            </div>
        </form>
    @endif

    <footer class="mt-auto flex flex-wrap items-center gap-0.5 border-t border-[var(--border)] pt-3 -mx-2">
        @auth
            <button type="button" class="icon-btn {{ $quote->liked_by_viewer ? 'is-active' : '' }}" data-like data-url="{{ route('quotes.like', $quote) }}"
                aria-pressed="{{ $quote->liked_by_viewer ? 'true' : 'false' }}" aria-label="إعجاب" title="إعجاب">
                <x-icon name="heart" /> <span data-count class="tabular-nums">{{ $quote->likes_count }}</span>
            </button>
        @else
            <a href="{{ route('login') }}" class="icon-btn" title="سجّل الدخول للإعجاب"><x-icon name="heart" /> <span class="tabular-nums">{{ $quote->likes_count }}</span></a>
        @endauth

        <a href="{{ $quoteUrl }}#comments" class="icon-btn" title="التعليقات"><x-icon name="comment" /> <span class="tabular-nums">{{ $quote->comments_count }}</span></a>

        @auth
            <button type="button" class="icon-btn {{ $quote->bookmarked_by_viewer ? 'is-active' : '' }}" data-bookmark data-url="{{ route('quotes.bookmark', $quote) }}"
                aria-pressed="{{ $quote->bookmarked_by_viewer ? 'true' : 'false' }}" aria-label="حفظ" title="حفظ">
                <x-icon name="bookmark" />
            </button>
        @else
            <a href="{{ route('login') }}" class="icon-btn" title="سجّل الدخول للحفظ"><x-icon name="bookmark" /></a>
        @endauth

        <span class="ms-auto"></span>

        <button type="button" class="icon-btn" data-copy title="نسخ الاقتباس" aria-label="نسخ"><x-icon name="copy" /></button>
        <div class="relative" data-dropdown>
            <button type="button" class="icon-btn" data-dropdown-trigger title="مشاركة" aria-label="مشاركة"><x-icon name="share" /></button>
            <div class="dropdown !min-w-44 !top-auto bottom-[calc(100%+0.5rem)]" data-dropdown-menu>
                <a class="dropdown-item" target="_blank" rel="noopener" href="https://twitter.com/intent/tweet?text={{ urlencode($shareText) }}&url={{ urlencode($quoteUrl) }}"><x-icon name="x" class="w-4 h-4" /> مشاركة على X</a>
                <a class="dropdown-item" target="_blank" rel="noopener" href="https://wa.me/?text={{ urlencode($shareText."\n".$quoteUrl) }}"><x-icon name="whatsapp" class="w-4 h-4" /> مشاركة على واتساب</a>
                <button type="button" class="dropdown-item" data-copy-link><x-icon name="copy" class="w-4 h-4" /> نسخ الرابط</button>
            </div>
        </div>
        <button type="button" class="icon-btn" data-make-image title="حوّله لصورة" aria-label="تحويل لصورة"><x-icon name="image" /></button>

        @if ($isOwner)
            <button type="button" class="icon-btn" data-edit-toggle title="تعديل" aria-label="تعديل"><x-icon name="edit" /></button>
            <form method="post" action="{{ route('quotes.destroy', $quote) }}" data-delete-form>
                @csrf
                @method('DELETE')
                <button type="submit" class="icon-btn hover:!text-[var(--danger)]" title="حذف" aria-label="حذف"><x-icon name="trash" /></button>
            </form>
        @endif
    </footer>
</article>
