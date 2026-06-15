<?php

namespace Database\Seeders;

use App\Models\ShippingZone;
use Illuminate\Database\Seeder;

class ShippingZonesSeeder extends Seeder
{
    public function run(): void
    {
        $zones = config('ecommerce.shipping.zones', []);
        foreach ($zones as $z) {
            ShippingZone::updateOrCreate(
                ['name' => $z['name']],
                [
                    'regions' => $z['cities'] ?? ['*'],
                    'countries' => $z['countries'] ?? null,
                    'cities' => $z['cities'] ?? null,
                    'cost' => $z['cost'],
                    'express_cost' => $z['express_cost'],
                    'free_threshold' => $z['free_threshold'] ?? null,
                    'status' => 'active',
                ]
            );
        }
        $this->command->info('Seeded ' . count($zones) . ' shipping zones');
    }
}
