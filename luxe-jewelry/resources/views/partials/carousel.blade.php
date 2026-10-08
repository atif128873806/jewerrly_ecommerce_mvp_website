{{-- ============ PRODUCT CAROUSEL (reusable) ============ --}}
{{-- expects: $eyebrow, $title, $items (Collection<Product>), $id, optional $viewAll --}}
<section id="{{ $id }}" class="py-16 md:py-24" x-data="carousel()">
    <div class="shell">
        <div class="flex items-end justify-between mb-9 gap-6">
            <div class="reveal" data-reveal>
                <p class="eyebrow mb-3">{{ $eyebrow }}</p>
                <h2 class="font-display text-4xl md:text-5xl font-medium">{{ $title }}</h2>
            </div>
            <div class="flex items-center gap-3">
                @isset($viewAll)
                    <a href="{{ $viewAll }}" class="hidden md:inline-flex text-[0.65rem] tracking-[0.22em] uppercase link-underline mr-2">View all</a>
                @endisset
                <button @click="scrollPrev()" aria-label="Previous" class="w-11 h-11 border border-[var(--line)] rounded-full hidden sm:flex items-center justify-center hover:border-[var(--color-gold)] hover:text-[var(--color-gold)] transition-colors">
                    <svg width="16" height="12" fill="none" stroke="currentColor" stroke-width="1.3" viewBox="0 0 16 12"><path d="M16 6H2M5 1 1 6l4 5"/></svg>
                </button>
                <button @click="scrollNext()" aria-label="Next" class="w-11 h-11 border border-[var(--line)] rounded-full hidden sm:flex items-center justify-center hover:border-[var(--color-gold)] hover:text-[var(--color-gold)] transition-colors">
                    <svg width="16" height="12" fill="none" stroke="currentColor" stroke-width="1.3" viewBox="0 0 16 12"><path d="M0 6h14M11 1l4 5-4 5"/></svg>
                </button>
            </div>
        </div>
    </div>

    <div x-ref="track"
         class="flex gap-3 md:gap-5 overflow-x-auto no-scrollbar snap-x snap-mandatory px-5 md:px-10 scroll-px-5 md:scroll-px-10">
        @foreach ($items as $product)
            <x-product-card :product="$product" class="snap-start shrink-0 w-[64vw] sm:w-[42vw] md:w-[26vw] lg:w-[20rem] reveal" data-reveal />
        @endforeach
    </div>
</section>
