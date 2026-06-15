<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Share categories with all frontend views (as a Collection, not array)
        View::composer('frontend.partials.header', function ($view) {
            $categories = Category::where('status', 'active')
                ->whereNull('parent_id')
                ->orderBy('order')
                ->take(6)
                ->get();
            $view->with('navCategories', $categories);
        });
    }
}
