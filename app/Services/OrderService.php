<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\ShippingAddress;
use App\Models\ShippingCompany;
use App\Models\ShippingZone;
use App\Models\Coupon;
use App\Events\OrderStatusChanged;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function __construct(private CartService $cartService) {}

    public function calculateShipping(string $city, string $method = 'standard', float $subtotal = 0, ?string $countryCode = null, string $deliveryType = 'home', float $weight = 0, ?int $companyId = null): float
    {
        // First try DB-stored zones (more flexible: company/delivery_type support)
        $zone = ShippingZone::where('status', 'active')
            ->when($companyId, fn($q) => $q->where('company_id', $companyId))
            ->get()
            ->first(function ($z) use ($city, $countryCode, $deliveryType) {
                return $z->isCityInZone($city, $countryCode) && $z->supportsDelivery($deliveryType);
            });

        if ($zone) {
            return $zone->calculateCost($city, $countryCode ?? '', $method, $deliveryType, $subtotal, $weight);
        }

        // If company was specified but no zone found, return 0 (don't fall back to another company)
        if ($companyId) {
            return 0;
        }

        // Fall back to config-defined zones (legacy)
        $default = config('ecommerce.shipping.zones', []);
        foreach ($default as $z) {
            // Country filter
            if (!empty($z['countries'])) {
                if ($countryCode && !in_array('*', $z['countries']) && !in_array($countryCode, $z['countries'])) {
                    continue;
                }
                if (!$countryCode) continue;
            }

            if (in_array('*', $z['cities'] ?? []) || in_array($city, $z['cities'] ?? [])) {
                $cost = $method === 'express' ? ($z['express_cost'] ?? 0) : ($z['cost'] ?? 0);
                if (isset($z['free_threshold']) && $subtotal >= $z['free_threshold']) {
                    return 0;
                }
                return (float) $cost;
            }
        }
        return 0;
    }

    public function calculateCodFee(float $orderTotal): float
    {
        $cod = config('ecommerce.cod');
        if (!$cod['enabled']) return 0;
        if ($orderTotal < $cod['min_order'] || $orderTotal > $cod['max_order']) {
            throw new \Exception("Order total must be between {$cod['min_order']} and {$cod['max_order']} for COD");
        }
        return (float) $cod['extra_fee'];
    }

    public function createOrder(array $data): Order
    {
        return DB::transaction(function () use ($data) {
            $cart = $this->cartService->getCart();

            if ($cart->items->isEmpty()) {
                throw new \Exception('السلة فارغة');
            }

            $subtotal = $cart->subtotal;
            $discount = $cart->discount;
            $shippingCost = $this->calculateShipping(
                $data['city'],
                $data['shipping_method'] ?? 'standard',
                $subtotal - $discount,
                $data['country_code'] ?? null
            );

            $codFee = 0;
            if (($data['payment_method'] ?? 'cod') === 'cod') {
                $codFee = $this->calculateCodFee($subtotal - $discount);
            }

            $tax = 0;
            $grandTotal = $subtotal - $discount + $shippingCost + $codFee + $tax;

            $address = ShippingAddress::create([
                'user_id' => auth()->id(),
                'name' => $data['name'],
                'phone' => $data['phone'],
                'country_code' => $data['country_code'] ?? 'SD',
                'state_code' => $data['state_code'] ?? null,
                'city' => $data['city'],
                'district' => $data['district'] ?? null,
                'address' => $data['address'],
                'zip' => $data['zip'] ?? null,
                'is_default' => $data['is_default'] ?? false,
            ]);

            $order = Order::create([
                'user_id' => auth()->id(),
                'shipping_address_id' => $address->id,
                'status' => 'pending',
                'payment_status' => 'pending',
                'shipping_status' => 'pending',
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'discount' => $discount,
                'tax' => $tax,
                'cod_fee' => $codFee,
                'grand_total' => $grandTotal,
                'notes' => $data['notes'] ?? null,
                'coupon_id' => $cart->coupon_id,
                'shipping_method' => $data['shipping_method'] ?? 'standard',
            ]);

            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'variant_id' => $item->variant_id,
                    'product_name' => $item->product->name,
                    'product_sku' => $item->product->sku,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'total' => $item->price * $item->quantity,
                    'options' => $item->options,
                    'custom_text' => $item->custom_text,
                    'custom_file' => $item->custom_file,
                ]);

                if ($item->product->type === 'simple') {
                    $item->product->decrement('stock', $item->quantity);
                }
            }

            Payment::create([
                'order_id' => $order->id,
                'method' => $data['payment_method'] ?? 'cod',
                'status' => 'pending',
                'amount' => $grandTotal,
            ]);

            $this->cartService->clear();

            if ($cart->coupon) {
                $cart->coupon->increment('used_count');
            }

            return $order->load('items', 'shippingAddress', 'payment');
        });
    }

    public function updateStatus(Order $order, string $status): Order
    {
        $previousStatus = $order->status;
        $order->update(['status' => $status]);

        // Fire event for notifications
        event(new OrderStatusChanged($order, $previousStatus, $status));

        return $order->fresh();
    }

    public function cancelOrder(Order $order, string $reason = null): Order
    {
        if (!$order->canBeCancelled()) {
            throw new \Exception('لا يمكن إلغاء الطلب في هذه المرحلة');
        }

        $previousStatus = $order->status;
        $order->update([
            'status' => 'cancelled',
            'cancel_reason' => $reason,
        ]);

        // Fire event for notifications
        event(new OrderStatusChanged($order, $previousStatus, 'cancelled'));

        foreach ($order->items as $item) {
            if ($item->product) {
                $item->product->increment('stock', $item->quantity);
            }
        }

        return $order->fresh();
    }

    public function markAsPaid(Order $order, ?string $confirmationCode = null): Order
    {
        $order->update(['payment_status' => 'paid']);
        $order->payment()->update([
            'status' => 'paid',
            'paid_at' => now(),
            'confirmation_code' => $confirmationCode,
        ]);
        return $order->fresh();
    }
}
