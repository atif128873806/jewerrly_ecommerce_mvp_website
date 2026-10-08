<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\PaymentMethod;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use RuntimeException;

final class OrderService
{
    public function __construct(private readonly CartService $cart) {}

    /**
     * Create an order from the current cart contents.
     *
     * @param  array<string, mixed>  $details  Validated customer + shipping fields.
     */
    public function createFromCart(array $details, PaymentMethod $method): Order
    {
        $lines = $this->cart->lines();

        if ($lines->isEmpty()) {
            throw new RuntimeException('Cannot place an order with an empty cart.');
        }

        $subtotal = $this->cart->subtotal();
        $shipping = $this->cart->shipping();
        $total = $subtotal + $shipping;

        $order = DB::transaction(function () use ($lines, $details, $method, $subtotal, $shipping, $total): Order {
            $order = Order::create([
                ...$details,
                'order_number' => 'PENDING',
                'payment_method' => $method->value,
                // Dummy gateway: cards are charged instantly; COD is collected on delivery.
                'payment_status' => $method === PaymentMethod::Card ? 'paid' : 'pending',
                'status' => 'confirmed',
                'subtotal' => $subtotal,
                'shipping' => $shipping,
                'total' => $total,
            ]);

            $order->update([
                'order_number' => 'LX-' . str_pad((string) $order->id, 6, '0', STR_PAD_LEFT),
            ]);

            foreach ($lines as $line) {
                $product = $line->product;

                $order->items()->create([
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'image' => $product->thumbnail,
                    'unit_price' => $product->price,
                    'qty' => $line->qty,
                    'line_total' => $line->line_total,
                ]);

                // Decrement stock, never below zero.
                $product->decrement('stock_qty', min($line->qty, $product->stock_qty));
            }

            return $order;
        });

        $this->cart->clear();

        return $order->load('items');
    }
}
