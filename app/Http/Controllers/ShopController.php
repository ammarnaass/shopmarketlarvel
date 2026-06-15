<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Services\CartService;
use App\Models\ShippingCompany;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function __construct(
        private ProductService $productService,
        private CartService $cartService,
    ) {}

    public function index(Request $request): View
    {
        $products = $this->productService->searchProducts($request->all())
            ->paginate(12)
            ->withQueryString();

        $categories = Category::where('status', 'active')->with('children')->get();
        $cartCount = $this->cartService->getCart()->total_items;

        return view('frontend.shop.index', compact('products', 'categories', 'cartCount'));
    }

    public function show(string $slug): View
    {
        $product = $this->productService->getProductBySlug($slug);
        $related = $this->productService->getRelated($product);
        $relatedProducts = $related;
        $cartCount = $this->cartService->getCart()->total_items;
        $shippingCompanies = ShippingCompany::where('status', 'active')->orderBy('name')->get();

        return view('frontend.shop.show', compact('product', 'related', 'relatedProducts', 'cartCount', 'shippingCompanies'));
    }

    public function category(string $slug): View
    {
        $category = Category::where('slug', $slug)->where('status', 'active')->firstOrFail();

        $products = $this->productService->searchProducts(['category_id' => $category->id])
            ->paginate(12);

        $cartCount = $this->cartService->getCart()->total_items;

        return view('frontend.shop.category', compact('category', 'products', 'cartCount'));
    }
}
