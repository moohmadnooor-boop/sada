<svg {{ $attributes }} viewBox="0 0 400 400" fill="none" aria-hidden="true">
    <defs>
        <radialGradient id="echo-glow" cx="50%" cy="50%" r="50%">
            <stop offset="0%" stop-color="var(--accent)" stop-opacity="0.35" />
            <stop offset="100%" stop-color="var(--accent)" stop-opacity="0" />
        </radialGradient>
    </defs>
    <circle cx="200" cy="200" r="190" fill="url(#echo-glow)" />
    @foreach ([40, 80, 120, 160, 195] as $i => $radius)
        <circle cx="200" cy="200" r="{{ $radius }}" stroke="var(--accent)" stroke-opacity="{{ 0.7 - $i * 0.12 }}" stroke-width="1.5">
            <animate attributeName="r" values="{{ $radius }};{{ $radius + 18 }};{{ $radius }}" dur="{{ 4 + $i }}s" repeatCount="indefinite" />
        </circle>
    @endforeach
    <circle cx="200" cy="200" r="22" fill="var(--accent)" />
    <text x="200" y="212" text-anchor="middle" font-family="Amiri, serif" font-size="34" fill="var(--bg)">”</text>
</svg>
