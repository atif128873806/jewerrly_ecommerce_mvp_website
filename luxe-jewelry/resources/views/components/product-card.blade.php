@props(['product'])

<article {{ $attributes->merge(['class' => 'group']) }}>
    <div class="relative aspect-[4/5] overflow-hidden bg-[var(--bg-soft)] mb-4">
        <a href="{{ route('products.show', $product) }}" class="block w-full h-full">
            <img src="{{ asset($product->thumbnail) }}" alt="{{ $product->name }}" loading="lazy"
                 class="absolute inset-0 w-full h-full object-cover transition-transform duration-[900ms] ease-out group-hover:scale-105">
        </a>

        @if ($product->is_on_sale)
            <span class="absolute top-3 left-3 bg-[var(--color-gold)] text-[#0a0a0a] text-[0.55rem] tracking-[0.18em] uppercase font-semibold px-2.5 py-1">Sale</span>
        @elseif ($product->is_new)
            <span class="absolute top-3 left-3 bg-[var(--text)] text-[var(--bg)] text-[0.55rem] tracking-[0.18em] uppercase font-semibold px-2.5 py-1">New</span>
        @endif

        {{-- Quick add to bag --}}
        <form action="{{ route('cart.add', $product) }}" method="POST"
              class="absolute inset-x-3 bottom-3 translate-y-3 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300">
            @csrf
            <button type="submit"
                    class="w-full text-center bg-[var(--bg)]/95 text-[var(--text)] text-[0.62rem] tracking-[0.22em] uppercase py-3 border border-[var(--line)] hover:bg-[var(--color-gold)] hover:text-[#0a0a0a] hover:border-[var(--color-gold)] transition-colors">
                Add to bag
            </button>
        </form>
    </div>

    @if ($product->collection)
        <p class="text-[0.6rem] tracking-[0.2em] uppercase text-[var(--color-gold)] mb-1.5">{{ $product->collection->name }}</p>
    @endif
    <h3 class="text-sm md:text-base mb-1.5 leading-snug">
        <a href="{{ route('products.show', $product) }}" class="link-underline">{{ $product->name }}</a>
    </h3>
    <p class="text-sm">
        <span class="tabular-nums">{{ money($product->price) }}</span>
        @if ($product->is_on_sale)
            <span class="text-[var(--text-dim)] line-through ml-2 text-xs tabular-nums">{{ money($product->compare_price) }}</span>
        @endif
    </p>
</article>
