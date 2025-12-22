<?php

echo "=== PRODUCT GALLERY IMAGES ===\n";
echo "Available images from the product gallery system:\n\n";

// Main product images
echo "MAIN PRODUCT IMAGES:\n";
$mainImages = glob("public/images/product/product_*.jpg");
sort($mainImages); // Sort alphabetically

foreach ($mainImages as $image) {
    $filename = basename($image);
    echo "• " . $filename . "\n";
    echo "  Full path: http://127.0.0.1:8000/images/product/" . $filename . "\n";
    echo "  File size: " . round(filesize($image) / 1024, 2) . " KB\n";
    echo "\n";
}

// Gallery images
echo "\nGALLERY IMAGES (product_img_*):\n";
$galleryImages = glob("public/images/product/product_img_*.jpg");
sort($galleryImages); // Sort alphabetically

foreach ($galleryImages as $image) {
    $filename = basename($image);
    echo "• " . $filename . "\n";
    echo "  Full path: http://127.0.0.1:8000/images/product/" . $filename . "\n";
    echo "  File size: " . round(filesize($image) / 1024, 2) . " KB\n";
    echo "\n";
}

// Group images by timestamp patterns
echo "\n=== IMAGES GROUPED BY PRODUCT TIMESTAMPS ===\n";

$allImages = array_merge($mainImages, $galleryImages);
$groupedImages = [];

// Group images by timestamp (extract timestamp from filename)
foreach ($allImages as $image) {
    $filename = basename($image);
    // Extract timestamp from filename (product_ or product_img_ followed by timestamp)
    if (preg_match('/product(?:_img)?_(\d+)\.jpg/', $filename, $matches)) {
        $timestamp = $matches[1];
        $groupedImages[$timestamp][] = $filename;
    }
}

// Sort by timestamp
ksort($groupedImages);

foreach ($groupedImages as $timestamp => $images) {
    echo "Product Group (Timestamp: $timestamp):\n";
    foreach ($images as $img) {
        echo "  • $img\n";
        echo "    URL: http://127.0.0.1:8000/images/product/$img\n";
    }
    echo "\n";
}

// Summary statistics
echo "=== SUMMARY ===\n";
echo "Total main product images: " . count($mainImages) . "\n";
echo "Total gallery images: " . count($galleryImages) . "\n";
echo "Total unique products: " . count($groupedImages) . "\n";

// Create a simple gallery HTML page
echo "\n=== GENERATING GALLERY HTML ===\n";
$html = generateGalleryHTML($mainImages, $galleryImages);
file_put_contents("gallery_output.html", $html);
echo "Gallery HTML saved to: gallery_output.html\n";

function generateGalleryHTML($mainImages, $galleryImages) {
    $html = "<!DOCTYPE html>
<html>
<head>
    <title>Product Gallery</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .image-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px; }
        .image-item { border: 1px solid #ddd; padding: 10px; text-align: center; }
        .image-item img { max-width: 100%; height: 200px; object-fit: cover; }
        .image-name { margin-top: 10px; font-size: 12px; word-break: break-all; }
    </style>
</head>
<body>
    <h1>Product Gallery Images</h1>
    
    <h2>Main Product Images</h2>
    <div class='image-grid'>";
    
    foreach ($mainImages as $image) {
        $filename = basename($image);
        $html .= "
        <div class='image-item'>
            <img src='images/product/$filename' alt='$filename'>
            <div class='image-name'>$filename</div>
        </div>";
    }
    
    $html .= "
    </div>
    
    <h2>Gallery Images (product_img_*)</h2>
    <div class='image-grid'>";
    
    foreach ($galleryImages as $image) {
        $filename = basename($image);
        $html .= "
        <div class='image-item'>
            <img src='images/product/$filename' alt='$filename'>
            <div class='image-name'>$filename</div>
        </div>";
    }
    
    $html .= "
    </div>
</body>
</html>";
    
    return $html;
}

?>