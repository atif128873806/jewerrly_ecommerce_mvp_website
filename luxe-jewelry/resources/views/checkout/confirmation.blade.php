@extends('layouts.app')

@section('title', 'Order Confirmed — ' . $brandName)

@section('content')
    <div class="shell py-16 md:py-24 max-w-3xl">
        {{-- Header --}}
        <div class="text-center mb-12">
            <div class="w-16 h-16 mx-auto mb-6 rounded-full border border-[var(--color-gold)] flex items-center justify-center text-[var(--color-gold)]">
                <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.3" viewBox="0 0 24 24"><path d="M20 6 9 17l-5-5"/></svg>
            </div>
            <p class="eyebrow mb-4">Thank you, {{ $order->first_name }}</p>
            <h1 class="font-display text-4xl md:text-5xl font-medium mb-3">Your order is confirmed</h1>
            <p class="text-[var(--text-dim)]">Order <span class="text-[var(--text)] tabular-nums">{{ $order->order_number }}</span> — a confirmation has been sent to {{ $order->email }}.</p>
        </div>

        {{-- Payment status banner --}}
        <div class="mb-10 p-5 border border-[var(--line)] bg-[var(--bg-soft)] flex items-start gap-4">
            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.3" viewBox="0 0 24 24" class="text-[var(--color-gold)] mt-0.5 shrink-0"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/></svg>
            <div class="text-sm">
                <p class="font-medium">{{ $order->payment_method->label() }}</p>
                @if ($order->payment_method === \App\Enums\PaymentMethod::Cod)
                    <p class="text-[var(--text-dim)] mt-1">Please have <span class="text-[var(--text)] tabular-nums">{{ money($order->total) }}</span> ready in cash when your order is delivered.</p>
                @else
                    <p class="text-[var(--text-dim)] mt-1">Payment received — <span class="text-[var(--color-gold)]">paid in full</span>. (Demo gateway, no real charge.)</p>
                @endif
            </div>
        </div>

        <div class="grid sm:grid-cols-2 gap-8 mb-10">
            {{-- Shipping --}}
            <div>
                <h2 class="eyebrow mb-3">Shipping to</h2>
                <address class="not-italic text-sm text-[var(--text-dim)] leading-relaxed">
                    <span class="text-[var(--text)]">{{ $order->full_name }}</span><br>
                    {{ $order->address }}<br>
                    @if ($order->address_line2){{ $order->address_line2 }}<br>@endif
                    {{ $order->postal_code }} {{ $order->city }}<br>
                    {{ $order->country }}
                    @if ($order->phone)<br>{{ $order->phone }}@endif
                </address>
            </div>
            {{-- Delivery --}}
            <div>
                <h2 class="eyebrow mb-3">Delivery</h2>
                <p class="text-sm text-[var(--text-dim)] leading-relaxed">
                    Express delivery in 3–5 working days.<br>
                    You'll receive tracking once your order ships from our Mallorca atelier.
                </p>
            </div>
        </div>

        {{-- Items --}}
        <div class="border-t border-[var(--line)]">
            @foreach ($order->items as $item)
                <div class="flex gap-4 py-5 border-b border-[var(--line)]">
                    <div class="w-16 aspect-[4/5] shrink-0 overflow-hidden bg-[var(--bg-soft)]">
                        @if ($item->image)
                            <img src="{{ asset($item->image) }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                        @endif
                    </div>
                    <div class="flex-1">
                        <p class="text-sm">{{ $item->name }}</p>
                        <p class="text-xs text-[var(--text-dim)] mt-1 tabular-nums">Qty {{ $item->qty }} · {{ $item->sku }}</p>
                    </div>
                    <p class="text-sm tabular-nums">{{ money($item->line_total) }}</p>
                </div>
            @endforeach
        </div>

        {{-- Totals --}}
        <dl class="mt-6 space-y-2.5 text-sm max-w-xs ml-auto">
            <div class="flex justify-between"><dt class="text-[var(--text-dim)]">Subtotal</dt><dd class="tabular-nums">{{ money($order->subtotal) }}</dd></div>
            <div class="flex justify-between"><dt class="text-[var(--text-dim)]">Shipping</dt><dd class="tabular-nums">{{ $order->shipping === 0 ? 'Free' : money($order->shipping) }}</dd></div>
            <div class="hairline my-3"></div>
            <div class="flex justify-between text-base"><dt>Total</dt><dd class="tabular-nums">{{ money($order->total) }}</dd></div>
        </dl>

        <div class="mt-12 text-center">
            <a href="{{ route('shop.index') }}" class="btn btn-gold">Continue shopping</a>
        </div>
    </div>
@endsection
