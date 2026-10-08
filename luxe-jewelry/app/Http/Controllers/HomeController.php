<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Collection;
use App\Models\Product;
use App\Models\Review;

final class HomeController extends Controller
{
    public function __invoke()
    {
        return view('home', [
            'categories' => Category::orderBy('position')->get(),
            'essentials' => Product::forCards()->bestseller()->inStock()->orderBy('position')->take(12)->get(),
            'newIn' => Product::forCards()->new()->inStock()->latest('id')->take(12)->get(),
            'collections' => Collection::featured()->with(['products' => fn ($q) => $q->forCards()->inStock()->take(4)])->get(),
            'materials' => [
                ['name' => 'Gold', 'sub' => '18K & 24K gold plating', 'img' => 'img/products/p050.jpg', 'material' => 'gold_plated'],
                ['name' => 'Silver', 'sub' => '925 sterling silver', 'img' => 'img/products/p085.jpg', 'material' => 'silver'],
            ],
            'reviews' => Review::featured()->with('product:id,name')->get(),
        ]);
    }
}
