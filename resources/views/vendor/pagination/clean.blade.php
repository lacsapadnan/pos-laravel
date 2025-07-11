{{-- resources/views/vendor/pagination/clean.blade.php --}}
@if ($paginator->hasPages())
<nav class="custom-pagination" aria-label="Pagination Navigation">
    <ul class="mb-2 pagination">
        {{-- Previous Page Link --}}
        <li class="page-item{{ $paginator->onFirstPage() ? ' disabled' : '' }}">
            <a class="page-link" href="{{ $paginator->previousPageUrl() ?: '#' }}" rel="prev" aria-label="Previous"
                tabindex="{{ $paginator->onFirstPage() ? '-1' : '0' }}">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
        {{-- "Three Dots" Separator --}}
        @if (is_string($element))
        <li class="page-item disabled"><span class="page-link">&hellip;</span></li>
        @endif

        {{-- Array Of Links --}}
        @if (is_array($element))
        @foreach ($element as $page => $url)
        <li class="page-item{{ $page == $paginator->currentPage() ? ' active' : '' }}">
            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
        </li>
        @endforeach
        @endif
        @endforeach

        {{-- Next Page Link --}}
        <li class="page-item{{ !$paginator->hasMorePages() ? ' disabled' : '' }}">
            <a class="page-link" href="{{ $paginator->nextPageUrl() ?: '#' }}" rel="next" aria-label="Next"
                tabindex="{{ !$paginator->hasMorePages() ? '-1' : '0' }}">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
    </ul>
    <div class="pagination-info">
        Showing <b>{{ $paginator->firstItem() }}</b> to <b>{{ $paginator->lastItem() }}</b> of <b>{{ $paginator->total()
            }}</b> products
    </div>
</nav>
@endif
