@props(['quotes'])

<div {{ $attributes->merge(['class' => 'grid gap-5 sm:grid-cols-2 lg:grid-cols-3 items-start']) }}>
    @foreach ($quotes as $quote)
        <x-quote-card :quote="$quote" :index="$loop->index" />
    @endforeach
</div>

@if (method_exists($quotes, 'links') && $quotes->hasPages())
    <div class="mt-10">
        {{ $quotes->links('components.pagination') }}
    </div>
@endif
