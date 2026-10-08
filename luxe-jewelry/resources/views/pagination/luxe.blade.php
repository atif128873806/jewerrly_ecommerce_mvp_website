@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination" class="flex items-center justify-center gap-2 text-xs tracking-wide">
        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <span class="w-10 h-10 flex items-center justify-center border border-[var(--line)] opacity-40 cursor-default">
                <svg width="14" height="10" fill="none" stroke="currentColor" stroke-width="1.3" viewBox="0 0 16 12"><path d="M16 6H2M5 1 1 6l4 5"/></svg>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Previous"
               class="w-10 h-10 flex items-center justify-center border border-[var(--line)] hover:border-[var(--color-gold)] hover:text-[var(--color-gold)] transition-colors">
                <svg width="14" height="10" fill="none" stroke="currentColor" stroke-width="1.3" viewBox="0 0 16 12"><path d="M16 6H2M5 1 1 6l4 5"/></svg>
            </a>
        @endif

        {{-- Numbers --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="w-10 h-10 flex items-center justify-center text-[var(--text-dim)]">…</span>
            @endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span aria-current="page" class="w-10 h-10 flex items-center justify-center border border-[var(--color-gold)] bg-[var(--color-gold)] text-[#0a0a0a] tabular-nums">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="w-10 h-10 flex items-center justify-center border border-[var(--line)] tabular-nums hover:border-[var(--color-gold)] hover:text-[var(--color-gold)] transition-colors">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Next"
               class="w-10 h-10 flex items-center justify-center border border-[var(--line)] hover:border-[var(--color-gold)] hover:text-[var(--color-gold)] transition-colors">
                <svg width="14" height="10" fill="none" stroke="currentColor" stroke-width="1.3" viewBox="0 0 16 12"><path d="M0 6h14M11 1l4 5-4 5"/></svg>
            </a>
        @else
            <span class="w-10 h-10 flex items-center justify-center border border-[var(--line)] opacity-40 cursor-default">
                <svg width="14" height="10" fill="none" stroke="currentColor" stroke-width="1.3" viewBox="0 0 16 12"><path d="M0 6h14M11 1l4 5-4 5"/></svg>
            </span>
        @endif
    </nav>
@endif
