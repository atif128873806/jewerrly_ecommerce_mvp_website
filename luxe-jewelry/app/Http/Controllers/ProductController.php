<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Product;

final class ProductController extends Controller
{
    public function show(Product $product)
    {
        $product->load(['images', 'category', 'collection', 'reviews']);

        $related = Product::forCards()
            ->inStock()
            ->where('id', '!=', $product->id)
            ->where(function ($q) use ($product): void {
                $q->where('category_id', $product->category_id)
                    ->orWhere('collection_id', $product->collection_id);
            })
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('products.show', [
            'product' => $product,
            'related' => $related,
        ]);
    }
}
