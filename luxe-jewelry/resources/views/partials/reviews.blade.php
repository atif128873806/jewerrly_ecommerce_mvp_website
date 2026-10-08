{{-- ============ TESTIMONIALS ============ --}}
<section class="py-20 md:py-28 bg-[var(--bg-soft)] border-y border-[var(--line)]" x-data="{ i: 0, n: {{ $reviews->count() }} }">
    <div class="shell max-w-4xl text-center">
        <div class="flex justify-center gap-1 mb-6 text-[var(--color-gold)]">
            @for ($s = 0; $s < 5; $s++)
                <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor"><path d="m10 1 2.6 5.3 5.9.9-4.2 4.1 1 5.8L10 14.8 4.7 17.6l1-5.8L1.5 7.7l5.9-.9L10 1Z"/></svg>
            @endfor
        </div>
        <p class="eyebrow mb-8">Let customers speak for us</p>

        <div class="relative min-h-[180px]">
            @foreach ($reviews as $idx => $rev)
                <figure
                    x-show="i === {{ $idx }}"
                    x-transition:enter="transition ease-out duration-500"
                    x-transition:enter-start="opacity-0 translate-y-3"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    @class(['absolute inset-0' => $idx !== 0])
                    style="{{ $idx !== 0 ? 'display:none' : '' }}"
                >
                    <blockquote class="font-display text-2xl md:text-4xl italic leading-snug mb-7 max-w-3xl mx-auto">
                        “{{ $rev->body }}”
                    </blockquote>
                    <figcaption class="text-sm">
                        <span class="font-medium tracking-wide">{{ $rev->author_name }}</span>
                        <span class="text-[var(--text-dim)]"> · {{ $rev->product?->name }} · Verified buyer</span>
                    </figcaption>
                </figure>
            @endforeach
        </div>

        <div class="flex items-center justify-center gap-3 mt-10">
            <button @click="i = (i - 1 + n) % n" aria-label="Previous review" class="w-10 h-10 border border-[var(--line)] rounded-full flex items-center justify-center hover:border-[var(--color-gold)] hover:text-[var(--color-gold)] transition-colors">
                <svg width="15" height="11" fill="none" stroke="currentColor" stroke-width="1.3" viewBox="0 0 16 12"><path d="M16 6H2M5 1 1 6l4 5"/></svg>
            </button>
            <template x-for="d in n" :key="d">
                <button @click="i = d - 1" :class="i === d - 1 ? 'bg-[var(--color-gold)] w-5' : 'bg-[var(--line)] w-2'" class="h-2 rounded-full transition-all"></button>
            </template>
            <button @click="i = (i + 1) % n" aria-label="Next review" class="w-10 h-10 border border-[var(--line)] rounded-full flex items-center justify-center hover:border-[var(--color-gold)] hover:text-[var(--color-gold)] transition-colors">
                <svg width="15" height="11" fill="none" stroke="currentColor" stroke-width="1.3" viewBox="0 0 16 12"><path d="M0 6h14M11 1l4 5-4 5"/></svg>
            </button>
        </div>
    </div>
</section>
