@extends('layouts.app')

@section('title', $product->name . ' — ' . $brandName)

@section('content')
    <div class="shell pt-8 md:pt-12 pb-20">
        {{-- Breadcrumb --}}
        <nav class="text-[0.65rem] tracking-[0.2em] uppercase text-[var(--text-dim)] mb-8">
            <a href="{{ route('home') }}" class="link-underline">Home</a>
            <span class="mx-2 opacity-50">/</span>
            <a href="{{ route('shop.category', $product->category) }}" class="link-underline">{{ $product->category->name }}</a>
            <span class="mx-2 opacity-50">/</span>
            <span class="text-[var(--text)]">{{ $product->name }}</span>
        </nav>

        <div class="grid lg:grid-cols-2 gap-8 lg:gap-16" x-data="{ active: '{{ asset($product->images->first()?->path) }}', qty: 1 }">
            {{-- Gallery --}}
            <div class="flex flex-col-reverse md:flex-row gap-3 md:gap-4">
                <div class="flex md:flex-col gap-3 md:w-20 shrink-0">
                    @foreach ($product->images as $img)
                        <button @click="active = '{{ asset($img->path) }}'"
                                class="relative w-16 md:w-full aspect-square overflow-hidden border transition-colors shrink-0"
                                :class="active === '{{ asset($img->path) }}' ? 'border-[var(--color-gold)]' : 'border-[var(--line)]'">
                            <img src="{{ asset($img->path) }}" alt="{{ $img->alt }}" class="absolute inset-0 w-full h-full object-cover">
                        </button>
                    @endforeach
                </div>
                <div class="relative flex-1 aspect-[4/5] overflow-hidden bg-[var(--bg-soft)]">
                    <img :src="active" alt="{{ $product->name }}" class="absolute inset-0 w-full h-full object-cover">
                    @if ($product->is_on_sale)
                        <span class="absolute top-4 left-4 bg-[var(--color-gold)] text-[#0a0a0a] text-[0.6rem] tracking-[0.18em] uppercase font-semibold px-3 py-1.5">Sale</span>
                    @endif
                </div>
            </div>

            {{-- Info --}}
            <div class="lg:py-4">
                @if ($product->collection)
                    <a href="{{ route('collections.show', $product->collection) }}" class="eyebrow link-underline">{{ $product->collection->name }} Collection</a>
                @endif
                <h1 class="font-display text-4xl md:text-5xl font-medium mt-3 mb-4">{{ $product->name }}</h1>

                {{-- Rating --}}
                <div class="flex items-center gap-3 mb-6">
                    <div class="flex gap-0.5 text-[var(--color-gold)]">
                        @for ($s = 1; $s <= 5; $s++)
                            <svg width="15" height="15" viewBox="0 0 20 20" fill="{{ $s <= round($product->rating) ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="1"><path d="m10 1 2.6 5.3 5.9.9-4.2 4.1 1 5.8L10 14.8 4.7 17.6l1-5.8L1.5 7.7l5.9-.9L10 1Z"/></svg>
                        @endfor
                    </div>
                    <span class="text-xs text-[var(--text-dim)] tabular-nums">{{ number_format($product->rating, 1) }} · {{ $product->reviews_count }} reviews</span>
                </div>

                {{-- Price --}}
                <div class="flex items-baseline gap-3 mb-7">
                    <span class="text-2xl tabular-nums">{{ money($product->price) }}</span>
                    @if ($product->is_on_sale)
                        <span class="text-[var(--text-dim)] line-through tabular-nums">{{ money($product->compare_price) }}</span>
                        <span class="text-[var(--color-gold)] text-xs tracking-[0.15em] uppercase">Save {{ money($product->compare_price - $product->price) }}</span>
                    @endif
                </div>

                <p class="text-[var(--text-dim)] leading-relaxed mb-8">{{ $product->description }}</p>

                {{-- Meta --}}
                <dl class="grid grid-cols-2 gap-y-2 text-sm mb-8 max-w-sm">
                    <dt class="text-[var(--text-dim)]">Material</dt>
                    <dd>{{ $product->material->label() }}</dd>
                    @if ($product->symbol)
                        <dt class="text-[var(--text-dim)]">Meaning</dt>
                        <dd>{{ $product->symbol }}</dd>
                    @endif
                    <dt class="text-[var(--text-dim)]">Reference</dt>
                    <dd class="tabular-nums">{{ $product->sku }}</dd>
                    <dt class="text-[var(--text-dim)]">Availability</dt>
                    <dd class="{{ $product->in_stock ? 'text-[var(--color-gold)]' : 'text-[var(--text-dim)]' }}">{{ $product->in_stock ? 'In stock' : 'Sold out' }}</dd>
                </dl>

                {{-- Add to bag --}}
                <form action="{{ route('cart.add', $product) }}" method="POST" class="flex flex-col sm:flex-row gap-3 mb-8">
                    @csrf
                    <div class="flex items-center border border-[var(--line)]">
                        <button type="button" @click="qty = Math.max(1, qty - 1)" class="w-12 h-12 flex items-center justify-center hover:text-[var(--color-gold)] transition-colors" aria-label="Decrease">−</button>
                        <input type="text" name="qty" x-model="qty" readonly class="w-10 text-center bg-transparent tabular-nums focus:outline-none" aria-label="Quantity">
                        <button type="button" @click="qty = Math.min(25, qty + 1)" class="w-12 h-12 flex items-center justify-center hover:text-[var(--color-gold)] transition-colors" aria-label="Increase">+</button>
                    </div>
                    <button type="submit" class="btn btn-solid flex-1" @disabled(!$product->in_stock)>Add to bag — {{ money($product->price) }}</button>
                </form>

                {{-- Accordions --}}
                <div class="border-t border-[var(--line)]" x-data="{ open: 'shipping' }">
                    @foreach (['shipping' => ['Shipping & Returns', 'Free shipping on orders over ' . money(config('store.free_shipping_threshold')) . '. Express delivery in 3–5 working days. 30-day easy returns on all unworn pieces.'], 'care' => ['Jewelry Care', 'Avoid contact with perfume, water and lotions. Store in the pouch provided. Polish gently with a soft cloth to keep the shine.'], 'mallorca' => ['Handcrafted in Mallorca', 'Each piece is finished by hand in our Mallorca atelier and arrives in signature gift packaging, ready to give.']] as $key => [$label, $body])
                        <div class="border-b border-[var(--line)]">
                            <button @click="open === '{{ $key }}' ? open = '' : open = '{{ $key }}'" class="w-full flex items-center justify-between py-4 text-left text-sm tracking-[0.12em] uppercase">
                                {{ $label }}
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.4" viewBox="0 0 24 24" class="transition-transform" :class="open === '{{ $key }}' && 'rotate-45'"><path d="M12 5v14M5 12h14"/></svg>
                            </button>
                            <div x-show="open === '{{ $key }}'" x-collapse.duration.300ms style="display:none">
                                <p class="text-sm text-[var(--text-dim)] leading-relaxed pb-5">{{ $body }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Reviews --}}
    @if ($product->reviews->isNotEmpty())
        <section class="border-t border-[var(--line)] py-16 md:py-20 bg-[var(--bg-soft)]">
            <div class="shell max-w-4xl">
                <h2 class="font-display text-3xl md:text-4xl font-medium mb-10 text-center">What our clients say</h2>
                <div class="grid md:grid-cols-2 gap-x-10 gap-y-8">
                    @foreach ($product->reviews as $rev)
                        <figure class="border-b border-[var(--line)] pb-6">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex gap-0.5 text-[var(--color-gold)]">
                                    @for ($s = 1; $s <= 5; $s++)
                                        <svg width="13" height="13" viewBox="0 0 20 20" fill="{{ $s <= $rev->rating ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="1"><path d="m10 1 2.6 5.3 5.9.9-4.2 4.1 1 5.8L10 14.8 4.7 17.6l1-5.8L1.5 7.7l5.9-.9L10 1Z"/></svg>
                                    @endfor
                                </div>
                                @if ($rev->is_verified)
                                    <span class="text-[0.6rem] tracking-[0.15em] uppercase text-[var(--text-dim)]">Verified</span>
                                @endif
                            </div>
                            @if ($rev->title)
                                <figcaption class="font-medium mb-1">{{ $rev->title }}</figcaption>
                            @endif
                            <blockquote class="text-sm text-[var(--text-dim)] leading-relaxed mb-2">“{{ $rev->body }}”</blockquote>
                            <p class="text-xs text-[var(--text-dim)]">— {{ $rev->author_name }}</p>
                        </figure>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Related --}}
    @if ($related->isNotEmpty())
        <section class="py-16 md:py-24">
            <div class="shell">
                <h2 class="font-display text-3xl md:text-4xl font-medium mb-10 text-center">You may also like</h2>
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-x-3 gap-y-10 md:gap-x-5">
                    @foreach ($related as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
