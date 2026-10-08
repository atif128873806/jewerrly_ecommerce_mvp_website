{{-- ============ CATEGORY GRID ============ --}}
<section class="py-16 md:py-24">
    <div class="shell">
        <div class="text-center mb-12 reveal" data-reveal>
            <p class="eyebrow mb-3">Find your piece</p>
            <h2 class="font-display text-4xl md:text-5xl font-medium">Shop by Category</h2>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-5">
            @foreach ($categories as $cat)
                <a href="{{ route('shop.category', $cat) }}" class="group relative block aspect-[3/4] overflow-hidden bg-[var(--bg-soft)] reveal" data-reveal>
                    <img
                        src="{{ asset($cat->image) }}"
                        alt="{{ $cat->name }}"
                        loading="lazy"
                        class="absolute inset-0 w-full h-full object-cover transition-transform duration-[900ms] ease-out group-hover:scale-110"
                    >
                    <span class="absolute inset-0 bg-gradient-to-t from-black/65 via-transparent to-transparent"></span>
                    <div class="absolute inset-x-0 bottom-0 p-5 md:p-6 text-white">
                        <h3 class="font-display text-2xl md:text-3xl mb-2">{{ $cat->name }}</h3>
                        <span class="inline-flex items-center gap-2 text-[0.65rem] tracking-[0.22em] uppercase border-b border-white/40 pb-1 transition-colors group-hover:border-[var(--color-gold)] group-hover:text-[var(--color-gold-soft)]">
                            Shop
                            <svg width="14" height="8" fill="none" stroke="currentColor" stroke-width="1.3" viewBox="0 0 14 8" class="transition-transform group-hover:translate-x-1"><path d="M0 4h12M9 1l3 3-3 3"/></svg>
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
