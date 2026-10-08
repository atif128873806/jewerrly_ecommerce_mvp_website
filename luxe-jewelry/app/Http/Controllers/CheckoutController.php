<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\PaymentMethod;
use App\Models\Order;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

final class CheckoutController extends Controller
{
    public function __construct(
        private readonly CartService $cart,
        private readonly OrderService $orders,
    ) {}

    public function show()
    {
        if ($this->cart->isEmpty()) {
            return redirect()->route('cart.index');
        }

        return view('checkout.show', [
            'lines' => $this->cart->lines(),
            'subtotal' => $this->cart->subtotal(),
            'shipping' => $this->cart->shipping(),
            'total' => $this->cart->total(),
        ]);
    }

    public function store(Request $request)
    {
        if ($this->cart->isEmpty()) {
            return redirect()->route('cart.index');
        }

        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:40'],
            'address' => ['required', 'string', 'max:255'],
            'address_line2' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:120'],
            'postal_code' => ['required', 'string', 'max:20'],
            'country' => ['required', 'string', 'max:120'],
            'notes' => ['nullable', 'string', 'max:500'],
            'payment_method' => ['required', Rule::enum(PaymentMethod::class)],

            // Dummy card fields — required only when paying by card. Not stored.
            'card_number' => ['nullable', 'required_if:payment_method,card', 'string'],
            'card_expiry' => ['nullable', 'required_if:payment_method,card', 'string'],
            'card_cvc' => ['nullable', 'required_if:payment_method,card', 'string'],
        ]);

        $method = PaymentMethod::from($validated['payment_method']);

        // Only persist real order fields (drop card + method from the address payload).
        $details = collect($validated)->except([
            'payment_method', 'card_number', 'card_expiry', 'card_cvc',
        ])->all();

        $order = $this->orders->createFromCart($details, $method);

        return redirect()->route('checkout.confirmation', $order);
    }

    public function confirmation(Order $order)
    {
        $order->load('items');

        return view('checkout.confirmation', [
            'order' => $order,
        ]);
    }
}
