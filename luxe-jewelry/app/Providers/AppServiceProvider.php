<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Category;
use App\Models\Collection;
use App\Services\CartService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // One cart instance per request, bound to the session.
        $this->app->singleton(CartService::class, function ($app): CartService {
            return new CartService($app['session.store']);
        });
    }

    public function boot(): void
    {
        // Share navigation + cart data with every view (header, footer, mega-menu).
        View::composer('*', function ($view): void {
            $view->with([
                'navCategories' => Category::orderBy('position')->get(),
                'navCollections' => Collection::featured()->get(),
                'cartCount' => app(CartService::class)->count(),
                'brandName' => config('catalog.brand', 'LUXE & CO'),
            ]);
        });
    }
}
