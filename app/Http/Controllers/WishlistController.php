<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WishlistController extends Controller
{
    public function index(): View
    {
        $wishlists = Wishlist::where('user_id', auth()->id())
            ->with('product.primaryImage', 'product.category')
            ->latest()
            ->paginate(12);

        return view('frontend.wishlist.index', compact('wishlists'));
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate(['product_id' => 'required|exists:products,id']);

        $exists = Wishlist::where('user_id', auth()->id())
            ->where('product_id', $request->product_id)
            ->exists();

        if ($exists) {
            return response()->json(['success' => false, 'message' => 'المنتج موجود بالفعل في المفضلة']);
        }

        Wishlist::create([
            'user_id' => auth()->id(),
            'product_id' => $request->product_id,
        ]);

        return response()->json(['success' => true, 'message' => 'تمت الإضافة إلى المفضلة']);
    }

    public function destroy(int $product): JsonResponse
    {
        Wishlist::where('user_id', auth()->id())
            ->where('product_id', $product)
            ->delete();

        return response()->json(['success' => true]);
    }
}
