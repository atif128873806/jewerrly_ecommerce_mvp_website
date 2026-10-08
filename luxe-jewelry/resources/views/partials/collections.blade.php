{{-- ============ COLLECTION SHOWCASES ============ --}}
<section class="py-4">
    @foreach ($collections as $col)
        <div id="collection-{{ $col->slug }}" class="py-12 md:py-16">
            <div class="shell">
                <div class="grid lg:grid-cols-12 gap-8 lg:gap-12 items-center {{ $loop->odd ? '' : 'lg:[direction:rtl]' }}">

                    {{-- Feature image --}}
                    <div class="lg:col-span-6 [direction:ltr] reveal" data-reveal>
                        <a href="{{ route('collections.show', $col) }}" class="relative block aspect-[16/11] overflow-hidden group">
                            <img src="{{ asset($col->feature_image) }}" alt="{{ $col->name }}" loading="lazy" class="absolute inset-0 w-full h-full object-cover" data-parallax>
                            <div class="absolute bottom-0 left-0 p-6 md:p-8 bg-gradient-to-t from-black/60 to-transparent w-full">
                                <p class="eyebrow !text-white/70 mb-2">Iconic Collection</p>
                                <h3 class="font-display text-4xl md:text-6xl text-white">{{ $col->name }}</h3>
                            </div>
                        </a>
                    </div>

                    {{-- Items --}}
                    <div class="lg:col-span-6 [direction:ltr] reveal" data-reveal>
                        <p class="font-display italic text-2xl md:text-3xl text-[var(--text-dim)] mb-7">{{ $col->tagline }}</p>
                        <div class="grid grid-cols-2 gap-3 md:gap-4 mb-8">
                            @foreach ($col->products as $product)
                                <x-product-card :product="$product" />
                            @endforeach
                        </div>
                        <a href="{{ route('collections.show', $col) }}" class="btn btn-gold">Discover {{ $col->name }}</a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</section>
