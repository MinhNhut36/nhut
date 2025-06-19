@if ($paginator->hasPages())
    <ul class="pagination justify-content-center">
        {{-- Nút "Trang trước" --}}
        @if ($paginator->onFirstPage())
            <li class="page-item disabled" aria-disabled="true">
                <span class="page-link">&laquo;</span>
            </li>
        @else
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo;</a>
            </li>
        @endif

        {{-- Phân trang kiểu Google --}}
        @php
            $total = $paginator->lastPage();
            $current = $paginator->currentPage();
            $start = max(1, $current - 2);
            $end = min($total, $current + 2);
        @endphp

        {{-- Trang đầu --}}
        @if ($start > 1)
            <li class="page-item"><a class="page-link" href="{{ $paginator->url(1) }}">1</a></li>
            @if ($start > 2)
                <li class="page-item disabled"><span class="page-link">...</span></li>
            @endif
        @endif

        {{-- Các trang ở giữa --}}
        @for ($i = $start; $i <= $end; $i++)
            @if ($i == $current)
                <li class="page-item active" aria-current="page"><span class="page-link">{{ $i }}</span></li>
            @else
                <li class="page-item"><a class="page-link" href="{{ $paginator->url($i) }}">{{ $i }}</a></li>
            @endif
        @endfor

        {{-- Trang cuối --}}
        @if ($end < $total)
            @if ($end < $total - 1)
                <li class="page-item disabled"><span class="page-link">...</span></li>
            @endif
            <li class="page-item"><a class="page-link" href="{{ $paginator->url($total) }}">{{ $total }}</a></li>
        @endif

        {{-- Nút "Trang sau" --}}
        @if ($paginator->hasMorePages())
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">&raquo;</a>
            </li>
        @else
            <li class="page-item disabled" aria-disabled="true">
                <span class="page-link">&raquo;</span>
            </li>
        @endif
    </ul>
@endif
