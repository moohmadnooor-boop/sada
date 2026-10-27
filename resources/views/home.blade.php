@extends('Layout.app', ['overlayNav' => true])

@section('title', 'كلمات يبقى صداها')

@section('content')
    {{-- ======================= HERO ======================= --}}
    <section class="hero" data-hero>
        <div class="hero-media" aria-hidden="true">
            @if (file_exists(public_path('videos/hero.mp4')))
                <video autoplay muted loop playsinline poster="{{ asset('images/hero.jpg') }}">
                    <source src="{{ asset('videos/hero.mp4') }}" type="video/mp4">
                </video>
            @elseif (file_exists(public_path('images/hero.jpg')))
                <img src="{{ asset('images/hero.jpg') }}" alt="">
            @endif
        </div>
        <canvas class="hero-canvas" data-echo-canvas aria-hidden="true"></canvas>

        <div class="floating-quote hidden xl:block" style="top: 22%; right: 7%; --r: -3deg">«على قدر أهل العزم تأتي العزائم»</div>
        <div class="floating-quote hidden xl:block" style="top: 64%; right: 12%; --r: 2deg; animation-delay: -3s">«خير الكلام ما قلّ ودلّ»</div>
        <div class="floating-quote hidden xl:block" style="top: 28%; left: 8%; --r: 3deg; animation-delay: -5s">«الصديق وقت الضيق»</div>
        <div class="floating-quote hidden xl:block" style="top: 68%; left: 10%; --r: -2deg; animation-delay: -1.5s">«من جدّ وجد، ومن زرع حصد»</div>

        <div class="container-sada text-center pt-36 lg:pt-24 pb-16 relative">
            <p class="section-eyebrow justify-center animate-fade-up">منصة الاقتباسات العربية</p>

            <h1 class="echo-title mt-4 animate-fade-up" style="--i: 1">صدى<span aria-hidden="true">صدى</span><span aria-hidden="true">صدى</span><span aria-hidden="true">صدى</span></h1>

            <p class="font-quote text-2xl sm:text-3xl mt-6 animate-fade-up" style="--i: 2">كلمات يبقى صداها</p>
            <p class="text-muted text-lg mt-4 max-w-xl mx-auto animate-fade-up" style="--i: 3">
                اكتشف اقتباسات تلامس قلبك، شارك كلماتك المفضلة، وتابع من يشبهك في ذائقته.
            </p>

            <div class="mt-10 flex flex-wrap items-center justify-center gap-3 animate-fade-up" style="--i: 4">
                <a href="{{ route('quotes.index') }}" class="btn btn-primary btn-lg">استكشف الاقتباسات <x-icon name="arrow" class="w-5 h-5" /></a>
                @guest
                    <a href="{{ route('signup') }}" class="btn btn-ghost btn-lg">انضم لصدى مجاناً</a>
                @else
                    <button type="button" class="btn btn-ghost btn-lg" data-open-modal="share-modal">شارك اقتباساً</button>
                @endguest
            </div>
        </div>

        <a href="#today" class="scroll-cue" aria-label="انزل للأسفل"></a>
    </section>

    {{-- ================= QUOTE OF THE DAY ================= --}}
    @if ($quoteOfTheDay)
        <section id="today" class="container-sada pt-20">
            <div class="card p-8 sm:p-14 text-center overflow-hidden" data-reveal>
                <x-echo-art class="absolute -top-24 -left-24 w-72 h-72 opacity-40 pointer-events-none" />
                <p class="section-eyebrow justify-center">🌟 اقتباس اليوم · {{ now()->translatedFormat('l j F') }}</p>
                <div class="quote-mark mt-6" aria-hidden="true">”</div>
                <blockquote class="quote-text quote-text--xl max-w-3xl mx-auto min-h-[5.5rem]" data-typewriter="{{ $quoteOfTheDay->content }}">{{ $quoteOfTheDay->content }}</blockquote>
                @if ($quoteOfTheDay->author)
                    <p class="mt-4 text-gold font-semibold text-lg">— {{ $quoteOfTheDay->author }}</p>
                @endif
                <p class="mt-6 text-sm text-muted">
                    شاركه <a href="{{ route('profile.show', $quoteOfTheDay->user) }}" class="text-accent hover:underline">{{ $quoteOfTheDay->user->name }}</a>
                    · <a href="{{ route('quotes.show', $quoteOfTheDay) }}" class="hover:underline">{{ $quoteOfTheDay->likes_count }} إعجاب</a>
                </p>
                <div class="mt-6 flex justify-center gap-2" data-quote data-content="{{ $quoteOfTheDay->content }}" data-author="{{ $quoteOfTheDay->author }}" data-sharer="{{ $quoteOfTheDay->user->name }}" data-url="{{ route('quotes.show', $quoteOfTheDay) }}">
                    <button type="button" class="btn btn-ghost btn-sm" data-copy><x-icon name="copy" class="w-4 h-4" /> نسخ</button>
                    <button type="button" class="btn btn-ghost btn-sm" data-make-image><x-icon name="image" class="w-4 h-4" /> حوّله لصورة</button>
                </div>
            </div>
        </section>
    @endif

    {{-- ======================= STATS ======================= --}}
    <section class="container-sada pt-20">
        <div class="grid grid-cols-3 gap-4 text-center">
            @foreach ([['quotes', 'اقتباس منشور'], ['users', 'عضو في صدى'], ['likes', 'إعجاب']] as [$key, $label])
                <div class="card p-6" data-reveal style="--i: {{ $loop->index }}">
                    <div class="stat-number tabular-nums" data-count-to="{{ $stats[$key] }}">0</div>
                    <p class="text-muted mt-1 text-sm sm:text-base">{{ $label }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ===================== CATEGORIES ===================== --}}
    <section class="container-sada pt-24">
        <div class="flex items-end justify-between gap-4 mb-8" data-reveal>
            <div>
                <p class="section-eyebrow">التصنيفات</p>
                <h2 class="font-display text-3xl sm:text-4xl font-bold mt-2">لكل مزاج صداه</h2>
            </div>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach ($categories as $category)
                <a href="{{ route('quotes.index', ['category' => $category->slug]) }}" class="card quote-card !p-5 !gap-2 group" data-reveal style="--i: {{ $loop->index }}; --chip: {{ $category->color }}">
                    <span class="text-3xl transition group-hover:scale-125 origin-right">{{ $category->icon }}</span>
                    <span class="category-name font-display text-xl font-bold">{{ $category->name }}</span>
                    <span class="text-sm text-muted">{{ $category->quotes_count }} اقتباس</span>
                </a>
            @endforeach
        </div>
    </section>

    {{-- ===================== FEATURED ===================== --}}
    <section class="container-sada pt-24">
        <div class="flex items-end justify-between gap-4 mb-8" data-reveal>
            <div>
                <p class="section-eyebrow">الأعلى صدى</p>
                <h2 class="font-display text-3xl sm:text-4xl font-bold mt-2">اقتباسات أحبها الناس</h2>
            </div>
            <a href="{{ route('trending') }}" class="btn btn-ghost btn-sm hidden sm:inline-flex">عرض الكل</a>
        </div>
        <x-quote-grid :quotes="$featuredQuotes" />
    </section>

    {{-- ===================== LIBRARY ===================== --}}
    @if ($books->isNotEmpty())
        <section class="container-sada pt-24">
            <div class="flex items-end justify-between gap-4 mb-8" data-reveal>
                <div>
                    <p class="section-eyebrow">📚 المكتبة</p>
                    <h2 class="font-display text-3xl sm:text-4xl font-bold mt-2">من رفوف المكتبة</h2>
                </div>
                <a href="{{ route('books.index') }}" class="btn btn-ghost btn-sm">كل الكتب</a>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-6">
                @foreach ($books as $book)
                    <a href="{{ route('books.show', $book) }}" class="book-link group" data-reveal style="--i: {{ $loop->index }}">
                        <x-book-cover :book="$book" />
                        <p class="mt-3 font-semibold group-hover:text-[var(--accent)] transition">{{ $book->title }}</p>
                        <p class="text-sm text-muted">{{ $book->quotes_count }} اقتباس</p>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- ===================== HOW IT WORKS ===================== --}}
    <section class="container-sada pt-24">
        <div class="text-center mb-10" data-reveal>
            <p class="section-eyebrow justify-center">كيف يعمل صدى؟</p>
            <h2 class="font-display text-3xl sm:text-4xl font-bold mt-2">ثلاث خطوات ويصل صوتك</h2>
        </div>
        <div class="grid md:grid-cols-3 gap-5">
            @foreach ([
                ['user', 'أنشئ حسابك', 'اسم وبريد وكلمة مرور فقط، وتصبح جزءاً من صدى خلال ثوانٍ.'],
                ['sparkle', 'شارك كلماتك', 'اضغط زر + واكتب اقتباسك، اختر له تصنيفاً وانشره للجميع.'],
                ['heart', 'تفاعل وتابع', 'أعجب، احفظ، علّق، وتابع من تحب ذائقتهم لتصلك اقتباساتهم.'],
            ] as [$icon, $title, $text])
                <div class="card p-8" data-reveal style="--i: {{ $loop->index }}">
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center bg-[var(--accent-soft)] text-accent mb-5">
                        <x-icon :name="$icon" class="w-7 h-7" />
                    </div>
                    <p class="text-muted text-sm font-semibold">0{{ $loop->iteration }}</p>
                    <h3 class="font-display text-2xl font-bold mt-1">{{ $title }}</h3>
                    <p class="text-muted mt-2 leading-relaxed">{{ $text }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ===================== CTA ===================== --}}
    <section class="container-sada pt-24">
        <div class="card p-10 sm:p-16 text-center overflow-hidden" data-reveal>
            <canvas class="hero-canvas opacity-60" data-echo-canvas data-echo-small aria-hidden="true"></canvas>
            <div class="relative">
                <h2 class="font-display text-3xl sm:text-5xl font-bold">عندك كلمة تستحق أن تُسمع؟</h2>
                <p class="text-muted text-lg mt-4">اكتبها في صدى، ودعها تتردد في قلوب الآخرين.</p>
                <div class="mt-8">
                    @guest
                        <a href="{{ route('signup') }}" class="btn btn-primary btn-lg">ابدأ الآن</a>
                    @else
                        <button type="button" class="btn btn-primary btn-lg" data-open-modal="share-modal">شارك اقتباسك الأول</button>
                    @endguest
                </div>
            </div>
        </div>
    </section>
@endsection
