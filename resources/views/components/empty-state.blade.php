@props(['title', 'text' => null])

<div class="card p-12 text-center flex flex-col items-center gap-4" data-reveal>
    <x-echo-art class="w-32 h-32" />
    <h3 class="font-display text-2xl font-bold">{{ $title }}</h3>
    @if ($text)
        <p class="text-muted max-w-md">{{ $text }}</p>
    @endif
    {{ $slot }}
</div>
