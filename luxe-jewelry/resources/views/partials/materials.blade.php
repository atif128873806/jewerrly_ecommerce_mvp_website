{{-- ============ GOLD / SILVER MATERIAL BLOCKS ============ --}}
<section class="grid md:grid-cols-2">
    @foreach ($materials as $mat)
        <a href="{{ route('shop.index', ['material' => $mat['material']]) }}" class="group relative h-[60vh] min-h-[420px] overflow-hidden reveal block" data-reveal>
            <img src="{{ asset($mat['img']) }}" alt="{{ $mat['name'] }}" loading="lazy" class="absolute inset-0 w-full h-full object-cover transition-transform duration-[1100ms] ease-out group-hover:scale-105">
            <div class="absolute inset-0 bg-black/40 group-hover:bg-black/30 transition-colors"></div>
            <div class="relative z-10 h-full flex flex-col items-center justify-center text-center text-white">
                <p class="eyebrow !text-white/70 mb-3">{{ $mat['sub'] }}</p>
                <h3 class="font-display text-5xl md:text-7xl mb-7">{{ $mat['name'] }}</h3>
                <span class="btn !text-white !border-white/70 group-hover:!bg-white group-hover:!text-black">Shop {{ $mat['name'] }}</span>
            </div>
        </a>
    @endforeach
</section>
