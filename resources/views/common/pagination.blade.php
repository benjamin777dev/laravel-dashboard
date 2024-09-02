<nav aria-label="..." class="dpaginationNav">
    <ul class="pagination ppipelinepage d-flex justify-content-end">
        @if ($module->hasPages())
            {{ $module->links() }}
        @endif

    </ul>
</nav>
