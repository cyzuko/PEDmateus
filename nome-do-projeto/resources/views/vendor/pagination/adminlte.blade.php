@if ($paginator->hasPages())
    <ul class="pagination pagination-sm justify-content-center">
        {{-- Página Anterior --}}
        @if ($paginator->onFirstPage())
            <li class="page-item disabled">
                <span class="page-link">&laquo; Anterior</span>
            </li>
        @else
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo; Anterior</a>
            </li>
        @endif

        {{-- Paginação das Páginas --}}
        @foreach ($elements as $element)
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    <li class="page-item {{ $page == $paginator->currentPage() ? 'active' : '' }}">
                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                    </li>
                @endforeach
            @endif
        @endforeach

        {{-- Próxima Página --}}
        @if ($paginator->hasMorePages())
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">Próxima &raquo;</a>
            </li>
        @else
            <li class="page-item disabled">
                <span class="page-link">Próxima &raquo;</span>
            </li>
        @endif
    </ul>
@endif
