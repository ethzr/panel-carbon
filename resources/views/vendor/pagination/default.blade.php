@if ($paginator->lastPage() > 1)
    <nav class="cds--pagination" aria-label="pagination">
        <div class="cds--pagination__left"></div>
        <div class="cds--pagination__right">
            <div class="ptero-pagination">
                @if (! $paginator->onFirstPage())
                    <a class="cds--btn cds--btn--ghost cds--btn--sm" href="{{ $paginator->previousPageUrl() }}" rel="prev">Previous</a>
                @endif
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span class="ptero-pagination__gap">{{ $element }}</span>
                    @endif
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="cds--btn cds--btn--primary cds--btn--sm" aria-current="page">{{ $page }}</span>
                            @else
                                <a class="cds--btn cds--btn--ghost cds--btn--sm" href="{{ $url }}">{{ $page }}</a>
                            @endif
                        @endforeach
                    @endif
                @endforeach
                @if ($paginator->hasMorePages())
                    <a class="cds--btn cds--btn--ghost cds--btn--sm" href="{{ $paginator->nextPageUrl() }}" rel="next">Next</a>
                @endif
            </div>
        </div>
    </nav>
@endif
