@if ($paginator->hasPages())
    <nav class="flex items-center justify-center gap-2" aria-label="التنقل بين الصفحات">
        @if ($paginator->onFirstPage())
            <span class="btn btn-ghost btn-sm opacity-40">السابق</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="btn btn-ghost btn-sm" rel="prev">السابق</a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="px-2 text-muted">{{ $element }}</span>
            @endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="btn btn-primary btn-sm !px-4" aria-current="page">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="btn btn-ghost btn-sm !px-4">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="btn btn-ghost btn-sm" rel="next">التالي</a>
        @else
            <span class="btn btn-ghost btn-sm opacity-40">التالي</span>
        @endif
    </nav>
@endif
