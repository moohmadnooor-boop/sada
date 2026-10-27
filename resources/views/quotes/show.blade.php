@extends('Layout.app')

@section('title', \Illuminate\Support\Str::limit($quote->content, 40))

@section('content')
    <div class="container-sada max-w-3xl pt-12">
        <a href="{{ route('quotes.index') }}" class="inline-flex items-center gap-2 text-muted hover:text-[var(--accent)] text-sm mb-6">
            <x-icon name="arrow" class="w-4 h-4 rotate-180" /> كل الاقتباسات
        </a>

        <x-quote-card :quote="$quote" size="xl" class="!p-8 sm:!p-10" />

        <section id="comments" class="mt-12 scroll-mt-24">
            <h2 class="font-display text-2xl font-bold mb-5">التعليقات <span class="text-muted text-lg">({{ $comments->count() }})</span></h2>

            @auth
                <form method="post" action="{{ route('comments.store', $quote) }}" class="card p-5 flex gap-3 items-start mb-6" data-reveal>
                    @csrf
                    <x-avatar :user="auth()->user()" />
                    <div class="flex-1">
                        <label for="comment-body" class="sr-only">تعليقك</label>
                        <textarea id="comment-body" name="body" rows="2" maxlength="300" required data-counter-max="300"
                            class="input resize-none {{ $errors->has('body') ? 'has-error' : '' }}" placeholder="شاركنا ما أثاره فيك هذا الاقتباس...">{{ old('body') }}</textarea>
                        <p class="hint"><span>حتى 300 حرف</span><span class="counter tabular-nums" data-counter-for="comment-body">0 / 300</span></p>
                        @error('body') <p class="error">{{ $message }}</p> @enderror
                        <div class="mt-3 flex justify-end">
                            <button type="submit" class="btn btn-primary btn-sm">تعليق</button>
                        </div>
                    </div>
                </form>
            @else
                <div class="card p-5 mb-6 text-center text-muted">
                    <a href="{{ route('login') }}" class="text-accent font-semibold hover:underline">سجّل الدخول</a> لتضيف تعليقك.
                </div>
            @endauth

            <div class="flex flex-col gap-3">
                @forelse ($comments as $comment)
                    <article class="card p-5 flex gap-3" data-reveal style="--i: {{ $loop->index % 6 }}">
                        <a href="{{ route('profile.show', $comment->user) }}"><x-avatar :user="$comment->user" /></a>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <a href="{{ route('profile.show', $comment->user) }}" class="font-semibold hover:text-[var(--accent)]">{{ $comment->user->name }}</a>
                                <time class="text-xs text-muted">{{ $comment->created_at->diffForHumans() }}</time>
                                @can('delete', $comment)
                                    <form method="post" action="{{ route('comments.destroy', $comment) }}" class="ms-auto">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="icon-btn !h-8 hover:!text-[var(--danger)]" aria-label="حذف التعليق" title="حذف"><x-icon name="trash" class="w-4 h-4" /></button>
                                    </form>
                                @endcan
                            </div>
                            <p class="mt-1 leading-relaxed overflow-wrap-anywhere">{{ $comment->body }}</p>
                        </div>
                    </article>
                @empty
                    <p class="text-muted text-center py-6">لا توجد تعليقات بعد. كن أول من يترك صداه هنا.</p>
                @endforelse
            </div>
        </section>
    </div>

    @if ($related->isNotEmpty())
        <section class="container-sada pt-20">
            <h2 class="font-display text-2xl font-bold mb-6" data-reveal>اقتباسات قد تعجبك</h2>
            <x-quote-grid :quotes="$related" />
        </section>
    @endif
@endsection
