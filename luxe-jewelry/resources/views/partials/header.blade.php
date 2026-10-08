@php
    $promos = ['Free shipping on orders over ' . money(config('store.free_shipping_threshold')), 'Express delivery in 3–5 days', '30-day easy returns', 'Handcrafted in Mallorca'];
@endphp

{{-- ============ PROMO BANNER ============ --}}
<div class="bg-[var(--color-gold)] text-[#0a0a0a] overflow-hidden">
    <div class="relative h-9 flex items-center">
        <div class="flex whitespace-nowrap animate-marquee will-change-transform">
            @foreach (array_merge($promos, $promos, $promos, $promos) as $promo)
                <span class="mx-8 text-[0.62rem] tracking-[0.28em] uppercase font-medium">{{ $promo }}</span>
                <span class="text-[0.5rem] self-center opacity-50">◆</span>
            @endforeach
        </div>
    </div>
</div>

{{-- ============ HEADER ============ --}}
<header
    x-data="{ menu: false, mega: null }"
    @keydown.escape.window="menu = false; mega = null"
    class="sticky top-0 z-50 bg-[var(--bg)]/85 backdrop-blur-md border-b border-[var(--line)]"
    @mouseleave="mega = null"
>
    <div class="shell">
        <div class="h-16 md:h-20 flex items-center justify-between gap-4">

            {{-- Left: mobile burger + desktop nav --}}
            <div class="flex items-center gap-6 flex-1">
                <button @click="menu = true" class="md:hidden -ml-1 p-1" aria-label="Open menu">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.4" viewBox="0 0 24 24"><path d="M3 6h18M3 12h18M3 18h18"/></svg>
                </button>

                <nav class="hidden md:flex items-center gap-7 text-[0.7rem] tracking-[0.18em] uppercase font-medium">
                    <button @mouseenter="mega = 'shop'" class="link-underline py-2" :class="mega === 'shop' && 'text-[var(--color-gold)]'">Shop</button>
                    <a href="{{ route('shop.index', ['sort' => 'newest']) }}" class="link-underline py-2">New In</a>
                    <a href="{{ route('shop.index') }}" class="link-underline py-2">Bestsellers</a>
                    <button @mouseenter="mega = 'collections'" class="link-underline py-2" :class="mega === 'collections' && 'text-[var(--color-gold)]'">Collections</button>
                </nav>
            </div>

            {{-- Center: logo --}}
            <a href="{{ route('home') }}" class="shrink-0 text-center">
                <span class="font-display text-2xl md:text-[1.7rem] tracking-[0.04em] font-semibold leading-none">{{ $brandName }}</span>
                <span class="block text-[0.5rem] tracking-[0.4em] uppercase text-[var(--text-dim)] mt-0.5">Maison de Joaillerie</span>
            </a>

            {{-- Right: actions --}}
            <div class="flex items-center gap-4 md:gap-5 flex-1 justify-end text-[var(--text)]">
                <button onclick="window.__toggleTheme()" aria-label="Toggle theme" class="p-1 hover:text-[var(--color-gold)] transition-colors">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.3" viewBox="0 0 24 24"><circle cx="12" cy="12" r="4.5"/><path d="M12 2v2M12 20v2M2 12h2M20 12h2M5 5l1.5 1.5M17.5 17.5L19 19M19 5l-1.5 1.5M6.5 17.5L5 19"/></svg>
                </button>
                <a href="{{ route('shop.index') }}" class="hidden sm:block p-1 hover:text-[var(--color-gold)] transition-colors" aria-label="Search">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.3" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
                </a>
                <button class="hidden sm:block p-1 hover:text-[var(--color-gold)] transition-colors" aria-label="Account">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.3" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 4-6 8-6s8 2 8 6"/></svg>
                </button>
                <a href="{{ route('cart.index') }}" class="relative p-1 hover:text-[var(--color-gold)] transition-colors" aria-label="Cart">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.3" viewBox="0 0 24 24"><path d="M6 7h12l-1 13H7L6 7Z"/><path d="M9 7a3 3 0 0 1 6 0"/></svg>
                    @if ($cartCount > 0)
                        <span class="absolute -top-1 -right-1 bg-[var(--color-gold)] text-[#0a0a0a] text-[0.55rem] font-semibold rounded-full w-4 h-4 flex items-center justify-center tabular-nums">{{ $cartCount }}</span>
                    @endif
                </a>
            </div>
        </div>
    </div>

    {{-- ============ MEGA MENU (desktop) ============ --}}
    <div x-show="mega" x-transition.opacity.duration.200ms
         class="hidden md:block absolute inset-x-0 top-full bg-[var(--bg)] border-b border-[var(--line)] shadow-2xl" style="display:none">

        {{-- SHOP panel --}}
        <div x-show="mega === 'shop'" class="shell py-10">
            <div class="grid grid-cols-4 gap-10">
                <div>
                    <p class="eyebrow mb-4">Jewelry</p>
                    <ul class="space-y-2.5 text-sm text-[var(--text-dim)]">
                        @foreach ($navCategories as $cat)
                            <li><a href="{{ route('shop.category', $cat) }}" class="link-underline hover:text-[var(--text)] transition-colors">{{ $cat->name }}</a></li>
                        @endforeach
                        <li><a href="{{ route('shop.index') }}" class="link-underline hover:text-[var(--text)] transition-colors">View all</a></li>
                    </ul>
                </div>
                <div>
                    <p class="eyebrow mb-4">Symbols &amp; Meanings</p>
                    <ul class="space-y-2.5 text-sm text-[var(--text-dim)]">
                        @foreach (['Love', 'Protection', 'Balance', 'Luck', 'Healing', 'Strength'] as $sym)
                            <li><a href="{{ route('shop.index', ['symbol' => $sym]) }}" class="link-underline hover:text-[var(--text)] transition-colors">{{ $sym }}</a></li>
                        @endforeach
                    </ul>
                </div>
                <div>
                    <p class="eyebrow mb-4">Material</p>
                    <ul class="space-y-2.5 text-sm text-[var(--text-dim)]">
                        @foreach (\App\Enums\Material::options() as $mat)
                            <li><a href="{{ route('shop.index', ['material' => $mat['value']]) }}" class="link-underline hover:text-[var(--text)] transition-colors">{{ $mat['label'] }}</a></li>
                        @endforeach
                    </ul>
                </div>
                <div>
                    <p class="eyebrow mb-4">Featured</p>
                    <ul class="space-y-2.5 text-sm text-[var(--text-dim)]">
                        <li><a href="{{ route('shop.index', ['sort' => 'newest']) }}" class="link-underline hover:text-[var(--text)] transition-colors">New Arrivals</a></li>
                        <li><a href="{{ route('shop.index', ['sale' => 1]) }}" class="link-underline hover:text-[var(--text)] transition-colors">Special Prices</a></li>
                        <li><a href="{{ route('collections.index') }}" class="link-underline hover:text-[var(--text)] transition-colors">All Collections</a></li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- COLLECTIONS panel --}}
        <div x-show="mega === 'collections'" class="shell py-8" style="display:none">
            <div class="grid grid-cols-3 gap-4">
                @foreach ($navCollections as $col)
                    <a href="{{ route('collections.show', $col) }}" class="group relative aspect-[16/9] overflow-hidden">
                        <img src="{{ asset($col->feature_image) }}" alt="{{ $col->name }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                        <span class="absolute inset-0 bg-black/30 group-hover:bg-black/15 transition-colors"></span>
                        <span class="absolute bottom-4 left-5 font-display text-2xl text-white">{{ $col->name }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ============ MOBILE DRAWER ============ --}}
    {{-- Teleported to <body> so it escapes the header's backdrop-blur containing block --}}
    <template x-teleport="body">
        <div x-show="menu" x-transition.opacity class="fixed inset-0 z-[70] md:hidden" style="display:none">
            <div @click="menu = false" class="absolute inset-0 bg-black/60"></div>
            <div x-show="menu"
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
                 class="absolute left-0 top-0 h-full w-[82%] max-w-sm bg-[var(--bg)] border-r border-[var(--line)] p-6 overflow-y-auto">
                <div class="flex items-center justify-between mb-8">
                    <span class="font-display text-xl font-semibold">{{ $brandName }}</span>
                    <button @click="menu = false" aria-label="Close menu" class="p-1">
                        <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.4" viewBox="0 0 24 24"><path d="M6 6l12 12M18 6L6 18"/></svg>
                    </button>
                </div>
                <nav class="space-y-1 text-sm uppercase tracking-[0.15em]">
                    @foreach ($navCategories as $cat)
                        <a href="{{ route('shop.category', $cat) }}" class="block py-3 border-b border-[var(--line)]">{{ $cat->name }}</a>
                    @endforeach
                    <a href="{{ route('shop.index', ['sort' => 'newest']) }}" class="block py-3 border-b border-[var(--line)]">New In</a>
                    <a href="{{ route('collections.index') }}" class="block py-3 border-b border-[var(--line)]">Collections</a>
                    <a href="{{ route('shop.index', ['sale' => 1]) }}" class="block py-3 border-b border-[var(--line)]">Special Prices</a>
                </nav>
                <div class="mt-8 flex items-center gap-4 text-xs text-[var(--text-dim)]">
                    <span>EN</span><span>·</span><span>ES</span><span>·</span><span>DE</span>
                    <span class="ml-auto">{{ config('store.currency.code') }} {{ config('store.currency.symbol') }}</span>
                </div>
            </div>
        </div>
    </template>
</header>

{{-- Flash toast --}}
@if (session('cart_flash'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3500)" x-show="show" x-transition
         class="fixed bottom-6 right-6 z-[60] bg-[var(--bg-soft)] border border-[var(--color-gold)] text-[var(--text)] text-sm px-5 py-4 shadow-2xl max-w-xs">
        {{ session('cart_flash') }}
    </div>
@endif
