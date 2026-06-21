<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\Facades\Blade;
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
        // Share categories and pages with all frontend views (as a Collection, not array)
        View::composer('frontend.partials.header', function ($view) {
            $selectedCatIds = json_decode(\App\Models\Setting::get('nav_categories_list', '[]'), true) ?: [];
            if (!empty($selectedCatIds)) {
                $categories = Category::whereIn('id', $selectedCatIds)
                    ->where('status', 'active')
                    ->orderBy('order')
                    ->get();
            } else {
                $categories = Category::where('status', 'active')
                    ->whereNull('parent_id')
                    ->orderBy('order')
                    ->take(6)
                    ->get();
            }

            $selectedPageIds = json_decode(\App\Models\Setting::get('nav_pages_list', '[]'), true) ?: [];
            if (!empty($selectedPageIds)) {
                $pages = \App\Models\Page::whereIn('id', $selectedPageIds)
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->get();
            } else {
                $pages = collect();
            }

            $view->with([
                'navCategories' => $categories,
                'navPages' => $pages,
            ]);
        });

        // Share stats with all admin views (needed by the layout for notifications)
        View::composer('admin.layout', function ($view) {
            $stats = [
                'pending_orders' => \App\Models\Order::where('status', 'pending')->count(),
            ];
            $view->with('stats', $stats);
        });


        // Blade directive: renders a category icon (FA or Material Symbols)
        // Usage: @categoryIcon($category->icon ?? 'local_offer', 'text-2xl text-brand-600')
        Blade::directive('categoryIcon', function ($expression) {
            return "<?php
                \$__iconArgs = [{$expression}];
                \$iconVal = \$__iconArgs[0] ?? 'local_offer';
                \$cls = \$__iconArgs[1] ?? '';
                if (\$iconVal && (str_starts_with(\$iconVal, 'fa-') || str_starts_with(\$iconVal, 'fas ') || str_starts_with(\$iconVal, 'fab ') || str_starts_with(\$iconVal, 'far '))) {
                    echo '<i class=\"fa-solid ' . e(\$iconVal) . ' ' . e(\$cls) . '\"></i>';
                } elseif (empty(\$iconVal)) {
                    echo '<span class=\"material-symbols-outlined ' . e(\$cls) . '\">local_offer</span>';
                } else {
                    echo '<span class=\"material-symbols-outlined ' . e(\$cls) . '\">' . e(\$iconVal) . '</span>';
                }
            ?>";
        });
    }
}
