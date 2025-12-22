<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\Product;
use App\Models\Discount;

// Bootstrap Laravel application
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    // Test the relationship fix
    echo "Testing discount relationship fix...\n";

    // Create a test product
    $product = Product::first();
    if (!$product) {
        echo "No products found in database.\n";
        exit(1);
    }

    echo "Found product: " . $product->id . "\n";

    // Try to access the discounts relationship (this was causing the error)
    $discounts = $product->discounts;
    echo "Successfully accessed discounts relationship.\n";

    // Try to get active discount
    $activeDiscount = $product->active_discount;
    echo "Successfully accessed active_discount attribute.\n";

    echo "Fix appears to be working correctly!\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
    exit(1);
}