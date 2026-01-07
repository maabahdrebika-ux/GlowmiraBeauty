@extends('front.app')
@section('title', app()->getLocale() == 'ar' ? ($product->name ?? __('products.unknown_product')) : ($product->namee ?? $product->name ?? __('products.unknown_product')))

@section('content')
<div class="breadcrumb">
    <div class="container">
        <h2>{{ __('products.products') }}</h2>
        <ul>
            <li><a href="{{ route('/') }}">{{ __('products.home') }}</a></li>
            <li><a href="{{ route('all_products') }}">{{ __('products.products') }}</a></li>
            <li class="active">
                {{ app()->getLocale() == 'ar' 
                    ? ($product->name ?? __('products.unknown_product')) 
                    : ($product->namee ?? $product->name ?? __('products.unknown_product')) }}
            </li>
        </ul>
    </div>
</div>

<div class="shop">
    <div class="container">
        <div class="product-detail__wrapper">
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="product-detail__slide-two">
                        <div class="product-detail__slide-two__big slick-slider">
                            {{-- Main product image first --}}
                            @if($product->image)
                                <div class="slider__item">
                                    <img src="{{ asset('images/product/'.rawurlencode($product->image)) }}" alt="{{ app()->getLocale() == 'ar' ? ($product->name ?? '') : ($product->namee ?? $product->name ?? '') }}"/>
                                </div>
                            @endif
                            
                            {{-- Additional images from imagesfiles table --}}
                            @if(isset($imagesfiles) && count($imagesfiles) > 0)
                                @foreach($imagesfiles as $image)
                                    <div class="slider__item">
                                        <img src="{{ asset('images/product/'.rawurlencode($image->name)) }}" alt="{{ app()->getLocale() == 'ar' ? ($product->name ?? '') : ($product->namee ?? $product->name ?? '') }}"/>
                                    </div>
                                @endforeach
                            @endif
                            
                            {{-- Default image if no images available --}}
                            @if(!$product->image && (!isset($imagesfiles) || count($imagesfiles) == 0))
                                <div class="slider__item">
                                    <img src="{{ asset('images/product/default-product.jpg') }}" alt="Default product image"/>
                                </div>
                            @endif
                        </div>
                        <div class="product-detail__slide-two__small slick-slider">
                            {{-- Main product image thumbnail first --}}
                            @if($product->image)
                                <div class="slider__item">
                                    <img src="{{ asset('images/product/'.rawurlencode($product->image)) }}" alt="Product thumbnail"/>
                                </div>
                            @endif
                            
                            {{-- Additional image thumbnails from imagesfiles table --}}
                            @if(isset($imagesfiles) && count($imagesfiles) > 0)
                                @foreach($imagesfiles as $image)
                                    <div class="slider__item">
                                        <img src="{{ asset('images/product/'.rawurlencode($image->name)) }}" alt="Product thumbnail"/>
                                    </div>
                                @endforeach
                            @endif
                            
                            {{-- Default thumbnail if no images available --}}
                            @if(!$product->image && (!isset($imagesfiles) || count($imagesfiles) == 0))
                                <div class="slider__item">
                                    <img src="{{ asset('images/product/default-product.jpg') }}" alt="Default product image"/>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="product-detail__content">
                        <div class="product-detail__content__header">
                            <h5>{{ app()->getLocale() == 'ar' 
                                ? ($product->categories->name ?? __('products.unknown_category')) 
                                : ($product->categories->englishname ?? $product->categories->name ?? __('products.unknown_category')) }}</h5>
                            <h2>{{ app()->getLocale() == 'ar' 
                                ? ($product->name ?? __('products.unknown_product')) 
                                : ($product->namee ?? $product->name ?? __('products.unknown_product')) }}</h2>
                        </div>
                        <div class="product-detail__content__header__comment-block">
                            <div class="rate">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= round($averageRating))
                                        <i class="fas fa-star"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                            </div>
                            <p>{{ $reviewCount }} {{ __('products.reviews_count') ?? 'reviews' }}</p>
                            @auth('customer')
                                <a href="#reviews-section">{{ __('products.write_review') ?? 'Write a review' }}</a>
                            @else
                                <a href="{{ route('customer.login') }}">{{ __('products.login_to_review') ?? 'Login to write a review' }}</a>
                            @endauth
                        </div>
                        
                        <h3>
                            @if($discountedPrice)
                                <span style="text-decoration: line-through; color: #999; font-size: 0.8em;">{{ number_format($product->price, 2) }} {{ __('products.lyd') }}</span>
                                <br>
                                <span style="color: #e74c3c;">{{ number_format($discountedPrice, 2) }} {{ __('products.lyd') }}</span>
                                @if($discount)
                                    <small style="background: #e74c3c; color: white; padding: 2px 8px; border-radius: 4px; margin-left: 10px;">
                                        -{{ $discount->percentage }}%
                                    </small>
                                @endif
                            @else
                                {{ number_format($product->price, 2) }} {{ __('products.lyd') }}
                            @endif
                        </h3>
                        
                        <div class="divider"></div>
                        
                        <div class="product-detail__content__footer">
                            <ul>
                                <li>{{ __('products.brand') }}:
                                    {{ app()->getLocale() == 'ar' 
                                        ? ($product->brandname_ar ?? $product->material ?? __('products.not_available')) 
                                        : ($product->brandname_en ?? $product->material ?? __('products.not_available')) }}
                                </li>
                                <li>{{ __('products.sku') }}: {{ $product->barcode ?? __('products.not_available') }}</li>
                                <li>{{ __('products.availability') ?? 'Availability' }}: 
                                    <span style="color: {{ $product->is_available ? 'green' : 'red' }}">
                                        {{ $product->is_available ? __('products.available_text') : __('products.unavailable_text') }}
                                    </span>
                                </li>
                                @if(app()->getLocale() == 'ar' && $product->country_of_origin_ar)
                                    <li>{{ __('products.country_of_origin') ?? 'Country of Origin' }}: {{ $product->country_of_origin_ar }}</li>
                                @elseif(app()->getLocale() == 'en' && $product->country_of_origin_en)
                                    <li>{{ __('products.country_of_origin') ?? 'Country of Origin' }}: {{ $product->country_of_origin_en }}</li>
                                @endif
                            </ul>
                            
                            @if($product->stocks && count($product->stocks) > 0)
                                @php
                                    $availableColors = $product->stocks->pluck('coolors')->filter()->unique();
                                    $availableSizes = $product->stocks->pluck('sizes')->filter()->unique();
                                @endphp
                                
                                @if($availableColors && count($availableColors) > 0)
                                    <div class="product-detail__colors">
                                        <span>{{ __('products.color') ?? 'Color' }}:</span>
                                        @foreach($availableColors as $color)
                                            <div class="product-detail__colors__item" style="background-color: {{ $color->name ?? '#000' }}"></div>
                                        @endforeach
                                    </div>
                                @endif
                                
                                @if($availableSizes && count($availableSizes) > 0)
                                    <div class="product-detail__sizes">
                                        <span>{{ __('products.size') ?? 'Size' }}:</span>
                                        @foreach($availableSizes as $size)
                                            <span class="size-option">{{ $size->name ?? __('products.unknown_size') }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            @endif
                            
                            <div class="product-detail__controller">
                                <div class="quantity-controller -border -round">
                                    <div class="quantity-controller__btn -descrease">-</div>
                                    <div class="quantity-controller__number">1</div>
                                    <div class="quantity-controller__btn -increase">+</div>
                                </div>
                                <div class="add-to-cart -dark">
                                    <button class="btn -round -red" onclick="addToCart({{ $product->id }})" id="addToCartBtn">
                                        <i class="fas fa-shopping-bag"></i>
                                        {{ __('products.add_to_cart') }}
                                    </button>
                                </div>
                                <div class="product-detail__controler__actions">
                                    <a class="btn -round -white" href="#"><i class="fas fa-heart"></i></a>
                                </div>
                            </div>
                            
                            <div class="divider"></div>
                            
                            <div class="product-detail__content__tab">
                                <ul class="tab-content__header">
                                    <li class="tab-switcher active" data-tab-index="0" tabindex="0">{{ __('products.description') ?? 'Description' }}</li>
                                    <li class="tab-switcher" data-tab-index="2" tabindex="0">{{ __('products.reviews') ?? 'Reviews' }} ({{ $reviewCount }})</li>
                                </ul>
                                <div id="allTabsContainer">
                                    <div class="tab-content__item -description" data-tab-index="0">
                                        <p>{{ app()->getLocale() == 'ar' 
                                            ? ($product->description_ar ?? $product->description ?? __('products.no_description_available')) 
                                            : ($product->description_en ?? $product->description ?? __('products.no_description_available')) }}</p>
                                    </div>
                                  
                                    <div class="tab-content__item -review" data-tab-index="2" style="display:none;" id="reviews-section">
                                        @if($reviews && count($reviews) > 0)
                                            <div class="reviews-summary">
                                                <h5>{{ __('products.customer_reviews') ?? 'Customer Reviews' }}</h5>
                                                <div class="row">
                                                    <div class="col-12 col-md-4">
                                                        <div class="overall-rating">
                                                            <h2>{{ $averageRating }}</h2>
                                                            <div class="rate">
                                                                @for($i = 1; $i <= 5; $i++)
                                                                    @if($i <= round($averageRating))
                                                                        <i class="fas fa-star"></i>
                                                                    @else
                                                                        <i class="far fa-star"></i>
                                                                    @endif
                                                                @endfor
                                                            </div>
                                                            <p>{{ $reviewCount }} {{ __('products.total_reviews') ?? 'total reviews' }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-md-8">
                                                        <div class="rating-breakdown">
                                                            @for($rating = 5; $rating >= 1; $rating--)
                                                                <div class="rating-bar">
                                                                    <span>{{ $rating }} {{ __('products.star') ?? 'star' }}</span>
                                                                    <div class="progress-bar">
                                                                        @php
                                                                            $percentage = $reviewCount > 0 ? ($ratingDistribution[$rating] / $reviewCount) * 100 : 0;
                                                                        @endphp
                                                                        <div class="progress-fill" style="width: {{ $percentage }}%"></div>
                                                                    </div>
                                                                    <span>{{ $ratingDistribution[$rating] }}</span>
                                                                </div>
                                                            @endfor
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                        @endif

                                        <div class="reviews-list">
                                            @forelse($reviews as $review)
                                                <div class="review">
                                                    <div class="review__header">
                                                        <div class="review__header__avatar">
                                                            <img src="https://via.placeholder.com/60x60/4a90e2/ffffff?text={{ strtoupper(substr($review->customer->name ?? 'U', 0, 1)) }}" alt="{{ $review->customer->name ?? 'User' }}"/>
                                                        </div>
                                                        <div class="review__header__info">
                                                            <h5>{{ $review->customer->name ?? __('products.anonymous') }}</h5>
                                                            <p>{{ $review->created_at->format('M d, Y') }}</p>
                                                            @if($review->is_verified_purchase)
                                                                <span class="verified-badge">{{ __('products.verified_purchase') ?? 'Verified Purchase' }}</span>
                                                            @endif
                                                        </div>
                                                        <div class="review__header__rate">
                                                            <div class="rate">
                                                                @for($i = 1; $i <= 5; $i++)
                                                                    @if($i <= $review->rating)
                                                                        <i class="fas fa-star"></i>
                                                                    @else
                                                                        <i class="far fa-star"></i>
                                                                    @endif
                                                                @endfor
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <p class="review__content">{{ $review->comment }}</p>
                                                </div>
                                            @empty
                                                <div class="no-reviews">
                                                    <p>{{ __('products.no_reviews_yet') ?? 'No reviews yet. Be the first to review this product!' }}</p>
                                                </div>
                                            @endforelse
                                        </div>

                                        @if($reviews && $reviews->hasPages())
                                            <div class="pagination-wrapper">
                                                {{ $reviews->links() }}
                                            </div>
                                        @endif

                                        @auth('customer')
                                            <hr>
                                            <div class="review-form-section">
                                                <h5>{{ __('products.write_review') ?? 'Write a review' }}</h5>
                                                <form action="{{ route('reviews.store') }}" method="POST" id="review-form">
                                                    @csrf
                                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                    
                                                    <div class="form-group">
                                                        <label>{{ __('products.your_rating') ?? 'Your Rating' }} <span class="text-danger">*</span></label>
                                                        <div class="rating-input">
                                                            @for($i = 5; $i >= 1; $i--)
                                                                <input type="radio" id="rating-{{ $i }}" name="rating" value="{{ $i }}" required>
                                                                <label for="rating-{{ $i }}">
                                                                    <i class="fas fa-star"></i>
                                                                </label>
                                                            @endfor
                                                        </div>
                                                        @error('rating')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label>{{ __('products.your_review') ?? 'Your Review' }} <span class="text-danger">*</span></label>
                                                        <textarea name="comment" class="form-control" rows="5" 
                                                                  placeholder="{{ __('products.enter_your_review') ?? 'Share your thoughts about this product...' }}" 
                                                                  required>{{ old('comment') }}</textarea>
                                                        @error('comment')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <button type="submit" class="btn -dark">
                                                            <i class="fas fa-paper-plane"></i>
                                                            {{ __('products.submit_review') ?? 'Submit Review' }}
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        @else
                                            <hr>
                                            <div class="login-prompt">
                                                <p>{{ __('products.login_to_write_review') ?? 'Please login to write a review' }}</p>
                                                <a href="{{ route('customer.login') }}" class="btn -dark">
                                                    {{ __('products.login') ?? 'Login' }}
                                                </a>
                                            </div>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($products && count($products) > 0)
<div class="product-slide">
    <div class="container">
        <div class="section-title -center" style="margin-bottom: 1.875em">
            <h2>{{ __('products.related_products') }}</h2>
            <p>{{ __('products.related_products_desc') }}</p>
        </div>
        <div class="product-slider">
            <div class="product-slide__wrapper">
                @foreach($products as $relatedProduct)
                <div class="product-slide__item">
                    <div class="product">
                        @if($relatedProduct->discounts && count($relatedProduct->discounts) > 0)
                            <div class="product-type"><h5 class="-sale">-{{ $relatedProduct->discounts->first()->percentage }}%</h5></div>
                        @else
                            <div class="product-type"></div>
                        @endif
                        <div class="product-thumb">
                            <a class="product-thumb__image" href="{{ route('product/info', encrypt($relatedProduct->id)) }}">
                                @if($relatedProduct->image)
                                    <img src="{{ asset('images/product/'.rawurlencode($relatedProduct->image)) }}" alt="{{ app()->getLocale() == 'ar' ? ($relatedProduct->name ?? '') : ($relatedProduct->namee ?? $relatedProduct->name ?? '') }}"/>
                                @else
                                    <img src="{{ asset('images/product/default-product.jpg') }}" alt="Default product image"/>
                                @endif
                            </a>
                            <div class="product-thumb__actions">
                                <div class="product-btn">
                                    <button class="btn -white product__actions__item -round product-atc" onclick="addToCart({{ $relatedProduct->id }})">
                                        <i class="fas fa-shopping-bag"></i>
                                    </button>
                                </div>
                                <div class="product-btn">
                                    <a class="btn -white product__actions__item -round product-qv" href="{{ route('product/info', encrypt($relatedProduct->id)) }}"><i class="fas fa-eye"></i></a>
                                </div>
                                <div class="product-btn">
                                    <a class="btn -white product__actions__item -round" href="#"><i class="fas fa-heart"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="product-content">
                            <div class="product-content__header">
                                <div class="product-category">{{ app()->getLocale() == 'ar' 
                                    ? ($relatedProduct->categories->name ?? __('products.unknown_category')) 
                                    : ($relatedProduct->categories->englishname ?? $relatedProduct->categories->name ?? __('products.unknown_category')) }}</div>
                                <div class="rate">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="far fa-star"></i>
                                </div>
                            </div>
                            <a class="product-name" href="{{ route('product/info', encrypt($relatedProduct->id)) }}">
                                {{ app()->getLocale() == 'ar' 
                                    ? ($relatedProduct->name ?? __('products.unknown_product')) 
                                    : ($relatedProduct->namee ?? $relatedProduct->name ?? __('products.unknown_product')) }}
                            </a>
                            <div class="product-content__footer">
                                @if($relatedProduct->discounts && count($relatedProduct->discounts) > 0)
                                    @php
                                        $relatedDiscount = $relatedProduct->discounts->first();
                                        $relatedDiscountedPrice = $relatedProduct->price - ($relatedProduct->price * $relatedDiscount->percentage) / 100;
                                    @endphp
                                    <h5 class="product-price--main">{{ number_format($relatedDiscountedPrice, 2) }} {{ __('products.lyd') }}</h5>
                                    <h5 class="product-price--discount">{{ number_format($relatedProduct->price, 2) }} {{ __('products.lyd') }}</h5>
                                @else
                                    <h5 class="product-price--main">{{ number_format($relatedProduct->price, 2) }} {{ __('products.lyd') }}</h5>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endif

<script>
// Ensure jQuery is loaded before initializing sliders
document.addEventListener('DOMContentLoaded', function() {
    if (typeof jQuery === 'undefined') {
        console.error('jQuery is not loaded. Slick slider requires jQuery.');
        return;
    }
    // Tab functionality
    const tabSwitchers = document.querySelectorAll('.tab-switcher');
    const tabContents = document.querySelectorAll('.tab-content__item');
    
    tabSwitchers.forEach(switcher => {
        switcher.addEventListener('click', function() {
            const targetTabIndex = this.getAttribute('data-tab-index');
            
            // Hide all tab contents
            tabContents.forEach(content => {
                content.style.display = 'none';
            });
            
            // Remove active class from all switchers
            tabSwitchers.forEach(switcher => {
                switcher.classList.remove('active');
            });
            
            // Show target tab content
            const targetContent = document.querySelector(`[data-tab-index="${targetTabIndex}"]`);
            if (targetContent) {
                targetContent.style.display = 'block';
            }
            
            // Add active class to clicked switcher
            this.classList.add('active');
        });
    });
    
    // Quantity controller functionality
    const decreaseBtn = document.querySelector('.quantity-controller__btn.-descrease');
    const increaseBtn = document.querySelector('.quantity-controller__btn.-increase');
    const quantityNumber = document.querySelector('.quantity-controller__number');
    
    if (decreaseBtn && increaseBtn && quantityNumber) {
        let quantity = 1;
        
        decreaseBtn.addEventListener('click', function() {
            if (quantity > 1) {
                quantity--;
                quantityNumber.textContent = quantity;
            }
        });
        
        increaseBtn.addEventListener('click', function() {
            quantity++;
            quantityNumber.textContent = quantity;
        });
    }
    
    // Initialize slick sliders with RTL support
    const isRTL = document.documentElement.getAttribute('dir') === 'rtl';
    
    // Wait for images to load before initializing sliders
    function initializeSliders() {
        // Check if sliders already initialized
        if ($('.product-detail__slide-two__big').hasClass('slick-initialized')) {
            return;
        }
        
        // Initialize big image slider
        $('.product-detail__slide-two__big').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false,
            fade: true,
            rtl: isRTL,
            asNavFor: '.product-detail__slide-two__small',
            adaptiveHeight: true
        });
        
        // Initialize small (thumbnail) slider
        $('.product-detail__slide-two__small').slick({
            slidesToShow: 3,
            slidesToScroll: 1,
            asNavFor: '.product-detail__slide-two__big',
            dots: false,
            centerMode: false,
            focusOnSelect: true,
            rtl: isRTL,
            responsive: [
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 2
                    }
                }
            ],
            // Fix for RTL direction
            cssEase: 'ease-in-out',
            speed: 300
        });
        
        // Fix for RTL transform issue
        if (isRTL) {
            setTimeout(function() {
                const slickTrack = document.querySelector('.product-detail__slide-two__small .slick-track');
                if (slickTrack) {
                    // Force reflow to fix RTL layout
                    slickTrack.style.transform = 'translate3d(0px, 0px, 0px)';
                    
                    // Trigger resize event to recalculate positions
                    $(window).trigger('resize');
                }
            }, 50);
        }
    }
    
    // Initialize sliders when page is ready
    initializeSliders();
    
    // Also initialize when images are loaded
    $('img').on('load', function() {
        initializeSliders();
    });
    
    // Reinitialize on window resize
    $(window).on('resize', function() {
        initializeSliders();
    });
});

