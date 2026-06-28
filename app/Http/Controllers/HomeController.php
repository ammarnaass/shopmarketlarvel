<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Slide;
use App\Services\CartService;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(
        private ProductService $productService,
        private CartService $cartService,
    ) {}

    public function index(): View
    {
        $featuredProducts = $this->productService->getFeatured(8);
        $latestProducts = Product::active()->with('primaryImage')->latest()->limit(8)->get();
        $categories = Category::where('status', 'active')->whereNull('parent_id')->with('children')->limit(8)->get();
        $slides = Slide::active()->ordered()->get();

        $cartCount = $this->cartService->getCart()->total_items ?? 0;

        return view('frontend.home', compact('featuredProducts', 'latestProducts', 'categories', 'cartCount', 'slides'));
    }
}
