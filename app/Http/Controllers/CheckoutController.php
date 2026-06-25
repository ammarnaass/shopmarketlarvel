<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        private CartService $cartService,
        private OrderService $orderService,
    ) {}

    public function index(): View|RedirectResponse
    {
        $cart = $this->cartService->getCart();

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'السلة فارغة');
        }

        $subtotal = $cart->subtotal;
        $discount = $cart->discount;
        $city = old('city', auth()->user()?->defaultAddress?->city);

        $shippingCost = $city
            ? $this->orderService->calculateShipping($city, 'standard', $subtotal - $discount)
            : 0;

        $codFee = 0;
        try {
            $codFee = $this->orderService->calculateCodFee($subtotal - $discount);
        } catch (\Exception $e) {
            $codFee = 0;
        }

        $grandTotal = $subtotal - $discount + $shippingCost + $codFee;

        return view('frontend.checkout.index', compact(
            'cart', 'subtotal', 'discount', 'shippingCost', 'codFee', 'grandTotal'
        ));
    }

    public function calculateShipping(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'city' => 'required|string',
            'country_code' => 'nullable|string|size:2',
            'method' => 'nullable|in:standard,express',
        ]);

        $cart = $this->cartService->getCart();
        $subtotal = $cart->subtotal - $cart->discount;

        $cost = $this->orderService->calculateShipping(
            $request->city,
            $request->method ?? 'standard',
            $subtotal,
            $request->country_code
        );

        $countryCode = $request->country_code ?? 'SD';
        $symbol = config("ecommerce.countries.{$countryCode}.currency_symbol") ?? config('ecommerce.store.currency_symbol');

        return response()->json([
            'success' => true,
            'shipping_cost' => $cost,
            'is_free' => $cost === 0,
            'currency_symbol' => $symbol,
        ]);
    }

    public function placeOrder(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'country_code' => 'required|string|size:2',
            'state_code' => 'nullable|string|max:5',
            'city' => 'required|string',
            'district' => 'nullable|string',
            'address' => 'required|string',
            'zip' => 'nullable|string',
            'shipping_method' => 'required|in:standard,express',
            'payment_method' => 'required|in:cod,bank_transfer',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            $order = $this->orderService->createOrder($request->all());
            return redirect()->route('orders.show', $order->id)
                ->with('success', 'تم إنشاء الطلب بنجاح. رقم الطلب: ' . $order->order_number);
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }
}
