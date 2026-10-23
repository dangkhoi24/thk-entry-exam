{{--
    Page links for a LengthAwarePaginator (keeps the search query)
    <x-pagination :paginator="$hotelList" />
--}}
@props([
    'paginator',
])

@if ($paginator->hasPages())
    @php
        // current page ±2, plus first and last
        $current = $paginator->currentPage();
        $last = $paginator->lastPage();
        $pages = collect([1, $last])
            ->merge(range(max(1, $current - 2), min($last, $current + 2)))
            ->unique()
            ->sort()
            ->values();
    @endphp

    <nav {{ $attributes->class('pagination') }} aria-label="Pagination">
        <p class="pagination__summary label-1 text-secondary">
            {{ __('admin.pagination.summary', ['from' => $paginator->firstItem(), 'to' => $paginator->lastItem(), 'total' => $paginator->total()]) }}
        </p>

        <div class="pagination__pages">
            @if ($paginator->onFirstPage())
                <span class="pagination__item is-disabled" aria-hidden="true">&lsaquo;</span>
            @else
                <a class="pagination__item" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="{{ __('admin.pagination.previous') }}">&lsaquo;</a>
            @endif

            @foreach ($pages as $page)
                @if (!$loop->first && $page - $pages[$loop->index - 1] > 1)
                    <span class="pagination__gap">&hellip;</span>
                @endif

                @if ($page === $current)
                    <span class="pagination__item is-current" aria-current="page">{{ $page }}</span>
                @else
                    <a class="pagination__item" href="{{ $paginator->url($page) }}">{{ $page }}</a>
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a class="pagination__item" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="{{ __('admin.pagination.next') }}">&rsaquo;</a>
            @else
                <span class="pagination__item is-disabled" aria-hidden="true">&rsaquo;</span>
            @endif
        </div>
    </nav>
@endif
