@props(['eyebrow' => null, 'title', 'text' => null])

<header {{ $attributes->merge(['class' => 'pt-14 pb-8']) }}>
    @if ($eyebrow)
        <p class="section-eyebrow animate-fade-up">{{ $eyebrow }}</p>
    @endif
    <h1 class="font-display text-4xl sm:text-5xl font-bold mt-3 animate-fade-up" style="--i: 1">{{ $title }}</h1>
    @if ($text)
        <p class="text-muted text-lg mt-3 max-w-2xl animate-fade-up" style="--i: 2">{{ $text }}</p>
    @endif
    {{ $slot }}
</header>
