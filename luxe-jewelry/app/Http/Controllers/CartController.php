<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;

final class CartController extends Controller
{
    public function __construct(private readonly CartService $cart) {}

    public function index()
    {
        return view('cart.index', [
            'lines' => $this->cart->lines(),
            'subtotal' => $this->cart->subtotal(),
            'shipping' => $this->cart->shipping(),
            'total' => $this->cart->total(),
            'freeRemaining' => $this->cart->freeShippingRemaining(),
        ]);
    }

    public function add(Request $request, Product $product)
    {
        $data = $request->validate([
            'qty' => ['nullable', 'integer', 'min:1', 'max:25'],
        ]);

        $this->cart->add($product, (int) ($data['qty'] ?? 1));

        return back()->with('cart_flash', "“{$product->name}” added to your bag.");
    }

    public function update(Request $request, int $product)
    {
        $data = $request->validate([
            'qty' => ['required', 'integer', 'min:0', 'max:25'],
        ]);

        $this->cart->update($product, (int) $data['qty']);

        return back();
    }

    public function remove(int $product)
    {
        $this->cart->remove($product);

        return back()->with('cart_flash', 'Item removed from your bag.');
    }

    public function clear()
    {
        $this->cart->clear();

        return back()->with('cart_flash', 'Your bag has been cleared.');
    }
}
