<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\Material;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Product;
use Illuminate\Http\Request;

final class ShopController extends Controller
{
    public function index(Request $request, ?Category $category = null)
    {
        $query = Product::forCards()->inStock();

        if ($category?->exists) {
            $query->where('category_id', $category->id);
        }

        // Filters
        if ($request->filled('collection')) {
            $query->whereHas('collection', fn ($q) => $q->where('slug', $request->string('collection')));
        }

        if ($request->filled('material')) {
            $query->where('material', $request->string('material'));
        }

        if ($request->filled('symbol')) {
            $query->where('symbol', $request->string('symbol'));
        }

        if ($request->boolean('sale')) {
            $query->whereNotNull('compare_price');
        }

        // Sorting
        match ($request->string('sort')->value()) {
            'price_asc' => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'newest' => $query->latest('id'),
            'rating' => $query->orderByDesc('rating'),
            default => $query->orderBy('position'),
        };

        $products = $query->paginate(12)->withQueryString();

        return view('shop.index', [
            'category' => $category?->exists ? $category : null,
            'products' => $products,
            'categories' => Category::orderBy('position')->get(),
            'collections' => Collection::orderBy('position')->get(),
            'materials' => Material::options(),
            'symbols' => Product::query()->whereNotNull('symbol')->distinct()->orderBy('symbol')->pluck('symbol'),
            'activeFilters' => $request->only(['collection', 'material', 'symbol', 'sort', 'sale']),
        ]);
    }
}
