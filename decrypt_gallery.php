<?php

// Load Laravel environment
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Encrypt/Decrypt helper function
function decryptValue($encryptedValue) {
    try {
        return decrypt($encryptedValue);
    } catch (Exception $e) {
        return "Error decrypting: " . $e->getMessage();
    }
}

// The encrypted parameter from the URL
$encryptedParam = "eyJpdiI6IkJONXdwcUdBYTNKM3V2cG1pbUZpOFE9PSIsInZhbHVlIjoiblNuenAyTXVJZnUvV1VQQUJSN1RUZz09IiwibWFjIjoiNzgzOTU3ZDY2NDBmYzc5ZWFmMzc3ZGY5YmEyY2I0ODJkNmVkYzBjM2QzOWVjZTRhODIyMzljNzg2YzA1NmM5MyIsInRhZyI6IiJ9";

// Decrypt the parameter
$decryptedValue = decryptValue($encryptedParam);

echo "Encrypted parameter: " . $encryptedParam . "\n";
echo "Decrypted value: " . $decryptedValue . "\n";

// Check if it's a valid number
if (is_numeric($decryptedValue)) {
    $productId = (int)$decryptedValue;
    echo "Product ID: " . $productId . "\n";
    
    // Now let's check for product images
    $imagePattern = "public/images/product/product_img_*" . $productId . "*.jpg";
    echo "Looking for images with pattern: " . $imagePattern . "\n";
    
    $images = glob($imagePattern);
    if (!empty($images)) {
        echo "Found " . count($images) . " images:\n";
        foreach ($images as $image) {
            echo "- " . $image . "\n";
        }
    } else {
        echo "No images found with this pattern.\n";
        
        // Alternative: check for any product images in the directory
        $allImages = glob("public/images/product/product_img_*.jpg");
        echo "\nAll product images found:\n";
        foreach ($allImages as $img) {
            echo "- " . $img . "\n";
        }
    }
    
    // Check for main product images
    $mainImagePattern = "public/images/product/product_" . $productId . "*.jpg";
    $mainImages = glob($mainImagePattern);
    if (!empty($mainImages)) {
        echo "\nFound main product images:\n";
        foreach ($mainImages as $image) {
            echo "- " . $image . "\n";
        }
    }
    
} else {
    echo "Decrypted value is not a valid product ID.\n";
}

// Also check what's in the public/images/product directory
echo "\n\nListing all files in public/images/product directory:\n";
if (is_dir("public/images/product")) {
    $files = scandir("public/images/product");
    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            echo "- " . $file . "\n";
        }
    }
} else {
    echo "Directory not found.\n";
}
?>