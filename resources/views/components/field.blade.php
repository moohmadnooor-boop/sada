@props([
    'name',
    'label',
    'type' => 'text',
    'hint' => null,
    'value' => null,
    'max' => null,
    'min' => null,
    'rows' => 3,
    'useOld' => true,
])

@php
    $id = $attributes->get('id', 'field-'.$name);
    $current = $useOld ? old($name, $value) : $value;
    $hasError = $useOld && $errors->has($name);
@endphp

<div {{ $attributes->only('class')->merge(['class' => '']) }}>
    <label for="{{ $id }}" class="label">{{ $label }}</label>

    @if ($type === 'textarea')
        <textarea id="{{ $id }}" name="{{ $name }}" rows="{{ $rows }}"
            @if ($max) maxlength="{{ $max }}" data-counter-max="{{ $max }}" @endif
            @if ($min) minlength="{{ $min }}" data-counter-min="{{ $min }}" @endif
            {{ $attributes->except(['class', 'id'])->merge(['class' => 'input resize-none font-quote text-lg leading-relaxed'.($hasError ? ' has-error' : '')]) }}>{{ $current }}</textarea>
    @elseif ($type === 'select')
        <select id="{{ $id }}" name="{{ $name }}" {{ $attributes->except(['class', 'id'])->merge(['class' => 'input'.($hasError ? ' has-error' : '')]) }}>
            {{ $slot }}
        </select>
    @else
        <input id="{{ $id }}" type="{{ $type }}" name="{{ $name }}" value="{{ $type === 'password' ? '' : $current }}"
            @if ($max) maxlength="{{ $max }}" @endif
            @if ($min) minlength="{{ $min }}" @endif
            {{ $attributes->except(['class', 'id'])->merge(['class' => 'input'.($hasError ? ' has-error' : '')]) }}>
    @endif

    @if ($hint || ($type === 'textarea' && $max))
        <p class="hint">
            <span>{{ $hint }}</span>
            @if ($type === 'textarea' && $max)
                <span class="counter tabular-nums" data-counter-for="{{ $id }}">{{ mb_strlen((string) $current) }} / {{ $max }}</span>
            @endif
        </p>
    @endif

    @if ($hasError)
        <p class="error">{{ $errors->first($name) }}</p>
    @endif
</div>
