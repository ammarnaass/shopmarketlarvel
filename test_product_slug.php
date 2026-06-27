<?php
$product = App\Models\Product::active()->where('slug', 'classic-jeans')->first();
echo $product ? 'Found: ' . $product->id . ' - ' . $product->name : 'NOT FOUND';
