@props(['size' => 'md'])

<a href="{{ route('home') }}" {{ $attributes->merge(['class' => 'flex items-center gap-2.5 shrink-0']) }} aria-label="صدى — الصفحة الرئيسية">
    <svg class="logo-rings {{ $size === 'lg' ? 'w-12 h-12' : 'w-9 h-9' }}" viewBox="0 0 40 40" fill="none" aria-hidden="true">
        <circle cx="20" cy="20" r="18" stroke="var(--accent)" stroke-width="1.5" />
        <circle cx="20" cy="20" r="18" stroke="var(--accent)" stroke-width="1.5" />
        <circle cx="20" cy="20" r="18" stroke="var(--accent)" stroke-width="1.5" />
        <circle cx="20" cy="20" r="6" fill="var(--accent)" />
    </svg>
    <span class="font-display font-bold {{ $size === 'lg' ? 'text-4xl' : 'text-2xl' }}">صدى</span>
</a>
