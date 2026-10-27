@extends('Layout.app')

@section('title', $user->name)

@section('content')
    <div class="container-sada pt-12">
        <section class="card p-8 sm:p-10 overflow-hidden animate-fade-up">
            <x-echo-art class="absolute -top-20 -left-20 w-72 h-72 opacity-30 pointer-events-none" />
            <div class="relative flex flex-col sm:flex-row items-center sm:items-start gap-6 text-center sm:text-start">
                <x-avatar :user="$user" size="24" />
                <div class="flex-1 min-w-0">
                    <h1 class="font-display text-3xl sm:text-4xl font-bold">{{ $user->name }}</h1>
                    <p class="text-muted mt-1 text-sm">عضو منذ {{ $user->created_at->translatedFormat('F Y') }}</p>
                    @if ($user->bio)
                        <p class="font-quote text-xl mt-3 leading-relaxed">{{ $user->bio }}</p>
                    @endif

                    <dl class="mt-6 grid grid-cols-4 gap-3 max-w-lg mx-auto sm:mx-0">
                        @foreach ([[$user->quotes_count, 'اقتباس'], [$likesReceived, 'إعجاب'], [$user->followers_count, 'متابِع'], [$user->following_count, 'يتابع']] as [$value, $label])
                            <div>
                                <dt class="sr-only">{{ $label }}</dt>
                                <dd class="font-display text-2xl font-bold text-accent tabular-nums" @if ($label === 'متابِع') data-follower-count @endif>{{ $value }}</dd>
                                <p class="text-xs text-muted">{{ $label }}</p>
                            </div>
                        @endforeach
                    </dl>
                </div>

                <div class="shrink-0">
                    @auth
                        @if (auth()->user()->is($user))
                            <a href="{{ route('profile.edit') }}" class="btn btn-ghost"><x-icon name="edit" class="w-4 h-4" /> تعديل الملف</a>
                        @else
                            <button type="button" class="btn {{ $isFollowing ? 'btn-ghost is-active' : 'btn-primary' }}" data-follow data-url="{{ route('users.follow', $user) }}" aria-pressed="{{ $isFollowing ? 'true' : 'false' }}">
                                <x-icon name="users" class="w-4 h-4" />
                                <span data-follow-label>{{ $isFollowing ? 'تتابعه' : 'متابعة' }}</span>
                            </button>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary"><x-icon name="users" class="w-4 h-4" /> متابعة</a>
                    @endauth
                </div>
            </div>
        </section>

        <h2 class="font-display text-2xl font-bold mt-12 mb-6" data-reveal>اقتباسات {{ $user->name }}</h2>

        @if ($quotes->isEmpty())
            <x-empty-state title="لم يشارك أي اقتباس بعد" text="عندما ينشر {{ $user->name }} اقتباساً سيظهر هنا." />
        @else
            <x-quote-grid :quotes="$quotes" />
        @endif
    </div>
@endsection
