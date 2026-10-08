@php($press = ['VOGUE', 'VANITY FAIR', 'ELLE', "HARPER'S BAZAAR", 'GRAZIA', 'MARIE CLAIRE', 'COSMOPOLITAN'])
{{-- ============ AS SEEN ON ============ --}}
<section class="py-10 border-b border-[var(--line)] bg-[var(--bg-soft)] overflow-hidden">
    <p class="text-center eyebrow mb-7">As seen on</p>
    <div class="relative">
        {{-- edge fades --}}
        <div class="pointer-events-none absolute inset-y-0 left-0 w-24 z-10 bg-gradient-to-r from-[var(--bg-soft)] to-transparent"></div>
        <div class="pointer-events-none absolute inset-y-0 right-0 w-24 z-10 bg-gradient-to-l from-[var(--bg-soft)] to-transparent"></div>
        <div class="flex whitespace-nowrap animate-marquee will-change-transform">
            @foreach (array_merge($press, $press, $press) as $logo)
                <span class="mx-10 font-display text-2xl md:text-3xl tracking-wide text-[var(--text-dim)]">{{ $logo }}</span>
            @endforeach
        </div>
    </div>
</section>
