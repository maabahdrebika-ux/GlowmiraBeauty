<?php

// The encrypted parameter from the URL
$encryptedParam = "eyJpdiI6IkJONXdwcUdBYTNKM3V2cG1pbUZpOFE9PSIsInZhbHVlIjoiblNuenAyTXVJZnUvV1VQQUJSN1RUZz09IiwibWFjIjoiNzgzOTU3ZDY2NDBmYzc5ZWFmMzc3ZGY5YmEyY2I0ODJkNmVkYzBjM2QzOWVjZTRhODIyMzljNzg2YzA1NmM5MyIsInRhZyI6IiJ9";

echo "Encrypted parameter: " . $encryptedParam . "\n\n";

// Try to decode as base64
$decoded = base64_decode($encryptedParam);
echo "Base64 decoded: " . $decoded . "\n";

// If it's JSON, decode as JSON
if (json_decode($decoded) !== null) {
    $jsonData = json_decode($decoded, true);
    echo "JSON decoded:\n";
    print_r($jsonData);
    
    // Extract the value field
    if (isset($jsonData['value'])) {
        $value = $jsonData['value'];
        echo "\nValue field: " . $value . "\n";
        
        // Decode the value field as base64
        $valueDecoded = base64_decode($value);
        echo "Value decoded: " . $valueDecoded . "\n";
        
        // Check if it's a number
        if (is_numeric($valueDecoded)) {
            $productId = (int)$valueDecoded;
            echo "Product ID: " . $productId . "\n";
            
            // Look for images
            echo "\nSearching for images related to product ID: " . $productId . "\n";
            
            // Check for main product images
            $mainImages = glob("public/images/product/product_" . $productId . "*.jpg");
            if (!empty($mainImages)) {
                echo "Found main product images:\n";
                foreach ($mainImages as $image) {
                    echo "- " . $image . "\n";
                }
            }
            
            // Check for gallery images
            $galleryImages = glob("public/images/product/product_img_*" . $productId . "*.jpg");
            if (!empty($galleryImages)) {
                echo "Found gallery images:\n";
                foreach ($galleryImages as $image) {
                    echo "- " . $image . "\n";
                }
            }
            
            // Check for any images that might match the timestamp pattern
            $timestampPattern = "*" . $productId . "*";
            $timestampImages = glob("public/images/product/*" . $productId . "*.jpg");
            if (!empty($timestampImages)) {
                echo "Found images with timestamp pattern:\n";
                foreach ($timestampImages as $image) {
                    echo "- " . $image . "\n";
                }
            }
            
        } else {
            echo "Value is not a valid product ID.\n";
        }
    }
} else {
    echo "Not valid JSON\n";
}

// Let's also try to find recent product images and their potential IDs
echo "\n\nRecent product images (last 10):\n";
$allImages = glob("public/images/product/product_*.jpg");
rsort($allImages); // Sort by newest first
$recentImages = array_slice($allImages, 0, 10);

foreach ($recentImages as $image) {
    echo "- " . $image . "\n";
}

// Let's also check the gallery images
echo "\nGallery images (product_img_*):\n";
$galleryImages = glob("public/images/product/product_img_*.jpg");
foreach ($galleryImages as $image) {
    echo "- " . $image . "\n";
}

?>