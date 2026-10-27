@props(['overlay' => false])

@php
    $links = [
        ['route' => 'home', 'label' => 'الرئيسية'],
        ['route' => 'quotes.index', 'label' => 'الاقتباسات'],
        ['route' => 'trending', 'label' => 'الأكثر صدى'],
        ['route' => 'books.index', 'label' => 'المكتبة', 'active' => 'books.*'],
    ];
    if (auth()->check()) {
        $links[] = ['route' => 'feed', 'label' => 'متابعاتي'];
        $links[] = ['route' => 'bookmarks', 'label' => 'محفوظاتي'];
    }
@endphp

<nav class="nav {{ $overlay ? 'nav--overlay' : '' }}" data-nav>
    <div class="container-sada flex h-[4.5rem] items-center gap-4">
        <x-logo />

        <ul class="hidden lg:flex items-center gap-1 ms-6">
            @foreach ($links as $link)
                <li>
                    <a href="{{ route($link['route']) }}" class="nav-link {{ request()->routeIs($link['active'] ?? $link['route']) ? 'is-active' : '' }}">{{ $link['label'] }}</a>
                </li>
            @endforeach
        </ul>

        <div class="ms-auto flex items-center gap-1.5">
            <a href="{{ route('quotes.index') }}#search" class="icon-btn xl:hidden" aria-label="بحث" title="بحث"><x-icon name="search" /></a>
            <form action="{{ route('quotes.index') }}" method="get" class="hidden xl:block relative" role="search">
                <x-icon name="search" class="w-4 h-4 absolute top-1/2 -translate-y-1/2 start-3 text-muted" />
                <input type="search" name="q" value="{{ request('q') }}" placeholder="ابحث عن كلمة أو قائل..." class="input !py-2 !ps-9 !rounded-full w-56 text-sm" aria-label="بحث">
            </form>

            <button type="button" class="icon-btn" data-theme-toggle aria-label="تبديل الوضع الليلي" title="تبديل الوضع">
                <x-icon name="sun" class="theme-icon-day" />
                <x-icon name="moon" class="theme-icon-night" />
            </button>

            @auth
                <div class="relative" data-dropdown>
                    <button type="button" class="flex items-center gap-2 rounded-full p-1 pe-3 hover:bg-[var(--surface-hover)] transition" data-dropdown-trigger aria-haspopup="true" aria-expanded="false">
                        <x-avatar :user="auth()->user()" size="8" />
                        <span class="hidden md:inline text-sm font-semibold max-w-28 truncate">{{ auth()->user()->name }}</span>
                    </button>
                    <div class="dropdown" data-dropdown-menu>
                        <a href="{{ route('profile.show', auth()->user()) }}" class="dropdown-item"><x-icon name="user" class="w-4 h-4" /> ملفي الشخصي</a>
                        <a href="{{ route('bookmarks') }}" class="dropdown-item"><x-icon name="bookmark" class="w-4 h-4" /> محفوظاتي</a>
                        <a href="{{ route('profile.edit') }}" class="dropdown-item"><x-icon name="edit" class="w-4 h-4" /> تعديل الملف</a>
                        <hr class="my-1 border-[var(--border)]">
                        <form method="post" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item !text-[var(--danger)]"><x-icon name="logout" class="w-4 h-4" /> تسجيل الخروج</button>
                        </form>
                    </div>
                </div>
            @endauth

            @guest
                @unless (request()->routeIs('login'))
                    <a href="{{ route('login') }}" class="btn btn-ghost btn-sm">تسجيل الدخول</a>
                @endunless
                @unless (request()->routeIs('signup'))
                    <a href="{{ route('signup') }}" class="btn btn-primary btn-sm hidden sm:inline-flex">انضم لصدى</a>
                @endunless
            @endguest

        </div>
    </div>

    {{-- Below lg the links stay visible as a scrollable row, so visitors always see what Sada offers. --}}
    <div class="lg:hidden border-t border-[var(--border)]">
        <ul class="container-sada flex items-center gap-1 overflow-x-auto py-1.5">
            @foreach ($links as $link)
                <li class="shrink-0">
                    <a href="{{ route($link['route']) }}" class="nav-link !py-1.5 text-sm {{ request()->routeIs($link['active'] ?? $link['route']) ? 'is-active' : '' }}">{{ $link['label'] }}</a>
                </li>
            @endforeach
        </ul>
    </div>
</nav>
