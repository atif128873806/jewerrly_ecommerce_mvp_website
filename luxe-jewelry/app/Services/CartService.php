<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Product;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Collection;

/**
 * Session-backed shopping cart for guest checkout.
 *
 * Items are stored as: [ productId => quantity ]. Product data is hydrated
 * from the database on read so prices/stock always reflect the catalog.
 */
final class CartService
{
    private const KEY = 'cart';

    public function __construct(private readonly Session $session) {}

    /** Raw [productId => qty] map. */
    private function items(): array
    {
        return $this->session->get(self::KEY, []);
    }

    private function persist(array $items): void
    {
        $this->session->put(self::KEY, $items);
    }

    public function add(Product $product, int $qty = 1): void
    {
        $items = $this->items();
        $current = $items[$product->id] ?? 0;
        $items[$product->id] = min($current + $qty, max(1, $product->stock_qty));
        $this->persist($items);
    }

    public function update(int $productId, int $qty): void
    {
        $items = $this->items();

        if ($qty <= 0) {
            unset($items[$productId]);
        } elseif (isset($items[$productId])) {
            $items[$productId] = $qty;
        }

        $this->persist($items);
    }

    public function remove(int $productId): void
    {
        $items = $this->items();
        unset($items[$productId]);
        $this->persist($items);
    }

    public function clear(): void
    {
        $this->session->forget(self::KEY);
    }

    /** Total number of units in the cart (for the header badge). */
    public function count(): int
    {
        return array_sum($this->items());
    }

    /**
     * Hydrated line items, each: { product, qty, line_total (cents) }.
     *
     * @return Collection<int, object>
     */
    public function lines(): Collection
    {
        $items = $this->items();

        if ($items === []) {
            return collect();
        }

        $products = Product::with(['primaryImage', 'category'])
            ->whereIn('id', array_keys($items))
            ->get()
            ->keyBy('id');

        return collect($items)
            ->filter(fn ($qty, $id) => $products->has($id))
            ->map(fn (int $qty, $id) => (object) [
                'product' => $products[$id],
                'qty' => $qty,
                'line_total' => $products[$id]->price * $qty,
            ])
            ->values();
    }

    /** Subtotal in cents. */
    public function subtotal(): int
    {
        return (int) $this->lines()->sum('line_total');
    }

    /** Shipping — free at/above the configured threshold, else flat fee. */
    public function shipping(): int
    {
        $sub = $this->subtotal();
        $threshold = (int) config('store.free_shipping_threshold');

        return ($sub === 0 || $sub >= $threshold) ? 0 : (int) config('store.shipping_fee');
    }

    public function total(): int
    {
        return $this->subtotal() + $this->shipping();
    }

    /** Amount remaining to qualify for free shipping (0 if already free). */
    public function freeShippingRemaining(): int
    {
        return max(0, (int) config('store.free_shipping_threshold') - $this->subtotal());
    }

    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }
}
