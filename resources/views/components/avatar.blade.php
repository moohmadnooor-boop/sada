@props(['user', 'size' => 10])

@php
    $dimensions = ['8' => 'w-8 h-8 text-sm', '10' => 'w-10 h-10 text-base', '12' => 'w-12 h-12 text-lg', '24' => 'w-24 h-24 text-4xl'][(string) $size] ?? 'w-10 h-10';
@endphp

<span {{ $attributes->merge(['class' => "avatar {$dimensions}"]) }} style="--hue: {{ $user->avatarHue() }}" aria-hidden="true">{{ $user->initial() }}</span>
