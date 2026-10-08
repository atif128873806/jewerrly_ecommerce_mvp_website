<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Collection;

final class CollectionController extends Controller
{
    public function index()
    {
        return view('collections.index', [
            'collections' => Collection::orderBy('position')
                ->withCount('products')
                ->get(),
        ]);
    }

    public function show(Collection $collection)
    {
        $products = $collection->products()
            ->forCards()
            ->inStock()
            ->orderBy('position')
            ->paginate(12);

        return view('collections.show', [
            'collection' => $collection,
            'products' => $products,
        ]);
    }
}
