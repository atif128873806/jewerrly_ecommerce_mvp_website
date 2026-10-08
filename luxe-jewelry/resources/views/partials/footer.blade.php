{{-- ============ NEWSLETTER ============ --}}
<section class="py-16 md:py-20 border-b border-[var(--line)]">
    <div class="shell max-w-2xl text-center">
        <h2 class="font-display text-3xl md:text-4xl font-medium mb-3">Join the Maison</h2>
        <p class="text-[var(--text-dim)] mb-7">Be first to know about new arrivals, private sales and the stories behind each piece.</p>
        <form class="flex flex-col sm:flex-row gap-3 max-w-md mx-auto" onsubmit="event.preventDefault(); this.reset();">
            <label for="nl-email" class="sr-only">Email address</label>
            <input id="nl-email" type="email" required placeholder="Your email address"
                   class="flex-1 bg-transparent border border-[var(--line)] px-4 py-3 text-sm focus:outline-none focus:border-[var(--color-gold)] transition-colors placeholder:text-[var(--text-dim)]">
            <button type="submit" class="btn btn-solid">Subscribe</button>
        </form>
    </div>
</section>

{{-- ============ FOOTER ============ --}}
<footer class="bg-[var(--bg-soft)] pt-16 pb-8">
    <div class="shell">
        <div class="grid grid-cols-2 md:grid-cols-5 gap-10 mb-14">
            {{-- Brand col --}}
            <div class="col-span-2">
                <span class="font-display text-2xl font-semibold">{{ $brandName }}</span>
                <p class="text-sm text-[var(--text-dim)] mt-4 max-w-xs leading-relaxed">Iconic jewelry, handcrafted in Mallorca. Designed to be layered, stacked & lived in.</p>
                <div class="flex items-center gap-4 mt-6">
                    <a href="#" aria-label="Instagram" class="hover:text-[var(--color-gold)] transition-colors">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.3" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor"/></svg>
                    </a>
                    <a href="#" aria-label="Facebook" class="hover:text-[var(--color-gold)] transition-colors">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.3" viewBox="0 0 24 24"><path d="M15 3h-3a4 4 0 0 0-4 4v3H5v4h3v7h4v-7h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                    </a>
                    <a href="#" aria-label="TikTok" class="hover:text-[var(--color-gold)] transition-colors">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.3" viewBox="0 0 24 24"><path d="M15 4c.5 2.5 2 4 4.5 4.3V11c-1.8 0-3.3-.5-4.5-1.4V15a5.5 5.5 0 1 1-5.5-5.5c.3 0 .7 0 1 .1v2.8a2.7 2.7 0 1 0 1.9 2.6V4z"/></svg>
                    </a>
                </div>
            </div>

            {{-- Shop column (real category links) --}}
            <div>
                <p class="eyebrow !text-[var(--text)] mb-4">Shop</p>
                <ul class="space-y-2.5 text-sm text-[var(--text-dim)]">
                    @foreach ($navCategories as $cat)
                        <li><a href="{{ route('shop.category', $cat) }}" class="link-underline hover:text-[var(--text)] transition-colors">{{ $cat->name }}</a></li>
                    @endforeach
                    <li><a href="{{ route('collections.index') }}" class="link-underline hover:text-[var(--text)] transition-colors">Collections</a></li>
                </ul>
            </div>

            @php
                $footerCols = [
                    'About' => ['Our History', 'Collaborations', 'Boutique', 'Sustainability'],
                    'Customer Care' => ['FAQ', 'Shipping & Delivery', 'Jewelry Care', 'Returns', 'Contact Us'],
                ];
            @endphp
            @foreach ($footerCols as $heading => $links)
                <div>
                    <p class="eyebrow !text-[var(--text)] mb-4">{{ $heading }}</p>
                    <ul class="space-y-2.5 text-sm text-[var(--text-dim)]">
                        @foreach ($links as $link)
                            <li><a href="#" class="link-underline hover:text-[var(--text)] transition-colors">{{ $link }}</a></li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>

        <div class="hairline mb-7"></div>

        {{-- Locale + payments --}}
        <div class="flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-4 text-xs text-[var(--text-dim)]">
                <span class="uppercase tracking-[0.15em]">Pakistan ({{ config('store.currency.code') }} {{ config('store.currency.symbol') }})</span>
                <span class="opacity-40">|</span>
                <span>EN · ES · DE</span>
            </div>

            {{-- payment chips --}}
            <div class="flex flex-wrap items-center justify-center gap-2">
                @foreach (['VISA', 'MC', 'AMEX', 'PayPal', 'ApplePay', 'GPay', 'Klarna', 'Maestro'] as $pay)
                    <span class="text-[0.55rem] tracking-wide font-medium px-2.5 py-1.5 border border-[var(--line)] rounded text-[var(--text-dim)]">{{ $pay }}</span>
                @endforeach
            </div>
        </div>

        <div class="hairline my-7"></div>

        <div class="flex flex-col md:flex-row items-center justify-between gap-3 text-xs text-[var(--text-dim)]">
            <p>© {{ date('Y') }} — {{ $brandName }}™. All rights reserved.</p>
            <div class="flex items-center gap-5">
                <a href="#" class="link-underline">Legal Notice</a>
                <a href="#" class="link-underline">Privacy Policy</a>
                <a href="#" class="link-underline">Terms</a>
            </div>
        </div>
    </div>
</footer>
