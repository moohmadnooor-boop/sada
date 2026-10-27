@props(['name'])

@php
    $paths = [
        'heart' => '<path d="M12 21s-7.5-4.6-9.6-9.3C.9 8.3 3 4.5 6.8 4.5c2.1 0 3.6 1.2 4.4 2.5h1.6c.8-1.3 2.3-2.5 4.4-2.5 3.8 0 5.9 3.8 4.4 7.2C19.5 16.4 12 21 12 21z"/>',
        'bookmark' => '<path d="M6 3h12a1 1 0 0 1 1 1v17l-7-4.5L5 21V4a1 1 0 0 1 1-1z"/>',
        'comment' => '<path d="M21 12a8.5 8.5 0 0 1-12.4 7.6L3 21l1.4-5.1A8.5 8.5 0 1 1 21 12z"/>',
        'copy' => '<rect x="9" y="9" width="12" height="12" rx="2"/><path d="M5 15H4a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v1"/>',
        'share' => '<circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><path d="m8.6 13.5 6.8 4M15.4 6.5l-6.8 4"/>',
        'image' => '<rect x="3" y="3" width="18" height="18" rx="3"/><circle cx="9" cy="9" r="2"/><path d="m21 15-5-5L5 21"/>',
        'edit' => '<path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/>',
        'trash' => '<path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6"/>',
        'search' => '<circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/>',
        'sun' => '<circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/>',
        'moon' => '<path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8z"/>',
        'menu' => '<path d="M4 6h16M4 12h16M4 18h16"/>',
        'close' => '<path d="M18 6 6 18M6 6l12 12"/>',
        'user' => '<circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/>',
        'logout' => '<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/>',
        'fire' => '<path d="M12 22c4 0 7-2.8 7-7 0-4-3-6.5-4-10-2 1.5-3 3.5-3 6-1.5-1-2.5-2.5-2.5-4.5C6.5 9 5 12 5 15c0 4.2 3 7 7 7z"/>',
        'users' => '<circle cx="9" cy="8" r="4"/><path d="M1 21a8 8 0 0 1 16 0M17 4a4 4 0 0 1 0 8M23 21a7 7 0 0 0-5-6.7"/>',
        'x' => '<path d="M4 4l16 16M20 4 4 20"/>',
        'whatsapp' => '<path d="M3 21l1.6-4.7A8.5 8.5 0 1 1 8 19.6z"/><path d="M9 9.5c.3 2.2 2.3 4.3 4.5 4.6l1.3-1.3 2 1-.4 1.6c-3.7.4-7.3-3.2-6.9-6.9l1.6-.4 1 2z"/>',
        'download' => '<path d="M12 3v12M7 10l5 5 5-5M5 21h14"/>',
        'arrow' => '<path d="M19 12H5M12 19l-7-7 7-7"/>',
        'sparkle' => '<path d="M12 3l1.8 5.4L19 10l-5.2 1.6L12 17l-1.8-5.4L5 10l5.2-1.6z"/>',
    ];
@endphp

<svg {{ $attributes->merge(['class' => 'w-5 h-5']) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">{!! $paths[$name] ?? '' !!}</svg>