// Simple notification function
function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        padding: 15px 20px;
        border-radius: 5px;
        color: white;
        font-weight: 500;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        transform: translateX(400px);
        transition: transform 0.3s ease;
    `;
    
    // Set background color based on type
    switch (type) {
        case 'success':
            notification.style.backgroundColor = '#aa6969';
            break;
        case 'warning':
            notification.style.backgroundColor = '#ffc107';
            notification.style.color = '#212529';
            break;
        case 'error':
            notification.style.backgroundColor = '#dc3545';
            break;
        default:
            notification.style.backgroundColor = '#17a2b8';
    }
    
    notification.textContent = message;
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        notification.style.transform = 'translateX(400px)';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}

// Check if item exists in cart
function checkCartItem(productId, color = null, size = null) {
    return fetch('/cart/check-item', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        },
        body: JSON.stringify({
            product_id: productId,
            color: color,
            size: size
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.exists) {
            return {
                exists: true,
                item: data.item
            };
        }
        return { exists: false, item: null };
    })
    .catch(error => {
        console.error('Error checking cart item:', error);
        return { exists: false, item: null };
    });
}

// Add to Cart functionality
function addToCart(productId, quantity = 1) {
    const addToCartBtn = document.getElementById('addToCartBtn') || event.target.closest('button');
    
    // Get quantity from quantity controller if it exists
    const quantityElement = document.querySelector('.quantity-controller__number');
    if (quantityElement) {
        quantity = parseInt(quantityElement.textContent);
    }

    // Show loading state
    if (addToCartBtn) {
        addToCartBtn.disabled = true;
        addToCartBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    }

    // Make AJAX request
    fetch('/cart/store', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: quantity
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show appropriate message based on action
            let message = data.message || '{{ __("products.added_to_cart") }}';
            let notificationType = 'success';
            
            // Check if item was added or quantity was updated
            if (message.includes('quantity_updated') || message.includes('updated')) {
                message = '{{ __("products.quantity_updated_in_cart") }}';
                notificationType = 'info';
            } else if (message.includes('added_to_cart')) {
                notificationType = 'success';
            }
            
            showNotification(message, notificationType);
            
            // Update cart count and UI
            updateCartCount();
            
            // Update button text temporarily to show confirmation
            if (addToCartBtn) {
                addToCartBtn.innerHTML = '<i class="fas fa-check"></i> {{ __("products.added") }}';
                setTimeout(() => {
                    addToCartBtn.innerHTML = '<i class="fas fa-shopping-bag"></i> {{ __("products.add_to_cart") }}';
                }, 1500);
            }
            
            // Reset button
            if (addToCartBtn) {
                addToCartBtn.disabled = false;
            }
        } else {
            // Handle different error types with specific messages
            let errorMessage = data.message || '{{ __("products.error_occurred") }}';
            let notificationType = 'error';
            
            if (errorMessage.includes('must_login_to_order')) {
                errorMessage = '{{ __("app.must_login_to_order") }}';
                notificationType = 'warning';
                showNotification(errorMessage, notificationType);
                
                // Redirect to login page after delay
                setTimeout(() => {
                    window.location.href = '{{ route("customer.login") }}';
                }, 2000);
            } else if (errorMessage.includes('insufficient_stock') || errorMessage.includes('exceeds_stock')) {
                notificationType = 'warning';
                showNotification(errorMessage, notificationType);
            } else {
                showNotification(errorMessage, notificationType);
            }
            
            // Reset button
            if (addToCartBtn) {
                addToCartBtn.disabled = false;
                addToCartBtn.innerHTML = '<i class="fas fa-shopping-bag"></i> {{ __("products.add_to_cart") }}';
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification(error.message || '{{ __("products.error_occurred") }}', 'error');
        
        // Reset button
        if (addToCartBtn) {
            addToCartBtn.disabled = false;
            addToCartBtn.innerHTML = '<i class="fas fa-shopping-bag"></i>';
        }
    });
}

// Universal cart count update function
function updateCartCount() {
    fetch('/cart/items/count', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.count !== undefined) {
            // Update ALL cart counter elements with different class names for compatibility
            const selectors = ['.cart__quantity', '.cart-count', '.cart-quantity', '.cart-badge'];
            
            selectors.forEach(selector => {
                const cartCountElements = document.querySelectorAll(selector);
                cartCountElements.forEach(element => {
                    element.textContent = data.count;
                });
            });
            
            // Trigger custom event for other scripts to listen to
            window.dispatchEvent(new CustomEvent('cartCountUpdated', { 
                detail: { count: data.count } 
            }));
        }
    })
    .catch(error => {
        console.error('Error updating cart count:', error);
    });
}

// Review system functionality
document.addEventListener('DOMContentLoaded', function() {
    // Star rating functionality
    const ratingInputs = document.querySelectorAll('.rating-input input[type="radio"]');
    const ratingLabels = document.querySelectorAll('.rating-input label');
    
    ratingInputs.forEach(input => {
        input.addEventListener('change', function() {
            // Update visual feedback
            const rating = this.value;
            updateStarDisplay(rating);
        });
    });
    
    ratingLabels.forEach(label => {
        label.addEventListener('mouseover', function() {
            const rating = this.getAttribute('for').split('-')[1];
            updateStarDisplay(rating);
        });
        
        label.addEventListener('mouseout', function() {
            // Reset to selected rating or none
            const selectedRating = document.querySelector('.rating-input input:checked')?.value || 0;
            updateStarDisplay(selectedRating);
        });
    });
    
    function updateStarDisplay(rating) {
        ratingLabels.forEach((label, index) => {
            const starValue = 5 - index; // Reverse because of RTL direction
            if (starValue <= rating) {
                label.style.color = '#ffc107';
            } else {
                label.style.color = '#ddd';
            }
        });
    }
    
    // Initialize star display
    const selectedRating = document.querySelector('.rating-input input:checked')?.value || 0;
    updateStarDisplay(selectedRating);
    
    // Review form submission
    const reviewForm = document.getElementById('review-form');
    if (reviewForm) {
        reviewForm.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
            
            // Form will submit normally, but we show loading state
            // The page will reload after submission
        });
    }
    
    // Auto-scroll to reviews section when "Write a review" link is clicked
    const writeReviewLink = document.querySelector('a[href="#reviews-section"]');
    if (writeReviewLink) {
        writeReviewLink.addEventListener('click', function(e) {
            e.preventDefault();
            const reviewsSection = document.querySelector('#reviews-section');
            if (reviewsSection) {
                reviewsSection.scrollIntoView({ behavior: 'smooth' });
                
                // Switch to reviews tab
                setTimeout(() => {
                    const reviewsTab = document.querySelector('.tab-switcher[data-tab-index="2"]');
                    if (reviewsTab) {
                        reviewsTab.click();
                    }
                }, 500);
            }
        });
    }
});
</script>

<style>
.add-to-cart .btn {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    font-size: 14px;
    font-weight: 500;
    white-space: nowrap;
}

/* Slick slider custom styles for product detail */
.product-detail__slide-two__big .slick-slide {
    display: flex;
    justify-content: center;
    align-items: center;
}

.product-detail__slide-two__big .slick-slide img {
    max-width: 100%;
    max-height: 500px;
    width: auto;
    height: auto;
}

.product-detail__slide-two__small .slick-slide {
    padding: 5px;
    cursor: pointer;
}

.product-detail__slide-two__small .slick-slide img {
    width: 100%;
    height: auto;
    border: 2px solid transparent;
}

.product-detail__slide-two__small .slick-slide.slick-current img {
    border: 2px solid #aa6969;
}

/* RTL specific styles */
body[dir="rtl"] .product-detail__slide-two__small .slick-slide {
    float: right;
}

body[dir="rtl"] .product-detail__slide-two__small .slick-track {
    transform: translate3d(0px, 0px, 0px);
}

/* Fix slick slider navigation arrows for RTL */
body[dir="rtl"] .product-detail__slide-two__small .slick-prev {
    left: auto;
    right: 10px;
}

body[dir="rtl"] .product-detail__slide-two__small .slick-next {
    right: auto;
    left: 10px;
}

.add-to-cart .btn i {
    font-size: 16px;
    margin: 0;
}

/* Review System Styles */
.reviews-summary {
    margin-bottom: 30px;
}

.overall-rating {
    text-align: center;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 8px;
}

.overall-rating h2 {
    font-size: 3rem;
    margin: 0;
    color: #333;
}

.overall-rating .rate {
    margin: 10px 0;
}

.rating-bar {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 8px;
}

.progress-bar {
    flex: 1;
    height: 8px;
    background: #e9ecef;
    border-radius: 4px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: #ffc107;
    transition: width 0.3s ease;
}

.rating-breakdown {
    padding: 20px;
}

.verified-badge {
    display: inline-block;
    padding: 2px 8px;
    background: #aa6969;
    color: white;
    font-size: 11px;
    border-radius: 4px;
    margin-top: 4px;
}

.review {
    border-bottom: 1px solid #eee;
    padding: 20px 0;
}

.review:last-child {
    border-bottom: none;
}

.review__header {
    display: flex;
    align-items: flex-start;
    gap: 15px;
    margin-bottom: 15px;
}

.review__header__avatar img {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
}

.review__header__info {
    flex: 1;
}

.review__header__info h5 {
    margin: 0;
    font-size: 16px;
    color: #333;
}

.review__header__info p {
    margin: 5px 0 0 0;
    color: #666;
    font-size: 14px;
}

.review__header__rate .rate {
    display: flex;
    gap: 2px;
}

.review__content {
    color: #555;
    line-height: 1.6;
    margin: 0;
}

.no-reviews {
    text-align: center;
    padding: 40px 20px;
    color: #666;
}

.pagination-wrapper {
    margin-top: 30px;
    text-align: center;
}

.review-form-section {
    margin-top: 30px;
}

.rating-input {
    display: flex;
    gap: 5px;
    direction: rtl;
}

.rating-input input[type="radio"] {
    display: none;
}

.rating-input label {
    cursor: pointer;
    font-size: 24px;
    color: #ddd;
    transition: color 0.2s;
}

.rating-input label:hover,
.rating-input label:hover ~ label,
.rating-input input[type="radio"]:checked ~ label {
    color: #ffc107;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    font-weight: 600;
    margin-bottom: 8px;
    display: block;
}

.form-control {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
}

.form-control:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
}

.text-danger {
    color: #dc3545;
    font-size: 14px;
    margin-top: 4px;
}

.login-prompt {
    text-align: center;
    padding: 30px;
    background: #f8f9fa;
    border-radius: 8px;
}

.login-prompt p {
    margin-bottom: 15px;
    color: #666;
}
</style>
@endsection