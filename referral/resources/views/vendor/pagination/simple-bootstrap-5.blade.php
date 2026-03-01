@if ($paginator->hasPages())
<nav class="pagination">
    {{-- Previous Page Link --}}
    @if ($paginator->onFirstPage())
        <span class="disabled"><span>&laquo;</span></span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}">&laquo;</a>
    @endif

    {{-- Next Page Link --}}
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}">&raquo;</a>
    @else
        <span class="disabled"><span>&raquo;</span></span>
    @endif
</nav>
@endif
