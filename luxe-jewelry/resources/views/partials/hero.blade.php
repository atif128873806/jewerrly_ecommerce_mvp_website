{{-- ============ HERO ============ --}}
{{-- Portrait image: mobile shows the full composition; on wide screens we go taller
     and shift the framing down so the necklace AND the ringed hand stay in view. --}}
<section class="relative h-[82vh] min-h-[520px] lg:h-[92vh] w-full overflow-hidden">
    <img src="{{ asset('img/products/p001.jpg') }}" alt="Signature jewelry"
         class="absolute inset-0 w-full h-full object-cover [object-position:50%_28%] lg:[object-position:50%_48%]" data-hero-img fetchpriority="high">
    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-black/40"></div>

    <div class="relative z-10 h-full shell flex flex-col items-center justify-center text-center text-white">
        <p class="eyebrow mb-5 reveal" data-hero="1">Iconic jewelry made in Mallorca</p>
        <h1 class="font-display text-[3.4rem] leading-[0.95] sm:text-7xl md:text-8xl font-medium tracking-[0.01em] max-w-4xl reveal" data-hero="2">
            Unmistakably You
        </h1>
        <div class="mt-9 reveal" data-hero="3">
            <a href="{{ route('shop.index') }}" class="btn btn-gold !text-white !border-white/70 hover:!bg-white hover:!text-black">Shop Now</a>
        </div>
    </div>

    <div class="absolute bottom-6 left-1/2 -translate-x-1/2 z-10 text-white/70 reveal" data-hero="4">
        <svg width="20" height="32" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 20 32"><rect x="1" y="1" width="18" height="30" rx="9"/><circle cx="10" cy="9" r="2" fill="currentColor" class="animate-bounce"/></svg>
    </div>
</section>
