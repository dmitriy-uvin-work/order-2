@if ($paginator->hasPages())
    <div class="pagination-ui">
        @if ($paginator->onFirstPage())
            <span class="pagination-ui__link pagination-ui__prev pagination-ui__disabled" style="opacity: .4;"></span>
        @else
            <a class="pagination-ui__link pagination-ui__prev" href="{{ $paginator->previousPageUrl() }}" rel="prev"></a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <a class="disabled"><span>{{ $element }}</span></a>
            @endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="pagination-ui__link pagination-ui__link--active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="pagination-ui__link">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a class="pagination-ui__link pagination-ui__next" href="{{ $paginator->nextPageUrl() }}" rel="next"></a>
        @else
            <span class="pagination-ui__link pagination-ui__next pagination-ui__disabled" style="opacity: .4;"></span>
        @endif
    </div>
@endif
