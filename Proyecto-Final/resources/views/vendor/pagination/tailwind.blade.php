@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="custom-pagination">
        <div class="custom-pagination-search">
            <div class="pagination-controls">
                {{-- Botón Anterior --}}
                @if ($paginator->onFirstPage())
                    <span><i class="bi bi-caret-left-square-fill"></i> Anterior</span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev"><i class="bi bi-caret-left-square-fill"></i> Anterior</a>
                @endif

                {{-- Botón Siguiente --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next">Siguiente <i class="bi bi-caret-right-square-fill"></i></a>
                @else
                    <span>Siguiente <i class="bi bi-caret-right-square-fill"></i></span>
                @endif
            </div>

            {{-- Lista de páginas --}}
            <ul class="pagination">
                {{-- Aquí van los números --}}
            </ul>
        </div>

        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-700 leading-5 dark:text-gray-400">
                    {!! __('Showing') !!}
                    @if ($paginator->firstItem())
                        <span class="font-medium">{{ $paginator->firstItem() }}</span>
                        {!! __('to') !!}
                        <span class="font-medium">{{ $paginator->lastItem() }}</span>
                    @else
                        {{ $paginator->count() }}
                    @endif
                    {!! __('of') !!}
                    <span class="font-medium">{{ $paginator->total() }}</span>
                    {!! __('results') !!}
                </p>
            </div>

            <div>
                <span class="custom-pagination-search relative z-0 inline-flex shadow-sm rounded-md">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <div class="disabled"><span><i class="bi bi-caret-left-square-fill"></i> Anterior</span></div>
                    @else
                        <div><a href="{{ $paginator->previousPageUrl() }}" class="custom-pagination__link" rel="prev"><i class="bi bi-caret-left-square-fill"></i> Anterior</a></div>
                    @endif

                    {{-- Pagination Elements --}}
                    <ul class="pagination flex">
                        @foreach ($elements as $element)
                            {{-- "Three Dots" Separator --}}
                            @if (is_string($element))
                                <li class="disabled"><span>{{ $element }}</span></li>
                            @endif

                            {{-- Array Of Links --}}
                            @if (is_array($element))
                                @foreach ($element as $page => $url)
                                    @if ($page == $paginator->currentPage())
                                        <li><span class="custom-pagination__current" aria-current="page">{{ $page }}</span></li>
                                    @else
                                        <li><a href="{{ $url }}" class="custom-pagination__link" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">{{ $page }}</a></li>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    </ul>

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <div><a href="{{ $paginator->nextPageUrl() }}" class="custom-pagination__link" rel="next">Siguiente <i class="bi bi-caret-right-square-fill"></i></a></div>
                    @else
                        <div class="disabled"><span>Siguiente <i class="bi bi-caret-right-square-fill"></i></span></div>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif
