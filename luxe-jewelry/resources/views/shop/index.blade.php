@extends('layouts.app')

@section('title', ($category?->name ?? 'Shop All') . ' — ' . $brandName)

@section('content')
    {{-- Page header --}}
    <section class="pt-14 md:pt-20 pb-10 border-b border-[var(--line)]">
        <div class="shell text-center">
            <nav class="text-[0.65rem] tracking-[0.2em] uppercase text-[var(--text-dim)] mb-5">
                <a href="{{ route('home') }}" class="link-underline">Home</a>
                <span class="mx-2 opacity-50">/</span>
                <a href="{{ route('shop.index') }}" class="link-underline">Shop</a>
                @if ($category)
                    <span class="mx-2 opacity-50">/</span>
                    <span class="text-[var(--text)]">{{ $category->name }}</span>
                @endif
            </nav>
            <h1 class="font-display text-5xl md:text-6xl font-medium mb-3">{{ $category?->name ?? 'All Jewelry' }}</h1>
            <p class="text-[var(--text-dim)] max-w-xl mx-auto">{{ $category?->description ?? 'The full house of collections — handcrafted in Mallorca, made to be layered and lived in.' }}</p>
        </div>
    </section>

    <div class="shell py-10" x-data="{ filters: false }">
        {{-- Toolbar --}}
        <div class="flex items-center justify-between gap-4 mb-8">
            <button @click="filters = !filters" class="inline-flex items-center gap-2 text-[0.7rem] tracking-[0.2em] uppercase border border-[var(--line)] px-4 py-2.5 hover:border-[var(--color-gold)] transition-colors">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.4" viewBox="0 0 24 24"><path d="M3 5h18M6 12h12M10 19h4"/></svg>
                Filter
            </button>

            <p class="hidden sm:block text-xs text-[var(--text-dim)] tabular-nums">{{ $products->total() }} {{ Str::plural('piece', $products->total()) }}</p>

            <form method="GET" class="flex items-center gap-2">
                @foreach (request()->except(['sort', 'page']) as $k => $v)
                    <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                @endforeach
                <label for="sort" class="text-[0.65rem] tracking-[0.2em] uppercase text-[var(--text-dim)] hidden sm:block">Sort</label>
                <select id="sort" name="sort" onchange="this.form.submit()"
                        class="bg-transparent border border-[var(--line)] text-xs tracking-wide px-3 py-2.5 focus:outline-none focus:border-[var(--color-gold)] cursor-pointer">
                    @foreach (['' => 'Featured', 'newest' => 'Newest', 'price_asc' => 'Price: Low to High', 'price_desc' => 'Price: High to Low', 'rating' => 'Top Rated'] as $val => $label)
                        <option value="{{ $val }}" @selected(request('sort') === $val || (request('sort') === null && $val === ''))>{{ $label }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        {{-- Filter chips (collapsible) --}}
        <div x-show="filters" x-transition style="display:none" class="mb-10 pb-8 border-b border-[var(--line)] space-y-5">
            @php
                $chip = fn ($active) => $active
                    ? 'border-[var(--color-gold)] text-[var(--color-gold)]'
                    : 'border-[var(--line)] text-[var(--text-dim)] hover:border-[var(--text-dim)]';
            @endphp

            <div>
                <p class="eyebrow mb-3">Collection</p>
                <div class="flex flex-wrap gap-2">
                    @foreach ($collections as $col)
                        <a href="{{ request()->fullUrlWithQuery(['collection' => request('collection') === $col->slug ? null : $col->slug, 'page' => null]) }}"
                           class="text-[0.65rem] tracking-[0.15em] uppercase border px-3.5 py-2 transition-colors {{ $chip(request('collection') === $col->slug) }}">{{ $col->name }}</a>
                    @endforeach
                </div>
            </div>

            <div>
                <p class="eyebrow mb-3">Material</p>
                <div class="flex flex-wrap gap-2">
                    @foreach ($materials as $mat)
                        <a href="{{ request()->fullUrlWithQuery(['material' => request('material') === $mat['value'] ? null : $mat['value'], 'page' => null]) }}"
                           class="text-[0.65rem] tracking-[0.15em] uppercase border px-3.5 py-2 transition-colors {{ $chip(request('material') === $mat['value']) }}">{{ $mat['label'] }}</a>
                    @endforeach
                </div>
            </div>

            @if ($symbols->isNotEmpty())
                <div>
                    <p class="eyebrow mb-3">Meaning</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($symbols as $sym)
                            <a href="{{ request()->fullUrlWithQuery(['symbol' => request('symbol') === $sym ? null : $sym, 'page' => null]) }}"
                               class="text-[0.65rem] tracking-[0.15em] uppercase border px-3.5 py-2 transition-colors {{ $chip(request('symbol') === $sym) }}">{{ $sym }}</a>
                        @endforeach
                    </div>
                </div>
            @endif

            @if (collect($activeFilters)->filter()->isNotEmpty())
                <a href="{{ $category ? route('shop.category', $category) : route('shop.index') }}" class="inline-block text-xs text-[var(--text-dim)] underline">Clear all filters</a>
            @endif
        </div>

        {{-- Grid --}}
        @if ($products->isEmpty())
            <div class="py-24 text-center">
                <p class="font-display text-3xl mb-3">Nothing here yet</p>
                <p class="text-[var(--text-dim)] mb-6">No pieces match these filters. Try clearing them.</p>
                <a href="{{ route('shop.index') }}" class="btn btn-gold">View all jewelry</a>
            </div>
        @else
            <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-x-3 gap-y-10 md:gap-x-5 md:gap-y-14">
                @foreach ($products as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>

            <div class="mt-16">
                {{ $products->links('pagination.luxe') }}
            </div>
        @endif
    </div>
@endsection
