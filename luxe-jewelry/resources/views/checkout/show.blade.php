@extends('layouts.app')

@section('title', 'Checkout — ' . $brandName)

@section('content')
    <div class="shell py-12 md:py-16">
        <nav class="text-[0.65rem] tracking-[0.2em] uppercase text-[var(--text-dim)] mb-8">
            <a href="{{ route('cart.index') }}" class="link-underline">Bag</a>
            <span class="mx-2 text-[var(--color-gold)]">/</span>
            <span class="text-[var(--text)]">Checkout</span>
        </nav>

        <h1 class="font-display text-4xl md:text-5xl font-medium mb-10">Checkout</h1>

        @if ($errors->any())
            <div class="mb-8 p-4 border border-red-500/50 bg-red-500/10 text-sm">
                <p class="font-medium mb-1">Please check the details below:</p>
                <ul class="list-disc list-inside text-[var(--text-dim)]">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('checkout.store') }}" method="POST" x-data="{ method: '{{ old('payment_method', 'cod') }}' }">
            @csrf
            <div class="grid lg:grid-cols-3 gap-10 lg:gap-16">
                {{-- Left: details --}}
                <div class="lg:col-span-2 space-y-10">

                    {{-- Contact --}}
                    <section>
                        <h2 class="eyebrow mb-5">1 — Contact</h2>
                        <div class="grid sm:grid-cols-2 gap-4">
                            <x-field name="email" label="Email" type="email" class="sm:col-span-2" required />
                            <x-field name="first_name" label="First name" required />
                            <x-field name="last_name" label="Last name" required />
                            <x-field name="phone" label="Phone (optional)" type="tel" class="sm:col-span-2" />
                        </div>
                    </section>

                    {{-- Shipping --}}
                    <section>
                        <h2 class="eyebrow mb-5">2 — Shipping address</h2>
                        <div class="grid sm:grid-cols-2 gap-4">
                            <x-field name="address" label="Address" class="sm:col-span-2" required />
                            <x-field name="address_line2" label="Apartment, suite (optional)" class="sm:col-span-2" />
                            <x-field name="city" label="City" required />
                            <x-field name="postal_code" label="Postal code" required />
                            <x-field name="country" label="Country" :value="old('country', 'Spain')" class="sm:col-span-2" required />
                            <div class="sm:col-span-2">
                                <label for="notes" class="block text-xs tracking-[0.12em] uppercase text-[var(--text-dim)] mb-2">Order notes (optional)</label>
                                <textarea id="notes" name="notes" rows="3" class="w-full bg-transparent border border-[var(--line)] px-4 py-3 text-sm focus:outline-none focus:border-[var(--color-gold)] transition-colors">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </section>

                    {{-- Payment --}}
                    <section>
                        <h2 class="eyebrow mb-5">3 — Payment</h2>
                        <div class="space-y-3">
                            {{-- COD --}}
                            <label class="flex items-start gap-4 border p-4 cursor-pointer transition-colors"
                                   :class="method === 'cod' ? 'border-[var(--color-gold)]' : 'border-[var(--line)]'">
                                <input type="radio" name="payment_method" value="cod" x-model="method" class="mt-1 accent-[var(--color-gold)]">
                                <span>
                                    <span class="block text-sm font-medium tracking-wide">Cash on Delivery</span>
                                    <span class="block text-xs text-[var(--text-dim)] mt-1">Pay with cash when your order arrives. Available across Spain.</span>
                                </span>
                            </label>

                            {{-- Card --}}
                            <label class="flex items-start gap-4 border p-4 cursor-pointer transition-colors"
                                   :class="method === 'card' ? 'border-[var(--color-gold)]' : 'border-[var(--line)]'">
                                <input type="radio" name="payment_method" value="card" x-model="method" class="mt-1 accent-[var(--color-gold)]">
                                <span class="flex-1">
                                    <span class="flex items-center justify-between">
                                        <span class="text-sm font-medium tracking-wide">Credit / Debit Card</span>
                                        <span class="flex gap-1.5">
                                            @foreach (['VISA', 'MC', 'AMEX'] as $c)
                                                <span class="text-[0.5rem] tracking-wide font-medium px-1.5 py-1 border border-[var(--line)] rounded text-[var(--text-dim)]">{{ $c }}</span>
                                            @endforeach
                                        </span>
                                    </span>
                                    <span class="block text-xs text-[var(--text-dim)] mt-1">Secure dummy gateway — no real charge is made.</span>

                                    {{-- Card fields --}}
                                    <span x-show="method === 'card'" x-collapse style="display:none">
                                        <span class="grid grid-cols-2 gap-3 mt-4">
                                            <input type="text" name="card_number" placeholder="Card number — 4242 4242 4242 4242" value="{{ old('card_number') }}"
                                                   class="col-span-2 bg-[var(--bg)] border border-[var(--line)] px-3 py-2.5 text-sm focus:outline-none focus:border-[var(--color-gold)]">
                                            <input type="text" name="card_expiry" placeholder="MM / YY" value="{{ old('card_expiry') }}"
                                                   class="bg-[var(--bg)] border border-[var(--line)] px-3 py-2.5 text-sm focus:outline-none focus:border-[var(--color-gold)]">
                                            <input type="text" name="card_cvc" placeholder="CVC" value="{{ old('card_cvc') }}"
                                                   class="bg-[var(--bg)] border border-[var(--line)] px-3 py-2.5 text-sm focus:outline-none focus:border-[var(--color-gold)]">
                                        </span>
                                    </span>
                                </span>
                            </label>
                        </div>
                    </section>
                </div>

                {{-- Right: summary --}}
                <aside class="lg:col-span-1">
                    <div class="border border-[var(--line)] p-6 lg:sticky lg:top-28 bg-[var(--bg-soft)]">
                        <h2 class="font-display text-2xl mb-5">Your Order</h2>

                        <div class="space-y-4 mb-5 max-h-72 overflow-y-auto no-scrollbar">
                            @foreach ($lines as $line)
                                <div class="flex gap-3">
                                    <div class="relative w-14 aspect-[4/5] shrink-0 overflow-hidden bg-[var(--bg)]">
                                        <img src="{{ asset($line->product->thumbnail) }}" alt="{{ $line->product->name }}" class="absolute inset-0 w-full h-full object-cover">
                                        <span class="absolute -top-1.5 -right-1.5 bg-[var(--color-gold)] text-[#0a0a0a] text-[0.55rem] font-semibold rounded-full w-4 h-4 flex items-center justify-center tabular-nums">{{ $line->qty }}</span>
                                    </div>
                                    <div class="flex-1 min-w-0 text-xs">
                                        <p class="leading-snug truncate">{{ $line->product->name }}</p>
                                        <p class="text-[var(--text-dim)] mt-0.5">{{ $line->product->material->label() }}</p>
                                    </div>
                                    <p class="text-xs tabular-nums">{{ money($line->line_total) }}</p>
                                </div>
                            @endforeach
                        </div>

                        <div class="hairline mb-4"></div>
                        <dl class="space-y-2.5 text-sm">
                            <div class="flex justify-between"><dt class="text-[var(--text-dim)]">Subtotal</dt><dd class="tabular-nums">{{ money($subtotal) }}</dd></div>
                            <div class="flex justify-between"><dt class="text-[var(--text-dim)]">Shipping</dt><dd class="tabular-nums">{{ $shipping === 0 ? 'Free' : money($shipping) }}</dd></div>
                            <div class="hairline my-3"></div>
                            <div class="flex justify-between text-base"><dt>Total</dt><dd class="tabular-nums">{{ money($total) }}</dd></div>
                        </dl>

                        <button type="submit" class="btn btn-solid w-full mt-6">
                            <span x-show="method === 'cod'">Place Order</span>
                            <span x-show="method === 'card'" style="display:none">Pay {{ money($total) }}</span>
                        </button>
                        <p class="text-[0.7rem] text-[var(--text-dim)] text-center mt-4 leading-relaxed">By placing your order you agree to our terms. This is a demo store — no real payment is processed.</p>
                    </div>
                </aside>
            </div>
        </form>
    </div>
@endsection
