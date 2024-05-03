<nav aria-label="..." class="dpaginationNav">
    <ul class="pagination ppipelinepage d-flex justify-content-end">
        <!-- Previous Page Link -->
        @if ($module->onFirstPage())
            <li class="page-item disabled">
                <span class="page-link">Previous</span>
            </li>
        @else
            <li class="page-item">
                <a class="page-link"
                    href="{{ $module->previousPageUrl() }}&tab={{ request()->query('tab') }}"
                    rel="prev">Previous</a>
            </li>
        @endif

        <!-- Pagination Elements -->
        @php
            $currentPage = $module->currentPage();
            $lastPage = $module->lastPage();
            $startPage = max($currentPage - 1, 1);
            $endPage = min($currentPage + 1, $lastPage);
        @endphp

        {{-- @if ($startPage > 1)
            <li class="page-item disabled">
                <span class="page-link">...</span>
            </li>
        @endif --}}

        @for ($page = $startPage; $page <= $endPage; $page++)
            <li class="page-item {{ $module->currentPage() == $page ? 'active' : '' }}">
                <a class="page-link"
                    href="{{ $module->url($page) }}&tab={{ request()->query('tab') }}">{{ $page }}</a>
            </li>
        @endfor

        {{-- @if ($endPage < $lastPage)
            <li class="page-item disabled">
                <span class="page-link">...</span>
            </li>
        @endif --}}

        <!-- Next Page Link -->
        @if ($module->hasMorePages())
            <li class="page-item">
                <a class="page-link"
                    href="{{ $module->nextPageUrl() }}&tab={{ request()->query('tab') }}"
                    rel="next">Next</a>
            </li>
        @else
            <li class="page-item disabled">
                <span class="page-link">Next</span>
            </li>
        @endif
    </ul>
</nav>