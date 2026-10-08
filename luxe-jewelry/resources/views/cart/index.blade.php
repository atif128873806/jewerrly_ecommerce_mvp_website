@extends('layouts.app')

@section('title', 'Your Bag — ' . $brandName)

@section('content')
    <div class="shell py-14 md:py-20">
        <h1 class="font-display text-5xl md:text-6xl font-medium mb-3 text-center">Your Bag</h1>
        <p class="text-center text-[var(--text-dim)] mb-12">{{ $lines->sum('qty') }} {{ Str::plural('item', $lines->sum('qty')) }}</p>

        @if ($lines->isEmpty())
            <div class="py-20 text-center">
                <p class="font-display text-2xl mb-4">Your bag is empty</p>
                <p class="text-[var(--text-dim)] mb-8">Discover pieces made to be layered and lived in.</p>
                <a href="{{ route('shop.index') }}" class="btn btn-gold">Start shopping</a>
            </div>
        @else
            <div class="grid lg:grid-cols-3 gap-10 lg:gap-16">
                {{-- Lines --}}
                <div class="lg:col-span-2">
                    {{-- Free shipping progress --}}
                    @if ($freeRemaining > 0)
                        <div class="mb-8 p-4 border border-[var(--line)] bg-[var(--bg-soft)]">
                            <p class="text-sm mb-2">You're <span class="text-[var(--color-gold)] tabular-nums">{{ money($freeRemaining) }}</span> away from free shipping.</p>
                            <div class="h-1 bg-[var(--line)] overflow-hidden">
                                <div class="h-full bg-[var(--color-gold)]" style="width: {{ min(100, ($subtotal / 15000) * 100) }}%"></div>
                            </div>
                        </div>
                    @else
                        <p class="mb-8 text-sm text-[var(--color-gold)]">✓ Your order qualifies for free shipping.</p>
                    @endif

                    <div class="hairline mb-2"></div>
                    @foreach ($lines as $line)
                        <div class="flex gap-4 md:gap-6 py-6 border-b border-[var(--line)]">
                            <a href="{{ route('products.show', $line->product) }}" class="w-24 md:w-28 aspect-[4/5] shrink-0 overflow-hidden bg-[var(--bg-soft)]">
                                <img src="{{ asset($line->product->thumbnail) }}" alt="{{ $line->product->name }}" class="w-full h-full object-cover">
                            </a>
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between gap-4">
                                    <div>
                                        @if ($line->product->collection)
                                            <p class="text-[0.6rem] tracking-[0.2em] uppercase text-[var(--color-gold)] mb-1">{{ $line->product->collection->name }}</p>
                                        @endif
                                        <h3 class="text-sm md:text-base"><a href="{{ route('products.show', $line->product) }}" class="link-underline">{{ $line->product->name }}</a></h3>
                                        <p class="text-xs text-[var(--text-dim)] mt-1">{{ $line->product->material->label() }}</p>
                                    </div>
                                    <p class="text-sm tabular-nums whitespace-nowrap">{{ money($line->line_total) }}</p>
                                </div>

                                <div class="flex items-center justify-between mt-4">
                                    {{-- Qty --}}
                                    <form action="{{ route('cart.update', $line->product->id) }}" method="POST" class="flex items-center border border-[var(--line)]" x-data>
                                        @csrf @method('PATCH')
                                        <button name="qty" value="{{ $line->qty - 1 }}" class="w-9 h-9 flex items-center justify-center hover:text-[var(--color-gold)]" aria-label="Decrease">−</button>
                                        <span class="w-8 text-center text-sm tabular-nums">{{ $line->qty }}</span>
                                        <button name="qty" value="{{ $line->qty + 1 }}" class="w-9 h-9 flex items-center justify-center hover:text-[var(--color-gold)]" aria-label="Increase">+</button>
                                    </form>

                                    <form action="{{ route('cart.remove', $line->product->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button class="text-xs text-[var(--text-dim)] link-underline">Remove</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div class="mt-6 flex justify-between">
                        <a href="{{ route('shop.index') }}" class="text-sm link-underline">← Continue shopping</a>
                        <form action="{{ route('cart.clear') }}" method="POST">
                            @csrf @method('DELETE')
                            <button class="text-sm text-[var(--text-dim)] link-underline">Clear bag</button>
                        </form>
                    </div>
                </div>

                {{-- Summary --}}
                <aside class="lg:col-span-1">
                    <div class="border border-[var(--line)] p-6 md:p-7 lg:sticky lg:top-28 bg-[var(--bg-soft)]">
                        <h2 class="font-display text-2xl mb-6">Order Summary</h2>
                        <dl class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <dt class="text-[var(--text-dim)]">Subtotal</dt>
                                <dd class="tabular-nums">{{ money($subtotal) }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-[var(--text-dim)]">Shipping</dt>
                                <dd class="tabular-nums">{{ $shipping === 0 ? 'Free' : money($shipping) }}</dd>
                            </div>
                            <div class="hairline my-4"></div>
                            <div class="flex justify-between text-base">
                                <dt>Total</dt>
                                <dd class="tabular-nums">{{ money($total) }}</dd>
                            </div>
                        </dl>
                        <a href="{{ route('checkout.show') }}" class="btn btn-solid w-full mt-6">Proceed to Checkout</a>
                        <p class="text-[0.7rem] text-[var(--text-dim)] text-center mt-4 leading-relaxed">Taxes calculated at checkout. Secure payment via Visa, Mastercard, PayPal, Apple Pay & Klarna.</p>
                    </div>
                </aside>
            </div>
        @endif
    </div>
@endsection
