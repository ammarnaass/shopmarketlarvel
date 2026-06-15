<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ShippingZone;
use App\Services\ShippingCalculator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShippingApiController extends Controller
{
    public function zones(): JsonResponse
    {
        $zones = ShippingZone::where('status', 'active')
            ->with(['activeMethods.carrier'])
            ->orderBy('priority')
            ->get()
            ->map(fn($z) => [
                'id' => $z->id,
                'name' => $z->name,
                'description' => $z->description,
                'countries' => $z->countries,
                'cities' => $z->cities ?? $z->regions,
                'methods' => $z->activeMethods->map(fn($m) => [
                    'id' => $m->id,
                    'name' => $m->name,
                    'type' => $m->type,
                    'type_label' => $m->getTypeLabel(),
                    'carrier' => $m->carrier?->name,
                    'estimated_days' => $m->estimated_days,
                ]),
            ]);

        return response()->json(['success' => true, 'data' => $zones]);
    }

    public function calculate(Request $request, ShippingCalculator $calculator): JsonResponse
    {
        $request->validate([
            'city' => 'nullable|string',
            'country_id' => 'nullable|integer',
            'state_id' => 'nullable|integer',
            'city_id' => 'nullable|integer',
            'method' => 'nullable|in:standard,express',
            'subtotal' => 'nullable|numeric|min:0',
            'weight' => 'nullable|numeric|min:0',
            'delivery_type' => 'nullable|in:home,office',
            'items' => 'nullable|array',
            'coupon_code' => 'nullable|string',
        ]);

        // Use new system if country_id provided
        if ($request->country_id || $request->city_id || $request->state_id) {
            $coupon = null;
            if ($request->coupon_code) {
                $coupon = \App\Models\Coupon::where('code', $request->coupon_code)->where('status', 'active')->first();
            }

            $result = $calculator->calculate(
                $request->items ?? [],
                $request->country_id,
                $request->state_id,
                $request->city_id,
                $coupon
            );

            return response()->json(array_merge(['success' => true], $result));
        }

        // Legacy: calculate by city name
        $zone = ShippingZone::where('status', 'active')
            ->get()
            ->first(fn($z) => $z->isCityInZone($request->city ?? '', $request->country_code ?? ''));

        $cost = 0;
        $zoneName = null;
        $estimatedDays = null;
        $options = [];

        if ($zone) {
            $zoneName = $zone->name;
            $method = $request->method ?? 'standard';
            $deliveryType = $request->delivery_type ?? 'home';
            $cost = $zone->calculateCost(
                $request->city ?? '',
                $request->country_code ?? '',
                $method,
                $deliveryType,
                $request->subtotal ?? 0,
                $request->weight ?? 0
            );
            $estimatedDays = $zone->estimatedDays($method);

            // Also include zone methods
            foreach ($zone->activeMethods as $m) {
                $mCost = $m->calculateCost($request->weight ?? 0, $request->subtotal ?? 0, $request->items ?? []);
                if ($mCost !== null) {
                    $options[] = [
                        'id' => $m->id,
                        'name' => $m->name,
                        'type' => $m->type,
                        'type_label' => $m->getTypeLabel(),
                        'carrier' => $m->carrier?->name,
                        'cost' => $mCost,
                        'estimated_days' => $m->estimated_days,
                        'is_free' => $mCost == 0,
                    ];
                }
            }
        }

        return response()->json([
            'success' => true,
            'zone' => $zoneName,
            'cost' => $cost,
            'is_free' => $cost === 0,
            'estimated_days' => $estimatedDays,
            'options' => $options,
        ]);
    }

    public function track(string $number, ShippingCalculator $calculator): JsonResponse
    {
        $tracking = $calculator->trackShipment($number);

        if (!$tracking) {
            return response()->json([
                'success' => false,
                'message' => 'رقم التتبع غير موجود',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $tracking,
        ]);
    }
}
